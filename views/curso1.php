<!-- ADMINISTRAR CONTROL DE SESION -->
<?php
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
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .notifyjs-corner {
            z-index: 999999 !important;
        }
    </style>
    <title>La Partida Perfecta | Preguntas</title>

</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <?php include "../componentes/partes/nav.php"; ?>




    <!-- ================= CONTENIDO ================= -->
    <section class="container my-3">

        <h1 class="dashboard-title mb-2">Curso Número 1</h1>
        <p class="text-secondary mb-4">Información acerca del curso nivel básico | <a href="../views/dashboard.php">Regresar al inicio</a></p>

        <div class="row justify-content-center">
            <div class="col-md-8">

              

            </div>
        </div>

    </section>

     

    <!-- <script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script> -->
    <script src="../assets/js/notify/notify.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <!-- <script src="../assets/js/ajaxjquery/ajax.js"></script> -->

</body>

</html>