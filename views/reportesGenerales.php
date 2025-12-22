<?php
require_once "../componentes/variables_globales.php";

if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../auth/login.php");
  exit;
}

$usuario = obtenerUsuarioSesion();
$idRol = (int)$usuario["id_rol"];

// Solo maestro
if ($idRol !== 3) {
  header("Location: dashboard.php");
  exit;
}
// Navbar por rol
$navbarClass = "navbar-maestro";
if ($idRol === 1 || $idRol === 2) $navbarClass = "navbar-estudiante";

require "../db/conexion.php";
function h($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, "UTF-8"); }

// ✅ Reporte incluye estudiantes básico, universitario y también maestros
$rolesReporte = "1,2,3";

/*
  REPORTE GENERAL:
  - Lista usuarios (roles 1,2,3)
  - Muestra último intento por curso (orden 1,2,3) según el rol de cada usuario
  - Muestra total (suma de los 3)
*/
$sql = "
SELECT
  u.id_usu,
  u.cedula_usu,
  CONCAT(u.nombre_usu,' ',u.apellido_usu) AS estudiante,
  r.nombre_rol,

  -- CURSO 1 (orden 1)
  d1.id_dificultad AS idDif1,
  d1.nombre_dificultad AS curso1,
  rp1.correctas AS c1_correctas,
  rp1.total     AS c1_total,
  rp1.aprobado  AS c1_aprobado,
  rp1.fecha     AS c1_fecha,

  -- CURSO 2 (orden 2)
  d2.id_dificultad AS idDif2,
  d2.nombre_dificultad AS curso2,
  rp2.correctas AS c2_correctas,
  rp2.total     AS c2_total,
  rp2.aprobado  AS c2_aprobado,
  rp2.fecha     AS c2_fecha,

  -- CURSO 3 (orden 3)
  d3.id_dificultad AS idDif3,
  d3.nombre_dificultad AS curso3,
  rp3.correctas AS c3_correctas,
  rp3.total     AS c3_total,
  rp3.aprobado  AS c3_aprobado,
  rp3.fecha     AS c3_fecha

FROM usuario u
JOIN rol r ON r.id_rol = u.id_rol

LEFT JOIN dificultad d1 ON d1.id_rol = u.id_rol AND d1.orden = 1
LEFT JOIN dificultad d2 ON d2.id_rol = u.id_rol AND d2.orden = 2
LEFT JOIN dificultad d3 ON d3.id_rol = u.id_rol AND d3.orden = 3

LEFT JOIN resultado_partida rp1
  ON rp1.id_usu = u.id_usu AND rp1.id_dificultad = d1.id_dificultad
 AND rp1.fecha = (
    SELECT MAX(x.fecha) FROM resultado_partida x
    WHERE x.id_usu = u.id_usu AND x.id_dificultad = d1.id_dificultad
 )

LEFT JOIN resultado_partida rp2
  ON rp2.id_usu = u.id_usu AND rp2.id_dificultad = d2.id_dificultad
 AND rp2.fecha = (
    SELECT MAX(x.fecha) FROM resultado_partida x
    WHERE x.id_usu = u.id_usu AND x.id_dificultad = d2.id_dificultad
 )

LEFT JOIN resultado_partida rp3
  ON rp3.id_usu = u.id_usu AND rp3.id_dificultad = d3.id_dificultad
 AND rp3.fecha = (
    SELECT MAX(x.fecha) FROM resultado_partida x
    WHERE x.id_usu = u.id_usu AND x.id_dificultad = d3.id_dificultad
 )

WHERE u.id_rol IN ($rolesReporte)
ORDER BY u.apellido_usu ASC, u.nombre_usu ASC
";

$rows = [];
$q = $conn->query($sql);
if ($q) while ($r = $q->fetch_assoc()) $rows[] = $r;

function safeInt($v){ return (int)($v ?? 0); }
function lastDate3($a, $b, $c){
  $dates = array_filter([$a, $b, $c]);
  if (!$dates) return null;
  rsort($dates);
  return $dates[0];
}

// ========================
// MODO PDF (Imprimir)
// ========================
$isPrint = isset($_GET["print"]) && $_GET["print"] == "1";

