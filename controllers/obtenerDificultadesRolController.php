<?php
session_start();
require "../db/conexion.php";
header("Content-Type: application/json; charset=UTF-8");

// rol del jugador tomado de sesión (lo guardaste en validarPinController)
$idRol = (int)($_SESSION["id_rol_jugador"] ?? 0);

if ($idRol <= 0) {
  echo json_encode([]);
  exit;
}

$res = $conn->query("
  SELECT id_dificultad, nombre_dificultad, orden
  FROM dificultad
  WHERE id_rol = $idRol
  ORDER BY orden ASC
");

$data = [];
if ($res) {
  while ($row = $res->fetch_assoc()) $data[] = $row;
}

echo json_encode($data);
exit;
