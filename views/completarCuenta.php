<?php
session_start();
require "../db/conexion.php";

$idUsu = 0;
if (isset($_SESSION["id_usu_jugador"])) $idUsu = (int)$_SESSION["id_usu_jugador"];
else if (isset($_SESSION["usuario_id"])) $idUsu = (int)$_SESSION["usuario_id"];

if ($idUsu <= 0) {
  header("Location: ../index.php");
  exit;
}

$q = $conn->query("
  SELECT id_usu, cedula_usu, nombre_usu, apellido_usu, usuario_usu, id_rol, perfil_completo
  FROM usuario
  WHERE id_usu = $idUsu
  LIMIT 1
");
if (!$q || $q->num_rows === 0) {
  header("Location: ../index.php");
  exit;
}

$u = $q->fetch_assoc();
$perfilCompleto = (int)($u["perfil_completo"] ?? 0);

$idRol = (int)($u["id_rol"] ?? 0);
$rolTxt = "Sin nivel";
if ($idRol === 1) $rolTxt = "Estudiante Básico";
else if ($idRol === 2) $rolTxt = "Estudiante Universitario";
else if ($idRol === 3) $rolTxt = "Maestro";

// Si tu cédula en BD es TEMP..., mostramos vacío para obligar a escribir la real
$cedulaBD = $u["cedula_usu"] ?? "";
$cedulaValue = (stripos($cedulaBD, "TEMP") === 0) ? "" : $cedulaBD;
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

          <form id="formCompletarCuenta">
            <div class="row g-3">

              <!-- CÉDULA EDITABLE -->
              <div class="col-md-6">
                <label class="fw-bold">Cédula</label>
                <input type="text"
                       class="form-control"
                       id="cedula"
                       placeholder="Ingresa tu cédula (10 dígitos)"
                       inputmode="numeric"
                       maxlength="10"
                       data-validate="cedula">
                <small class="text-muted">Debe tener exactamente 10 dígitos.</small>
              </div>

              <!-- NIVEL SOLO LECTURA -->
              <div class="col-md-6">
                <label class="fw-bold">Nivel</label>
                <input type="text"
                       class="form-control"
                       value="<?= htmlspecialchars($rolTxt) ?>"
                       readonly>
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Nombres</label>
                <input type="text"
                       id="nombre"
                       data-validate="letras"
                       class="form-control"
                       placeholder="Ej: Juan Carlos"
                       value="<?= htmlspecialchars($u["nombre_usu"] ?? "") ?>">
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Apellidos</label>
                <input type="text"
                       id="apellido"
                       data-validate="letras"
                       class="form-control"
                       placeholder="Ej: Pérez Gómez"
                       value="<?= htmlspecialchars($u["apellido_usu"] ?? "") ?>">
              </div>

              <div class="col-12">
                <label class="fw-bold">Usuario</label>
                <input type="text"
                       id="usuario"
                       class="form-control"
                       placeholder="Ej: jperez"
                       value="<?= htmlspecialchars($u["usuario_usu"] ?? "") ?>">
                <small class="text-muted">Este será tu usuario para iniciar sesión.</small>
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Contraseña</label>
                <div class="input-group">
                  <input type="password"
                         id="contrasena"
                         placeholder="Mínimo 6 caracteres"
                         class="form-control">
                  <button type="button"
                          class="btn btn-outline-secondary toggle-password"
                          data-target="contrasena">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <div class="col-md-6">
                <label class="fw-bold">Confirmar contraseña</label>
                <div class="input-group">
                  <input type="password"
                         id="contrasena2"
                         placeholder="Repite la contraseña"
                         class="form-control">
                  <button type="button"
                          class="btn btn-outline-secondary toggle-password"
                          data-target="contrasena2">
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
<script src="../assets/js/ajaxjquery/ajax.js"></script>
<script src="../assets/js/validaciones.js"></script>



</body>
</html>
