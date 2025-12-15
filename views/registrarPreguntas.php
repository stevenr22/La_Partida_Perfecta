<!-- ADMINISTRAR CONTROL DE SESION -->
<?php
require_once("../componentes/variables_globales.php");
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
$usuario = obtenerUsuarioSesion();
$idRol = (int)$usuario["id_rol"];

$navbarClass = "navbar-maestro";
if ($idRol === 1 || $idRol === 2) {
    $navbarClass = "navbar-estudiante";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZZPRINT | Registrar Preguntas</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<!-- ================= NAVBAR ================= -->
<?php include "../componentes/partes/nav.php"; ?>

<!-- ================= CONTENIDO ================= -->
<section class="container my-3">

    <h1 class="dashboard-title mb-2">Registrar preguntas</h1>
    <p class="text-secondary mb-4">
        Registro de preguntas para el Quizz |
        <a href="../views/dashboard.php">Regresar al inicio</a>
    </p>

    <!-- SELECCIÓN DEL NIVEL -->
    <div class="mb-4">
        <label class="form-label fw-bold">Selecciona el nivel de estudio:</label>
        <select id="nivel_estudio" class="form-select">
            <option value="">-- Seleccionar --</option>
            <option value="estudiante">Nivel Estudiante</option>
            <option value="universitario">Nivel Estudiante Universitario</option>
            <option value="profesional">Nivel Maestro / Profesional</option>
        </select>
    </div>

    <!-- CONTENEDOR DE FORMULARIOS -->
    <div id="contenedor_formularios"></div>

</section>

<!-- ================= SCRIPTS ================= -->
<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script src="../assets/js/notify/notify.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

<script>
const contenedor = document.getElementById("contenedor_formularios");

document.getElementById("nivel_estudio").addEventListener("change", function () {
    const nivel = this.value;
    contenedor.innerHTML = "";

    if (!nivel) return;

    if (nivel === "estudiante") {
        crearBloque("Nivel Básico");
        crearBloque("Nivel Intermedio");
        crearBloque("Nivel Difícil");
    }

    if (nivel === "universitario" || nivel === "profesional") {
        crearBloque("Auditor Junior");
        crearBloque("Auditor Semi Senior");
        crearBloque("Auditor Senior");
    }
});

function crearBloque(nombreNivel) {
    contenedor.innerHTML += `
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <strong>${nombreNivel}</strong>
        </div>
        <div class="card-body">
            ${crearFormulariosPreguntas(nombreNivel)}
        </div>
    </div>`;
}

function crearFormulariosPreguntas(nombreNivel) {
    let html = "";
    for (let i = 1; i <= 10; i++) {
        html += `
        <div class="border rounded p-3 mb-3 pregunta">
            <h5>Pregunta ${i}</h5>

            <label class="form-label">Tipo de pregunta:</label>
            <select class="form-select mb-3 tipo-pregunta">
                <option value="">-- Seleccionar --</option>
                <option value="trivia">Trivia</option>
                <option value="vf">Verdadero / Falso</option>
                <option value="completar">Completar</option>
            </select>

            <div class="contenido"></div>
        </div>`;
    }
    return html;
}

$(document).on("change", ".tipo-pregunta", function () {
    const cont = $(this).closest(".pregunta").find(".contenido");
    const tipo = $(this).val();
    cont.html("");

    if (tipo === "trivia") {
        cont.html(`
            <input class="form-control mb-2" placeholder="Pregunta">
            <input class="form-control mb-2" placeholder="Opción A">
            <input class="form-control mb-2" placeholder="Opción B">
            <input class="form-control mb-2" placeholder="Opción C">
            <input class="form-control mb-2" placeholder="Opción D">
            <button class="btn btn-success btn-guardar">Registrar</button>
        `);
    }

    if (tipo === "vf") {
        cont.html(`
            <input class="form-control mb-2" placeholder="Pregunta">
            <select class="form-select mb-2">
                <option>Verdadero</option>
                <option>Falso</option>
            </select>
            <button class="btn btn-success btn-guardar">Registrar</button>
        `);
    }

    if (tipo === "completar") {
        cont.html(`
            <input class="form-control mb-2" placeholder="Frase">
            <input class="form-control mb-2" placeholder="Respuesta">
            <button class="btn btn-success btn-guardar">Registrar</button>
        `);
    }
});

// EMULACIÓN DE GUARDADO
$(document).on("click", ".btn-guardar", function (e) {
    e.preventDefault();

    const bloque = $(this).closest(".pregunta");
    let valido = false;

    bloque.find("input, textarea").each(function () {
        if ($(this).val().trim() !== "") valido = true;
    });

    if (!valido) {
        $.notify("Complete la pregunta antes de registrar", "warn");
        return;
    }

    $.notify("Pregunta registrada correctamente ✔", "success");

    setTimeout(() => {
        bloque.find("input, textarea").val("");
        bloque.find("select").prop("selectedIndex", 0);
        bloque.find(".contenido").html("");
    }, 600);
});
</script>

</body>
</html>
