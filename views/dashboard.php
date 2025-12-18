<?php
// ================= CONTROL DE SESIÓN =================
require_once "../componentes/variables_globales.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuario = obtenerUsuarioSesion();

$idUsu = (int)$usuario["id_usu"];
$idRol = (int)$usuario["id_rol"];

// Navbar por rol
$navbarClass = "navbar-maestro";
if ($idRol === 1 || $idRol === 2) $navbarClass = "navbar-estudiante";

// DB
require "../db/conexion.php";

/*
  =================================================
  PROGRESO DINÁMICO POR ROL
=================================================
*/
$progreso = [];
$sql = "
SELECT 
  d.id_dificultad,
  d.nombre_dificultad,
  d.orden,

  (
    SELECT rp.aprobado
    FROM resultado_partida rp
    WHERE rp.id_usu = $idUsu
      AND rp.id_dificultad = d.id_dificultad
    ORDER BY rp.fecha DESC
    LIMIT 1
  ) AS aprobado,

  (
    SELECT rp.correctas
    FROM resultado_partida rp
    WHERE rp.id_usu = $idUsu
      AND rp.id_dificultad = d.id_dificultad
    ORDER BY rp.fecha DESC
    LIMIT 1
  ) AS correctas,

  (
    SELECT rp.total
    FROM resultado_partida rp
    WHERE rp.id_usu = $idUsu
      AND rp.id_dificultad = d.id_dificultad
    ORDER BY rp.fecha DESC
    LIMIT 1
  ) AS total,

  (
    SELECT rp.fecha
    FROM resultado_partida rp
    WHERE rp.id_usu = $idUsu
      AND rp.id_dificultad = d.id_dificultad
    ORDER BY rp.fecha DESC
    LIMIT 1
  ) AS fecha_ultimo
FROM dificultad d
WHERE d.id_rol = $idRol
ORDER BY d.orden ASC
";

$r = $conn->query($sql);
if ($r) {
    while ($row = $r->fetch_assoc()) $progreso[] = $row;
}

// helper
function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, "UTF-8"); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

  <title>La Partida Perfecta | Dashboard</title>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<?php include "../componentes/partes/nav.php"; ?>

<!-- ================= BIENVENIDA ================= -->
<div class="container">
  <div class="bienvenida-box mt-4">
    <h4 class="mb-1">Bienvenido, <?= h($usuario["nombre_usu"]) ?> 👋</h4>
    <p class="text-secondary mb-0">
      Tu rol: <strong><?= h($usuario["nombre_rol"]) ?></strong>
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
          <div class="card-icon"><i class="bi bi-play-circle"></i></div>
          <h4>Iniciar Juego</h4>
          <p>Generar PIN y comenzar una partida.</p>
          <a href="../views/iniciarPartida.php" class="btn btn-success rounded-circle">
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="col-12">
        <hr class="my-1">
        <h5 class="mt-2">Cursos / Certificaciones (tu progreso)</h5>
        <p class="text-secondary mb-0">Aquí también puedes ver tu avance como usuario.</p>
      </div>
    <?php endif; ?>

    <!-- ================= CURSOS ================= -->
    <?php if (count($progreso) === 0): ?>
      <div class="col-12">
        <div class="alert alert-warning">
          No hay dificultades configuradas para tu rol.
        </div>
      </div>
    <?php else: ?>

      <?php
      $anteriorAprobado = true;

      foreach ($progreso as $p):
        $idDif = (int)$p["id_dificultad"];
        $orden = (int)$p["orden"];
        $nombreDif = $p["nombre_dificultad"];

        $aprobado   = ((int)($p["aprobado"] ?? 0) === 1);
        $correctas  = (int)($p["correctas"] ?? 0);
        $total      = (int)($p["total"] ?? 0);
        $fecha      = $p["fecha_ultimo"] ?? null;

        $bloqueado = !$anteriorAprobado;
      ?>
      <div class="col-md-4">
        <div class="card-custom">
          <div class="card-icon"><i class="bi bi-book"></i></div>

          <h4>Curso <?= $orden ?></h4>
          <p><?= h($nombreDif) ?></p>

          <?php if ($aprobado): ?>
            <span class="badge bg-success mb-2">
              Aprobado (<?= $correctas ?>/<?= $total ?>)
            </span>

            <!-- ===== CERTIFICADOS SEGÚN ORDEN ===== -->
            <?php if ($orden == 1): ?>
              <a href="../reports/certificadoNivelBasico.php?id_dificultad=<?= $idDif ?>"
                 target="_blank"
                 class="btn btn-success rounded-circle"
                 title="Certificado Nivel Básico">
                <i class="bi bi-award"></i>
              </a>
            <?php elseif ($orden == 2): ?>
              <a href="../reports/certificadoNivelIntermedio.php?id_dificultad=<?= $idDif ?>"
                 target="_blank"
                 class="btn btn-success rounded-circle"
                 title="Certificado Nivel Intermedio">
                <i class="bi bi-award"></i>
              </a>
            <?php elseif ($orden == 3): ?>
              <a href="../reports/certificadoNivelDificil.php?id_dificultad=<?= $idDif ?>"
                 target="_blank"
                 class="btn btn-success rounded-circle"
                 title="Certificado Nivel Dificil">
                <i class="bi bi-award"></i>
              </a>
            <?php endif; ?>

            <?php if ($fecha): ?>
              <div class="text-secondary small mt-2">
                Último intento: <?= h($fecha) ?>
              </div>
            <?php endif; ?>

          <?php elseif ($bloqueado): ?>
            <span class="badge bg-secondary mb-2">Bloqueado</span>
            <button class="btn btn-secondary rounded-circle" disabled>
              <i class="bi bi-lock"></i>
            </button>

          <?php else: ?>
            <?php if ($total > 0): ?>
              <span class="badge bg-warning text-dark mb-2">
                No aprobado (<?= $correctas ?>/<?= $total ?>) — Inténtalo otra vez
              </span>
            <?php else: ?>
              <span class="badge bg-primary mb-2">Disponible</span>
            <?php endif; ?>

            <a href="../views/reintentarCurso.php?id_dificultad=<?= $idDif ?>"
               class="btn btn-primary rounded-circle"
               title="Intentar nuevamente este curso">
              <i class="bi bi-arrow-repeat"></i>
            </a>

            <?php if ($fecha): ?>
              <div class="text-secondary small mt-2">
                Último intento: <?= h($fecha) ?>
              </div>
            <?php endif; ?>
          <?php endif; ?>

        </div>
      </div>

      <?php
        $anteriorAprobado = $aprobado;
      endforeach;
      ?>

    <?php endif; ?>

  </div>
</section>

<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>