// filtro rol en modo print (opcional)
$rolPrint = trim($_GET["rol"] ?? "");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

  <?php if(!$isPrint): ?>
    <link rel="stylesheet" href="../assets/css/datatable/datatables.min.css">
  <?php endif; ?>

  <link rel="stylesheet" href="../assets/css/style.css">

  <title>Reportes Generales | Maestro</title>

  <style>
    /* ✅ contenedor más ancho */
    .container-wide{
      width: 100%;
      max-width: 1700px;
      margin: 0 auto;
      padding-left: 12px;
      padding-right: 12px;
    }

    /* ✅ tabla compacta */
    #tablaReportesGenerales{
      font-size: .88rem;
      white-space: nowrap;
    }
    #tablaReportesGenerales th,
    #tablaReportesGenerales td{
      padding: .42rem .55rem;
      vertical-align: middle;
    }
    #tablaReportesGenerales .badge{
      font-size: .78rem;
    }

    /* inputs datatable */
    div.dataTables_wrapper .dataTables_filter input{
      font-size: .9rem;
      padding: .25rem .5rem;
    }
    div.dataTables_wrapper .dataTables_length select{
      font-size: .9rem;
      padding: .2rem .45rem;
    }

    /* Modo impresión */
    @media print{
      .no-print{ display:none !important; }
      body{ background:#fff !important; }
      .card{ border:0 !important; box-shadow:none !important; }
      table{ font-size: 10px !important; }
    }
  </style>
</head>

<body>

<?php if(!$isPrint): ?>
  <?php include "../componentes/partes/nav.php"; ?>
<?php endif; ?>

<div class="container-wide my-4">

  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
      <h1 class="dashboard-title mb-1">Reportes Generales</h1>
      <p class="text-secondary mb-0">Puntajes por curso y certificados (Roles: Básico, Universitario y Maestro)
        | <a href="../views/dashboard.php">Regresar al inicio</a>
      </p>
    </div>

    <div class="no-print d-flex gap-2 flex-wrap">


      <!-- ✅ Botón PDF (Imprimir navegador) -->
      <button class="btn btn-danger" id="btnPDF">
        <i class="bi bi-filetype-pdf"></i> Ver / Descargar PDF
      </button>
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-body">

      <!-- ✅ FILTROS -->
      <?php if(!$isPrint): ?>
      <div class="no-print row g-2 align-items-end mb-3">
        <div class="col-md-12">
          <label class="form-label fw-bold mb-1">Filtrar por rol</label>
          <select id="filtroRol" class="form-select">
            <option value="">Todos</option>
            <option value="Estudiante Básico">Estudiante Básico</option>
            <option value="Estudiante Universitario">Estudiante Universitario</option>
            <option value="Maestro">Maestro</option>
          </select>
          <div class="form-text">Filtra usando el texto exacto del rol que tengas en tu tabla.</div>
        </div>

      

       
      </div>
      <?php endif; ?>

      <?php if (count($rows) === 0): ?>
        <div class="alert alert-warning mb-0">
          No hay usuarios para los roles configurados.
        </div>
      <?php else: ?>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle" id="tablaReportesGenerales">
            <thead class="table-dark">
              <tr>
                <th>Cédula</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Fecha último intento</th>

                <th>Curso 1</th>
                <th>Puntaje</th>
                <th>Cert. 1</th>

                <th>Curso 2</th>
                <th>Puntaje</th>
                <th>Cert. 2</th>

                <th>Curso 3</th>
                <th>Puntaje</th>
                <th>Cert. 3</th>

                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r):

                // Si estamos en modo print y filtraron rol, saltamos lo que no coincide
                if($isPrint && $rolPrint !== "" && trim($r["nombre_rol"]) !== $rolPrint){
                  continue;
                }

                $c1c = safeInt($r["c1_correctas"]);
                $c1t = safeInt($r["c1_total"]);
                $c1ok = (safeInt($r["c1_aprobado"]) === 1);

                $c2c = safeInt($r["c2_correctas"]);
                $c2t = safeInt($r["c2_total"]);
                $c2ok = (safeInt($r["c2_aprobado"]) === 1);

                $c3c = safeInt($r["c3_correctas"]);
                $c3t = safeInt($r["c3_total"]);
                $c3ok = (safeInt($r["c3_aprobado"]) === 1);

                $totalCorrectas = $c1c + $c2c + $c3c;
                $totalPreguntas = $c1t + $c2t + $c3t;

                $fechaFinal = lastDate3($r["c1_fecha"] ?? null, $r["c2_fecha"] ?? null, $r["c3_fecha"] ?? null);

                $idDif1 = safeInt($r["idDif1"]);
                $idDif2 = safeInt($r["idDif2"]);
                $idDif3 = safeInt($r["idDif3"]);

                $idUsuTarget = (int)$r["id_usu"];
              ?>
              <tr>
                <td><?= h($r["cedula_usu"]) ?></td>
                <td><?= h($r["estudiante"]) ?></td>
                <td><span class="badge text-bg-primary"><?= h($r["nombre_rol"]) ?></span></td>
                <td><?= $fechaFinal ? h($fechaFinal) : '<span class="text-secondary">—</span>' ?></td>

                <!-- Curso 1 -->
                <td><?= h($r["curso1"] ?? "—") ?></td>
                <td>
                  <?php if ($c1t > 0): ?>
                    <span class="badge <?= $c1ok ? "text-bg-success" : "text-bg-warning text-dark" ?>">
                      <?= $c1c ?>/<?= $c1t ?>
                    </span>
                  <?php else: ?>
                    <span class="text-secondary">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($c1ok && $idDif1 > 0): ?>
                    <a class="btn btn-success btn-sm" target="_blank"
                      href="../reports/certificadoNivelBasico.php?id_dificultad=<?= $idDif1 ?>&id_usu=<?= $idUsuTarget ?>"
                      title="Generar certificado Curso 1">
                      <i class="bi bi-award"></i>
                    </a>
                  <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled title="No aprobado / no disponible">
                      <i class="bi bi-x-circle"></i>
                    </button>
                  <?php endif; ?>
                </td>

                <!-- Curso 2 -->
                <td><?= h($r["curso2"] ?? "—") ?></td>
                <td>
                  <?php if ($c2t > 0): ?>
                    <span class="badge <?= $c2ok ? "text-bg-success" : "text-bg-warning text-dark" ?>">
                      <?= $c2c ?>/<?= $c2t ?>
                    </span>
                  <?php else: ?>
                    <span class="text-secondary">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($c2ok && $idDif2 > 0): ?>
                    <a class="btn btn-success btn-sm" target="_blank"
                      href="../reports/certificadoNivelIntermedio.php?id_dificultad=<?= $idDif2 ?>&id_usu=<?= $idUsuTarget ?>"
                      title="Generar certificado Curso 2">
                      <i class="bi bi-award"></i>
                    </a>
                  <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled title="No aprobado / no disponible">
                      <i class="bi bi-x-circle"></i>
                    </button>
                  <?php endif; ?>
                </td>

                <!-- Curso 3 -->
                <td><?= h($r["curso3"] ?? "—") ?></td>
                <td>
                  <?php if ($c3t > 0): ?>
                    <span class="badge <?= $c3ok ? "text-bg-success" : "text-bg-warning text-dark" ?>">
                      <?= $c3c ?>/<?= $c3t ?>
                    </span>
                  <?php else: ?>
                    <span class="text-secondary">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($c3ok && $idDif3 > 0): ?>
                    <a class="btn btn-success btn-sm" target="_blank"
                      href="../reports/certificadoNivelDificil.php?id_dificultad=<?= $idDif3 ?>&id_usu=<?= $idUsuTarget ?>"
                      title="Generar certificado Curso 3">
                      <i class="bi bi-award"></i>
                    </a>
                  <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled title="No aprobado / no disponible">
                      <i class="bi bi-x-circle"></i>
                    </button>
                  <?php endif; ?>
                </td>

                <!-- Total -->
                <td>
                  <?php if ($totalPreguntas > 0): ?>
                    <span class="badge text-bg-dark"><?= $totalCorrectas ?>/<?= $totalPreguntas ?></span>
                  <?php else: ?>
                    <span class="text-secondary">—</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      <?php endif; ?>

    </div>
  </div>
