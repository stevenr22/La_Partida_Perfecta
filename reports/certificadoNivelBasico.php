<?php
require('../assets/fpdf/fpdf.php');

/* ===============================================
   CLASE PDF CON POLYGON
=============================================== */
class PDF extends FPDF {

    function Polygon($points, $style = 'F') {
        $h = $this->h;
        $k = $this->k;

        $op = ($style == 'F') ? 'f' :
              (($style == 'FD' || $style == 'DF') ? 'b' : 's');

        $this->_out(sprintf(
            '%.2F %.2F m',
            $points[0] * $k,
            ($h - $points[1]) * $k
        ));

        for ($i = 2; $i < count($points); $i += 2) {
            $this->_out(sprintf(
                '%.2F %.2F l',
                $points[$i] * $k,
                ($h - $points[$i + 1]) * $k
            ));
        }

        $this->_out($op);
    }
}

/* ===============================================
   DATOS
=============================================== */
$nombre = "ISABEL MERCADO";
$curso  = "CONTABILIDAD FINANCIERA";
$nivel  = "NIVEL BÁSICO";
$fecha  = "15 de Diciembre de 2025";

/* ===============================================
   PDF
=============================================== */
$pdf = new PDF('L','mm','A4');
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

/* ===============================================
   COLORES
=============================================== */
$negro   = [25,25,25];
$gris    = [130,130,130];
$dorado  = [212,175,55];
$azul    = [32,58,92];

/* ===============================================
   MARCO FINO
=============================================== */
$pdf->SetLineWidth(0.8);
$pdf->SetDrawColor(180,180,180);
$pdf->Rect(10,10,277,190);

/* ===============================================
   FONDO DECORATIVO DERECHO
=============================================== */
$pdf->SetFillColor(...$azul);
$pdf->Polygon([220,10, 287,10, 287,190, 250,190]);

$pdf->SetFillColor(...$dorado);
$pdf->Polygon([245,10, 265,10, 287,90, 267,90]);
$pdf->Polygon([245,110, 265,110, 287,190, 267,190]);

/* ===============================================
   MEDALLA
=============================================== */
$pdf->Image('../assets/img/medalla.png', 240, 30, 38);

/* ===============================================
   TITULO
=============================================== */
$pdf->SetFont('Arial','B',34);
$pdf->SetTextColor(...$negro);
$pdf->SetXY(25,35);
$pdf->Cell(0,15, utf8_decode('CERTIFICADO'),0,1);

$pdf->SetFont('Arial','',20);
$pdf->SetX(25);
$pdf->Cell(0,12, utf8_decode('DE APROBACIÓN'),0,1);

/* ===============================================
   TEXTO OTORGADO
=============================================== */
$pdf->Ln(8);
$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(...$gris);
$pdf->SetX(25);
$pdf->Cell(0,8, utf8_decode('Otorgado a'),0,1);

/* ===============================================
   NOMBRE DESTACADO
=============================================== */
$pdf->SetFont('Times','I',30);
$pdf->SetTextColor(0,0,0);
$pdf->SetX(25);
$pdf->Cell(0,15, utf8_decode($nombre),0,1);

/* Línea elegante */
$pdf->SetDrawColor(...$dorado);
$pdf->SetLineWidth(1);
$pdf->Line(25,95,185,95);

/* ===============================================
   TEXTO PRINCIPAL
=============================================== */
$pdf->Ln(8);
$pdf->SetFont('Arial','',14);
$pdf->SetTextColor(40,40,40);
$pdf->SetX(25);
$pdf->MultiCell(
    160,
    9,
    utf8_decode(
        "Por haber completado satisfactoriamente el curso académico\n"
        ."$nivel de $curso."
    )
);

/* ===============================================
   FECHA
=============================================== */
$pdf->Ln(6);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(0,0,0);
$pdf->SetX(25);
$pdf->Cell(0,10, utf8_decode($fecha),0,1);

/* ===============================================
   TEXTO FINAL
=============================================== */
$pdf->Ln(10);
$pdf->SetFont('Arial','I',12);
$pdf->SetTextColor(...$gris);
$pdf->SetX(25);
$pdf->MultiCell(
    160,
    8,
    utf8_decode(
        "Este certificado acredita los conocimientos adquiridos y\n"
        ."habilita para continuar con el siguiente nivel académico."
    )
);

/* ===============================================
   FIRMA
=============================================== */
$pdf->SetTextColor(0,0,0);
$pdf->SetY(165);
$pdf->SetX(25);
$pdf->Cell(80,8,'______________________________',0,1);
$pdf->SetX(25);
$pdf->SetFont('Arial','',11);
$pdf->Cell(80,6, utf8_decode('Instructor'),0,1);

/* ===============================================
   SALIDA
=============================================== */
$pdf->Output('I','Certificado_Nivel_Basico.pdf');
