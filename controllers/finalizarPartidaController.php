<?php
require "../db/conexion.php";
session_start();

ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

/* =========================
   VALIDAR SESIÓN
========================= */
if (
  !isset($_SESSION["id_partida"], $_SESSION["id_quiz"], $_SESSION["id_dificultad"])
) {
  http_response_code(401);
  echo json_encode(["ok"=>false, "mensaje"=>"Sesión inválida"]);
  exit;
}

$idPartida    = (int)$_SESSION["id_partida"];
$idQuiz       = (int)$_SESSION["id_quiz"];
$idDificultad = (int)$_SESSION["id_dificultad"];

// usuario
$idUsu = 0;
if (isset($_SESSION["usuario_id"])) {
  $idUsu = (int)$_SESSION["usuario_id"];
} elseif (isset($_SESSION["id_usu_jugador"])) {
  $idUsu = (int)$_SESSION["id_usu_jugador"];
}

// =========================
// 🔑 LEER CONTADORES (NO BORRAR)
// =========================
$correctas = (int)($_SESSION["correctas"] ?? 0);
$total     = (int)($_SESSION["total_resp"] ?? 0);

// regla de aprobación (ajusta si quieres)
$minimo = 0.6; // 60%
$aprobado = ($total > 0 && ($correctas / $total) >= $minimo) ? 1 : 0;

// =========================
// GUARDAR RESULTADO
// =========================
$conn->query("
  INSERT INTO resultado_partida
  (id_partida, id_quiz, id_dificultad, id_usu, correctas, total, aprobado, fecha)
  VALUES
  ($idPartida, $idQuiz, $idDificultad, $idUsu, $correctas, $total, $aprobado, NOW())
");

// =========================
// RESPUESTA AL FRONTEND
// =========================
echo json_encode([
  "ok"            => true,
  "correctas"     => $correctas,
  "total"         => $total,
  "aprobado"      => (bool)$aprobado,
  "id_dificultad" => $idDificultad
]);

// =========================
// LIMPIAR SESIÓN DEL JUEGO
// (DESPUÉS DE RESPONDER)
// =========================
unset($_SESSION["id_pregunta"]);
unset($_SESSION["correctas"]);
unset($_SESSION["total_resp"]);

exit;
