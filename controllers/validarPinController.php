<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json; charset=UTF-8");

if (!isset($_SESSION["id_usu_jugador"])) {
  echo json_encode(["ok"=>false, "mensaje"=>"Primero valida tu cédula"]);
  exit;
}

$idUsuJugador = (int)$_SESSION["id_usu_jugador"];
$pin = trim($_POST["pin"] ?? "");

if ($pin === "") {
  echo json_encode(["ok"=>false, "mensaje"=>"Debe ingresar el PIN"]);
  exit;
}

$pinEsc = $conn->real_escape_string($pin);

// 1) Buscar partida activa
$res = $conn->query("
  SELECT id_partida, id_quiz
  FROM partida
  WHERE pin = '$pinEsc'
    AND estado = 'esperando'
  LIMIT 1
");

if (!$res || $res->num_rows === 0) {
  echo json_encode(["ok"=>false, "mensaje"=>"PIN inválido o la partida ya inició"]);
  exit;
}

$partida = $res->fetch_assoc();
$idPartida = (int)$partida["id_partida"];
$idQuiz    = (int)$partida["id_quiz"];

// 2) Obtener rol del jugador (para tu selector de dificultad si lo necesitas)
$ur = $conn->query("
  SELECT id_rol, estado
  FROM usuario
  WHERE id_usu = $idUsuJugador
  LIMIT 1
");

if (!$ur || $ur->num_rows === 0) {
  echo json_encode(["ok"=>false, "mensaje"=>"No se encontró el usuario"]);
  exit;
}

$u = $ur->fetch_assoc();
if ((int)$u["estado"] !== 1) {
  echo json_encode(["ok"=>false, "mensaje"=>"Usuario inactivo"]);
  exit;
}

$_SESSION["id_rol_jugador"] = (int)$u["id_rol"];

// ✅ MARCAR ORIGEN
$_SESSION["origen_juego"] = "pin";

// 3) Guardar partida en sesión
$_SESSION["id_partida"] = $idPartida;
$_SESSION["id_quiz"]    = $idQuiz;

// (Opcional) marcar que ya está en juego
$conn->query("UPDATE partida SET estado='en_juego' WHERE id_partida=$idPartida");

// Limpieza de sesión del juego anterior (por si acaso)
unset($_SESSION["id_pregunta"], $_SESSION["correctas"], $_SESSION["total_resp"]);
foreach ($_SESSION as $k => $v) {
  if (strpos($k, "orden_actual_{$idPartida}_") === 0) unset($_SESSION[$k]);
}

echo json_encode(["ok"=>true]);
exit;
