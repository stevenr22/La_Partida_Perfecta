<?php

require "../db/conexion.php";

// ========================
// 1. Recibir datos
// ========================
$cedula    = trim($_POST["cedula"]);
$nombre    = trim($_POST["nombre"]);
$apellido  = trim($_POST["apellido"]);
$email     = trim($_POST["email"]);
$usuario   = trim($_POST["usuario"]);
$contrsena = trim($_POST["contrsena"]);

// ========================
// 2. Validación: campos vacíos
// ========================
if ($nombre === "" || $apellido === "" || $email === "" || $usuario === "" || $contrsena === "" || $cedula === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos son obligatorios."
    ]);
    exit;
}



// ========================
// 4. Validación: verificar SI EL EMAIL YA EXISTE
// ========================
$checkEmail = $conn->query("SELECT email_usu FROM usuario WHERE email_usu='$email' LIMIT 1");

if ($checkEmail->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "El correo ya está registrado."
    ]);
    exit;
}

// ========================
// 5. Validación: verificar SI EL USUARIO YA EXISTE
// ========================
$checkUser = $conn->query("SELECT usuario_usu FROM usuario WHERE usuario_usu='$usuario' LIMIT 1");

if ($checkUser->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "El nombre de usuario ya existe."
    ]);
    exit;
}

// ========================
// 6. Insertar datos
// ========================
$sql = "INSERT INTO usuario (
            cedula_usu,
            nombre_usu, 
            apellido_usu, 
            email_usu, 
            usuario_usu, 
            contrasena_usu
        )
        VALUES (
            '$cedula',
            '$nombre',
            '$apellido',
            '$email',
            '$usuario',
            '$contrsena'
        )";

if ($conn->query($sql)) {
    echo json_encode([
        "ok" => true,
        "mensaje" => "Registro exitoso."
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al guardar: " . $conn->error
    ]);
}
