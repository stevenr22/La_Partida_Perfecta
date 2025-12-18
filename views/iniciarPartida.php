<?php
require_once("../componentes/variables_globales.php");
require "../db/conexion.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* =====================
   VALIDAR SESIÓN
===================== */
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../auth/login.php");
  exit;
}

$usuario = obtenerUsuarioSesion();

/* =====================
   SOLO MAESTRO
===================== */
if ((int)$usuario["id_rol"] !== 3) {
  header("Location: dashboard.php");
  exit;
}

$navbarClass = "navbar-maestro";

/* =====================
   PETICIÓN AJAX (POST)
===================== */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  header("Content-Type: application/json; charset=UTF-8");

  $idQuiz = (int)($_POST["id_quiz"] ?? 0);
  if ($idQuiz <= 0) {
    echo json_encode(["ok"=>false, "mensaje"=>"Quizz inválido"]);
    exit;
  }

  // validar que el quizz pertenezca al maestro
  $idProfesor = (int)$usuario["id_usu"];
  $val = $conn->query("
    SELECT id_quiz
    FROM quiz
    WHERE id_quiz = $idQuiz AND id_profesor = $idProfesor
    LIMIT 1
  ");

  if (!$val || $val->num_rows === 0) {
    echo json_encode(["ok"=>false, "mensaje"=>"No tienes permiso para este quizz"]);
    exit;
  }

  // limpiar sesión de juego anterior
  unset(
    $_SESSION["id_partida"],
    $_SESSION["id_quiz"],
    $_SESSION["id_dificultad"],
    $_SESSION["id_pregunta"],
    $_SESSION["correctas"],
    $_SESSION["total_resp"]
  );

  foreach ($_SESSION as $k => $v) {
    if (strpos($k, "orden_actual_") === 0) {
      unset($_SESSION[$k]);
    }
  }

  // generar PIN único
  do {
    $pin = (string)rand(100000, 999999);
    $check = $conn->query("SELECT id_partida FROM partida WHERE pin='$pin' LIMIT 1");
  } while ($check && $check->num_rows > 0);

  // crear partida
  $ok = $conn->query("
    INSERT INTO partida (id_quiz, pin, estado, fecha)
    VALUES ($idQuiz, '$pin', 'esperando', NOW())
  ");

  if (!$ok) {
    echo json_encode(["ok"=>false, "mensaje"=>"Error BD", "error"=>$conn->error]);
    exit;
  }

  $_SESSION["id_partida"] = (int)$conn->insert_id;
  $_SESSION["id_quiz"] = $idQuiz;

  echo json_encode(["ok"=>true, "pin"=>$pin]);
  exit;
}

/* =====================
   GET → MOSTRAR VISTA
===================== */
$idProfesor = (int)$usuario["id_usu"];
$quizzes = $conn->query("
  SELECT id_quiz, nombre_quiz
  FROM quiz
  WHERE id_profesor = $idProfesor
  ORDER BY nombre_quiz ASC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Juego</title>
  <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../componentes/partes/nav.php"; ?>

<section class="container my-3">
  <h1 class="dashboard-title">Iniciar Juego </h1>
          <p class="text-secondary mb-4">Iniciar juego | <a href="../views/dashboard.php">Regresar al inicio</a></p>



  <div class="card shadow-sm">
    <div class="card-body">

      <form id="formIniciarPartida">
        <label class="form-label fw-bold">Selecciona el quizz:</label>
        <select id="id_quiz" class="form-select mb-3" required>
          <option value="">-- Seleccionar --</option>
          <?php while ($q = $quizzes->fetch_assoc()): ?>
            <option value="<?= (int)$q["id_quiz"] ?>">
              <?= htmlspecialchars($q["nombre_quiz"]) ?>
            </option>
          <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-success">Iniciar Juego</button>
      </form>

      <div id="resultadoPin" class="alert alert-success text-center mt-4 d-none">
        <h5>PIN DE LA PARTIDA</h5>
        <h1 id="pinTexto"></h1>
      </div>

    </div>
  </div>
</section>

<script src="../assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
<script src="../assets/js/notify/notify.min.js"></script>
<script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>


<script>
$("#formIniciarPartida").on("submit", function(e){
  e.preventDefault();

  const idQuiz = $("#id_quiz").val();
  if(!idQuiz){
    $.notify("Seleccione un quizz", "warn");
    return;
  }

  $.ajax({
    url: "iniciarPartida.php",
    type: "POST",
    dataType: "json",
    data: { id_quiz: idQuiz },
    success: function(res){
      if(res.ok){
        $("#pinTexto").text(res.pin);
        $("#resultadoPin").removeClass("d-none");
        $.notify("Partida iniciada ✔", "success");
      }else{
        $.notify(res.mensaje, "error");
      }
    },
    error: function(xhr){
      console.log(xhr.responseText);
      $.notify("Error del servidor ❌", "error");
    }
  });
});
</script>

</body>
</html>
