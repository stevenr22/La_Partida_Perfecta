<?php
require_once("../componentes/variables_globales.php");

/* =====================
   VALIDAR SESIÓN
===================== */
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuario = obtenerUsuarioSesion();

/* =====================
   SOLO MAESTRO
===================== */
if ((int)$usuario["id_rol"] !== 3) {
    header("Location: dashboard.php");
    exit;
}

/* =====================
   NAVBAR POR ROL
===================== */
$idRol = (int)$usuario["id_rol"];
$navbarClass = "navbar-maestro";

/* =====================
   BD
===================== */
require "../db/conexion.php";

/* =====================
   QUIZZES DEL PROFESOR
===================== */
$idProfesor = (int)$usuario["id_usu"];
$quizzes = $conn->query("
    SELECT id_quiz, nombre_quiz
    FROM quiz
    WHERE id_profesor = $idProfesor
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>La Partida Perfecta | Iniciar Juego</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- ================= NAVBAR ================= -->
<?php include "../componentes/partes/nav.php"; ?>

<!-- ================= CONTENIDO ================= -->
<section class="container my-3">

    <h1 class="dashboard-title">Iniciar Juego</h1>
    <p class="text-secondary mb-4">
        Generar PIN para iniciar una partida |
        <a href="../views/dashboard.php">Regresar al inicio</a>
    </p>

    <div class="card shadow-sm">
        <div class="card-body">

            <form id="formIniciarPartida">

                <label class="form-label fw-bold">Selecciona el quizz:</label>
                <select id="id_quiz" class="form-select mb-3" required>
                    <option value="">-- Seleccionar --</option>
                    <?php while ($q = $quizzes->fetch_assoc()): ?>
                        <option value="<?= $q["id_quiz"] ?>">
                            <?= htmlspecialchars($q["nombre_quiz"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="btn btn-success">
                    Iniciar Juego
                </button>

            </form>

            <!-- RESULTADO PIN -->
            <div id="resultadoPin" class="alert alert-success text-center mt-4 d-none">
                <h5 class="mb-2">PIN DE LA PARTIDA</h5>
                <h1 id="pinTexto" class="fw-bold"></h1>
                <p class="mb-0">Esperando a los estudiantes…</p>
            </div>

        </div>
    </div>

</section>

<!-- ================= SCRIPTS ================= -->
<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script src="../assets/js/notify/notify.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
<script src="../assets/js/ajaxjquery/ajax.js"></script>

</body>
</html>
