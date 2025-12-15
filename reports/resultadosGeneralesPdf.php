<?php
require_once("../componentes/variables_globales.php");
require_once("../assets/fpdf/fpdf.php");

// ================= DATOS DE EJEMPLO =================
$resultados = [
    ["Juan Pérez", "Estudiante Universitario", "85 / 100", "Aprobado", "2025-01-03"],
    ["Laura Rojas", "Profesional / Maestro", "62 / 100", "Reprobado", "2025-01-03"],
    ["Kevin Morales", "Estudiante", "90 / 100", "Aprobado", "2025-01-02"],
    ["María López", "Estudiante", "78 / 100", "Aprobado", "2025-01-04"],
    ["Carlos Jiménez", "Estudiante Universitario", "55 / 100", "Reprobado", "2025-01-04"],
    ["Sofía Martínez", "Profesional / Maestro", "92 / 100", "Aprobado", "2025-01-05"],
    ["Andrés Castillo", "Estudiante", "47 / 100", "Reprobado", "2025-01-06"],
    ["Daniela Cedeño", "Estudiante Universitario", "81 / 100", "Aprobado", "2025-01-06"],
    ["Miguel Torres", "Profesional / Maestro", "60 / 100", "Reprobado", "2025-01-07"],
    ["Valentina Ruiz", "Estudiante", "96 / 100", "Aprobado", "2025-01-07"],
];

// ================= PDF =================
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// ================= ENCABEZADO =================
$pdf->SetFillColor(13, 110, 253); // Azul Bootstrap
$pdf->Rect(0, 0, 297, 28, 'F');

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetY(8);
$pdf->Cell(0, 8, utf8_decode('REPORTE GENERAL DE RESULTADOS'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, utf8_decode('Sistema QUIZZPRINT'), 0, 1, 'C');

$pdf->Ln(12);

// ================= FECHA =================
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'Fecha de generación: ' . date("Y-m-d H:i"), 0, 1, 'R');
$pdf->Ln(3);

// ================= ENCABEZADO TABLA =================
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(52, 58, 64);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(80, 10, 'Estudiante', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'Nivel', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Puntaje', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Estado', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Fecha', 1, 1, 'C', true);

// ================= CUERPO TABLA =================
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

$fill = false;

foreach ($resultados as $fila) {

    // Zebra
    $pdf->SetFillColor($fill ? 245 : 255, $fill ? 247 : 255, $fill ? 249 : 255);

    // Nombre
    $pdf->Cell(80, 8, utf8_decode($fila[0]), 1, 0, 'L', true);

    // Nivel
    $pdf->Cell(80, 8, utf8_decode($fila[1]), 1, 0, 'L', true);

    // Puntaje
    $pdf->Cell(35, 8, $fila[2], 1, 0, 'C', true);

    // Estado con color
    if ($fila[3] === "Aprobado") {
        $pdf->SetTextColor(25, 135, 84); // Verde
    } else {
        $pdf->SetTextColor(220, 53, 69); // Rojo
    }
    $pdf->Cell(35, 8, $fila[3], 1, 0, 'C', true);

    // Fecha
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(35, 8, $fila[4], 1, 1, 'C', true);

    $fill = !$fill;
}

// ================= RESUMEN =================
$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 8, utf8_decode('Resumen General'), 0, 1);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 8, 'Total registros:', 0, 0);
$pdf->Cell(20, 8, count($resultados), 0, 1);

// ================= PIE =================
$pdf->Ln(8);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(0, 8, utf8_decode('Documento generado automáticamente • La Partida Perfecta'), 0, 1, 'C');

$pdf->Output('I', 'reporte_general_La_partida_perfecta.pdf');
