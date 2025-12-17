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

if (!isset($_SESSION["orden_actual"])) {
    $_SESSION["orden_actual"] = 1;
}

$respuesta  = $_POST["respuesta"] ?? "";
$idPregunta = (int)$_SESSION["id_pregunta"];

// Obtener tipo y respuesta_texto
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

// TIMEOUT -> siempre incorrecto
if ($respuesta === "__TIMEOUT__") {
    $correcta = false;

    $_SESSION["orden_actual"] = (int)$_SESSION["orden_actual"] + 1;

    echo json_encode(["correcta" => $correcta]);
    exit;
}

// =====================
// COMPLETAR (texto)
// =====================
if ($tipo === "completar") {
    $respUser = mb_strtolower(trim($respuesta));
    $respBd   = mb_strtolower(trim($p["respuesta_texto"] ?? ""));

    $correcta = ($respUser !== "" && $respUser === $respBd);

    $_SESSION["orden_actual"] = (int)$_SESSION["orden_actual"] + 1;

    echo json_encode(["correcta" => $correcta]);
    exit;
}

// =====================
// TRIVIA / V-F (opciones)
// =====================
$res = $conn->query("
    SELECT es_correcta
    FROM opcion
    WHERE id_pregunta = $idPregunta
    ORDER BY id_opcion ASC
");

$letras = ($tipo === "verdadero_falso") ? ["V","F"] : ["A","B","C","D"];
$i = 0;

while ($row = $res->fetch_assoc()) {
    $letraActual = $letras[$i] ?? "";
    if ($letraActual === $respuesta && (int)$row["es_correcta"] === 1) {
        $correcta = true;
        break;
    }
    $i++;
}

// Avanzar de pregunta
$_SESSION["orden_actual"] = (int)$_SESSION["orden_actual"] + 1;

echo json_encode([
    "correcta" => $correcta
]);
exit;
