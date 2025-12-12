<?php
require "../db/conexion.php";
session_start();
// ========================
// 1. Recibir datos
// ========================
$cedula   = trim($_POST["cedula"]);

// ========================
// 2. Validación: campos vacíos
// ========================
if ($cedula === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos son obligatorios."
    ]);
    exit;
}



// ===================================
// CONSULTA SIN CONSULTAS PREPARADAS
// ===================================
$sql = "SELECT nombre_usu, apellido_usu FROM usuario WHERE cedula_usu = '$cedula' and estado_usu = 1";
$result = $conn->query($sql);

// Usuario encontrado
if ($result->num_rows > 0) {

    $fila = $result->fetch_assoc();
    $nombreCompleto = $fila["nombre_usu"] . " " . $fila["apellido_usu"];

    echo json_encode([
        "ok" => true,
        "nombre" => $nombreCompleto
    ]);
    exit;
}

// Usuario NO encontrado
echo json_encode([
    "ok" => false,
    "mensaje" => "No existe un usuario con esa cédula."
]);
exit;