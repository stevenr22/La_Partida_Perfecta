<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">


    <title>La Partida Perfecta | Registro</title>

    <style>
        

        .register-card:hover {
            transform: translateY(-5px);
            transition: .2s ease;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center min-vh-100">

        <div class="card shadow p-4 register-card">

            <div class="text-center mb-3">
                <img src="https://cdn-icons-png.flaticon.com/512/3209/3209265.png" width="80">
                <h3 class="fw-bold mt-2">Crear Cuenta</h3>
                <p class="text-muted">Regístrate para ingresar al sistema y jugar</p>
            </div>

            <form id="formRegistro">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nombres</label>
                        <input type="text" placeholder="Ingrese su nombre" id="nombre" class="form-control" >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Apellidos</label>
                        <input type="text" placeholder="Ingrese su apellido" id="apellido" class="form-control" >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Cedula</label>
                        <input type="text" placeholder="Ingrese su cedula" id="cedu" class="form-control" >
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Usuario</label>
                        <input type="text" placeholder="Ingrese su usuario" id="usuario" class="form-control" >
                    </div>
                </div>

               
                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <input type="password" placeholder="*********" id="contrasena" class="form-control" >
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Selecciona el nivel de estudio:</label>
                        <select id="nivel_estudio" class="form-select">
                            <option value="">-- Seleccionar --</option>
                            <option value="1">Nivel Estudiante Básico</option>
                            <option value="2">Nivel Estudiante Universitario</option>
                            <option value="3">Nivel Maestro</option>
                        </select>
                    </div>
                </div>

                

                <button class="btn btn-success w-100 py-2 fw-bold">Registrar</button>

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
    
    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const nivel = urlParams.get('nivel');
        if (nivel) {
            document.getElementById("nivelEstudio").value = nivel;
        }
    </script>




</body>

</html>
