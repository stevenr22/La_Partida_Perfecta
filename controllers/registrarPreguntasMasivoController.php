<?php
require "../db/conexion.php";
session_start();

// JSON limpio (para evitar "Error AJAX" aunque inserte)
ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=utf-8");

$data = json_decode(file_get_contents("php://input"), true);

$id_quiz = (int)($data["id_quiz"] ?? 0);
$id_dificultad = (int)($data["id_dificultad"] ?? 0);
$preguntas = $data["preguntas"] ?? [];

if ($id_quiz === 0 || $id_dificultad === 0 || !is_array($preguntas) || count($preguntas) !== 10) {
    echo json_encode(["ok"=>false,"mensaje"=>"Datos inválidos"]);
    exit;
}

// Validar límite real: no permitir pasar de 10
$check = $conn->query("
    SELECT COUNT(*) AS total
    FROM pregunta
    WHERE id_quiz=$id_quiz AND id_dificultad=$id_dificultad
");
$totalActual = (int)$check->fetch_assoc()["total"];

if ($totalActual >= 10) {
    echo json_encode(["ok"=>false,"mensaje"=>"Ya existen 10 preguntas para esta dificultad"]);
    exit;
}

if ($totalActual + count($preguntas) > 10) {
    echo json_encode([
        "ok"=>false,
        "mensaje"=>"Ya hay $totalActual preguntas. Solo puedes agregar ".(10-$totalActual)." más."
    ]);
    exit;
}

// Orden incremental
$ordenBaseRes = $conn->query("
  SELECT IFNULL(MAX(orden_preg),0) AS maxOrden
  FROM pregunta
  WHERE id_quiz=$id_quiz AND id_dificultad=$id_dificultad
");
$ordenBase = (int)$ordenBaseRes->fetch_assoc()["maxOrden"];

// Insertar 10 preguntas
foreach ($preguntas as $p) {

    $tipo = trim($p["tipo"] ?? "");
    $enunciado = trim($p["enunciado"] ?? "");

    if ($tipo === "" || $enunciado === "") {
        echo json_encode(["ok"=>false,"mensaje"=>"Faltan datos en una pregunta"]);
        exit;
    }

    // Evitar duplicados exactos por reintento (opcional pero recomendado)
    $ex = $conn->query("
        SELECT id_pregunta
        FROM pregunta
        WHERE id_quiz=$id_quiz
          AND id_dificultad=$id_dificultad
          AND enunciado_preg='$enunciado'
          AND tipo='$tipo'
        LIMIT 1
    ");
    if ($ex && $ex->num_rows > 0) {
        // si ya existe, saltamos (así no duplicas si se reenvía)
        continue;
    }

    $ordenBase++;

    // ======================
    // TRIVIA (A-B-C-D)
    // ======================
    if ($tipo === "trivia") {

        $correcta = trim($p["correcta"] ?? "");
        $opciones = $p["opciones"] ?? [];

        if ($correcta === "" || !is_array($opciones) || count($opciones) !== 4) {
            echo json_encode(["ok"=>false,"mensaje"=>"Trivia inválida (correcta u opciones incompletas)"]);
            exit;
        }

        $conn->query("
            INSERT INTO pregunta (id_quiz,id_dificultad,tipo,enunciado_preg,tiempo_preg,orden_preg,respuesta_texto)
            VALUES ($id_quiz,$id_dificultad,'trivia','$enunciado',25,$ordenBase,NULL)
        ");
        $id_p = $conn->insert_id;

        foreach ($opciones as $op) {
            $letra = trim($op["op"] ?? "");
            $texto = trim($op["texto"] ?? "");

            if ($letra === "" || $texto === "") {
                echo json_encode(["ok"=>false,"mensaje"=>"Una opción de trivia está vacía"]);
                exit;
            }

            $ok = ($letra === $correcta) ? 1 : 0;

            $conn->query("
                INSERT INTO opcion (id_pregunta,texto_opc,es_correcta)
                VALUES ($id_p,'$texto',$ok)
            ");
        }
        continue;
    }

    // ======================
    // VERDADERO / FALSO
    // ======================
    if ($tipo === "verdadero_falso") {

        $correcta = trim($p["correcta"] ?? "");
        if ($correcta !== "Verdadero" && $correcta !== "Falso") {
            echo json_encode(["ok"=>false,"mensaje"=>"Verdadero/Falso inválido"]);
            exit;
        }

        $conn->query("
            INSERT INTO pregunta (id_quiz,id_dificultad,tipo,enunciado_preg,tiempo_preg,orden_preg,respuesta_texto)
            VALUES ($id_quiz,$id_dificultad,'verdadero_falso','$enunciado',25,$ordenBase,NULL)
        ");
        $id_p = $conn->insert_id;

        $vOk = ($correcta === "Verdadero") ? 1 : 0;
        $fOk = ($correcta === "Falso") ? 1 : 0;

        $conn->query("
            INSERT INTO opcion (id_pregunta,texto_opc,es_correcta)
            VALUES
            ($id_p,'Verdadero',$vOk),
            ($id_p,'Falso',$fOk)
        ");
        continue;
    }

    // ======================
    // COMPLETAR
    // ======================
    if ($tipo === "completar") {

        $respuesta = trim($p["respuesta_texto"] ?? "");
        if ($respuesta === "") {
            echo json_encode(["ok"=>false,"mensaje"=>"Completar inválido (respuesta vacía)"]);
            exit;
        }

        $conn->query("
            INSERT INTO pregunta (id_quiz,id_dificultad,tipo,enunciado_preg,respuesta_texto,tiempo_preg,orden_preg)
            VALUES ($id_quiz,$id_dificultad,'completar','$enunciado','$respuesta',25,$ordenBase)
        ");
        continue;
    }

    echo json_encode(["ok"=>false,"mensaje"=>"Tipo de pregunta inválido"]);
    exit;
}

echo json_encode(["ok"=>true]);
exit;
