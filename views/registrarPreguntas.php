<?php
require_once("../componentes/variables_globales.php");
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
$usuario = obtenerUsuarioSesion();

$idRol = (int)$usuario["id_rol"];
if ($idRol !== 3) {
    header("Location: ../views/dashboard.php");
    exit;
}
$id_quiz = (int)($_GET['id_quiz'] ?? 0);
if ($id_quiz === 0) {
    header("Location: ../views/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUIZZPRINT | Registrar Preguntas</title>
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../componentes/partes/nav.php"; ?>

<section class="container my-4">

    <h2 class="dashboard-title">Registrar Preguntas</h2>
    <p class="text-secondary mb-4">
        Agrega preguntas y opciones al quizz seleccionado
    </p>

    <div class="card shadow-sm">
        <div class="card-body">

            <form id="formPregunta">
                <input type="hidden" id="id_quiz" value="<?= $id_quiz ?>">

                <div class="mb-3">
                    <label class="form-label fw-bold">Enunciado de la pregunta</label>
                    <textarea id="enunciado" class="form-control" rows="3" placeholder="Escribe la pregunta..." required></textarea>
                </div>

                <div class="mb-3 col-md-3">
                    <label class="form-label fw-bold">Tiempo (segundos)</label>
                    <input type="number" id="tiempo" class="form-control" value="25" min="5" max="120">
                </div>

                <hr>
                <h5 class="fw-bold mb-3">Opciones de respuesta</h5>

                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <input type="radio" name="correcta" value="0">
                            </span>
                            <input type="text" class="form-control opcion" placeholder="Opción A" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <input type="radio" name="correcta" value="1">
                            </span>
                            <input type="text" class="form-control opcion" placeholder="Opción B" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <input type="radio" name="correcta" value="2">
                            </span>
                            <input type="text" class="form-control opcion" placeholder="Opción C">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <input type="radio" name="correcta" value="3">
                            </span>
                            <input type="text" class="form-control opcion" placeholder="Opción D">
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    Marca con el círculo la <strong>respuesta correcta</strong>.
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    Guardar Pregunta
                </button>
            </form>

        </div>
    </div>

</section>

<script src="../assets/js/ajaxjquery/jquer