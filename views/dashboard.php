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
/*
=================================================
EMULACIÓN DE PROGRESO
true  = aprobado
false = no aprobado / bloqueado
=================================================
*/

// Estudiante básico
$progresoBasico = [
    1 => true,
    2 => false,
    3 => false
];

// Estudiante universitario
$progresoUniversitario = [
    1 => true,
    2 => true,
    3 => false
];
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
    <p class="text-secondary mb-4">Seguimiento de cursos y certificaciones.</p>

    <div class="row g-4">

        <!-- ================= MAESTRO ================= -->
        <?php if ($idRol === 3): ?>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-journal-text"></i></div>
                    <h4>Quizz</h4>
                    <p>Registrar datos del quizz.</p>
                    <a href="registrarQuizz.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-question-circle"></i></div>
                    <h4>Preguntas</h4>
                    <p>Gestión de preguntas.</p>
                    <a href="registrarPreguntas.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon">
                        <i class="bi bi-play-circle"></i>
                    </div>
                    <h4>Iniciar Juego</h4>
                    <p>Generar PIN y comenzar una partida.</p>
                    <a href="../views/iniciarPartida.php" class="btn btn-success rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-bar-chart"></i></div>
                    <h4>Reportes</h4>
                    <p>Resultados y estadísticas.</p>
                    <a href="reportesGenerales.php" class="btn btn-primary rounded-circle">
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>

        <?php endif; ?>

        <!-- ================= ESTUDIANTE BÁSICO ================= -->
        <?php if ($idRol === 1): ?>

            <!-- CURSO 1 -->
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-book"></i></div>
                    <h4>Curso 1</h4>
                    <p>Nivel Básico.</p>

                    <?php if ($progresoBasico[1]): ?>
                        <span class="badge bg-success mb-2">Aprobado</span>
                        <a href="../reports/certificadoNivelBasico.php" target="_blank" class="btn btn-success rounded-circle">
                            <i class="bi bi-award"></i>
                        </a>
                    <?php else: ?>
                        <a href="../views/curso1.php" class="btn btn-primary rounded-circle">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CURSO 2 -->
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-book-half"></i></div>
                    <h4>Curso 2</h4>
                    <p>Nivel Intermedio.</p>

                    <?php if ($progresoBasico[2]): ?>
                        <span class="badge bg-success mb-2">Aprobado</span>
                        <a href="certificado.php?nivel=Intermedio" class="btn btn-success rounded-circle">
                            <i class="bi bi-award"></i>
                        </a>
                    <?php elseif (!$progresoBasico[1]): ?>
                        <span class="badge bg-secondary mb-2">Bloqueado</span>
                        <button class="btn btn-secondary rounded-circle" disabled>
                            <i class="bi bi-lock"></i>
                        </button>
                    <?php else: ?>
                        <a href="../views/curso2.php" class="btn btn-primary rounded-circle">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CURSO 3 -->
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-mortarboard"></i></div>
                    <h4>Curso 3</h4>
                    <p>Nivel Avanzado.</p>

                    <?php if ($progresoBasico[3]): ?>
                        <span class="badge bg-success mb-2">Aprobado</span>
                        <a href="certificado.php?nivel=Avanzado" class="btn btn-success rounded-circle">
                            <i class="bi bi-award"></i>
                        </a>
                    <?php elseif (!$progresoBasico[2]): ?>
                        <span class="badge bg-secondary mb-2">Bloqueado</span>
                        <button class="btn btn-secondary rounded-circle" disabled>
                            <i class="bi bi-lock"></i>
                        </button>
                    <?php else: ?>
                        <a href="../views/curso3.php" class="btn btn-primary rounded-circle">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>

        <!-- ================= ESTUDIANTE UNIVERSITARIO ================= -->
        <?php if ($idRol === 2 || $idRol === 3): ?>

            <!-- CURSO 1 -->
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-book"></i></div>
                    <h4>Curso 1</h4>
                    <p>Auditor Junior.</p>

                    <?php if ($progresoUniversitario[1]): ?>
                        <span class="badge bg-success mb-2">Aprobado</span>
                        <a href="certificado.php?nivel=Auditor Junior" class="btn btn-success rounded-circle">
                            <i class="bi bi-award"></i>
                        </a>
                    <?php else: ?>
                        <a href="../views/curso1AJ.php" class="btn btn-primary rounded-circle">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CURSO 2 -->
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-book-half"></i></div>
                    <h4>Curso 2</h4>
                    <p>Auditor Semi Senior.</p>

                    <?php if ($progresoUniversitario[2]): ?>
                        <span class="badge bg-success mb-2">Aprobado</span>
                        <a href="certificado.php?nivel=Auditor Semi Senior" class="btn btn-success rounded-circle">
                            <i class="bi bi-award"></i>
                        </a>
                    <?php elseif (!$progresoUniversitario[1]): ?>
                        <span class="badge bg-secondary mb-2">Bloqueado</span>
                        <button class="btn btn-secondary rounded-circle" disabled>
                            <i class="bi bi-lock"></i>
                        </button>
                    <?php else: ?>
                        <a href="../views/curso2ASS.php" class="btn btn-primary rounded-circle">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CURSO 3 -->
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-icon"><i class="bi bi-mortarboard"></i></div>
                    <h4>Curso 3</h4>
                    <p>Auditor Senior.</p>

                    <?php if ($progresoUniversitario[3]): ?>
                        <span class="badge bg-success mb-2">Aprobado</span>
                        <a href="certificado.php?nivel=Auditor Senior" class="btn btn-success rounded-circle">
                            <i class="bi bi-award"></i>
                        </a>
                    <?php elseif (!$progresoUniversitario[2]): ?>
                        <span class="badge bg-secondary mb-2">Bloqueado</span>
                        <button class="btn btn-secondary rounded-circle" disabled>
                            <i class="bi bi-lock"></i>
                        </button>
                    <?php else: ?>
                        <a href="../views/curso3AS.php" class="btn btn-primary rounded-circle">
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>

    </div>

</section>

<!-- Bootstrap -->
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

</body>
</html>
