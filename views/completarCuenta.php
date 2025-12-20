<?php
session_start();
require "../db/conexion.php";

$idUsu = 0;
if (isset($_SESSION["id_usu_jugador"])) $idUsu = (int)$_SESSION["id_usu_jugador"];
else if (isset($_SESSION["usuario_id"])) $idUsu = (int)$_SESSION["usuario_id"];

if ($idUsu <= 0) { header("Location: ../index.php"); exit; }

$q = $conn->query("
  SELECT id_usu, cedula_usu, nombre_usu, apellido_usu, usuario_usu, id_rol, perfil_completo
  FROM usuario
  WHERE id_usu = $idUsu
  LIMIT 1
");
if (!$q || $q->num_rows === 0) { header("Location: ../index.php"); exit; }

$u = $q->fetch_assoc();
$perfilCompleto = (int)($u["perfil_completo"] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">
  <title>Completar cuenta | La Partida Perfecta</title>
  <style>
    body{ background:#f6f7fb; }
    .card{ border:0; border-radius:18px; box-shadow:0 12px 28px rgba(0,0,0,.08); }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-xl-6">
      <div class="card p-4">

        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="rounded-circle d-grid place-items-center" style="width:44px;height:44px;background:#e9efff;">
            <i class="bi bi-person-check fs-4 text-primary"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold">Completar mi cuenta</h4>
            <small class="text-muted">Para ver historial, certificados y cursos aprobados</small>
          </div>
        </div>

        <?php if ($perfilCompleto === 1): ?>
          <div class="alert alert-success">✅ Tu perfil ya está completo.</div>
          <div class="d-grid gap-2">
            <a class="btn btn-primary" href="../auth/login.php">Ir a iniciar sesión</a>
            <a class="btn btn-outline-secondary" href="../index.php">Volver al inicio</a>
          </div>
        <?php else: ?>

          <form id="formCompletarCuenta" >
            <div class="row g-3">
              <div class="col-md-6">
                <label class="fw-bold">Cédula</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($u["cedula_usu"]) ?>" readonly>
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Nivel</label>
                <select id="nivel_estudio" class="form-select">
                  <option value="">-- Seleccionar --</option>
                  <option value="1" <?= ((int)$u["id_rol"]===1?'selected':'') ?>>Estudiante Básico</option>
                  <option value="2" <?= ((int)$u["id_rol"]===2?'selected':'') ?>>Estudiante Universitario</option>
                  <option value="3" <?= ((int)$u["id_rol"]===3?'selected':'') ?>>Maestro</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Nombres</label>
                <input type="text" id="nombre" class="form-control" value="<?= htmlspecialchars($u["nombre_usu"]) ?>">
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Apellidos</label>
                <input type="text" id="apellido" class="form-control" value="<?= htmlspecialchars($u["apellido_usu"]) ?>">
              </div>

              <div class="col-12">
                <label class="fw-bold">Usuario</label>
                <input type="text" id="usuario" class="form-control" value="<?= htmlspecialchars($u["usuario_usu"] ?? "") ?>">
                <small class="text-muted">Este será tu usuario para iniciar sesión.</small>
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Contraseña</label>
                <div class="input-group">
                  <input type="password" id="contrasena" class="form-control" placeholder="********">
                  <button class="btn btn-outline-secondary" type="button" id="btnToggleNueva">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Confirmar contraseña</label>
                <div class="input-group">
                  <input type="password" id="contrasena2" class="form-control" placeholder="********">
                  <button class="btn btn-outline-secondary" type="button" id="btnToggleConfirmar">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>
            </div>

            <div class="d-grid gap-2 mt-4">
              <button class="btn btn-primary btn-lg" type="submit">
                Guardar y completar perfil
              </button>
              <a class="btn btn-outline-secondary" href="../index.php">Volver al inicio</a>
            </div>
          </form>

        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script src="../assets/js/notify/notify.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>



</body>
</html>
