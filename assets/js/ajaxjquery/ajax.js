$(document).ready(function () {
    // AJAX OPARA EL REGISTRO DE USUARIOS
    $("#formRegistro").on("submit", function (e) {
        e.preventDefault();

        let datos = {
            cedula: $("#cedu").val(),
            nombre: $("#nombre").val(),
            apellido: $("#apellido").val(),
            usuario: $("#usuario").val(),
            contrasena: $("#contrasena").val(),
            nivel_estudio: $("#nivel_estudio").val() // ✅ ID CORRECTO
        };


        $.ajax({
            url: "../controllers/registroController.php",
            method: "POST",
            data: datos,
            dataType: "json",
            success: function (respuesta) {

                if (respuesta.ok) {
                    $.notify("Registro exitoso ✔", "success");
                    setTimeout(() => {
                        window.location.href = "login.php";
                    }, 1500);
                } else {
                    $.notify(respuesta.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
    });


    // AJAX PARA EL LOGIN DE USUARIOS
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
            success: function (respuesta) {
                if (respuesta.ok) {
                    // Esta línea ejecuta la redirección a dashboard.php
                    window.location.href = "../views/dashboard.php";
                } else {
                    $.notify(respuesta.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
    });

    // ACTUALIZAR PERFIL DE USUARIO
    $("#formEditarPerfil").on("submit", function (e) {
        e.preventDefault();
        let datos = {
            id_usu: $("#id_usu").val(),
            nombre: $("#nombre_usu").val(),
            apellido: $("#apellido_usu").val(),
            usuario: $("#usuario_usu").val(),

        };
        $.ajax({
            url: "../controllers/actualizarPerfilController.php",
            method: "POST",
            data: datos,
            dataType: "json",
            success: function (respuesta) {
                if (respuesta.ok) {
                    $.notify("Perfil actualizado ✔", "success");
                    //  TIEMPO DE ESPERA PARA VER LA NOTIFICACIÓN
                    setTimeout(() => {
                        // Esta línea ejecuta la redirección a dashboard.php
                        window.location.href = "../views/perfil.php";
                    }, 1500); // <-- Milisegundos de espera


                } else {
                    $.notify(respuesta.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
    });

    // ===============================
    // CONSULTAR USUARIO POR CÉDULA
    // ===============================
    $("#formCedula").on("submit", function (e) {
        e.preventDefault();

        let cedula = $("#cedula").val().trim();
        if (!cedula) return $.notify("Ingresa la cédula", "warn");

        $.ajax({
            url: "controllers/consultarUsuController.php",
            method: "POST",
            data: { cedula },
            dataType: "json",
            success: function (res) {

                if (res.ok) {
                    $("#nombreUsuarioCodigo").text(res.nombre);
                    $("#cedulaUsuarioCodigo").text(cedula);

                    bootstrap.Modal.getInstance(
                        document.getElementById("modalCedula")
                    ).hide();

                    bootstrap.Modal.getOrCreateInstance(
                        document.getElementById("modalCodigo")
                    ).show();

                    $.notify("Cédula encontrada ✔", "success");
                } else {
                    $.notify(res.mensaje, "warn");
                }
            }
        });
    });

    /* CAMBIAR CLAVE */
    $("#linkCambiarClave").on("click", function () {
        $("#nombreUsuarioClave").text($("#nombreUsuarioCodigo").text());

        bootstrap.Modal.getInstance(
            document.getElementById("modalCodigo")
        ).hide();

        bootstrap.Modal.getOrCreateInstance(
            document.getElementById("modalNuevaClave")
        ).show();
    });


    // ===============================
    // CAMBIAR CONTRASEÑA
    // ===============================
    $("#formCambiarClave").on("submit", function (e) {
        e.preventDefault();

        let datos = {
            cedula: $("#cedula").val(),   // reutilizamos la cédula ingresada
            nueva_clave: $("#nueva_clave").val()
        };
        $.ajax({
            url: "../controllers/cambiarClaveController.php",
            method: "POST",
            data: datos,
            dataType: "json",
            success: function (respuesta) {

                if (respuesta.ok) {

                    $.notify("Contraseña actualizada correctamente ✔", "success");

                    // Cerrar modal
                    let modal2 = bootstrap.Modal.getInstance(document.getElementById('modalNuevaClave'));
                    modal2.hide();

                    // Limpiar campos
                    $("#cedula").val("");
                    $("#nueva_clave").val("");

                } else {
                    $.notify(respuesta.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error al actualizar contraseña ❌", "error");
            }
        });
    });





});

