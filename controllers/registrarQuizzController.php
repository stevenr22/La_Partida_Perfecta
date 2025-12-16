<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json");

// ========================
// 1. Recibir datos
// ========================
$nombre_quizz = trim($_POST["nombre_quizz"] ?? "");
$descripcion_quizz = trim($_POST["descripcion_quizz"] ?? "");
$id_rol = (int)($_POST["id_rol"] ?? 0);
$id_usuario = (int)($_POST["id_usuario"] ?? 0);

// ========================
// 2. Validación
// ========================
if ($nombre_quizz === "" || $id_rol === 0 || $id_usuario === 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos obligatorios deben estar completos."
    ]);
    exit;
}

// ========================
// 3. Validar que no exista quizz repetido
// ========================
$verificar = $conn->query("
    SELECT id_quiz 
    FROM quiz 
    WHERE nombre_quiz = '$nombre_quizz'
    AND id_profesor = $id_usuario
");

if ($verificar->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Ya existe un quizz con este nombre."
    ]);
    exit;
}

// ========================
// 4. Insertar quizz
// ========================
$sql = "
    INSERT INTO quiz (
        nombre_quiz,
        descripcion_quiz,
        id_rol,
        id_profesor
    ) VALUES (
        '$nombre_quizz',
        '$descripcion_quizz',
        $id_rol,
        $id_usuario
    )
";

if ($conn->query($sql)) {
    echo json_encode([
        "ok" => true,
        "mensaje" => "Quizz registrado correctamente."
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al registrar el quizz: " . $conn->error
    ]);
}
exit;
