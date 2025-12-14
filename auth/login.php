<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

    <title>La Partida Perfecta | Iniciar Sesión</title>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-card {
            transition: transform .2s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .notifyjs-corner {
            z-index: 999999 !important;
        }

        /* BOTÓN VOLVER */
        .back-icon {
            position: fixed;
            top: 20px;
            left: 25px;
            font-size: 22px;
            color: #0d6efd;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(6px);
            border-radius: 12px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
            transition: all .25s ease;
            text-decoration: none;
            z-index: 1000;
            animation: fadeSlide .5s ease;
        }

        .back-icon span {
            font-size: 14px;
            font-weight: 600;
        }

        .back-icon:hover {
            transform: translateX(-6px);
            color: #0a58ca;
            background: #ffffff;
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.18);
        }

        @keyframes fadeSlide {
            from {
                opacity: 0;
                transform: translateX(-15px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 576px) {
            .back-icon span {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- BOTÓN VOLVER -->
    <a href="../index.php" class="back-icon">
        <i class="bi bi-arrow-left"></i>
        <span>Volver</span>
    </a>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!-- CARD LOGIN -->
        <div class="card shadow p-4 rounded-4 w-100 login-card" style="max-width: 480px;">

            <div class="text-center mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" width="80" alt="Login">
                <h3 class="fw-bold mt-2">Iniciar Sesión</h3>
                <p class="text-muted">Accede a tu cuenta para continuar</p>
            </div>

            <form id="formLogin">

                <div class="mb-3">
                    <label class="form-label fw-bold">Usuario</label>
                    <input type="text" id="usuario" class="form-control" placeholder="Ingrese su usuario">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <input type="password" id="contrasena" class="form-control" placeholder="********">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    Ingresar
                </button>

            </form>

            <div class="text-center mt-3">
                <p class="mb-0">¿Olvidaste tu contraseña?</p>
                <a class="fw-bold" data-bs-toggle="modal" data-bs-target="#modalCedula" style="cursor:pointer;">
                    Restablecer contraseña
                </a>
            </div>

            <div class="text-center mt-3">
                <p class="mb-0 small">¿No tienes cuenta?</p>
                <a href="registro.php" class="fw-semibold">Crear una cuenta</a>
            </div>

        </div>

    </div>

    <?php include("../componentes/modales.php"); ?>

    <!-- JS -->
    <script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/notify/notify.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/ajaxjquery/ajax.js"></script>

</body>

</html>
