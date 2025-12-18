<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

$idDif = (int)($_POST["id_dificultad"] ?? 0);

if ($idDif <= 0) {
  echo json_encode(["ok"=>false, "mensaje"=>"Selecciona una dificultad válida"]);
  exit;
}

$_SESSION["id_dificultad"] = $idDif;

echo json_encode(["ok"=>true]);
exit;
