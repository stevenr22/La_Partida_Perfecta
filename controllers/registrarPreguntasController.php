<?php
require "../db/conexion.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$id_quiz = (int)($data["id_quiz"] ?? 0);
$id_dificultad = (int)($data["id_dificultad"] ?? 0);
$enunciado = trim($data["enunciado_preg"] ?? "");
$correcta = $data["correcta"] ?? "";
$opciones = $data["opciones"] ?? [];

if ($id_quiz === 0 || $id_dificultad === 0 || $enunciado === "" || count($opciones) < 4) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Datos incompletos"
    ]);
    exit;
}

/* =========================
   VALIDAR LIMITE 10
========================= */
$conteo = $conn->query("
    SELECT COUNT(*) AS total
    FROM pregunta
    WHERE id_quiz = $id_quiz
    AND id_dificultad = $id_dificultad
")->fetch_assoc()["total"];

if ($conteo >= 10) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Ya existen 10 preguntas para esta dificultad"
    ]);
    exit;
}

/* =========================
   ORDEN AUTOMÁTICO
========================= */
$orden = $conn->query("
    SELECT IFNULL(MAX(orden_preg),0) + 1 AS orden
    FROM pregunta
    WHERE id_quiz = $id_quiz
")->fetch_assoc()["orden"];

/* =========================
   INSERTAR PREGUNTA
========================= */
$conn->query("
    INSERT INTO pregunta (id_quiz, enunciado_preg, id_dificultad, orden_preg)
    VALUES ($id_quiz, '$enunciado', $id_dificultad, $orden)
");

$id_pregunta = $conn->insert_id;

/* =========================
   INSERTAR OPCIONES
========================= */
foreach ($opciones as $op) {
    $texto = trim($op["texto"]);
    $letra = $op["op"];
    $esCorrecta = ($letra === $correcta) ? 1 : 0;

    $conn->query("
        INSERT INTO opcion (id_pregunta, texto_opc, es_correcta)
        VALUES ($id_pregunta, '$texto', $esCorrecta)
    ");
}

echo json_encode(["ok" => true]);
exit;
