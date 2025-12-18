$(document).ready(function () {



  // ===============================
  // LOGIN
  // ===============================
  if ($("#formLogin").length) {
    $("#formLogin").on("submit", function (e) {
      e.preventDefault();

      $.ajax({
        url: "../controllers/loginController.php",
        method: "POST",
        data: {
          usuario: $("#usuario").val().trim(),
          contrasena: $("#contrasena").val().trim()
        },
        dataType: "json",
        success: function (res) {
          if (res.ok) window.location.href = "../views/dashboard.php";
          else $.notify(res.mensaje || "No se pudo iniciar sesión", "warn");
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          $.notify("Error en la petición AJAX ❌", "error");
        }
      });
    });
  }
  // ===============================
  // RESET PASSWORD: CÉDULA -> MODAL NUEVA CLAVE
  // ===============================
  if ($("#formCedulaReset").length) {
    $("#formCedulaReset").on("submit", function (e) {
      e.preventDefault();

      let cedula = ($("#cedulaReset").val() || "").replace(/\D/g, "").trim();
      if (!cedula) { $.notify("Ingresa la cédula", "warn"); return; }

      $.ajax({
        url: "../controllers/verificarCedulaResetController.php", // 👈 controller separado
        method: "POST",
        data: { cedula },
        dataType: "json",
        success: function (res) {
          if (!res.ok) { $.notify(res.mensaje || "No se pudo verificar", "error"); return; }

          $("#nombreUsuarioClave").text(res.usuario.nombre_usu + " " + res.usuario.apellido_usu);
          $("#cedulaUsuarioClave").val(res.usuario.cedula_usu);

          bootstrap.Modal.getInstance(document.getElementById("modalCedulaReset")).hide();
          bootstrap.Modal.getOrCreateInstance(document.getElementById("modalNuevaClave")).show();
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          $.notify("Error al consultar cédula ❌", "error");
        }
      });
    });
  }

  // Mostrar/ocultar nueva clave
  $("#btnToggleNueva").on("click", function(){
    const $inp = $("#contrasena");
    const type = $inp.attr("type") === "password" ? "text" : "password";
    $inp.attr("type", type);
    $(this).find("i").toggleClass("bi-eye bi-eye-slash");
  });

  // Mostrar/ocultar confirmar clave
  $("#btnToggleConfirmar").on("click", function(){
    const $inp = $("#contrasena2");
    const type = $inp.attr("type") === "password" ? "text" : "password";
    $inp.attr("type", type);
    $(this).find("i").toggleClass("bi-eye bi-eye-slash");
  });

// Limpiar cuando se cierre el modal
$("#modalNuevaClave").on("hidden.bs.modal", function(){
  $("#nueva_clave").val("");
  $("#confirmar_clave").val("");
  $("#nueva_clave").attr("type","password");
  $("#confirmar_clave").attr("type","password");
  $("#btnToggleNueva i").attr("class","bi bi-eye");
  $("#btnToggleConfirmar i").attr("class","bi bi-eye");
});

// Cambiar clave
  if ($("#formCambiarClave").length) {
    $("#formCambiarClave").on("submit", function (e) {
      e.preventDefault();

      const cedula = ($("#cedulaUsuarioClave").val() || "").trim();
      const nueva  = ($("#nueva_clave").val() || "").trim();
      const conf   = ($("#confirmar_clave").val() || "").trim();

      if (!cedula) return $.notify("No se encontró la cédula (consulta de nuevo)", "warn");
      if (nueva.length < 6) return $.notify("Mínimo 6 caracteres", "warn");
      if (nueva !== conf) return $.notify("Las contraseñas no coinciden", "warn");

      $.ajax({
        url: "../controllers/cambiarClaveController.php",
        method: "POST",
        data: { cedula, nueva_clave: nueva },
        dataType: "json",
        success: function (res) {
          if (res.ok) {
            $.notify(res.mensaje || "Contraseña actualizada ✔", "success");
            bootstrap.Modal.getInstance(document.getElementById("modalNuevaClave")).hide();
          } else {
            $.notify(res.mensaje || "No se pudo actualizar", "error");
          }
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          $.notify("Error al actualizar contraseña ❌", "error");
        }
      });
    });
  }





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

    





    // PROCESO VERIFICAR POR USU POR CEDULA, SINO TIENE SE HACE EL REGISTRO RAPIDO LUEGO PIDE PIN,
// ===============================
  // JUEGO: CÉDULA -> (REGISTRO RÁPIDO) -> PIN
  // ===============================
  if ($("#formCedulaJuego").length) {

    let bloqueCedula = false, bloqueRegistro = false, bloquePin = false;
    let jugador = { id_usu: null, cedula: "", nombre: "", apellido: "" };

    $("#formCedulaJuego").on("submit", function(e){
      e.preventDefault();
      if (bloqueCedula) return;
      bloqueCedula = true;

      let cedula = ($("#cedulaJuego").val() || "").replace(/\D/g,'').trim();
      if (!cedula) { $.notify("Ingresa la cédula", "warn"); bloqueCedula=false; return; }

      $.ajax({
        url: "controllers/verificarCedulaController.php", // 👈 index.php (sin ../)
        type: "POST",
        data: { cedula },
        dataType: "json",
        success: function(res){
          bloqueCedula = false;

          if(!res.ok){
            $.notify(res.mensaje || "No se pudo verificar", "error");
            return;
          }

          if(res.existe){
            jugador.id_usu = res.usuario.id_usu;
            jugador.cedula = res.usuario.cedula_usu;
            jugador.nombre = res.usuario.nombre_usu;
            jugador.apellido = res.usuario.apellido_usu;

            $("#nombreUsuarioCodigo").text(jugador.nombre + " " + jugador.apellido);
            $("#cedulaUsuarioCodigo").text(jugador.cedula);

            bootstrap.Modal.getInstance(document.getElementById("modalCedulaJuego")).hide();
            bootstrap.Modal.getOrCreateInstance(document.getElementById("modalCodigo")).show();
            return;
          }

          // no existe -> registro rápido
          $("#cedulaRR").val(cedula);
          bootstrap.Modal.getInstance(document.getElementById("modalCedulaJuego")).hide();
          bootstrap.Modal.getOrCreateInstance(document.getElementById("modalRegistroRapido")).show();
        },
        error: function(){
          bloqueCedula = false;
          $.notify("Error de servidor verificando cédula", "error");
        }
      });
    });


$("#formRegistroRapido").on("submit", function(e){
      e.preventDefault();
      if (bloqueRegistro) return;
      bloqueRegistro = true;

      let cedula = ($("#cedulaRR").val() || "").replace(/\D/g,'').trim();
      let nombre = ($("#nombreRR").val() || "").trim();
      let apellido = ($("#apellidoRR").val() || "").trim();

      if(!cedula || !nombre || !apellido){
        $.notify("Completa todos los campos", "warn");
        bloqueRegistro=false;
        return;
      }

      $.ajax({
        url: "controllers/registroRapidoController.php",
        type: "POST",
        data: { cedula, nombre, apellido },
        dataType: "json",
        success: function(res){
          bloqueRegistro = false;

          if(!res.ok){
            $.notify(res.mensaje || "No se pudo registrar", "error");
            return;
          }

          jugador.id_usu = res.id_usu;
          jugador.cedula = cedula;
          jugador.nombre = nombre;
          jugador.apellido = apellido;

          $("#nombreUsuarioCodigo").text(jugador.nombre + " " + jugador.apellido);
          $("#cedulaUsuarioCodigo").text(jugador.cedula);

          bootstrap.Modal.getInstance(document.getElementById("modalRegistroRapido")).hide();
          bootstrap.Modal.getOrCreateInstance(document.getElementById("modalCodigo")).show();
          $.notify("✅ Registro creado. Ahora ingresa el PIN.", "success");
        },
        error: function(){
          bloqueRegistro = false;
          $.notify("Error de servidor registrando", "error");
        }
      });
    });

    $("#btnIngresarJuego").on("click", function(){
      if (bloquePin) return;
      bloquePin = true;

      let pin = ($("#codigo").val() || "").trim();
      if(!pin){
        $.notify("Ingrese el PIN", "warn");
        bloquePin=false;
        return;
      }

      $.ajax({
        url: "controllers/validarPinController.php",
        type: "POST",
        data: { pin },
        dataType: "json",
        success: function(res){
          bloquePin = false;

          if(res.ok) window.location.href = "views/quizz.php";
          else $.notify(res.mensaje || "PIN inválido", "error");
        },
        error: function(){
          bloquePin = false;
          $.notify("Error al validar el PIN", "error");
        }
      });
    });





 


  }


    














});
