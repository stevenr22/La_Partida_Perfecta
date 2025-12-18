<?php
require "../db/conexion.php";
session_start();

/* =====================
   VALIDAR SESIÓN
===================== */
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$idUsu = (int)$_SESSION["usuario_id"];
$idRol = (int)$_SESSION["id_rol"];

/* =====================
   VALIDAR DIFICULTAD
===================== */
$idDificultad = (int)($_GET["id_dificultad"] ?? 0);
if ($idDificultad <= 0) {
    header("Location: ../views/dashboard.php");
    exit;
}

// validar que pertenezca al rol
$chk = $conn->query("
  SELECT 1 FROM dificultad
  WHERE id_dificultad = $idDificultad
    AND id_rol = $idRol
  LIMIT 1
");
if (!$chk || $chk->num_rows === 0) {
    header("Location: ../views/dashboard.php");
    exit;
}

/* =====================
   LIMPIAR SESIÓN DE JUEGO
===================== */
foreach ($_SESSION as $k => $v) {
    if (str_starts_with($k, "orden_actual_")) unset($_SESSION[$k]);
}
unset(
    $_SESSION["id_partida"],
    $_SESSION["id_quiz"],
    $_SESSION["id_pregunta"],
    $_SESSION["correctas"],
    $_SESSION["total_resp"]
);

/* =====================
   OBTENER QUIZ
===================== */
$qz = $conn->query("
  SELECT rp.id_quiz
  FROM resultado_partida rp
  WHERE rp.id_usu = $idUsu
    AND rp.id_dificultad = $idDificultad
  ORDER BY rp.fecha DESC
  LIMIT 1
");
if (!$qz || $qz->num_rows === 0) {
    header("Location: ../views/dashboard.php");
    exit;
}
$idQuiz = (int)$qz->fetch_assoc()["id_quiz"];

/* =====================
   CREAR PARTIDA
===================== */
$conn->query("
  INSERT INTO partida (id_quiz, estado)
  VALUES ($idQuiz, 'en_juego')
");
$idPartida = (int)$conn->insert_id;

/* =====================
   INICIALIZAR SESIÓN
===================== */
$_SESSION["id_partida"]    = $idPartida;
$_SESSION["id_quiz"]       = $idQuiz;
$_SESSION["id_dificultad"] = $idDificultad;
$_SESSION["origen_juego"]  = "dashboard";

// 🔑 contadores
$_SESSION["correctas"]   = 0;
$_SESSION["total_resp"]  = 0;
$_SESSION["orden_actual_$idDificultad"] = 1;

/* =====================
   IR AL JUEGO
===================== */
header("Location: ../views/quizz.php");
exit;
