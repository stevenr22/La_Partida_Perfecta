<?php
require "../db/conexion.php";
session_start();

ini_set('display_errors', 0);
error_reporting(0);
header("Content-Type: application/json; charset=UTF-8");

// 1) Validar sesión (SIN redirect)
if (!isset($_SESSION["id_partida"], $_SESSION["id_quiz"])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "mensaje" => "Sesión expirada o PIN inválido"]);
    exit;
}

$idPartida = (int)$_SESSION["id_partida"];
$idQuiz    = (int)$_SESSION["id_quiz"];

// 2) Orden actual
if (!isset($_SESSION["orden_actual"])) {
    $_SESSION["orden_actual"] = 1;
}
$orden = (int)$_SESSION["orden_actual"];

// 3) Buscar pregunta por orden
$q = $conn->query("
  SELECT id_pregunta, enunciado_preg, tipo, tiempo_preg
  FROM pregunta
  WHERE id_quiz = $idQuiz AND orden_preg = $orden
  LIMIT 1
");

if (!$q || $q->num_rows === 0) {
    echo json_encode(["ok" => false, "mensaje" => "No hay más preguntas"]);
    exit;
}

$p = $q->fetch_assoc();
$idPregunta = (int)$p["id_pregunta"];
$tipo = $p["tipo"];

// ✅ GUARDAR id_pregunta en sesión para responder
$_SESSION["id_pregunta"] = $idPregunta;

// 4) Opciones
$opciones = [];
if ($tipo === "completar") {
    // En completar NO hay opciones, solo un input (front lo dibuja)
    $opciones = [];
} else {
    $rs = $conn->query("
        SELECT texto_opc
        FROM opcion
        WHERE id_pregunta = $idPregunta
        ORDER BY id_opcion ASC
    ");

    $letras = ($tipo === "verdadero_falso") ? ["V", "F"] : ["A", "B", "C", "D"];
    $i = 0;

    while ($o = $rs->fetch_assoc()) {
        $opciones[] = [
            "letra" => $letras[$i] ?? chr(65 + $i),
            "texto" => $o["texto_opc"]
        ];
        $i++;
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
