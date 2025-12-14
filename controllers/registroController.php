<?php
require "../db/conexion.php";

// ========================
// 1. Recibir datos
// ========================
$cedula        = trim($_POST["cedula"]);
$nombre        = trim($_POST["nombre"]);
$apellido      = trim($_POST["apellido"]);
$usuario       = trim($_POST["usuario"]);
$contrasena     = trim($_POST["contrasena"]);
$nivel_estudio = trim($_POST["nivel_estudio"]);

// ========================
// 2. Validación
// ========================
if (
    $cedula === "" ||
    $nombre === "" ||
    $apellido === "" ||
    $usuario === "" ||
    $contrasena === "" ||
    $nivel_estudio === ""
) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos son obligatorios."
    ]);
    exit;
}

// ========================
// 3. Verificar usuario existente
// ========================
$checkUser = $conn->query("SELECT usuario_usu FROM usuario WHERE usuario_usu='$usuario'");

if ($checkUser->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "El nombre de usuario ya existe."
    ]);
    exit;
}

// ========================
// 4. Insertar datos
// ========================
$sql = "INSERT INTO usuario (
            cedula_usu,
            nombre_usu, 
            apellido_usu, 
            usuario_usu, 
            contrasena_usu,
            id_rol
        )
        VALUES (
            '$cedula',
            '$nombre',
            '$apellido',
            '$usuario',
            '$contrasena',
            '$nivel_estudio'
        )";

if ($conn->query($sql)) {
    echo json_encode([
        "ok" => true,
        "mensaje" => "Registro exitoso."
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error al guardar."
    ]);
}
exit;
