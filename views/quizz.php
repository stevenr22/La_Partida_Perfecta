<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Quiz Nivel Básico</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">

    <style>
        body {
            background: #f4f6f9;
        }
        .card-intro {
            max-width: 600px;
            margin: auto;
            margin-top: 80px;
            border-radius: 15px;
        }
        .resultado-img {
            width: 140px;
        }
    </style>
</head>

<body>

<!-- 🎵 AUDIO -->
<audio id="quizMusic" src="../assets/songs/quizzGame.mp3" loop></audio>

<!-- INTRO -->
<div class="card card-intro shadow text-center p-4">
    <h2 class="mb-2">📘 Quiz Nivel Básico</h2>
    <p class="text-muted">
        Responde 10 preguntas básicas de contabilidad.<br>
        Tiempo por pregunta: <b>25 segundos</b>
    </p>
    <button class="btn btn-success btn-lg mt-3" onclick="iniciarPreparacion()">
        ▶ Empezar Quiz
    </button>
</div>

<!-- MODAL PREPARACIÓN -->
<div class="modal fade" id="modalPreparacion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title">Prepárate</h5>
            </div>
            <div class="modal-body">
                <p>El quiz iniciará en:</p>
                <h1 id="contador" class="text-danger fw-bold">5</h1>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PREGUNTA -->
<div class="modal fade" id="modalPregunta" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="tituloPregunta" class="modal-title"></h5>
            </div>
            <div class="modal-body">
                <p id="textoPregunta" class="fw-semibold"></p>
                <div id="opciones" class="mt-3"></div>
                <p class="text-danger mt-3">
                    ⏱ Tiempo restante: <b id="timerPregunta">25</b>s
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="siguientePregunta()">
                    Siguiente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL RESULTADO -->
<div class="modal fade" id="modalFinal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <h3 id="resultadoTitulo"></h3>
            <img id="resultadoImg" class="resultado-img my-3">
            <p id="resultadoTexto"></p>

            <a href="../index.php" class="btn btn-primary mt-3">
                Volver al panel
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap -->
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

<script>
/* ================= PREGUNTAS BÁSICAS ================= */
const preguntas = [
 { p:"¿Qué es la contabilidad?", o:["El arte de vender productos","El registro y control de operaciones financieras","El cálculo de impuestos únicamente","El manejo de dinero personal"], r:1 },
 { p:"¿Cuál es el principal objetivo de la contabilidad?", o:["Aumentar las ventas","Controlar empleados","Proporcionar información financiera","Pagar impuestos"], r:2 },
 { p:"¿Cuál de los siguientes es un activo?", o:["Préstamo bancario","Proveedores","Efectivo","Capital social"], r:2 },
 { p:"¿Qué es un pasivo?", o:["Lo que la empresa posee","Lo que la empresa debe","Las ganancias obtenidas","Los ingresos diarios"], r:1 },
 { p:"¿Qué representa el patrimonio?", o:["Las deudas","Ingresos del mes","La diferencia entre activos y pasivos","Dinero en caja"], r:2 },
 { p:"¿Cuál es la ecuación contable básica?", o:["Ingresos – Gastos = Utilidad","Activo = Pasivo + Patrimonio","Activo + Gastos = Pasivo","Patrimonio – Pasivo = Activo"], r:1 },
 { p:"¿Qué rama se usa para decisiones internas?", o:["Financiera","Fiscal","Administrativa","Bancaria"], r:2 },
 { p:"¿Qué rama se enfoca en impuestos?", o:["Costos","Fiscal","Administrativa","Comercial"], r:1 },
 { p:"¿Qué es la contabilidad financiera?", o:["Solo contador","Impuestos","Información a terceros","Costos"], r:2 },
 { p:"¿Qué rama calcula costos de producción?", o:["Fiscal","Administrativa","Costos","Financiera"], r:2 }
];

let indice = 0;
let puntaje = 0;
let tiempoPregunta;
let timerInterval;

/* ================= FUNCIONES ================= */
function iniciarPreparacion() {
    const modal = new bootstrap.Modal(document.getElementById('modalPreparacion'));
    modal.show();

    let tiempo = 5;
    const contador = document.getElementById("contador");

    const intervalo = setInterval(() => {
        tiempo--;
        contador.textContent = tiempo;

        if (tiempo === 0) {
            clearInterval(intervalo);
            modal.hide();

            document.getElementById("quizMusic").play();
            mostrarPregunta();
        }
    }, 1000);
}

function mostrarPregunta() {
    const modal = new bootstrap.Modal(document.getElementById('modalPregunta'));
    const q = preguntas[indice];

    document.getElementById("tituloPregunta").innerText =
        `Pregunta ${indice + 1} / ${preguntas.length}`;
    document.getElementById("textoPregunta").innerText = q.p;

    let html = "";
    q.o.forEach((op, i) => {
        html += `
            <div class="form-check">
                <input class="form-check-input" type="radio" name="respuesta" value="${i}">
                <label class="form-check-label">${op}</label>
            </div>`;
    });

    document.getElementById("opciones").innerHTML = html;
    iniciarTimerPregunta();
    modal.show();
}

function iniciarTimerPregunta() {
    tiempoPregunta = 25;
    document.getElementById("timerPregunta").textContent = tiempoPregunta;

    timerInterval = setInterval(() => {
        tiempoPregunta--;
        document.getElementById("timerPregunta").textContent = tiempoPregunta;

        if (tiempoPregunta === 0) {
            clearInterval(timerInterval);
            siguientePregunta();
        }
    }, 1000);
}

function siguientePregunta() {
    clearInterval(timerInterval);

    const seleccion = document.querySelector('input[name="respuesta"]:checked');
    if (!seleccion) {
        alert("⚠️ Debes seleccionar una respuesta.");
        iniciarTimerPregunta();
        return;
    }

    if (parseInt(seleccion.value) === preguntas[indice].r) {
        puntaje++;
    }

    bootstrap.Modal.getInstance(document.getElementById('modalPregunta')).hide();
    indice++;

    if (indice < preguntas.length) {
        setTimeout(mostrarPregunta, 300);
    } else {
        finalizarQuiz();
    }
}

function finalizarQuiz() {
    const audio = document.getElementById("quizMusic");
    audio.pause();
    audio.currentTime = 0;

    const aprobado = puntaje >= 7;

    document.getElementById("resultadoTitulo").innerText =
        aprobado ? "🎉 APROBADO" : "😞 NO APROBADO";

    document.getElementById("resultadoImg").src =
        aprobado ? "../assets/img/aplausos.gif" : "../assets/img/triste.gif";

    document.getElementById("resultadoTexto").innerText =
        `Tu puntaje fue ${puntaje} / 10`;

    new bootstrap.Modal(document.getElementById('modalFinal')).show();
}
</script>

</body>
</html>
