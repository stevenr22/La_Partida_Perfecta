<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json; charset=UTF-8");

// ========================
// RECIBIR DATOS
// ========================
$cedula   = preg_replace('/\D+/', '', trim($_POST["cedula"] ?? ""));
$nombre   = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$rol      = (int)($_POST["rol"] ?? 0);

// ========================
// VALIDACIÓN
// ========================
if ($cedula === "" || $nombre === "" || $apellido === "" || $rol <= 0) {
  echo json_encode([
    "ok" => false,
    "mensaje" => "Completa todos los campos y selecciona el nivel"
  ]);
  exit;
}

if (!preg_match('/^\d{10}$/', $cedula)) {
  echo json_encode([
    "ok" => false,
    "mensaje" => "La cédula debe tener 10 dígitos"
  ]);
  exit;
}

// ========================
// ESCAPAR
// ========================
$ced = $conn->real_escape_string($cedula);
$nom = $conn->real_escape_string($nombre);
$ape = $conn->real_escape_string($apellido);

// ========================
// VERIFICAR SI EXISTE
// ========================
$ex = $conn->query("
  SELECT id_usu, estado, id_rol
  FROM usuario
  WHERE cedula_usu = '$ced'
  LIMIT 1
");

if ($ex && $ex->num_rows > 0) {
  $u = $ex->fetch_assoc();

  if ((int)$u["estado"] !== 1) {
    echo json_encode([
      "ok" => false,
      "mensaje" => "Usuario inactivo"
    ]);
    exit;
  }

  // Si ya existe, usar su rol actual
  $_SESSION["id_usu_jugador"] = (int)$u["id_usu"];

  echo json_encode([
    "ok" => true,
    "id_usu" => (int)$u["id_usu"],
    "ya_existia" => true,
    "id_rol" => (int)$u["id_rol"]
  ]);
  exit;
}

// ========================
// REGISTRO NUEVO
// ========================
$usuario_usu = "temp_" . $cedula;
$hash        = password_hash($cedula, PASSWORD_DEFAULT);

$userEsc = $conn->real_escape_string($usuario_usu);
$hashEsc = $conn->real_escape_string($hash);
$idRol   = (int)$rol;

$ok = $conn->query("
  INSERT INTO usuario
  (cedula_usu, nombre_usu, apellido_usu, usuario_usu, contrasena_usu, estado, id_rol, perfil_completo)
  VALUES
  ('$ced', '$nom', '$ape', '$userEsc', '$hashEsc', 1, $idRol, 0)
");

if (!$ok) {
  echo json_encode([
    "ok" => false,
    "mensaje" => "No se pudo registrar",
    "error" => $conn->error
  ]);
  exit;
}

// ========================
// SESIÓN Y RESPUESTA
// ========================
$id = (int)$conn->insert_id;
$_SESSION["id_usu_jugador"] = $id;

echo json_encode([
  "ok" => true,
  "id_usu" => $id,
  "ya_existia" => false,
  "id_rol" => $idRol
]);
exit;
