<?php
require "../db/conexion.php";
header("Content-Type: application/json");

$id_quiz = (int)($_POST["id_quiz"] ?? 0);

if ($id_quiz === 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Quizz inválido"
    ]);
    exit;
}

/* =========================
   GENERAR PIN ÚNICO
========================= */
do {
    $pin = strval(rand(100000, 999999));
    $check = $conn->query("SELECT id_partida FROM partida WHERE pin = '$pin'");
} while ($check->num_rows > 0);

/* =========================
   CREAR PARTIDA
========================= */
$conn->query("
    INSERT INTO partida (id_quiz, pin, estado)
    VALUES ($id_quiz, '$pin', 'esperando')
");

echo json_encode([
    "ok" => true,
    "pin" => $pin
]);
exit;
