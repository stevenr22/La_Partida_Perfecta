<?php
header('Content-Type: application/json; charset=utf-8');
require "../db/conexion.php";

// ========================
// RECIBIR DATOS
// ========================
$cedula        = trim($_POST["cedula"] ?? "");
$nombre        = trim($_POST["nombre"] ?? "");
$apellido      = trim($_POST["apellido"] ?? "");
$usuario       = trim($_POST["usuario"] ?? "");
$contrasena    = trim($_POST["contrasena"] ?? "");
$nivel_estudio = trim($_POST["nivel_estudio"] ?? "");

// ========================
// VALIDACIÓN
// ========================
if ($cedula==="" || $nombre==="" || $apellido==="" || $usuario==="" || $contrasena==="" || $nivel_estudio==="") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Todos los campos son obligatorios."
    ]);
    exit;
}

// ========================
// VERIFICAR USUARIO
// ========================
$usuarioEsc = mysqli_real_escape_string($conn, $usuario);
$check = $conn->query("SELECT 1 FROM usuario WHERE usuario_usu='$usuarioEsc' LIMIT 1");

if ($check && $check->num_rows > 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "El usuario ya existe."
    ]);
    exit;
}

// ========================
// INSERTAR
// ========================
$cedulaEsc   = mysqli_real_escape_string($conn, $cedula);
$nombreEsc   = mysqli_real_escape_string($conn, $nombre);
$apellidoEsc = mysqli_real_escape_string($conn, $apellido);
$passHash    = password_hash($contrasena, PASSWORD_BCRYPT);
$passEsc     = mysqli_real_escape_string($conn, $passHash);
$rol         = (int)$nivel_estudio;

$sql = "INSERT INTO usuario
        (cedula_usu, nombre_usu, apellido_usu, usuario_usu, contrasena_usu, id_rol, estado, perfil_completo)
        VALUES
        ('$cedulaEsc','$nombreEsc','$apellidoEsc','$usuarioEsc','$passEsc',$rol,1,0)";

if ($conn->query($sql)) {
    echo json_encode([
        "ok" => true,
        "mensaje" => "Registro exitoso"
    ]);
} else {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Error SQL: ".$conn->error
    ]);
}
exit;
