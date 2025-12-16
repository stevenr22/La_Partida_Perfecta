<?php
require_once("../componentes/variables_globales.php");

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
    <title>QUIZZPRINT | Registrar Quizz</title>
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../componentes/partes/nav.php"; ?>

<section class="container my-3">

    <h1 class="dashboard-title mb-2">Registrar Quizz</h1>
    <p class="text-secondary mb-4">
        Registrar datos del Quizz |
        <a href="../views/dashboard.php">Regresar al inicio</a>
    </p>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="formRegistrarQuizz">

                <label class="form-label fw-bold">Nombre del quizz:</label>
                <input type="text" id="nombre_quizz" name="nombre_quizz"
                       class="form-control" required>

                <label class="form-label fw-bold mt-3">Descripción:</label>
                <textarea id="descripcion_quizz" name="descripcion_quizz"
                          class="form-control"></textarea>

                <label class="form-label fw-bold mt-3">Nivel de estudio:</label>
                <select id="id_rol" name="id_rol" class="form-select" required>
                    <option value="">-- Seleccionar --</option>
                    <option value="1">Estudiante básico</option>
                    <option value="2">Estudiante Universitario</option>
                    <option value="3">Maestro / Profesional</option>
                </select>

                <!-- ID DEL PROFESOR -->
                <input type="hidden" id="id_usuario" name="id_usuario"
                       value="<?= $usuario["id_usu"] ?>">

                <button type="submit" class="btn btn-primary mt-4">
                    Registrar Quizz
                </button>

            </form>
        </div>
    </div>

</section>

<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script src="../assets/js/notify/notify.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
<script src="../assets/js/ajaxjquery/ajax.js"></script>

</body>
</html>
