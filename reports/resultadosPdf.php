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
];

// ================= PDF =================
$pdf = new FPDF('L', 'mm', 'A4');
$pdf->AddPage();

// ================= ENCABEZADO =================
$pdf->SetFillColor(33, 150, 243); // Azul elegante
$pdf->Rect(0, 0, 297, 30, 'F');

$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetY(10);
$pdf->Cell(0, 10, utf8_decode('REPORTE GENERAL DE RESULTADOS'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, utf8_decode('Sistema "La Partida Perfecta"'), 0, 1, 'C');

$pdf->Ln(10);

// ================= FECHA =================
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 8, 'Fecha de generación: ' . date("Y-m-d H:i"), 0, 1, 'R');
$pdf->Ln(3);

// ================= ENCABEZADO TABLA =================
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(52, 58, 64);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(75, 10, 'Estudiante', 1, 0, 'C', true);
$pdf->Cell(75, 10, 'Nivel', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Puntaje', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Estado', 1, 0, 'C', true);
$pdf->Cell(35, 10, 'Fecha', 1, 1, 'C', true);

// ================= CUERPO TABLA =================
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

$fill = false;

foreach ($resultados as $fila) {

    // Filas alternadas
    $pdf->SetFillColor($fill ? 245 : 255, $fill ? 247 : 255, $fill ? 249 : 255);

    // Color según estado
    if ($fila[3] === "Aprobado") {
        $pdf->SetTextColor(25, 135, 84); // Verde
    } else {
        $pdf->SetTextColor(220, 53, 69); // Rojo
    }

    $pdf->Cell(75, 8, utf8_decode($fila[0]), 1, 0, 'L', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(75, 8, utf8_decode($fila[1]), 1, 0, 'L', true);

    $pdf->Cell(35, 8, $fila[2], 1, 0, 'C', true);

    // Estado con color
    if ($fila[3] === "Aprobado") {
        $pdf->SetTextColor(25, 135, 84);
    } else {
        $pdf->SetTextColor(220, 53, 69);
    }
    $pdf->Cell(35, 8, $fila[3], 1, 0, 'C', true);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(35, 8, $fila[4], 1, 1, 'C', true);

    $fill = !$fill;
}

// ================= PIE =================
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 8, utf8_decode('Documento generado automáticamente • No requiere firma'), 0, 1, 'C');

$pdf->Output('I', 'reporte_resultados_quizzprint.pdf');
