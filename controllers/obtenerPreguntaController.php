<?php
require "../db/conexion.php";
session_start();

ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

// 1) Validar sesión
if (!isset($_SESSION["id_partida"], $_SESSION["id_quiz"])) {
  http_response_code(401);
  echo json_encode(["ok" => false, "mensaje" => "Sesión expirada"]);
  exit;
}

$idPartida = (int)$_SESSION["id_partida"];
$idQuiz    = (int)$_SESSION["id_quiz"];

// 2) Dificultad obligatoria
$idDificultad = (int)($_SESSION["id_dificultad"] ?? 0);
if ($idDificultad <= 0) {
  http_response_code(400);
  echo json_encode(["ok" => false, "mensaje" => "Primero selecciona la dificultad"]);
  exit;
}

// 3) Validar dificultad vs rol (opcional recomendado)
$idRolJugador = (int)($_SESSION["id_rol_jugador"] ?? 0);
if ($idRolJugador > 0) {
  $chk = $conn->query("
    SELECT 1
    FROM dificultad
    WHERE id_dificultad = $idDificultad
      AND id_rol = $idRolJugador
    LIMIT 1
  ");
  if (!$chk || $chk->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["ok"=>false, "mensaje"=>"Dificultad no permitida para tu rol"]);
    exit;
  }
}

// ✅ 4) ORDEN ACTUAL (CLAVE ÚNICA POR PARTIDA+DIFICULTAD)
$claveOrden = "orden_actual_{$idPartida}_{$idDificultad}";
if (!isset($_SESSION[$claveOrden])) $_SESSION[$claveOrden] = 1;
$orden = (int)$_SESSION[$claveOrden];

// 5) Buscar pregunta por quiz + dificultad + orden
$q = $conn->query("
  SELECT id_pregunta, enunciado_preg, tipo, tiempo_preg
  FROM pregunta
  WHERE id_quiz = $idQuiz
    AND id_dificultad = $idDificultad
    AND orden_preg = $orden
  LIMIT 1
");

if (!$q || $q->num_rows === 0) {
  echo json_encode(["ok" => false, "mensaje" => "No hay más preguntas"]);
  exit;
}

$p = $q->fetch_assoc();
$idPregunta = (int)$p["id_pregunta"];
$tipo = $p["tipo"];

// Guardar id_pregunta para responder
$_SESSION["id_pregunta"] = $idPregunta;

// 6) Opciones
$opciones = [];
if ($tipo !== "completar") {
  $rs = $conn->query("
    SELECT texto_opc
    FROM opcion
    WHERE id_pregunta = $idPregunta
    ORDER BY id_opcion ASC
  ");

  $letras = ($tipo === "verdadero_falso") ? ["V", "F"] : ["A", "B", "C", "D"];
  $i = 0;

  if ($rs) {
    while ($o = $rs->fetch_assoc()) {
      $opciones[] = [
        "letra" => $letras[$i] ?? chr(65 + $i),
        "texto" => $o["texto_opc"]
      ];
      $i++;
    }
  }
}

echo json_encode([
  "ok" => true,
  "id_pregunta" => $idPregunta,
  "pregunta" => $p["enunciado_preg"],
  "tipo" => $tipo,
  "tiempo" => (int)$p["tiempo_preg"],
  "opciones" => $opciones
]);
exit;
