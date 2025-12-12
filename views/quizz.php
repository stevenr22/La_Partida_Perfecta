<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Quizz Básico</title>
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container text-center mt-5">
    <h2 class="mb-3">Bienvenido al Quizz</h2>
    <p>Presiona el botón para comenzar.</p>

    <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalInicio">
        Comenzar
    </button>
</div>

<!-- ============================= -->
<!-- MODAL DE CUENTA REGRESIVA -->
<!-- ============================= -->
<div class="modal fade" id="modalInicio" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-3">
      <h4 class="mb-2">Prepárate</h4>
      <p>El quiz comenzará en <b id="contadorInicio">15</b> segundos...</p>
    </div>
  </div>
</div>

<!-- ============================= -->
<!-- MODAL DE PREGUNTA 1 -->
<!-- ============================= -->
<div class="modal fade" id="pregunta1" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <h5 class="mb-2">Pregunta 1 (25s)</h5>
      <p>¿Cuál es la capital de Francia?</p>

      <button class="btn btn-outline-primary w-100 mb-2" onclick="siguientePregunta(2)">París</button>
      <button class="btn btn-outline-primary w-100 mb-2" onclick="siguientePregunta(2)">Roma</button>
      <button class="btn btn-outline-primary w-100" onclick="siguientePregunta(2)">Londres</button>

      <p class="mt-3 text-danger">Tiempo restante: <b id="timer1">25</b>s</p>
    </div>
  </div>
</div>

<!-- ============================= -->
<!-- MODAL DE PREGUNTA 2 -->
<!-- ============================= -->
<div class="modal fade" id="pregunta2" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <h5 class="mb-2">Pregunta 2 (25s)</h5>
      <p>2 + 2 = ?</p>

      <button class="btn btn-outline-primary w-100 mb-2" onclick="finalizar()">3</button>
      <button class="btn btn-outline-primary w-100 mb-2" onclick="finalizar()">4</button>
      <button class="btn btn-outline-primary w-100" onclick="finalizar()">5</button>

      <p class="mt-3 text-danger">Tiempo restante: <b id="timer2">25</b>s</p>
    </div>
  </div>
</div>

<!-- ============================= -->
<!-- MODAL FINAL -->
<!-- ============================= -->
<div class="modal fade" id="modalFinal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-3">
      <h4 class="mb-2">¡Quiz Finalizado!</h4>
      <p>Gracias por participar.</p>
    </div>
  </div>
</div>

<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

<script>
let inicioTimer;
let preguntaTimer;
let currentQuestion = 1;

/* ================================
   Cuenta regresiva inicial (15s)
================================ */
document.getElementById("modalInicio").addEventListener("shown.bs.modal", () => {
    let tiempo = 15;
    let lbl = document.getElementById("contadorInicio");

    inicioTimer = setInterval(() => {
        tiempo--;
        lbl.textContent = tiempo;

        if (tiempo <= 0) {
            clearInterval(inicioTimer);
            var modalInicio = bootstrap.Modal.getInstance(document.getElementById("modalInicio"));
            modalInicio.hide();
            mostrarPregunta(1);
        }
    }, 1000);
});

/* ================================
   Mostrar pregunta N
================================ */
function mostrarPregunta(n) {
    let modal = new bootstrap.Modal(document.getElementById("pregunta" + n));
    modal.show();
    iniciarTimerPregunta(n);
}

/* ================================
   Timer de cada pregunta (25s)
================================ */
function iniciarTimerPregunta(n) {
    let tiempo = 25;
    let lbl = document.getElementById("timer" + n);

    preguntaTimer = setInterval(() => {
        tiempo--;
        lbl.textContent = tiempo;

        if (tiempo <= 0) {
            clearInterval(preguntaTimer);
            siguientePregunta(n + 1);
        }
    }, 1000);
}

/* ================================
   Pasar a la siguiente pregunta
================================ */
function siguientePregunta(n) {
    clearInterval(preguntaTimer);

    let actual = bootstrap.Modal.getInstance(document.getElementById("pregunta" + (n - 1)));
    if (actual) actual.hide();

    if (document.getElementById("pregunta" + n)) {
        mostrarPregunta(n);
    } else {
        finalizar();
    }
}

/* ================================
   Final del quizz
================================ */
function finalizar() {
    clearInterval(preguntaTimer);

    let finalModal = new bootstrap.Modal(document.getElementById("modalFinal"));
    finalModal.show();
}
</script>

</body>
</html>
