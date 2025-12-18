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
        :root {
            --primary: #4f46e5;
            --secondary: #22c55e;
            --accent: #f59e0b;
            --dark: #0f172a;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: #f8fafc;
        }

        /* NAVBAR */
        .navbar-brand {
            letter-spacing: .5px;
        }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, var(--primary), #0ea5e9);
            color: #fff;
            border-radius: 25px;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            top: -80px;
            right: -80px;
            filter: blur(40px);
        }

        .hero h1 {
            animation: fadeUp 1s ease;
        }

        .hero p {
            animation: fadeUp 1.3s ease;
        }

        .hero-gif {
            max-height: 260px;
            width: 70%;
            object-fit: contain;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        /* BOTÓN CTA */
        .btn-cta {
            background: linear-gradient(135deg, #facc15, var(--accent));
            border: none;
            color: #1f2937;
            font-weight: bold;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .btn-cta:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 25px rgba(0, 0, 0, .25);
        }

        /* TARJETAS */
        .feature-card {
            border-radius: 20px;
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 18px 35px rgba(79, 70, 229, .35);
        }

        .gif-icon {
            max-height: 120px;
        }

        /* MINI TEST */
        .test-btn {
            font-weight: bold;
            transition: all .25s ease;
        }

        .test-btn:hover {
            background: var(--primary);
            color: #fff;
            transform: scale(1.08);
        }

        #test-response {
            font-size: 1.2rem;
            animation: pop .4s ease;
        }

        @keyframes pop {
            0% {
                transform: scale(.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* CTA FINAL */
        .final-cta {
            background: linear-gradient(135deg, #0ea5e9, var(--primary));
        }

        @media (max-width: 768px) {
            .hero-gif {
                max-height: 220px;
            }
        }

        .notifyjs-corner {
            z-index: 999999 !important;
        }
    </style>
</head>

<body>

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
                        Refuerza tus conocimientos contables mediante quizzes
                        interactivos, simulaciones reales y retos dinámicos.
                    </p>

                   <button class="btn btn-cta px-4 py-2 mt-3"
                    data-bs-toggle="modal" data-bs-target="#modalCedulaJuego">
                    <i class="bi bi-play-circle me-2"></i> Comenzar ahora
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

            <div class="col-md-4">
                <div class="card feature-card h-100 border-0 shadow text-center p-4"
                    style="background: linear-gradient(135deg,#eef2ff,#ffffff);">
                    <img src="assets/img/gifs/accounting.gif" class="gif-icon mx-auto">
                    <h5 class="fw-bold mt-3">Asientos Contables</h5>
                    <p class="text-muted">
                        Aprende a registrar transacciones reales paso a paso.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card h-100 border-0 shadow text-center p-4"
                    style="background: linear-gradient(135deg,#ecfeff,#ffffff);">
                    <img src="assets/img/gifs/contract.gif" class="gif-icon mx-auto">
                    <h5 class="fw-bold mt-3">Estados Financieros</h5>
                    <p class="text-muted">
                        Analiza balances y resultados de forma visual.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card h-100 border-0 shadow text-center p-4"
                    style="background: linear-gradient(135deg,#fef3c7,#ffffff);">
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
    <section class="final-cta text-white text-center py-5 mt-5">
        <h2 class="fw-bold">Aprende contabilidad de forma divertida</h2>
        <p>Diseñado para estudiantes y docentes</p>

        <a href="#top" class="btn btn-warning fw-bold px-4 py-2 mt-3 shadow">
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
