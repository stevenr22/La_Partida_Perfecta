<?php
session_start();
require "../db/conexion.php";

ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

$idUsu = 0;
if (isset($_SESSION["id_usu_jugador"])) $idUsu = (int)$_SESSION["id_usu_jugador"];
else if (isset($_SESSION["usuario_id"])) $idUsu = (int)$_SESSION["usuario_id"];

if ($idUsu <= 0) {
  http_response_code(401);
  echo json_encode(["ok"=>false, "mensaje"=>"Sesión expirada. Vuelve a ingresar."]);
  exit;
}

$nombre = trim($_POST["nombre"] ?? "");
$apellido = trim($_POST["apellido"] ?? "");
$usuario = trim($_POST["usuario"] ?? "");
$contrasena = trim($_POST["contrasena"] ?? "");
$nivel = (int)($_POST["nivel_estudio"] ?? 0);

if ($nombre==="" || $apellido==="" || $usuario==="" || $contrasena==="" || $nivel<=0) {
  echo json_encode(["ok"=>false, "mensaje"=>"Completa todos los campos."]);
  exit;
}

if (mb_strlen($contrasena) < 6) {
  echo json_encode(["ok"=>false, "mensaje"=>"La contraseña debe tener al menos 6 caracteres."]);
  exit;
}

$nombreEsc = $conn->real_escape_string($nombre);
$apeEsc = $conn->real_escape_string($apellido);
$userEsc = $conn->real_escape_string($usuario);

$dup = $conn->query("
  SELECT id_usu
  FROM usuario
  WHERE usuario_usu='$userEsc'
    AND id_usu <> $idUsu
  LIMIT 1
");

if ($dup && $dup->num_rows > 0) {
  echo json_encode(["ok"=>false, "mensaje"=>"Ese nombre de usuario ya está en uso."]);
  exit;
}

$hash = password_hash($contrasena, PASSWORD_DEFAULT);
$hashEsc = $conn->real_escape_string($hash);

$ok = $conn->query("
  UPDATE usuario
  SET
    nombre_usu='$nombreEsc',
    apellido_usu='$apeEsc',
    usuario_usu='$userEsc',
    contrasena_usu='$hashEsc',
    id_rol=$nivel,
    perfil_completo=1
  WHERE id_usu=$idUsu
  LIMIT 1
");

if (!$ok) {
  echo json_encode(["ok"=>false, "mensaje"=>"Error al actualizar.", "error"=>$conn->error]);
  exit;
}

echo json_encode(["ok"=>true, "mensaje"=>"✅ Perfil completado. Ya puedes iniciar sesión."]);
exit;
