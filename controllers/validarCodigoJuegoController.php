<?php
require "../db/conexion.php";
session_start();

$codigo = trim($_POST["codigo"] ?? "");

if ($codigo === "") {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Código vacío."
    ]);
    exit;
}

// EJEMPLO SIMPLE (luego lo conectas a BD)
$codigoValido = "ABC123";

if ($codigo !== $codigoValido) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Código incorrecto."
    ]);
    exit;
}

// Guardar código en sesión si deseas
$_SESSION["codigo_juego"] = $codigo;

echo json_encode([
    "ok" => true,
    "mensaje" => "Código válido"
]);
exit;
