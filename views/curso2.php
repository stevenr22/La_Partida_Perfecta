<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Quiz Nivel Intermedio</title>

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
            max-width: 220px;
        }
    </style>
</head>

<body>

<!-- AUDIO -->
<audio id="quizMusic" src="../assets/songs/quizzGame.mp3" loop></audio>

<!-- INTRO -->
<div class="card card-intro shadow text-center p-4">
    <h2 class="mb-2">📘 Quiz Nivel Intermedio</h2>
    <p class="text-muted">
        Responde 10 preguntas de contabilidad financiera.
    </p>
    <button class="btn btn-success btn-lg mt-3" onclick="iniciarPreparacion()">
        ▶ Empezar Quiz
    </button>
</div>

<!-- MODAL PREPARACIÓN -->
<div class="modal fade" id="modalPreparacion" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
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
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="siguientePregunta()">
                    Siguiente
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FINAL -->
<div class="modal fade" id="modalFinal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <h3 id="tituloFinal"></h3>
            <p id="mensajeFinal"></p>
            <img id="imgResultado" class="resultado-img my-3">
            <p class="fw-bold">Puntaje: <span id="puntaje"></span>/10</p>

            <a href="../views/dashboard.php" class="btn btn-primary mt-2">
                Volver al panel
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap -->
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

<script>
/* ================= PREGUNTAS ================= */
const preguntas = [
    {
        tipo: "opcion",
        pregunta: "¿Cuál es el objetivo principal de los estados financieros?",
        opciones: [
            "Proporcionar información financiera útil",
            "Calcular impuestos",
            "Controlar empleados",
            "Fijar precios"
        ],
        correcta: 0
    },
    {
        tipo: "verdadero_falso",
        pregunta: "El principio del devengo reconoce ingresos cuando se cobra el dinero.",
        correcta: 1 // Falso
    },
    {
        tipo: "completar",
        pregunta: "El capital de trabajo se calcula como activos ______ menos pasivos corrientes.",
        correcta: "corrientes"
    },
    {
        tipo: "opcion",
        pregunta: "¿Cuál es un activo no corriente?",
        opciones: ["Caja", "Inventarios", "Maquinaria", "Clientes"],
        correcta: 2
    },
    {
        tipo: "verdadero_falso",
        pregunta: "Los gastos anticipados se consideran un activo.",
        correcta: 0 // Verdadero
    },
    {
        tipo: "opcion",
        pregunta: "¿Qué estado financiero muestra los movimientos de efectivo?",
        opciones: [
            "Balance General",
            "Estado de Resultados",
            "Flujo de Efectivo",
            "Notas"
        ],
        correcta: 2
    },
    {
        tipo: "completar",
        pregunta: "La liquidez mide la capacidad de cumplir obligaciones a ______ plazo.",
        correcta: "corto"
    },
    {
        tipo: "opcion",
        pregunta: "¿Qué método de depreciación reconoce mayor gasto al inicio?",
        opciones: [
            "Línea recta",
            "Saldos decrecientes",
            "Unidades producidas",
            "Valor residual"
        ],
        correcta: 1
    },
    {
        tipo: "verdadero_falso",
        pregunta: "El patrimonio representa las obligaciones de la empresa.",
        correcta: 1 // Falso
    },
    {
        tipo: "opcion",
        pregunta: "¿Qué indicador mide la capacidad de pago a corto plazo?",
        opciones: [
            "Liquidez",
            "Rentabilidad",
            "Endeudamiento",
            "Margen bruto"
        ],
        correcta: 0
    }
];

let indice = 0;
let puntaje = 0;

/* ================= FUNCIONES ================= */
function iniciarPreparacion() {
    const modal = new bootstrap.Modal(document.getElementById('modalPreparacion'));
    modal.show();

    const audio = document.getElementById("quizMusic");
    audio.currentTime = 0;
    audio.play();

    let tiempo = 5;
    const contador = document.getElementById("contador");

    const intervalo = setInterval(() => {
        tiempo--;
        contador.textContent = tiempo;

        if (tiempo === 0) {
            clearInterval(intervalo);
            modal.hide();
            mostrarPregunta();
        }
    }, 1000);
}

function mostrarPregunta() {
    const modal = new bootstrap.Modal(document.getElementById('modalPregunta'));
    const pregunta = preguntas[indice];

    document.getElementById("tituloPregunta").innerText =
        `Pregunta ${indice + 1} / ${preguntas.length}`;
    document.getElementById("textoPregunta").innerText = pregunta.pregunta;

    let html = "";

    if (pregunta.tipo === "opcion") {
        pregunta.opciones.forEach((op, i) => {
            html += `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="respuesta" value="${i}">
                    <label class="form-check-label">${op}</label>
                </div>`;
        });
    }

    if (pregunta.tipo === "verdadero_falso") {
        html = `
            <div class="form-check">
                <input class="form-check-input" type="radio" name="respuesta" value="0">
                <label class="form-check-label">Verdadero</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="respuesta" value="1">
                <label class="form-check-label">Falso</label>
            </div>`;
    }

    if (pregunta.tipo === "completar") {
        html = `
            <input type="text" id="respuestaTexto"
                   class="form-control"
                   placeholder="Escribe tu respuesta aquí">`;
    }

    document.getElementById("opciones").innerHTML = html;
    modal.show();
}

function siguientePregunta() {
    const pregunta = preguntas[indice];
    let correcta = false;

    if (pregunta.tipo === "completar") {
        const texto = document.getElementById("respuestaTexto").value.trim().toLowerCase();
        if (!texto) {
            alert("⚠️ Debes responder la pregunta.");
            return;
        }
        correcta = texto === pregunta.correcta;
    } else {
        const seleccion = document.querySelector('input[name="respuesta"]:checked');
        if (!seleccion) {
            alert("⚠️ Debes seleccionar una respuesta.");
            return;
        }
        correcta = parseInt(seleccion.value) === pregunta.correcta;
    }

    if (correcta) puntaje++;

    bootstrap.Modal.getInstance(document.getElementById('modalPregunta')).hide();
    indice++;

    indice < preguntas.length ? setTimeout(mostrarPregunta, 300) : finalizarQuiz();
}

function finalizarQuiz() {
    const audio = document.getElementById("quizMusic");
    audio.pause();

    document.getElementById("puntaje").innerText = puntaje;

    if (puntaje >= 7) {
        document.getElementById("tituloFinal").innerText = "🎉 ¡Felicidades!";
        document.getElementById("mensajeFinal").innerText = "Has aprobado el nivel intermedio.";
        document.getElementById("imgResultado").src =
            "https://media.giphy.com/media/3o6Zt481isNVuQI1l6/giphy.gif";
    } else {
        document.getElementById("tituloFinal").innerText = "😞 Inténtalo nuevamente";
        document.getElementById("mensajeFinal").innerText =
            "No alcanzaste el puntaje mínimo de aprobación.";
        document.getElementById("imgResultado").src =
            "https://media.giphy.com/media/3o7aD2saalBwwftBIY/giphy.gif";
    }

    new bootstrap.Modal(document.getElementById('modalFinal')).show();
}
</script>

</body>
</html>