</div>

<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

<?php if(!$isPrint): ?>
  <script src="../assets/js/datatable/datatables.min.js"></script>
<?php endif; ?>

<script>
<?php if($isPrint): ?>
  // ✅ Auto abrir imprimir en modo print
  window.onload = () => window.print();
<?php else: ?>

  $(document).ready(function () {
    const tabla = $('#tablaReportesGenerales').DataTable({
      scrollX: true,
      pageLength: 10,
      order: [[1, "asc"]],
      language: {
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        zeroRecords: "No se encontraron resultados",
        emptyTable: "No hay datos disponibles",
        paginate: {
          first: "Primero",
          last: "Último",
          next: "Siguiente",
          previous: "Anterior"
        }
      }
    });

    // ✅ Filtro por Rol (columna 2 => índice 2)
    $("#filtroRol").on("change", function(){
      const rol = $(this).val();
      tabla.column(2).search(rol ? "^" + rol + "$" : "", true, false).draw();
    });

    $("#btnLimpiar").on("click", function(){
      $("#filtroRol").val("");
      tabla.search("").columns().search("").draw();
    });

    $("#btnPDF").on("click", function(){
      const rolSeleccionado = $("#filtroRol").val(); // texto exacto del rol
      let url = "../reports/resultadosGeneralesPdf.php";
      if(rolSeleccionado){
        url += "?rol=" + encodeURIComponent(rolSeleccionado);
      }
      window.open(url, "_blank");
    });

  });

<?php endif; ?>
</script>

</body>
</html>
