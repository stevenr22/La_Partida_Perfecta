<!-- ADMINISTRAR CONTROL DE SESION -->
<?php
require_once("../componentes/variables_globales.php");
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
$usuario = obtenerUsuarioSesion();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>QUIZZPRINT | Registrar Preguntas</title>

</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <?php include "../componentes/partes/nav.php"; ?>

    <!-- ================= CONTENIDO ================= -->
    <section class="container my-3">

        <h1 class="dashboard-title mb-2">Registrar preguntas</h1>
        <p class="text-secondary mb-4">Registro de preguntas para el Quizz |
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

    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- ============================================= -->
    <!--           SISTEMA DE PREGUNTAS NUEVO          -->
    <!-- ============================================= -->
    <script>
        const contenedor = document.getElementById("contenedor_formularios");

        document.getElementById("nivel_estudio").addEventListener("change", function() {
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
            let html = `
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>${nombreNivel}</strong>
            </div>
            <div class="card-body">
                ${crearFormulariosPreguntas(nombreNivel)}
            </div>
        </div>`;
            contenedor.innerHTML += html;
        }

        function crearFormulariosPreguntas(nombreNivel) {
            let html = "";
            for (let i = 1; i <= 10; i++) {

                html += `
            <div class="border rounded p-3 mb-3">
                <h5 class="mb-3">Pregunta ${i}</h5>

                <!-- Tipo de pregunta -->
                <label class="form-label">Tipo de pregunta:</label>
                <select class="form-select mb-3" onchange="cambiarTipoPregunta(this, '${nombreNivel}', ${i})">
                    <option value="">-- Seleccionar --</option>
                    <option value="trivia">Trivia (4 opciones)</option>
                    <option value="vf">Verdadero / Falso</option>
                    <option value="completar">Completar la frase</option>
                    <option value="ordenar">Ordenar pasos (Secuencia lógica)</option>
                    <option value="emparejar">Emparejar conceptos simples</option>
                    <option value="multiple">Respuesta múltiple</option>
                    <option value="arrastrar">Arrastrar respuesta</option>
                </select>

                <!-- Contenedor dinámico -->
                <div id="contenido_${nombreNivel}_${i}"></div>

            </div>
            `;
            }
            return html;
        }

        function cambiarTipoPregunta(select, nivel, numero) {
            const contenedor = document.getElementById(`contenido_${nivel}_${numero}`);
            const tipo = select.value;

            contenedor.innerHTML = "";

            /* ======================= TRIVIA ======================= */
            if (tipo === "trivia") {
                contenedor.innerHTML = `
                <label>Pregunta:</label>
                <input type="text" class="form-control mb-3">

                <div class="row g-2">
                    <div class="col-md-6">
                        <label>Opción A:</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Opción B:</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>Opción C:</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>Opción D:</label>
                        <input type="text" class="form-control">
                    </div>
                </div>

                <label class="mt-3">Respuesta correcta:</label>
                <select class="form-select">
                    <option>A</option><option>B</option><option>C</option><option>D</option>
                </select>

                <button class="btn btn-success mt-3">Registrar</button>`;
            }

            /* ======================= VERDADERO O FALSO ======================= */
            if (tipo === "vf") {
                contenedor.innerHTML = `
                <label>Pregunta:</label>
                <input type="text" class="form-control mb-3">

                <label>Respuesta correcta:</label>
                <select class="form-select">
                    <option>Verdadero</option>
                    <option>Falso</option>
                </select>

                <button class="btn btn-success mt-3">Registrar</button>`;
            }

            /* ======================= COMPLETAR ======================= */
            if (tipo === "completar") {
                contenedor.innerHTML = `
                <label>Frase con espacio vacío:</label>
                <input type="text" class="form-control mb-2" placeholder="Ejemplo: La capital de Ecuador es _____">

                <label>Respuesta correcta:</label>
                <input type="text" class="form-control mb-2">

                <button class="btn btn-success mt-3">Registrar</button>`;
            }

            /* ======================= ORDENAR PASOS ======================= */
            if (tipo === "ordenar") {
                contenedor.innerHTML = `
                <p class="text-secondary">Escriba los pasos en el orden correcto:</p>

                <input type="text" class="form-control mb-2" placeholder="Paso 1">
                <input type="text" class="form-control mb-2" placeholder="Paso 2">
                <input type="text" class="form-control mb-2" placeholder="Paso 3">
                <input type="text" class="form-control mb-2" placeholder="Paso 4">

                <button class="btn btn-success mt-3">Registrar</button>`;
            }

            /* ======================= EMPAREJAR ======================= */
            if (tipo === "emparejar") {
                contenedor.innerHTML = `
                <p class="text-secondary">Relacione cada concepto con su respuesta:</p>

                <div class="row">
                    <div class="col-md-6">
                        <label>Conceptos</label>
                        <input type="text" class="form-control mb-2" placeholder="Concepto 1">
                        <input type="text" class="form-control mb-2" placeholder="Concepto 2">
                        <input type="text" class="form-control mb-2" placeholder="Concepto 3">
                    </div>

                    <div class="col-md-6">
                        <label>Respuestas</label>
                        <input type="text" class="form-control mb-2" placeholder="Respuesta 1">
                        <input type="text" class="form-control mb-2" placeholder="Respuesta 2">
                        <input type="text" class="form-control mb-2" placeholder="Respuesta 3">
                    </div>
                </div>

                <button class="btn btn-success mt-3">Registrar</button>`;
            }

            /* ======================= MULTIPLE ======================= */
            if (tipo === "multiple") {
                contenedor.innerHTML = `
                <label>Pregunta:</label>
                <input type="text" class="form-control mb-2">

                <label>Opciones (marca las correctas):</label>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox"> A
                    <input type="text" class="form-control mt-1">
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox"> B
                    <input type="text" class="form-control mt-1">
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox"> C
                    <input type="text" class="form-control mt-1">
                </div>

                <button class="btn btn-success mt-3">Registrar</button>`;
            }

            /* ======================= IMAGEN ======================= */
            if (tipo === "arrastrar") {
                contenedor.innerHTML = `
                <label>Instrucción de la pregunta:</label>
                <input type="text" class="form-control mb-3" placeholder="Ej: Arrastra cada elemento a su categoría correcta">

                <label>Elementos para arrastrar (1 por línea):</label>
                <textarea class="form-control mb-3" rows="4" placeholder="Ej:
            Perro
            Gato
            Loro
            Conejo"></textarea>

                <label>Categorías (1 por línea):</label>
                <textarea class="form-control mb-3" rows="2" placeholder="Ej:
            Animales domésticos
            Animales salvajes"></textarea>

                <label>Respuestas correctas (formato: elemento → categoría):</label>
                <textarea class="form-control mb-3" rows="4" placeholder="Ej:
            Perro → Animales domésticos
            Gato → Animales domésticos
            Loro → Animales salvajes
            Conejo → Animales domésticos"></textarea>

                <button class="btn btn-success mt-3">Registrar</button>`;
            }

        }
    </script>
</body>

</html>