<?php
require "../db/conexion.php";
session_start();
header("Content-Type: application/json");

// =======================
// 1. Recibir PIN
// =======================
$pin = trim($_POST["pin"] ?? "");

// =======================
// 2. Validaciones básicas
// =======================
if ($pin === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Debe ingresar el PIN"
    ]);
    exit;
}

// =======================
// 3. Buscar partida activa
// =======================
$sql = "
    SELECT id_partida, id_quiz
    FROM partida
    WHERE pin = '$pin'
    AND estado = 'esperando'
";

$res = $conn->query($sql);

if (!$res || $res->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "PIN inválido o la partida ya inició"
    ]);
    exit;
}

// =======================
// 4. Partida válida
// =======================
$partida = $res->fetch_assoc();

// Guardar datos de la partida en sesión
$_SESSION["id_partida"] = $partida["id_partida"];
$_SESSION["id_quiz"]    = $partida["id_quiz"];

// (opcional) marcar que está en juego
$conn->query("
    UPDATE partida
    SET estado = 'en_juego'
    WHERE id_partida = {$partida['id_partida']}
");

// =======================
// 5. Respuesta OK
// =======================
echo json_encode([
    "ok" => true
]);
exit;
