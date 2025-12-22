$(document).ready(function () {
  // REGISTRO
  $("#formRegistro").on("submit", function (e) {
    e.preventDefault();

    let datos = {
      cedula: $("#cedu").val(),
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      usuario: $("#usuario").val(),
      contrasena: $("#contrasena").val(),
      nivel_estudio: $("#nivel_estudio").val(),
    };

    $.ajax({
      url: "../controllers/registroController.php",
      method: "POST",
      data: datos,
      dataType: "json",
      success: function (res) {
        if (res.ok) {
          $.notify("Registro exitoso ✔", "success");
          $("#formRegistro")[0].reset();
        } else {
          $.notify(res.mensaje, "warn");
        }
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        $.notify("Error en el servidor ❌", "error");
      },
    });
  });

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
          contrasena: $("#contrasena").val().trim(),
        },
        dataType: "json",
        success: function (res) {
          if (res.ok) window.location.href = "../views/dashboard.php";
          else $.notify(res.mensaje || "No se pudo iniciar sesión", "warn");
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          $.notify("Error en la petición AJAX ❌", "error");
        },
      });
    });
  }

  // ===============================
  // COMPLETAR CUENTA  ✅
  // ===============================
  $("#formCompletarCuenta").on("submit", function(e){
    e.preventDefault();

    const nombre = $("#nombre").val().trim();
    const apellido = $("#apellido").val().trim();
    const usuario = $("#usuario").val().trim();
    const cedula = ($("#cedula").val() || "").replace(/\D/g,'').trim();
    const pass1 = $("#contrasena").val().trim();
    const pass2 = $("#contrasena2").val().trim();

    if(!nombre || !apellido || !usuario || !pass1 || !pass2 || !cedula){
      $.notify("Completa todos los campos", "warn");
      return;
    }


    if(pass1.length < 6){
      $.notify("La contraseña debe tener al menos 6 caracteres", "warn");
      return;
    }

    if(pass1 !== pass2){
      $.notify("Las contraseñas no coinciden", "warn");
      return;
    }

    $.ajax({
      url: "../controllers/completarCuentaController.php",
      method: "POST",
      dataType: "json",
      data: { nombre, apellido, usuario, contrasena: pass1, cedula },
      success: function(res){
        if(res.ok){
          $.notify(res.mensaje || "Perfil completado ✔", "success");
          setTimeout(()=> window.location.href = "../auth/login.php", 1200);
        } else {
          $.notify(res.mensaje || "No se pudo guardar", "error");
        }
      },
      error: function(xhr){
        console.log(xhr.responseText);
        $.notify("Error en el servidor ❌", "error");
      }
    });

  });

  // ===============================
  // RESET PASSWORD: CÉDULA -> MODAL NUEVA CLAVE
  // ===============================
  if ($("#formCedulaReset").length) {
    $("#formCedulaReset").on("submit", function (e) {
      e.preventDefault();

      let cedula = ($("#cedulaReset").val() || "").replace(/\D/g, "").trim();
      if (!cedula) {
        $.notify("Ingresa la cédula", "warn");
        return;
      }

      $.ajax({
        url: "../controllers/verificarCedulaResetController.php", // 👈 controller separado
        method: "POST",
        data: { cedula },
        dataType: "json",
        success: function (res) {
          if (!res.ok) {
            $.notify(res.mensaje || "No se pudo verificar", "error");
            return;
          }

          $("#nombreUsuarioClave").text(
            res.usuario.nombre_usu + " " + res.usuario.apellido_usu
          );
          $("#cedulaUsuarioClave").val(res.usuario.cedula_usu);

          bootstrap.Modal.getInstance(
            document.getElementById("modalCedulaReset")
          ).hide();
          bootstrap.Modal.getOrCreateInstance(
            document.getElementById("modalNuevaClave")
          ).show();
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          $.notify("Error al consultar cédula ❌", "error");
        },
      });
    });
  }

  // Cambiar clave
  if ($("#formCambiarClave").length) {
    $("#formCambiarClave").on("submit", function (e) {
      e.preventDefault();

      const cedula = ($("#cedulaUsuarioClave").val() || "").trim();
      const nueva = ($("#nueva_clave").val() || "").trim();
      const conf = ($("#confirmar_clave").val() || "").trim();

      if (!cedula)
        return $.notify("No se encontró la cédula (consulta de nuevo)", "warn");
      if (nueva.length < 6) return $.notify("Mínimo 6 caracteres", "warn");
      if (nueva !== conf)
        return $.notify("Las contraseñas no coinciden", "warn");

      $.ajax({
        url: "../controllers/cambiarClaveController.php",
        method: "POST",
        data: { cedula, nueva_clave: nueva },
        dataType: "json",
        success: function (res) {
          if (res.ok) {
            $.notify(res.mensaje || "Contraseña actualizada ✔", "success");
            bootstrap.Modal.getInstance(
              document.getElementById("modalNuevaClave")
            ).hide();
          } else {
            $.notify(res.mensaje || "No se pudo actualizar", "error");
          }
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          $.notify("Error al actualizar contraseña ❌", "error");
        },
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
      usuario: $("#usuario_usu").val(),
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
      },
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
      id_usuario: $("#id_usuario").val(),
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
      },
    });
  });
  // ===============================
  // CARGAR DIFICULTADES SEGÚN QUIZZ
  // ===============================
  $("#id_quiz").on("change", function () {
    let idQuiz = $(this).val();

    // Resetear dificultad y contador
    $("#id_dificultad").html(
      '<option value="">-- Seleccionar dificultad --</option>'
    );
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

        data.forEach((d) => {
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
        op: $(this).data("op"),
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
      opciones: opciones,
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
      },
    });
  });

  // PROCESO VERIFICAR POR USU POR CEDULA, SINO TIENE SE HACE EL REGISTRO RAPIDO LUEGO PIDE PIN,
  // ===============================
  // JUEGO: CÉDULA -> (REGISTRO RÁPIDO) -> PIN
  // ===============================
  let bloqueInicial = false;
  let bloquePin = false;

  // Guardamos datos del jugador en memoria JS
  let jugador = {
    id_usu: null,
    nombre: "",
    nivel: "",
  };

  function nombreNivelTexto(nivelVal) {
    if (String(nivelVal) === "1") return "Estudiante Básico";
    if (String(nivelVal) === "2") return "Estudiante Universitario";
    if (String(nivelVal) === "3") return "Maestro";
    return "Sin nivel";
  }

  // ===============================
  // MODAL 1: DATOS INICIALES -> REGISTRO RÁPIDO
  // ===============================
  $("#formDatosIniciales").on("submit", function (e) {
    e.preventDefault();
    if (bloqueInicial) return;
    bloqueInicial = true;

    const nombre = ($("#nombreInicial").val() || "").trim();
    const nivel = $("#nivel_estudio_inicial").val();

    if (!nombre || !nivel) {
      $.notify("Ingresa tu nombre y selecciona el nivel", "warn");
      bloqueInicial = false;
      return;
    }

   

    $.ajax({
      url: "controllers/registroRapidoController.php",
      type: "POST",
      dataType: "json",
      data: { nombre, nivel },
      success: function (res) {
        bloqueInicial = false;

        if (!res.ok) {
          $.notify(res.mensaje || "No se pudo registrar", "error");
          return;
        }

        jugador.id_usu = res.id_usu;
        jugador.nombre = res.nombre;
        jugador.nivel = res.id_rol;

        // Pintar en modal código
        $("#nombreUsuarioCodigo").text(jugador.nombre);
        $("#nivelUsuarioCodigo").text(nombreNivelTexto(jugador.nivel));

        // Cerrar modal 1 y abrir modal 2
        bootstrap.Modal.getInstance(
          document.getElementById("modalDatosIniciales")
        ).hide();
        bootstrap.Modal.getOrCreateInstance(
          document.getElementById("modalCodigo")
        ).show();

        $.notify("✅ Registro creado. Ahora ingresa el PIN.", "success");
      },
      error: function (xhr) {
        console.log(xhr.responseText);
        bloqueInicial = false;
        $.notify("Error en el servidor", "error");
      },
    });
  });

  // MODAL 2: VALIDAR PIN -> ENTRAR A JUEGO
  // ===============================
  $("#btnIngresarJuego").on("click", function () {
    if (bloquePin) return;
    bloquePin = true;

    const pin = ($("#codigo").val() || "").trim();
    if (!pin) {
      $.notify("Ingrese el PIN", "warn");
      bloquePin = false;
      return;
    }

    $.ajax({
      url: "controllers/validarPinController.php",
      type: "POST",
      dataType: "json",
      data: { pin },
      success: function (res) {
        bloquePin = false;

        if (res.ok) {
          window.location.href = "views/quizz.php";
        } else {
          $.notify(res.mensaje || "PIN inválido", "error");
        }
      },
      error: function () {
        bloquePin = false;
        $.notify("Error al validar el PIN", "error");
      },
    });
  });
});
