<?php
require "../db/conexion.php";
session_start();
header('Content-Type: application/json');

// ========================
// 1. Recibir datos
// ========================
$nombre_quizz    = trim($_POST["nombre_quizz"] ?? '');
$descripcion_quizz = trim($_POST["descripcion_quizz"] ?? '');
$nivel_estudio   = trim($_POST["nivel_estudio"] ?? '');
$id_usuario      = trim($_POST["id_usuario"] ?? ''); // id del profesor

// ========================
// 2. Validación
// ========================
if ($nombre_quizz === "" || $nivel_estudio === "" || $id_usuario === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos obligatorios deben estar completos."
    ]);
    exit;
}

// ========================
// 3. Verificar quizz existente por nombre y profesor
// ========================
$checkQuiz = $conn->query("SELECT id_quiz FROM quiz WHERE nombre_quiz='$nombre_quizz' AND id_profesor='$id_usuario'");
if ($checkQuiz->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Ya existe un quizz con este nombre para este profesor."
    ]);
    exit;
}

// ========================
// 4. Insertar datos
// ========================
$sql = "INSERT INTO quiz (
            nombre_quiz,
            descripcion_quiz,
            nivel_estudio,
            id_profesor
            
           
        )
        VALUES (
            '$nombre_quizz',
            '$descripcion_quizz',
            '$nivel_estudio',
            '$id_usuario'
            
        )";

if ($conn->query($sql)) {
    echo json_encode([
        "ok" => true,
        "mensaje" => "Quizz registrado exitosamente."
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al guardar el quizz: " . $conn->error
    ]);
}
exit;
