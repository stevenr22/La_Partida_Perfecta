<?php
require "../db/conexion.php";
session_start();

ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

$usuario    = trim($_POST["usuario"] ?? "");
$contrasena = trim($_POST["contrasena"] ?? "");

if ($usuario === "" || $contrasena === "") {
  echo json_encode(["ok" => false, "mensaje" => "Todos los campos son obligatorios."]);
  exit;
}

$userEsc = $conn->real_escape_string($usuario);

$sql = "SELECT id_usu, usuario_usu, contrasena_usu, estado
        FROM usuario
        WHERE usuario_usu='$userEsc'
        LIMIT 1";

$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
  echo json_encode(["ok" => false, "mensaje" => "Usuario incorrecto o no existente."]);
  exit;
}

$datosUsuario = $result->fetch_assoc();

if ((int)$datosUsuario["estado"] !== 1) {
  echo json_encode(["ok" => false, "mensaje" => "Usuario inactivo."]);
  exit;
}

$passGuardada = $datosUsuario["contrasena_usu"] ?? "";

// Detectar hash
$esHash = password_get_info($passGuardada)["algo"] !== 0;

$valida = false;

if ($esHash) {
  $valida = password_verify($contrasena, $passGuardada);
} else {
  $valida = ($passGuardada === $contrasena);

  // migrar a hash si la contraseña vieja era texto plano
  if ($valida) {
    $nuevoHash = password_hash($contrasena, PASSWORD_DEFAULT);
    $hashEsc = $conn->real_escape_string($nuevoHash);
    $id = (int)$datosUsuario["id_usu"];
    $conn->query("UPDATE usuario SET contrasena_usu='$hashEsc' WHERE id_usu=$id");
  }
}

if (!$valida) {
  echo json_encode(["ok" => false, "mensaje" => "Contraseña incorrecta."]);
  exit;
}

$_SESSION["usuario_id"] = (int)$datosUsuario["id_usu"];

echo json_encode(["ok" => true, "mensaje" => "Inicio de sesión exitoso."]);
exit;
