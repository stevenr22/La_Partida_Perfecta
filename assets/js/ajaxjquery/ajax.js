$(document).ready(function () {

    

    // ===============================
    // REGISTRO DE USUARIOS
    // ===============================
    $("#formRegistro").on("submit", function (e) {
        e.preventDefault();

        let datos = {
            cedula: $("#cedu").val(),
            nombre: $("#nombre").val(),
            apellido: $("#apellido").val(),
            usuario: $("#usuario").val(),
            contrasena: $("#contrasena").val(),
            nivel_estudio: $("#nivel_estudio").val()
        };

        $.ajax({
            url: "../controllers/registroController.php",
            method: "POST",
            data: datos,
            dataType: "json",
            success: function (res) {
                if (res.ok) {
                    $.notify("Registro exitoso ✔", "success");
                    setTimeout(() => {
                        window.location.href = "login.php";
                    }, 1500);
                } else {
                    $.notify(res.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
    });


    // ===============================
    // LOGIN
    // ===============================
    $("#formLogin").on("submit", function (e) {
        e.preventDefault();

        let datos = {
            usuario: $("#usuario").val(),
            contrasena: $("#contrasena").val()
        };

        $.ajax({
            url: "../controllers/loginController.php",
            method: "POST",
            data: datos,
            dataType: "json",
            success: function (res) {
                if (res.ok) {
                    window.location.href = "../views/dashboard.php";
                } else {
                    $.notify(res.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
    });


    // ===============================
    // EDITAR PERFIL
    // ===============================
    $("#formEditarPerfil").on("submit", function (e) {
        e.preventDefault();

        let datos = {
            id_usu: $("#id_usu").val(),
            nombre: $("#nombre_usu").val(),
            apellido: $("#apellido_usu").val(),
            usuario: $("#usuario_usu").val()
        };

        $.ajax({
            url: "../controllers/actualizarPerfilController.php",
            method: "POST",
            data: datos,
            dataType: "json",
            success: function (res) {
                if (res.ok) {
                    $.notify("Perfil actualizado ✔", "success");
                    setTimeout(() => {
                        window.location.href = "../views/perfil.php";
                    }, 1500);
                } else {
                    $.notify(res.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
    });


    

    // ===============================
    // CONSULTAR USUARIO POR CÉDULA "JUEGO"
    // ===============================
    $("#formCedulaJuego").on("submit", function (e) {
        e.preventDefault();

        let cedula = $("#cedula").val().trim();
        if (!cedula) {
            $.notify("Ingresa la cédula", "warn");
            return;
        }

        $.ajax({
            url: "controllers/consultarUsuController.php",
            method: "POST",
            data: { cedula },
            dataType: "json",
            success: function (res) {

                if (res.ok) {

                    // Llenar datos en ambos modales
                    $("#nombreUsuarioCodigo").text(res.nombre);
                    $("#cedulaUsuarioCodigo").text(cedula);

                    // cerrar modal cedula
                    bootstrap.Modal.getInstance(
                        document.getElementById("modalCedulaJuego")
                    ).hide();

                     bootstrap.Modal.getOrCreateInstance(
                        document.getElementById("modalCodigo")
                    ).show();

                   
                    $.notify("Cédula encontrada ✔", "success");

                } else {
                    $.notify(res.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error al consultar cédula ❌", "error");
            }
        });
    });
    // ===============================
    // CONSULTAR USUARIO POR CÉDULA "CLAVE"
    // ===============================
    $("#formCedulaClave").on("submit", function (e) {
        e.preventDefault();

        let cedula = $("#cedulaClave").val().trim();
        if (!cedula) {
            $.notify("Ingresa la cédula", "warn");
            return;
        }

        $.ajax({
            url: "../controllers/consultarUsuController.php",
            method: "POST",
            data: { cedula },
            dataType: "json",
            success: function (res) {

                if (res.ok) {

                    // Llenar datos en ambos modales
                    $("#nombreUsuarioClave").text(res.nombre);
                    $("#cedulaUsuarioClave").val(res.cedula);
                   

                    // cerrar modal cedula
                    bootstrap.Modal.getInstance(
                        document.getElementById("modalCedulaClave")
                    ).hide();

                     bootstrap.Modal.getOrCreateInstance(
                        document.getElementById("modalNuevaClave")
                    ).show();

                   
                    $.notify("Cédula encontrada ✔", "success");

                } else {
                    $.notify(res.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error al consultar cédula ❌", "error");
            }
        });
    });


    // ===============================
    // CAMBIAR CONTRASEÑA
    // ===============================
    $("#formCambiarClave").on("submit", function (e) {
        e.preventDefault();

        let datos = {
            cedula: $("#cedulaUsuarioClave").val(),
            nueva_clave: $("#nueva_clave").val()
        };
        console.log(datos);

        $.ajax({
            url: "../controllers/cambiarClaveController.php",
            method: "POST",
            data: datos,
            dataType: "json",
            success: function (res) {

                if (res.ok) {

                    $.notify("Contraseña actualizada correctamente ✔", "success");

                    bootstrap.Modal.getInstance(
                        document.getElementById("modalNuevaClave")
                    ).hide();

                  
                    $("#nueva_clave").val("");

                } else {
                    $.notify(res.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error al actualizar contraseña ❌", "error");
            }
        });
    });


    // ==============================
    // REGISTRAR QUIZZ
    // ==============================
    $("#formRegistrarQuizz").on("submit", function (e) {
        e.preventDefault();

        let datos = {
            nombre_quizz: $("#nombre_quizz").val(),
            descripcion_quizz: $("#descripcion_quizz").val(),
            id_rol: $("#id_rol").val(),
            id_usuario: $("#id_usuario").val()
        };

        $.ajax({
            url: "../controllers/registrarQuizzController.php",
            type: "POST",
            data: datos,
            dataType: "json",
            success: function (res) {
                if (res.ok) {
                    $.notify("Quizz registrado correctamente ✔", "success");
                    $("#formRegistrarQuizz")[0].reset();
                } else {
                    $.notify(res.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
    });



});
