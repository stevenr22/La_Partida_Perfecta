$(document).ready(function () {
    // AJAX OPARA EL REGISTRO DE USUARIOS
    $("#formRegistro").on("submit", function (e) {
        e.preventDefault();
        let datos = {
            cedula: $("#cedu").val(),
            nombre: $("#nombre").val(),
            apellido: $("#apellido").val(),
            email: $("#email").val(),
            usuario: $("#usuario").val(),
             
            contrsena: $("#contrasena").val()
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
                        // Esta línea ejecuta la redirección a login.php
                        window.location.href = "login.php";
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
            email: $("#email_usu").val(),
            usuario: $("#usuario_usu").val(),
            contrasena: $("#contrasena_usu").val()
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

        $.ajax({
            url: "../controllers/consultarUsuController.php",
            method: "POST",
            data: { cedula: $("#cedula").val() },
            dataType: "json",
            success: function (respuesta) {

                if (respuesta.ok) {

                    // Mostrar el nombre en el modal 2
                    $("#nombreUsuario").text(respuesta.nombre);

                    // Cerrar modal 1 y abrir modal 2
                    let modal1 = bootstrap.Modal.getInstance(document.getElementById('modalCedula'));
                    modal1.hide();

                    let modal2 = new bootstrap.Modal(document.getElementById('modalNuevaClave'));
                    modal2.show();

                    $.notify("Cédula encontrada ✔", "success");
                }
                else {
                    $.notify(respuesta.mensaje, "warn");
                }
            },
            error: function () {
                $.notify("Error en la petición AJAX ❌", "error");
            }
        });
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

