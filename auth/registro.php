<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Partida Perfecta | Registro</title>

    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
     <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

    <style>
        .register-card:hover {
            transform: translateY(-5px);
            transition: .2s ease;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4 register-card" style="width: 420px">

            <div class="text-center mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3209/3209265.png" width="80">
                <h3 class="fw-bold mt-2">Crear Cuenta</h3>
                <p class="text-muted">Regístrate para ingresar al sistema</p>
            </div>

            <form id="formRegistro">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nombres</label>
                        <input type="text" id="nombre" data-validate="letras" placeholder="Ingrese sus nombre" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Apellidos</label>
                        <input type="text" id="apellido" data-validate="letras" placeholder="Ingrese sus apellidos" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Cédula</label>
                        <input type="text" id="cedu"   data-validate="cedula" placeholder="Ingrese su cédula" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Usuario</label>
                        <input type="text" id="usuario" placeholder="Ingrese su usuario" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <div class="input-group">
                        <input type="password" placeholder="Ingrese su contraseña" id="contrasena" class="form-control">

                        <button type="button"
                            class="btn btn-outline-secondary toggle-password"
                            data-target="contrasena">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nivel de estudio</label>
                    <select id="nivel_estudio" class="form-select">
                        <option value="">-- Seleccionar --</option>
                        <option value="1">Estudiante Básico</option>
                        <option value="2">Estudiante Universitario</option>
                        <option value="3">Maestro</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-bold">
                    Registrar
                </button>

            </form>

            <div class="text-center mt-3">
                <p class="mb-0">¿Ya tienes cuenta?</p>
                <a href="login.php" class="fw-bold">Iniciar sesión</a>
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