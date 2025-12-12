<?php

require "../db/conexion.php";
session_start();

// ========================
// 1. Recibir datos
// ========================
$usuario   = trim($_POST["usuario"]);
$contrasena = trim($_POST["contrasena"]);

// ========================
// 2. Validación: campos vacíos
// ========================
if ($usuario === "" || $contrasena === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos son obligatorios."
    ]);
    exit;
}

// ========================
// 3. Validación: verificar si el usuario esta correcto
// ========================
$sql = "SELECT * FROM usuario WHERE usuario_usu='$usuario'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Usuario incorrecto o no existente."
    ]);
    exit;
}




// ========================
// 4. Usuario encontrado → obtener datos
// ========================
$datosUsuario = $result->fetch_assoc();

// Contraseña guardada en BD (texto plano)
$passGuardada = $datosUsuario["contrasena_usu"];

// ========================
// 5. Validar contraseña
// ========================
if ($passGuardada !== $contrasena) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Contraseña incorrecta."
    ]);
    exit;
}

// ========================
// 6. Iniciar sesión
// ========================
$_SESSION["usuario_id"] = $datosUsuario["id_usu"]; 

// ========================
// 7. Respuesta final
// ========================
echo json_encode([
    "ok" => true,
    "mensaje" => "Inicio de sesión exitoso."
]);
exit;
