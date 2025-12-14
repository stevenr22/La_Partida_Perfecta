<?php
// ================= CONTROL DE SESIÓN =================
require_once "../componentes/variables_globales.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuario = obtenerUsuarioSesion();
$idRol = (int)$usuario["id_rol"];

// Color del navbar según rol
$navbarClass = "navbar-maestro";

if ($idRol === 1 || $idRol === 2) {
    $navbarClass = "navbar-estudiante";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

    <title>La Partida Perfecta | Dashboard</title>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<?php include "../componentes/partes/nav.php"; ?>

<!-- ================= BIENVENIDA ================= -->
<div class="container">
    <div class="bienvenida-box mt-4">
        <h4 class="mb-1">Bienvenido, <?= $usuario["nombre_usu"] ?> 👋</h4>
        <p class="text-secondary mb-0">
            Tu rol: <strong><?= $usuario["nombre_rol"] ?></strong>
        </p>
    </div>
</div>

<!-- ================= CONTENIDO ================= -->
<section class="container my-3">

    <h1 class="dashboard-title mb-2">Panel de Control</h1>
    <p class="text-secondary mb-4">Administra los módulos del sistema.</p>

    <div class="row g-4">

        <!-- ================= MAESTRO ================= -->
        <?php if ($idRol === 3): ?>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-journal-text"></i></div>
                    <h4>Preguntas</h4>
                    <p>Crear y gestionar preguntas del quiz.</p>
                    <a href="registrarPreguntas.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-people"></i></div>
                    <h4>Resultados</h4>
                    <p>Consulta los resultados de los participantes.</p>
                    <a href="resutadosPreguntas.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-bar-chart-line"></i></div>
                    <h4>Reportes</h4>
                    <p>Genera reportes de desempeño estudiantil.</p>
                    <a href="reportesGenerales.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        <?php endif; ?>

        <!-- ================= ESTUDIANTES ================= -->
        <?php if ($idRol === 1 || $idRol === 2): ?>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-book"></i></div>
                    <h4>Curso 1</h4>
                    <p>Completa el curso nivel básico para continuar.</p>
                    <a href="cursoBasico.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-book-half"></i></div>
                    <h4>Curso 2</h4>
                    <p>Completa el curso nivel intermedio para continuar.</p>
                    <a href="cursoIntermedio.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-mortarboard"></i></div>
                    <h4>Curso 3</h4>
                    <p>Completa el curso nivel difícil para continuar.</p>
                    <a href="cursoDificil.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>

</section>

<!-- Bootstrap JS -->
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>
