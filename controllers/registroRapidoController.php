<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json; charset=UTF-8");

$nombre = trim($_POST["nombre"] ?? "");
$rol    = (int)($_POST["nivel"] ?? 0);

if ($nombre === "" || $rol <= 0) {
  echo json_encode(["ok"=>false, "mensaje"=>"Nombre y nivel son obligatorios"]);
  exit;
}

// Solo letras y espacios
if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) {
  echo json_encode(["ok"=>false, "mensaje"=>"El nombre solo debe contener letras"]);
  exit;
}

$nomEsc = $conn->real_escape_string($nombre);
$idRol  = $rol;

// Generar un usuario temporal único
$rand = bin2hex(random_bytes(3)); // 6 chars
$userTemp = "temp_" . $rand;
$userEsc  = $conn->real_escape_string($userTemp);

// Password temporal (random)
$passTemp = bin2hex(random_bytes(4)); // 8 chars
$hash = password_hash($passTemp, PASSWORD_DEFAULT);
$hashEsc = $conn->real_escape_string($hash);

// Como aún no tienes cédula: guardamos algo temporal
// (si tu campo cedula_usu es NOT NULL, debes poner algo único)
$cedTemp = "TEMP" . strtoupper($rand) . "00"; // 10 chars aprox
$cedEsc  = $conn->real_escape_string($cedTemp);

$ok = $conn->query("
  INSERT INTO usuario
  (cedula_usu, nombre_usu, apellido_usu, usuario_usu, contrasena_usu, estado, id_rol, perfil_completo)
  VALUES
  ('$cedEsc', '$nomEsc', '', '$userEsc', '$hashEsc', 1, $idRol, 0)
");

if (!$ok) {
  echo json_encode(["ok"=>false, "mensaje"=>"No se pudo registrar", "error"=>$conn->error]);
  exit;
}

$id = (int)$conn->insert_id;
$_SESSION["id_usu_jugador"] = $id;

echo json_encode([
  "ok" => true,
  "id_usu" => $id,
  "nombre" => $nombre,
  "id_rol" => $idRol
]);
exit;
