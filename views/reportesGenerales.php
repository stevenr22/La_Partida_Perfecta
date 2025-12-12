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

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">

    <!-- 🔥 BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap-icons-1.13.1/bootstrap-icons.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="../assets/css/datatable/datatables.min.css">

    <link rel="stylesheet" href="../assets/css/style.css">
    <title>QUIZZPRINT | Reportes</title>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <?php include "../componentes/partes/nav.php"; ?>

    <!-- ================= CONTENIDO ================= -->
    <section class="container my-3">

        <h1 class="dashboard-title mb-2">Reportes Generales</h1>
        <p class="text-secondary mb-4">
            Visualizar y descargar en formato pdf los reportes de datos de los resultados de los estudiantes del quizz. |
            <a href="../views/dashboard.php">Regresar al inicio</a>
        </p>

       <!-- ===== TABLA DE RESULTADOS ===== -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Resultados de los estudiantes</strong>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tablaResultados" class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>Nivel</th>
                                <th>Puntaje</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>Juan Pérez</td>
                                <td>Estudiante Universitario</td>
                                <td>85 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-03</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Laura Rojas</td>
                                <td>Profesional / Maestro</td>
                                <td>62 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-03</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Kevin Morales</td>
                                <td>Estudiante</td>
                                <td>90 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-02</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <!-- === 30 NUEVOS REGISTROS === -->

                            <tr>
                                <td>4</td>
                                <td>María López</td>
                                <td>Estudiante</td>
                                <td>78 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-04</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>5</td>
                                <td>Carlos Jiménez</td>
                                <td>Estudiante Universitario</td>
                                <td>55 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-04</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td>Sofía Martínez</td>
                                <td>Profesional / Maestro</td>
                                <td>92 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-05</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>7</td>
                                <td>Andrés Castillo</td>
                                <td>Estudiante</td>
                                <td>47 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-06</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>Daniela Cedeño</td>
                                <td>Estudiante Universitario</td>
                                <td>81 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-06</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>9</td>
                                <td>Miguel Torres</td>
                                <td>Profesional / Maestro</td>
                                <td>60 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-07</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>10</td>
                                <td>Valentina Ruiz</td>
                                <td>Estudiante</td>
                                <td>96 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-07</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>11</td>
                                <td>Fernando Aguilar</td>
                                <td>Estudiante Universitario</td>
                                <td>73 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-08</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>12</td>
                                <td>Paola Vaca</td>
                                <td>Profesional / Maestro</td>
                                <td>58 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-08</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>13</td>
                                <td>David Zamora</td>
                                <td>Estudiante</td>
                                <td>88 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-09</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>14</td>
                                <td>Nicole Herrera</td>
                                <td>Estudiante Universitario</td>
                                <td>91 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-09</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>15</td>
                                <td>Ricardo López</td>
                                <td>Profesional / Maestro</td>
                                <td>66 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-10</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>16</td>
                                <td>Mariana Flores</td>
                                <td>Estudiante</td>
                                <td>79 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-10</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>17</td>
                                <td>José García</td>
                                <td>Estudiante Universitario</td>
                                <td>52 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-11</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>18</td>
                                <td>Ana Villalba</td>
                                <td>Profesional / Maestro</td>
                                <td>99 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-12</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>19</td>
                                <td>Jorge Cárdenas</td>
                                <td>Estudiante</td>
                                <td>50 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-12</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>20</td>
                                <td>Lucía Molina</td>
                                <td>Estudiante Universitario</td>
                                <td>89 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-13</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>21</td>
                                <td>Gabriel Santos</td>
                                <td>Profesional / Maestro</td>
                                <td>71 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-13</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>22</td>
                                <td>Elena Ponce</td>
                                <td>Estudiante</td>
                                <td>83 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-14</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>23</td>
                                <td>Luis Robles</td>
                                <td>Estudiante Universitario</td>
                                <td>49 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-14</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>24</td>
                                <td>Carolina Díaz</td>
                                <td>Profesional / Maestro</td>
                                <td>94 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-15</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>25</td>
                                <td>Santiago Paredes</td>
                                <td>Estudiante</td>
                                <td>72 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-15</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>26</td>
                                <td>Daniel Rivas</td>
                                <td>Estudiante Universitario</td>
                                <td>57 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-16</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>27</td>
                                <td>Andrea Zambrano</td>
                                <td>Profesional / Maestro</td>
                                <td>85 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-16</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>28</td>
                                <td>Esteban Castillo</td>
                                <td>Estudiante</td>
                                <td>67 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-17</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>29</td>
                                <td>Alexandra Torres</td>
                                <td>Estudiante Universitario</td>
                                <td>44 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-18</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>30</td>
                                <td>Henry Vargas</td>
                                <td>Profesional / Maestro</td>
                                <td>98 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-18</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>31</td>
                                <td>Camila León</td>
                                <td>Estudiante</td>
                                <td>76 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-19</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>32</td>
                                <td>Oliver Sánchez</td>
                                <td>Estudiante Universitario</td>
                                <td>63 / 100</td>
                                <td><span class="badge bg-danger">Reprobado</span></td>
                                <td>2025-01-19</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                            <tr>
                                <td>33</td>
                                <td>Diana Méndez</td>
                                <td>Profesional / Maestro</td>
                                <td>87 / 100</td>
                                <td><span class="badge bg-success">Aprobado</span></td>
                                <td>2025-01-20</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> Ver</a></td>
                            </tr>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        

    </section>

    <!-- Bootstrap -->
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
     <!-- DataTables -->
    <script src="../assets/js/datatable/datatables.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Guardar instancia de datatable
            let tabla = new DataTable("#tablaResultados", {
                responsive: true,
                pageLength: 5,
                language: {
                    url: "../assets/js/datatable/es-ES.json"
                },
                layout: {
                    topStart: {
                        buttons: [
                            'pdf'
                        ]
                    }
                }
                
            });

          
        });
    </script>

  


 

</body>

</html>