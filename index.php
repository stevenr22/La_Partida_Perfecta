<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

    <title>La Partida Perfecta | Inicio</title>

    <style>
        /* HERO */
        .hero {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: #fff;
            border-radius: 20px;
        }
        .hero-gif {
            max-height: 200px;
            width: 50%;
            object-fit: contain;
        }



        /* TARJETAS */
        .feature-card {
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, .15);
        }

        /* GIF */
        .gif-icon {
            max-height: 120px;
        }

        /* MINI TEST */
        .test-btn:hover {
            transform: scale(1.05);
        }

        .notifyjs-corner {
            z-index: 999999 !important;
        }
        html {
            scroll-behavior: smooth;
        }

        @media (max-width: 768px) {
            .hero-gif {
                max-height: 350px;
            }
        }

    </style>
</head>

<body class="bg-light">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" id="top">
        <div class="container">

            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php">
                <i class="bi bi-journal-check text-primary fs-4"></i>
                La Partida Perfecta
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="index.php">Inicio</a>
                    </li>
                </ul>

                <a href="auth/login.php" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Iniciar sesión
                </a>
            </div>

        </div>
    </nav>

    <!-- HERO -->
    <section class="container my-5">
        <div class="hero p-5 shadow">
            <div class="row align-items-center g-4">

                <div class="col-md-6">
                    <h1 class="fw-bold display-6">
                        Aprende Contabilidad <br> Jugando y Practicando
                    </h1>

                    <p class="mt-3 lead">
                        Refuerza tus conocimientos contables mediante
                        quizzes interactivos, simulaciones reales y retos dinámicos.
                    </p>

                    <button class="btn btn-light fw-bold px-4 py-2 mt-3"
                        data-bs-toggle="modal"
                        
                        data-bs-target="#modalCedula">
                        <i class="bi bi-play-circle me-2"></i>
                        Comenzar ahora
                    </button>
                </div>

                <div class="col-md-6 text-center">
                    <img src="assets/img/gifs/isometric.gif" class="img-fluid hero-gif">
                </div>

            </div>
        </div>
    </section>

    <!-- CARACTERÍSTICAS -->
    <section class="container my-5">
        <h2 class="text-center fw-bold mb-4">
            <i class="bi bi-lightning-charge text-primary"></i>
            ¿Qué aprenderás?
        </h2>

        <div class="row g-4">

            <!-- CARD 1 -->
            <div class="col-md-4">
                <div class="card feature-card h-100 border-0 shadow-sm text-center p-3">
                    <img src="assets/img/gifs/accounting.gif" class="gif-icon mx-auto">
                    <h5 class="fw-bold mt-3">Asientos Contables</h5>
                    <p class="text-muted">
                        Aprende a registrar transacciones reales paso a paso.
                    </p>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="col-md-4">
                <div class="card feature-card h-100 border-0 shadow-sm text-center p-3">
                    <img src="assets/img/gifs/contract.gif" class="gif-icon mx-auto">
                    <h5 class="fw-bold mt-3">Estados Financieros</h5>
                    <p class="text-muted">
                        Analiza balances y resultados de forma visual.
                    </p>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="col-md-4">
                <div class="card feature-card h-100 border-0 shadow-sm text-center p-3">
                    <i class="bi bi-controller fs-1 text-success"></i>
                    <h5 class="fw-bold mt-3">Quizzes Dinámicos</h5>
                    <p class="text-muted">
                        Pon a prueba tus conocimientos con preguntas rápidas.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- MINI TEST -->
    <section class="container my-5">
        <h2 class="fw-bold text-center mb-4">
            <i class="bi bi-patch-question text-primary"></i>
            Prueba rápida
        </h2>

        <div class="card shadow border-0">
            <div class="card-body text-center">

                <p class="fw-bold fs-5">
                    Si compras un computador para la empresa, ¿qué tipo de cuenta es?
                </p>

                <div class="d-flex justify-content-center gap-3 mt-3 flex-wrap">
                    <button class="btn btn-outline-primary test-btn" data-answer="correcto">
                        Activo
                    </button>
                    <button class="btn btn-outline-primary test-btn" data-answer="incorrecto">
                        Pasivo
                    </button>
                    <button class="btn btn-outline-primary test-btn" data-answer="incorrecto">
                        Gasto
                    </button>
                </div>

                <div id="test-response" class="mt-3 fw-bold"></div>

            </div>
        </div>
    </section>

  
 
    <!-- CTA FINAL -->
    <section class="bg-primary text-white text-center py-5 mt-5">
        <h2 class="fw-bold">Aprende contabilidad de forma divertida</h2>
        <p>Diseñado para estudiantes y docentes</p>

        <a href="#top" class="btn btn-light fw-bold px-4 py-2 mt-3">
            <i class="bi bi-arrow-up-circle me-2"></i>
            Empezar ahora
        </a>
    </section>





    <!-- MODALES -->
    <?php include("componentes/modales.php"); ?>

    <!-- JS -->
    <script src="assets/js/ajaxjquery/jquery-3.7.1.min.js"></script>
    <script src="assets/js/notify/notify.min.js"></script>
    <script src="assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets/js/ajaxjquery/ajax.js"></script>


    <script>
        document.querySelectorAll('.test-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const resp = document.getElementById('test-response');
                if (btn.dataset.answer === "correcto") {
                    resp.innerHTML = "✅ Correcto. Es un ACTIVO.";
                    resp.className = "text-success";
                } else {
                    resp.innerHTML = "❌ Incorrecto. Intenta otra vez.";
                    resp.className = "text-danger";
                }
            });
            
        });
    </script>

</body>

</html>
