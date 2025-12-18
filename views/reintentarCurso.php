<?php
require_once "../componentes/variables_globales.php";
require "../db/conexion.php";

/* =====================
   VALIDAR SESIÓN
===================== */
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuario = obtenerUsuarioSesion();
$idUsu = (int)$usuario["id_usu"];
$idRol = (int)$usuario["id_rol"];

/* =====================
   VALIDAR DIFICULTAD
===================== */
$idDificultad = (int)($_GET["id_dificultad"] ?? 0);
if ($idDificultad <= 0) {
    header("Location: dashboard.php");
    exit;
}

// validar que la dificultad pertenezca al rol
$chk = $conn->query("
    SELECT 1
    FROM dificultad
    WHERE id_dificultad = $idDificultad
      AND id_rol = $idRol
    LIMIT 1
");
if (!$chk || $chk->num_rows === 0) {
    header("Location: dashboard.php");
    exit;
}

/* =====================
   LIMPIAR SESIÓN DE JUEGO
===================== */
foreach ($_SESSION as $k => $v) {
    if (strpos($k, "orden_actual_") === 0) unset($_SESSION[$k]);
}

unset(
    $_SESSION["id_partida"],
    $_SESSION["id_quiz"],
    $_SESSION["id_pregunta"],
    $_SESSION["correctas"],
    $_SESSION["total_resp"]
);

/* =====================
   OBTENER QUIZ DE LA DIFICULTAD
===================== */
$qz = $conn->query("
    SELECT DISTINCT q.id_quiz
    FROM quiz q
    JOIN pregunta p ON p.id_quiz = q.id_quiz
    WHERE p.id_dificultad = $idDificultad
    LIMIT 1
");
if (!$qz || $qz->num_rows === 0) {
    header("Location: dashboard.php");
    exit;
}

$idQuiz = (int)$qz->fetch_assoc()["id_quiz"];

/* =====================
   CREAR NUEVA PARTIDA
===================== */
$conn->query("
    INSERT INTO partida (id_quiz, pin, estado)
VALUES ($idQuiz, NULL, 'en_juego')

");
$idPartida = (int)$conn->insert_id;

/* =====================
   INICIALIZAR SESIÓN
===================== */
$_SESSION["id_partida"]    = $idPartida;
$_SESSION["id_quiz"]       = $idQuiz;
$_SESSION["id_dificultad"] = $idDificultad;
$_SESSION["origen_juego"]  = "dashboard";
$_SESSION["correctas"]     = 0;
$_SESSION["total_resp"]    = 0;
$_SESSION["orden_actual_{$idPartida}_{$idDificultad}"] = 1;

/* =====================
   IR AL JUEGO
===================== */
header("Location: quizz.php");
exit;
