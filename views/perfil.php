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

        <h1 class="dashboard-title mb-2">Perfil</h1>
        <p class="text-secondary mb-4">Información de tu cuenta | <a href="../views/dashboard.php">Regresar al inicio</a></p>

        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-lg border-0 rounded-4 p-4">

                    <div class="row g-4">

                        <!-- Avatar -->
                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                            <div style="
                            width:130px;
                            height:130px;
                            background:#3949ab;
                            border-radius:50%;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:50px;
                            color:#fff;
                            font-weight:bold;">
                                <?= strtoupper($usuario["nombre_usu"][0]) ?>
                            </div>
                        </div>

                        <!-- Información del usuario -->
                        <div class="col-md-8">

                            <h3 class="fw-bold mb-1">
                                <?= $usuario["nombre_usu"] . " " . $usuario["apellido_usu"] ?>
                            </h3>

                            <span class="badge bg-primary px-3 py-2 mb-3">
                                <?= $usuario["nombre_rol"] ?>
                            </span>
                            <div class="mb-3">
                                <strong class="text-secondary">Nombres:</strong><br>
                                <span><?= $usuario["nombre_usu"] ?></span>
                            </div>

                            <div class="mb-3">
                                <strong class="text-secondary">Apellidos:</strong><br>
                                <span><?= $usuario["apellido_usu"] ?></span>
                            </div>

                      

                            <div class="mb-3">
                                <strong class="text-secondary">Nombre de usuario:</strong><br>
                                <span><?= $usuario["usuario_usu"] ?></span>
                            </div>

                          

                           <button class="btn btn-primary mt-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditarPerfil">
                                ✏ Editar perfil
                            </button>


                        </div>

                    </div>
                </div>

            </div>
        </div>

    </section>
    <!-- MODAL EDITAR -->
    <?= include("../componentes/modales.php"); ?>
    <!-- FIN MODAL -->
     

    <script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/notify/notify.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/ajaxjquery/ajax.js"></script>

</body>

</html>