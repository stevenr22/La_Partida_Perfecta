<?php
require "../db/conexion.php";
header("Content-Type: application/json");

$id_quiz = (int)($_GET["id_quiz"] ?? 0);

$res = $conn->query("
    SELECT d.id_dificultad, d.nombre_dificultad
    FROM quiz q
    JOIN dificultad d ON q.id_rol = d.id_rol
    WHERE q.id_quiz = $id_quiz
    ORDER BY d.orden
");

$dificultades = [];
while ($row = $res->fetch_assoc()) {
    $dificultades[] = $row;
}

echo json_encode($dificultades);
