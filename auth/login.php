<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <title>La Partida Perfecta | Iniciar Sesión</title>

    <style>
        .login-card {
            max-width: 420px;
            border-radius: 15px;
        }

        .login-card:hover {
            transform: translateY(-5px);
            transition: .2s ease;
        }
        .notifyjs-corner {
            z-index: 999999 !important;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow p-4 login-card">

            <div class="text-center mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" width="80">
                <h3 class="fw-bold mt-2">Iniciar Sesión</h3>
                <p class="text-muted">Accede a tu cuenta para continuar</p>
            </div>

            <form id="formLogin">

                <div class="mb-3">
                    <label class="form-label fw-bold">Usuario</label>
                    <input type="text" id="usuario" placeholder="Ingrese su usuario" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <input type="password" placeholder="********" id="contrasena" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Ingresar</button>

            </form>
            <div class="text-center mt-3">
                <p class="mb-0">¿Olvidaste tu contraseña?</p>
                <a class="fw-bold" data-bs-toggle="modal" data-bs-target="#modalCedula" style="cursor: pointer;">
                    Restablecer contraseña
                </a>
            </div>

            <div class="text-center mt-3">
                <p class="mb-0">¿No tienes cuenta?</p>
                <a href="registro.php" class="fw-bold">Crear una cuenta</a>
            </div>
           

        </div>

    </div>

    <?php include("../componentes/modales.php"); ?>
    <script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/notify/notify.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/ajaxjquery/ajax.js"></script>

</body>

</html>