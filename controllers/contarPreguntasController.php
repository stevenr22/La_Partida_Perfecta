<?php
require "../db/conexion.php";
header("Content-Type: application/json");

$id_quiz = (int)$_GET["id_quiz"];
$id_dificultad = (int)$_GET["id_dificultad"];

$res = $conn->query("
    SELECT COUNT(*) AS total
    FROM pregunta
    WHERE id_quiz = $id_quiz
    AND id_dificultad = $id_dificultad
");

echo json_encode($res->fetch_assoc());
