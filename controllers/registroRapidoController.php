<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json; charset=UTF-8");

$cedula   = preg_replace('/\D+/', '', trim($_POST["cedula"] ?? ""));
$nombre   = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");

if ($cedula === "" || $nombre === "" || $apellido === "") {
  echo json_encode(["ok"=>false, "mensaje"=>"Completa cédula, nombre y apellido"]);
  exit;
}

$ced = $conn->real_escape_string($cedula);
$nom = $conn->real_escape_string($nombre);
$ape = $conn->real_escape_string($apellido);

$ex = $conn->query("SELECT id_usu, estado, id_rol FROM usuario WHERE cedula_usu='$ced' LIMIT 1");
if ($ex && $ex->num_rows > 0) {
  $u = $ex->fetch_assoc();
  if ((int)$u["estado"] !== 1) {
    echo json_encode(["ok"=>false, "mensaje"=>"Usuario inactivo"]);
    exit;
  }
  $_SESSION["id_usu_jugador"] = (int)$u["id_usu"];
  echo json_encode(["ok"=>true, "id_usu"=>(int)$u["id_usu"], "ya_existia"=>true]);
  exit;
}

$usuario_usu = "temp_" . $cedula;
$hash = password_hash($cedula, PASSWORD_DEFAULT);

$userEsc = $conn->real_escape_string($usuario_usu);
$hashEsc = $conn->real_escape_string($hash);

$idRolDefault = 1; // Básico

$ok = $conn->query("
  INSERT INTO usuario (cedula_usu, nombre_usu, apellido_usu, usuario_usu, contrasena_usu, estado, id_rol, perfil_completo)
  VALUES ('$ced', '$nom', '$ape', '$userEsc', '$hashEsc', 1, $idRolDefault, 0)
");

if (!$ok) {
  echo json_encode(["ok"=>false, "mensaje"=>"No se pudo registrar", "error"=>$conn->error]);
  exit;
}

$id = (int)$conn->insert_id;
$_SESSION["id_usu_jugador"] = $id;

echo json_encode(["ok"=>true, "id_usu"=>$id, "ya_existia"=>false]);
exit;
