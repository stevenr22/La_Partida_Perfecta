<?php
require "../db/conexion.php";
session_start();

ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

// Validar sesión mínima
if (!isset($_SESSION["id_partida"], $_SESSION["id_quiz"], $_SESSION["id_pregunta"])) {
  http_response_code(401);
  echo json_encode(["correcta" => false, "mensaje" => "Sesión inválida"]);
  exit;
}

$idPartida   = (int)$_SESSION["id_partida"];
$idQuiz      = (int)$_SESSION["id_quiz"];
$idPregunta  = (int)$_SESSION["id_pregunta"];

$idDificultad = (int)($_SESSION["id_dificultad"] ?? 0);
if ($idDificultad <= 0) {
  http_response_code(400);
  echo json_encode(["correcta"=>false, "mensaje"=>"Falta dificultad"]);
  exit;
}

// ✅ clave de orden (MISMA QUE obtenerPregunta)
$claveOrden = "orden_actual_{$idPartida}_{$idDificultad}";
if (!isset($_SESSION[$claveOrden])) $_SESSION[$claveOrden] = 1;

// contadores
if (!isset($_SESSION["total_resp"])) $_SESSION["total_resp"] = 0;
if (!isset($_SESSION["correctas"])) $_SESSION["correctas"] = 0;

$respuesta = $_POST["respuesta"] ?? "";

// obtener tipo y respuesta_texto
$qp = $conn->query("
  SELECT tipo, respuesta_texto
  FROM pregunta
  WHERE id_pregunta = $idPregunta
  LIMIT 1
");

$p = $qp ? $qp->fetch_assoc() : null;
if (!$p) {
  echo json_encode(["correcta" => false]);
  exit;
}

$tipo = $p["tipo"];
$correcta = false;

// TIMEOUT
if ($respuesta === "__TIMEOUT__") {
  $_SESSION["total_resp"] = (int)$_SESSION["total_resp"] + 1;

  // ✅ avanzar orden
  $_SESSION[$claveOrden] = (int)$_SESSION[$claveOrden] + 1;

  echo json_encode(["correcta" => false]);
  exit;
}

// COMPLETAR
if ($tipo === "completar") {
  $respUser = mb_strtolower(trim($respuesta));
  $respBd   = mb_strtolower(trim($p["respuesta_texto"] ?? ""));

  $correcta = ($respUser !== "" && $respUser === $respBd);

  $_SESSION["total_resp"] = (int)$_SESSION["total_resp"] + 1;
  if ($correcta) $_SESSION["correctas"] = (int)$_SESSION["correctas"] + 1;

  // ✅ avanzar orden
  $_SESSION[$claveOrden] = (int)$_SESSION[$claveOrden] + 1;

  echo json_encode(["correcta" => $correcta]);
  exit;
}

// TRIVIA / V-F
$res = $conn->query("
  SELECT es_correcta
  FROM opcion
  WHERE id_pregunta = $idPregunta
  ORDER BY id_opcion ASC
");

$letras = ($tipo === "verdadero_falso") ? ["V","F"] : ["A","B","C","D"];
$i = 0;

if ($res) {
  while ($row = $res->fetch_assoc()) {
    $letraActual = $letras[$i] ?? "";
    if ($letraActual === $respuesta && (int)$row["es_correcta"] === 1) {
      $correcta = true;
      break;
    }
    $i++;
  }
}

$_SESSION["total_resp"] = (int)$_SESSION["total_resp"] + 1;
if ($correcta) $_SESSION["correctas"] = (int)$_SESSION["correctas"] + 1;

// ✅ avanzar orden
$_SESSION[$claveOrden] = (int)$_SESSION[$claveOrden] + 1;

echo json_encode(["correcta" => $correcta]);
exit;
