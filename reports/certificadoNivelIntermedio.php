<?php
require_once "../componentes/variables_globales.php";
require "../db/conexion.php";
require "../assets/fpdf/fpdf.php";

if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../auth/login.php");
  exit;
}

// Sesión (permisos)
$ses = obtenerUsuarioSesion();
$idUsuSesion = (int)($ses["id_usu"] ?? 0);
$idRolSesion = (int)($ses["id_rol"] ?? 0);

// Params
$idDificultad = (int)($_GET["id_dificultad"] ?? 0);
if ($idDificultad <= 0) die("Parámetros inválidos.");

// Usuario objetivo
$idUsuTarget = (int)($_GET["id_usu"] ?? 0);
if ($idRolSesion !== 3) $idUsuTarget = $idUsuSesion;
if ($idRolSesion === 3 && $idUsuTarget <= 0) $idUsuTarget = $idUsuSesion;
if ($idUsuTarget <= 0) die("Usuario inválido");

// Datos usuario objetivo
$uq = $conn->query("SELECT nombre_usu, apellido_usu FROM usuario WHERE id_usu=$idUsuTarget LIMIT 1");
if (!$uq || $uq->num_rows === 0) die("Usuario no existe");
$u = $uq->fetch_assoc();

$nombre = trim(($u["nombre_usu"] ?? "") . " " . ($u["apellido_usu"] ?? ""));
if ($nombre === "") $nombre = "USUARIO";

// Último aprobado del usuario objetivo
$sql = "
SELECT
  rp.correctas,
  rp.total,
  rp.fecha,
  d.nombre_dificultad,
  d.orden
FROM resultado_partida rp
INNER JOIN dificultad d ON d.id_dificultad = rp.id_dificultad
WHERE rp.id_usu = $idUsuTarget
  AND rp.id_dificultad = $idDificultad
  AND rp.aprobado = 1
ORDER BY rp.fecha DESC
LIMIT 1
";
$r = $conn->query($sql);
if (!$r || $r->num_rows === 0) die("No existe un intento aprobado para este curso.");

$data = $r->fetch_assoc();

$cursoNombre = $data["nombre_dificultad"] ?? "CURSO";
$correctas   = (int)($data["correctas"] ?? 0);
$total       = (int)($data["total"] ?? 0);
$fechaDB     = $data["fecha"] ?? date("Y-m-d H:i:s");
$fecha = date("d/m/Y", strtotime($fechaDB));

$nivel = "NIVEL INTERMEDIO"; // fijo

class PDF extends FPDF {
  function Polygon($points, $style = 'F') {
    $h = $this->h; $k = $this->k;
    $op = ($style == 'F') ? 'f' : (($style == 'FD' || $style == 'DF') ? 'b' : 's');
    $this->_out(sprintf('%.2F %.2F m', $points[0]*$k, ($h-$points[1])*$k));
    for ($i=2; $i<count($points); $i+=2) {
      $this->_out(sprintf('%.2F %.2F l', $points[$i]*$k, ($h-$points[$i+1])*$k));
    }
    $this->_out($op);
  }
}

$pdf = new PDF('L','mm','A4');
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

$negro=[25,25,25]; $gris=[130,130,130]; $dorado=[212,175,55]; $azul=[32,58,92];

$pdf->SetLineWidth(0.8);
$pdf->SetDrawColor(180,180,180);
$pdf->Rect(10,10,277,190);

$pdf->SetFillColor(...$azul);
$pdf->Polygon([220,10, 287,10, 287,190, 250,190]);

$pdf->SetFillColor(...$dorado);
$pdf->Polygon([245,10, 265,10, 287,90, 267,90]);
$pdf->Polygon([245,110, 265,110, 287,190, 267,190]);

$medallaPath = "../assets/img/medalla.png";
if (file_exists($medallaPath)) $pdf->Image($medallaPath, 240, 30, 38);

$pdf->SetFont('Arial','B',34);
$pdf->SetTextColor(...$negro);
$pdf->SetXY(25,35);
$pdf->Cell(0,15, utf8_decode('CERTIFICADO'),0,1);

$pdf->SetFont('Arial','',20);
$pdf->SetX(25);
$pdf->Cell(0,12, utf8_decode('DE APROBACIÓN'),0,1);

$pdf->Ln(8);
$pdf->SetFont('Arial','',13);
$pdf->SetTextColor(...$gris);
$pdf->SetX(25);
$pdf->Cell(0,8, utf8_decode('Otorgado a'),0,1);

$pdf->SetFont('Times','I',30);
$pdf->SetTextColor(0,0,0);
$pdf->SetX(25);
$pdf->Cell(0,15, utf8_decode(strtoupper($nombre)),0,1);

$pdf->SetDrawColor(...$dorado);
$pdf->SetLineWidth(1);
$pdf->Line(25,95,185,95);

$pdf->Ln(8);
$pdf->SetFont('Arial','',14);
$pdf->SetTextColor(40,40,40);
$pdf->SetX(25);
$pdf->MultiCell(
  160,
  9,
  utf8_decode(
    "Por haber completado satisfactoriamente el curso académico\n"
    ."$nivel: $cursoNombre.\n"
    ."Puntaje obtenido: $correctas/$total."
  )
);

$pdf->Ln(6);
$pdf->SetFont('Arial','B',14);
$pdf->SetTextColor(0,0,0);
$pdf->SetX(25);
$pdf->Cell(0,10, utf8_decode("Fecha: $fecha"),0,1);

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

$pdf->SetTextColor(0,0,0);
$pdf->SetY(165);
$pdf->SetX(25);
$pdf->Cell(80,8,'______________________________',0,1);
$pdf->SetX(25);
$pdf->SetFont('Arial','',11);
$pdf->Cell(80,6, utf8_decode('Instructor'),0,1);

$pdf->Output('I', "Certificado_Intermedio_{$idDificultad}_Usuario_{$idUsuTarget}.pdf");
exit;
