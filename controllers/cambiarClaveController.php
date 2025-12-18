<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json; charset=UTF-8");

$cedula = preg_replace('/\D+/', '', trim($_POST["cedula"] ?? ""));
$nueva  = trim($_POST["nueva_clave"] ?? "");

if ($cedula === "" || $nueva === "") {
  echo json_encode(["ok"=>false, "mensaje"=>"Debe ingresar la nueva contraseña"]);
  exit;
}
if (strlen($nueva) < 6) {
  echo json_encode(["ok"=>false, "mensaje"=>"Mínimo 6 caracteres"]);
  exit;
}

$cedEsc = $conn->real_escape_string($cedula);
$hash   = password_hash($nueva, PASSWORD_DEFAULT);
$hashEsc = $conn->real_escape_string($hash);

$ok = $conn->query("
  UPDATE usuario
  SET contrasena_usu = '$hashEsc'
  WHERE cedula_usu = '$cedEsc'
");

if ($ok && $conn->affected_rows > 0) {
  echo json_encode(["ok"=>true, "mensaje"=>"Contraseña actualizada correctamente ✔"]);
} else {
  echo json_encode(["ok"=>false, "mensaje"=>"No se encontró el usuario"]);
}
exit;
