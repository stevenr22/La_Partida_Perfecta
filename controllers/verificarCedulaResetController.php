<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json; charset=UTF-8");

$cedula = preg_replace('/\D+/', '', trim($_POST["cedula"] ?? ""));
if ($cedula === "" || strlen($cedula) < 6) {
  echo json_encode(["ok"=>false, "mensaje"=>"Ingrese una cédula válida"]);
  exit;
}

$ced = $conn->real_escape_string($cedula);

$q = $conn->query("
  SELECT id_usu, cedula_usu, nombre_usu, apellido_usu, estado
  FROM usuario
  WHERE cedula_usu='$ced'
  LIMIT 1
");

if (!$q || $q->num_rows === 0) {
  echo json_encode(["ok"=>false, "mensaje"=>"No existe un usuario con esa cédula"]);
  exit;
}

$u = $q->fetch_assoc();
if ((int)$u["estado"] !== 1) {
  echo json_encode(["ok"=>false, "mensaje"=>"Usuario inactivo"]);
  exit;
}

echo json_encode(["ok"=>true, "usuario"=>$u]);
exit;
