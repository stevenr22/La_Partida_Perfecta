<?php
require "../db/conexion.php";
session_start();

ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=utf-8");

$data = json_decode(file_get_contents("php://input"), true);

$id_quiz       = (int)($data["id_quiz"] ?? 0);
$id_dificultad = (int)($data["id_dificultad"] ?? 0);
$preguntas     = $data["preguntas"] ?? [];

if ($id_quiz === 0 || $id_dificultad === 0 || !is_array($preguntas) || count($preguntas) !== 10) {
    echo json_encode(["ok"=>false,"mensaje"=>"Datos inválidos"]);
    exit;
}

// ======================
// VALIDAR LÍMITE REAL
// ======================
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

// ======================
// ORDEN BASE
// ======================
$resOrden = $conn->query("
  SELECT IFNULL(MAX(orden_preg),0) AS maxOrden
  FROM pregunta
  WHERE id_quiz=$id_quiz AND id_dificultad=$id_dificultad
");
$ordenBase = (int)$resOrden->fetch_assoc()["maxOrden"];

// ======================
// INSERTAR PREGUNTAS
// ======================
foreach ($preguntas as $p) {

    $tipo      = trim($p["tipo"] ?? "");
    $enunciado = trim($p["enunciado"] ?? "");

    if ($tipo === "" || $enunciado === "") {
        echo json_encode(["ok"=>false,"mensaje"=>"Faltan datos en una pregunta"]);
        exit;
    }

    $tipoEsc      = $conn->real_escape_string($tipo);
    $enunciadoEsc = $conn->real_escape_string($enunciado);

    // Evitar duplicados
    $ex = $conn->query("
        SELECT id_pregunta
        FROM pregunta
        WHERE id_quiz=$id_quiz
          AND id_dificultad=$id_dificultad
          AND enunciado_preg='$enunciadoEsc'
          AND tipo='$tipoEsc'
        LIMIT 1
    ");
    if ($ex && $ex->num_rows > 0) {
        continue;
    }

    $ordenBase++;

    // ======================
    // TRIVIA
    // ======================
    if ($tipo === "trivia") {

        $correcta = trim($p["correcta"] ?? "");
        $opciones = $p["opciones"] ?? [];

        if ($correcta === "" || !is_array($opciones) || count($opciones) !== 4) {
            echo json_encode(["ok"=>false,"mensaje"=>"Trivia inválida"]);
            exit;
        }

        $conn->query("
            INSERT INTO pregunta
            (id_quiz,id_dificultad,tipo,enunciado_preg,tiempo_preg,orden_preg)
            VALUES
            ($id_quiz,$id_dificultad,'trivia','$enunciadoEsc',25,$ordenBase)
        ");
        $id_p = $conn->insert_id;

        foreach ($opciones as $op) {
            $letra = $conn->real_escape_string($op["op"]);
            $texto = $conn->real_escape_string($op["texto"]);
            $ok    = ($letra === $correcta) ? 1 : 0;

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
            INSERT INTO pregunta
            (id_quiz,id_dificultad,tipo,enunciado_preg,tiempo_preg,orden_preg)
            VALUES
            ($id_quiz,$id_dificultad,'verdadero_falso','$enunciadoEsc',25,$ordenBase)
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
            echo json_encode(["ok"=>false,"mensaje"=>"Respuesta vacía en completar"]);
            exit;
        }

        $respuestaEsc = $conn->real_escape_string($respuesta);

        $conn->query("
            INSERT INTO pregunta
            (id_quiz,id_dificultad,tipo,enunciado_preg,respuesta_texto,tiempo_preg,orden_preg)
            VALUES
            ($id_quiz,$id_dificultad,'completar','$enunciadoEsc','$respuestaEsc',25,$ordenBase)
        ");
        continue;
    }

    echo json_encode(["ok"=>false,"mensaje"=>"Tipo inválido"]);
    exit;
}

echo json_encode(["ok"=>true]);
exit;
