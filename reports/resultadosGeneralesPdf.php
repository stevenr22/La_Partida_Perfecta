<?php
require_once "../componentes/variables_globales.php";

if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../auth/login.php");
  exit;
}

$ses = obtenerUsuarioSesion();
$idRol = (int)($ses["id_rol"] ?? 0);

// Solo maestro
if ($idRol !== 3) {
  die("Acceso denegado.");
}

require "../db/conexion.php";
require "../assets/fpdf/fpdf.php";

function safeInt($v){ return (int)($v ?? 0); }
function txt($v){ return trim((string)($v ?? "")); }

// Filtro por rol (texto)
$rolFiltro = trim($_GET["rol"] ?? "");

// Roles incluidos
$rolesReporte = "1,2,3";

/* =========================
   CONSULTA BASE
========================= */
$sql = "
SELECT
  u.id_usu,
  CONCAT(u.nombre_usu,' ',u.apellido_usu) AS usuario,
  r.nombre_rol,
  d.orden,
  d.nombre_dificultad AS curso,
  rp.correctas,
  rp.total
FROM usuario u
JOIN rol r ON r.id_rol = u.id_rol
JOIN dificultad d ON d.id_rol = u.id_rol
LEFT JOIN resultado_partida rp
  ON rp.id_usu = u.id_usu
 AND rp.id_dificultad = d.id_dificultad
 AND rp.fecha = (
    SELECT MAX(x.fecha)
    FROM resultado_partida x
    WHERE x.id_usu = u.id_usu
      AND x.id_dificultad = d.id_dificultad
 )
WHERE u.id_rol IN ($rolesReporte)
ORDER BY u.apellido_usu, u.nombre_usu, d.orden
";

$data = [];
$q = $conn->query($sql);
if ($q) while ($r = $q->fetch_assoc()) {

  if ($rolFiltro !== "" && trim($r["nombre_rol"]) !== $rolFiltro) continue;

  $id = (int)$r["id_usu"];

  if (!isset($data[$id])) {
    $data[$id] = [
      "usuario" => $r["usuario"],
      "cursos"  => [
        1 => ["nombre" => "--", "puntaje" => "--", "ok" => false],
        2 => ["nombre" => "--", "puntaje" => "--", "ok" => false],
        3 => ["nombre" => "--", "puntaje" => "--", "ok" => false],
      ]
    ];
  }

  $orden = (int)$r["orden"];
  if ($orden >= 1 && $orden <= 3) {
    $c = safeInt($r["correctas"]);
    $t = safeInt($r["total"]);
    $ok = ($t > 0 && $c >= ceil($t * 0.7)); // regla de aprobado

    $data[$id]["cursos"][$orden] = [
      "nombre"  => txt($r["curso"]),
      "puntaje" => ($t > 0 ? "$c/$t" : "--"),
      "ok"      => $ok
    ];
  }
}

/* =========================
   PDF PREMIUM
========================= */
class PDF extends FPDF {

  function Header(){
    // Franja superior
    $this->SetFillColor(32,58,92);
    $this->Rect(0,0,297,14,"F");

    // Línea dorada
    $this->SetFillColor(212,175,55);
    $this->Rect(0,14,297,3,"F");

    // Título
    $this->SetFont("Arial","B",15);
    $this->SetTextColor(255,255,255);
    $this->SetY(4);
    $this->Cell(0,6, utf8_decode("REPORTE GENERAL DE CURSOS"), 0, 1, "C");

    $this->Ln(14);
  }

  function Footer(){
    $this->SetY(-12);
    $this->SetFont("Arial","I",8);
    $this->SetTextColor(120,120,120);
    $this->Cell(0,10, utf8_decode("Página ".$this->PageNo()), 0, 0, "C");
  }
}

$pdf = new PDF("L","mm","A4");
$pdf->SetMargins(10,22,10);
$pdf->SetAutoPageBreak(true, 18);
$pdf->AddPage();

/* =========================
   TABLA CENTRADA
========================= */

// Anchos de columnas
$w = [
  "nombre" => 46,
  "c1" => 42, "p1" => 18,
  "c2" => 42, "p2" => 18,
  "c3" => 42, "p3" => 18
];

$totalWidth = array_sum($w);
$startX = (297 - $totalWidth) / 2;
$pdf->SetX($startX);

// Cabecera
$pdf->SetFont("Arial","B",10);
$pdf->SetFillColor(0,0,0);
$pdf->SetTextColor(255,255,255);

$pdf->Cell($w["nombre"],10,"NOMBRE",1,0,"C",true);
$pdf->Cell($w["c1"],10,"CURSO 1",1,0,"C",true);
$pdf->Cell($w["p1"],10,"PUNTAJE",1,0,"C",true);
$pdf->Cell($w["c2"],10,"CURSO 2",1,0,"C",true);
$pdf->Cell($w["p2"],10,"PUNTAJE",1,0,"C",true);
$pdf->Cell($w["c3"],10,"CURSO 3",1,0,"C",true);
$pdf->Cell($w["p3"],10,"PUNTAJE",1,1,"C",true);

// Filas
$pdf->SetFont("Arial","",9);
$fill = false;

foreach ($data as $row) {

  $pdf->SetX($startX);
  $pdf->SetFillColor($fill ? 245 : 255, $fill ? 245 : 255, $fill ? 245 : 255);
  $fill = !$fill;

  $pdf->SetTextColor(0,0,0);
  $pdf->Cell($w["nombre"],9, utf8_decode($row["usuario"]),1,0,"L",true);

  for ($i=1; $i<=3; $i++) {

    $pdf->Cell($w["c".$i],9, utf8_decode($row["cursos"][$i]["nombre"]),1,0,"L",true);

    // Color puntaje
    if ($row["cursos"][$i]["puntaje"] === "--") {
      $pdf->SetTextColor(150,150,150);
    } elseif ($row["cursos"][$i]["ok"]) {
      $pdf->SetTextColor(25,135,84);
    } else {
      $pdf->SetTextColor(220,53,69);
    }

    $pdf->Cell($w["p".$i],9, $row["cursos"][$i]["puntaje"],1,0,"C",true);
    $pdf->SetTextColor(0,0,0);
  }

  $pdf->Ln();
}

$pdf->Output("I", "Reporte_General_Cursos_Premium.pdf");
exit;
