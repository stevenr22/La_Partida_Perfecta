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
    // ===============================
    // CARGAR DIFICULTADES SEGÚN QUIZZ
    // ===============================
    $("#id_quiz").on("change", function () {

        let idQuiz = $(this).val();

        // Resetear dificultad y contador
        $("#id_dificultad").html('<option value="">-- Seleccionar dificultad --</option>');
        $("#contadorPreguntas").addClass("d-none");

        if (!idQuiz) return;

        $.getJSON(
            "../controllers/obtenerDificultadesController.php",
            { id_quiz: idQuiz },
            function (data) {

                if (data.length === 0) {
                    $.notify("Este quizz no tiene dificultades asignadas", "warn");
                    return;
                }

                data.forEach(d => {
                    $("#id_dificultad").append(
                        `<option value="${d.id_dificultad}">
                            ${d.nombre_dificultad}
                        </option>`
                    );
                });
            }
        );
    });


    // ===============================
    // MOSTRAR NIVEL DEL QUIZZ
    // ===============================
    $("#id_quiz").on("change", function () {
        let nivel = $("#id_quiz option:selected").data("nivel");
        if (nivel) {
            $("#nivelTexto").text(nivel);
            $("#infoNivel").removeClass("d-none");
        } else {
            $("#infoNivel").addClass("d-none");
        }
    });

    // ===============================
    // REGISTRAR PREGUNTA
    // ===============================
    $("#formRegistrarPregunta").on("submit", function (e) {
        e.preventDefault();

        let opciones = [];
        let error = false;

        $(".opcion").each(function () {
            let texto = $(this).val().trim();
            if (texto === "") error = true;

            opciones.push({
                texto: texto,
                op: $(this).data("op")
            });
        });

        if (!$("#id_quiz").val()) {
            $.notify("Seleccione un quizz", "warn");
            return;
        }

        if (!$("#id_dificultad").val()) {
            $.notify("Seleccione una dificultad", "warn");
            return;
        }

        if ($("#enunciado_preg").val().trim() === "") {
            $.notify("Ingrese la pregunta", "warn");
            return;
        }

        if (error) {
            $.notify("Complete todas las opciones", "warn");
            return;
        }

        let correcta = $("input[name='correcta']:checked").val();
        if (!correcta) {
            $.notify("Seleccione la respuesta correcta", "warn");
            return;
        }

        let datos = {
            id_quiz: $("#id_quiz").val(),
            id_dificultad: $("#id_dificultad").val(),
            enunciado_preg: $("#enunciado_preg").val(),
            correcta: correcta,
            opciones: opciones
        };

        $.ajax({
            url: "../controllers/registrarPreguntaController.php",
            type: "POST",
            data: JSON.stringify(datos),
            contentType: "application/json",
            dataType: "json",
            success: function (res) {
                if (res.ok) {
                    $.notify("Pregunta registrada correctamente ✔", "success");
                    $("#formRegistrarPregunta")[0].reset();
                    $("#contadorPreguntas").addClass("d-none");
                } else {
                    $.notify(res.mensaje, "error");
                }
            },
            error: function () {
                $.notify("Error en el servidor ❌", "error");
            }
        });
    });

    // GENERAR PIN
    $("#formIniciarPartida").on("submit", function (e) {
        e.preventDefault();

        let idQuiz = $("#id_quiz").val();
        if (!idQuiz) {
            $.notify("Seleccione un quizz", "warn");
            return;
        }

        $.ajax({
            url: "../controllers/iniciarPartidaController.php",
            type: "POST",
            data: { id_quiz: idQuiz },
            dataType: "json",
            success: function (res) {
                if (res.ok) {
                    $("#pinTexto").text(res.pin);
                    $("#resultadoPin").removeClass("d-none");
                    $.notify("Partida iniciada correctamente ✔", "success");
                } else {
                    $.notify(res.mensaje, "error");
                }
            },
            error: function () {
                $.notify("Error al iniciar la partida ❌", "error");
            }
        });
    });

    // INGRESAR AL JUEGO
    $("#btnIngresarJuego").on("click", function () {

        let pin = $("#codigo").val().trim();

        if (!pin) {
            $.notify("Ingrese el código", "warn");
            return;
        }

        $.ajax({
            url: "/La_Partida_Perfecta/controllers/validarPinController.php",
            type: "POST",
            data: { pin },
            dataType: "json",
            success: function (res) {
                if (res.ok) {
                    window.location.href = "/La_Partida_Perfecta/views/quizz.php";
                } else {
                    $.notify(res.mensaje, "error");
                }
            },
            error: function () {
                $.notify("Error al validar el PIN", "error");
            }
        });
    });









});
