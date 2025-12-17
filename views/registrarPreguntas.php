<?php
require_once("../componentes/variables_globales.php");

if (!isset($_SESSION["usuario_id"])) { header("Location: ../auth/login.php"); exit; }

$usuario = obtenerUsuarioSesion();
if ((int)$usuario["id_rol"] !== 3) { header("Location: ../views/dashboard.php"); exit; }

$idRol = (int)$usuario["id_rol"];
$navbarClass = "navbar-maestro";
if ($idRol === 1 || $idRol === 2) $navbarClass = "navbar-estudiante";

require "../db/conexion.php";

$idProfesor = (int)$usuario["id_usu"];
$quizzes = $conn->query("
    SELECT q.id_quiz, q.nombre_quiz, r.nombre_rol
    FROM quiz q
    JOIN rol r ON q.id_rol = r.id_rol
    WHERE q.id_profesor = $idProfesor
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Preguntas</title>
<link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../componentes/partes/nav.php"; ?>

<section class="container my-3">

<h1 class="dashboard-title">Registrar Preguntas (10 por dificultad)</h1>
<p class="text-secondary">Todas las preguntas se guardan en un solo envío | <a href="../views/dashboard.php">Regresar al inicio</a></p>

<div class="card shadow-sm">
<div class="card-body">

<label class="fw-bold">Quizz:</label>
<select id="id_quiz" class="form-select mb-2">
    <option value="">-- Seleccionar --</option>
    <?php while ($q = $quizzes->fetch_assoc()): ?>
        <option value="<?= $q["id_quiz"] ?>" data-nivel="<?= htmlspecialchars($q["nombre_rol"]) ?>">
            <?= htmlspecialchars($q["nombre_quiz"]) ?>
        </option>
    <?php endwhile; ?>
</select>

<div id="infoNivel" class="alert alert-info d-none">
    Nivel del quizz: <strong><span id="nivelTexto"></span></strong>
</div>

<label class="fw-bold">Dificultad:</label>
<select id="id_dificultad" class="form-select mb-2">
    <option value="">-- Seleccionar dificultad --</option>
</select>

<div id="contadorPreguntas" class="alert alert-secondary d-none">
    Registradas: <strong><span id="cantidadActual">0</span> / 10</strong>
</div>

<hr>

<form id="formPreguntasMasivo">
  <div id="bloquePreguntas"></div>

  <button class="btn btn-success mt-3" type="submit">
      Guardar 10 preguntas
  </button>
</form>

</div>
</div>
</section>

<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script src="../assets/js/notify/notify.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>


<script>
// ======================
// GENERAR 10 BLOQUES
// ======================
function render10Preguntas() {
    let html = "";
    for (let i = 1; i <= 10; i++) {
        html += `
        <div class="border rounded p-3 mb-3 pregunta-bloque" data-index="${i}">
            <h5 class="mb-2">Pregunta ${i}</h5>

            <label class="fw-bold">Tipo de pregunta:</label>
            <select class="form-select tipo-pregunta mb-2">
                <option value="">-- Seleccionar --</option>
                <option value="trivia">Trivia (Opción múltiple A/B/C/D)</option>
                <option value="verdadero_falso">Verdadero / Falso</option>
                <option value="completar">Completar</option>
            </select>

            <label class="fw-bold">Enunciado:</label>
            <textarea class="form-control enunciado mb-2" rows="2" placeholder="Escribe el enunciado"></textarea>

            <div class="contenedor-opciones"></div>
        </div>`;
    }
    $("#bloquePreguntas").html(html);
}
render10Preguntas();

// ======================
// CARGAR DIFICULTADES + NIVEL
// ======================
$("#id_quiz").on("change", function(){
    let nivel = $("#id_quiz option:selected").data("nivel");
    if (nivel) {
        $("#nivelTexto").text(nivel);
        $("#infoNivel").removeClass("d-none");
    } else {
        $("#infoNivel").addClass("d-none");
    }

    $("#id_dificultad").html('<option value="">-- Seleccionar dificultad --</option>');
    $("#contadorPreguntas").addClass("d-none");

    let idQuiz = $(this).val();
    if (!idQuiz) return;

    $.getJSON("../controllers/obtenerDificultadesController.php",
        { id_quiz: idQuiz },
        function(data){
            data.forEach(d => {
                $("#id_dificultad").append(`<option value="${d.id_dificultad}">${d.nombre_dificultad}</option>`);
            });
        }
    );
});

// ======================
// CONTAR PREGUNTAS
// ======================
$("#id_dificultad").on("change", function(){
    let idQuiz = $("#id_quiz").val();
    let idDif  = $(this).val();
    if (!idQuiz || !idDif) return;

    $.getJSON("../controllers/contarPreguntasController.php",
        { id_quiz: idQuiz, id_dificultad: idDif },
        function(res){
            $("#cantidadActual").text(res.total);
            $("#contadorPreguntas").removeClass("d-none");

            if (parseInt(res.total) >= 10) {
                $.notify("Ya existen 10 preguntas en esta dificultad.", "warn");
            }
        }
    );
});

// ======================
// CAMPOS SEGÚN TIPO
// ======================
$(document).on("change", ".tipo-pregunta", function(){
    let bloque = $(this).closest(".pregunta-bloque");
    let cont = bloque.find(".contenedor-opciones");
    let idx  = bloque.data("index");
    let tipo = $(this).val();

    cont.html("");

    if (tipo === "trivia") {
        ["A","B","C","D"].forEach(op => {
            cont.append(`
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="radio" name="correcta_${idx}" value="${op}">
                </div>
                <input class="form-control opcion" data-op="${op}" placeholder="Opción ${op}">
            </div>`);
        });
        cont.append(`<small class="text-muted">Marca la opción correcta (A/B/C/D).</small>`);
    }

    if (tipo === "verdadero_falso") {
        cont.html(`
            <div class="form-check">
                <input class="form-check-input" type="radio" name="correcta_${idx}" value="Verdadero">
                <label class="form-check-label">Verdadero</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="correcta_${idx}" value="Falso">
                <label class="form-check-label">Falso</label>
            </div>
            <small class="text-muted">Selecciona la respuesta correcta.</small>
        `);
    }

    if (tipo === "completar") {
        cont.html(`
            <label class="fw-bold mt-2">Respuesta correcta:</label>
            <input type="text" class="form-control respuesta-texto" placeholder="Ej: información financiera">
            <small class="text-muted">Se valida por texto exacto (puedes manejarlo con trim/lower en el juego).</small>
        `);
    }
});

// ======================
// GUARDAR 10 PREGUNTAS
// ======================
$("#formPreguntasMasivo").on("submit", function(e){
    e.preventDefault();

    if (!$("#id_quiz").val()) { $.notify("Seleccione un quizz", "warn"); return; }
    if (!$("#id_dificultad").val()) { $.notify("Seleccione una dificultad", "warn"); return; }

    let preguntas = [];
    let error = false;

    $(".pregunta-bloque").each(function(){
        let idx = $(this).data("index");
        let tipo = $(this).find(".tipo-pregunta").val();
        let enunciado = $(this).find(".enunciado").val().trim();

        if (!tipo || !enunciado) error = true;

        let correcta = $(`input[name='correcta_${idx}']:checked`).val() || "";
        let respuesta_texto = $(this).find(".respuesta-texto").val() ? $(this).find(".respuesta-texto").val().trim() : "";

        let opciones = [];
        $(this).find(".opcion").each(function(){
            opciones.push({ op: $(this).data("op"), texto: $(this).val().trim() });
        });

        // Validaciones por tipo
        if (tipo === "trivia") {
            if (!correcta) error = true;
            if (opciones.length !== 4) error = true;
            opciones.forEach(o => { if (!o.texto) error = true; });
        }

        if (tipo === "verdadero_falso") {
            if (!correcta) error = true;
        }

        if (tipo === "completar") {
            if (!respuesta_texto) error = true;
        }

        preguntas.push({ tipo, enunciado, correcta, respuesta_texto, opciones });
    });

    if (error) {
        $.notify("Complete correctamente las 10 preguntas según su tipo.", "warn");
        return;
    }

    $.ajax({
        url: "../controllers/registrarPreguntasMasivoController.php",
        type: "POST",
        data: JSON.stringify({
            id_quiz: $("#id_quiz").val(),
            id_dificultad: $("#id_dificultad").val(),
            preguntas
        }),
        contentType: "application/json",
        dataType: "json",
        success: function(res){
            if (res.ok) {
                $.notify("✅ Preguntas guardadas correctamente", "success");
                render10Preguntas();
                $("#id_dificultad").trigger("change");
            } else {
                $.notify(res.mensaje, "error");
            }
        },
        error: function(xhr){
            $.notify("Error en el servidor (AJAX) ❌", "error");
            // Si quieres depurar: console.log(xhr.responseText);
        }
    });
});
</script>

</body>
</html>
