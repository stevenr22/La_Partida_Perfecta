<!-- ADMINISTRAR CONTROL DE SESION -->
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>QUIZZPRINT | Registrar Quizz</title>

</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <?php include "../componentes/partes/nav.php"; ?>

    <!-- ================= CONTENIDO ================= -->
    <section class="container my-3">

        <h1 class="dashboard-title mb-2">Registrar quizz</h1>
        <p class="text-secondary mb-4">Registrar datos del Quizz |
            <a href="../views/dashboard.php">Regresar al inicio</a>
        </p>
        <!-- Datos del quizz -->
         <div class="card-body">
            <form id="formRegistrarQuizz">
                <label class="form-label fw-bold">Nombre del quizz:</label>
                <input type="text" name="nombre_quizz" id="nombre_quizz" class="form-control">

                <label class="form-label fw-bold mt-2">Descripción del quizz:</label>
                <textarea name="descripcion_quizz" id="descripcion_quizz" class="form-control"></textarea>

                <label class="form-label fw-bold mt-2">Nivel de estudio:</label>
                <select name="nivel_estudio" class="form-select" id="nivel_estudio">
                    <option value="">-- Seleccionar --</option>
                    <option value="1">Estudiante básico</option>
                    <option value="2">Estudiante Universitario</option>
                    <option value="3">Maestro / Profesional</option>
                </select>

                <!-- ID PROFESOR -->
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $usuario["id_usu"] ?>">
                <button type="submit" class="btn btn-primary mt-3">Registrar Quizz</button>
            </form>
         </div>
      

      

       
    </section>

    <script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/notify/notify.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/ajaxjquery/ajax.js"></script>

</body>

</html>