<?php
session_start();
if(!isset($_POST['submit'])){

    Header('location: index.php');
    exit();

}
require('fpdf/fpdf.php');
require_once 'includes/connect-DB.inc.php';
require_once 'includes/functions.inc.php';

$bilet_id = $_GET['bilet_id'];
$title = 'Bilet '.$bilet_id;
$infoBilet = infoBilet($conn, $bilet_id);
$user_id = $infoBilet['user_id'];

class PDF extends FPDF{
    function Header(){
        global $title;

        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Calculate width of title and position
        $w = $this->GetStringWidth($title)+6;
        $this->SetX((210-$w)/2);
        // Title
        $this->Cell($w,9,$title,0,1,'C');
        // Line break
        $this->Ln(10);
    }
}
$pdf = new PDF();
$pdf->AddPage('L', 'A5');
$pdf->SetTitle($title);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Utilizator ID: '.$user_id, 'B', 1);
$pdf->MultiCell(0, 10,'Film: "'.iconv('UTF-8', 'windows-1252', $infoBilet['titlu_film']).'"', 'B', 1);
$pdf->Cell(0,10,'Cinema: '.iconv('UTF-8', 'windows-1252', $infoBilet['nume_cinema']), 'B', 1);
$pdf->Cell(0,10,'Sala: '.iconv('UTF-8', 'windows-1252', $infoBilet['nume_sala']), 'B', 1);
$pdf->Cell(0,10,'Loc: '.$infoBilet['loc_id'], 'B', 1);
$pdf->Cell(0,10,'Tip: '.iconv('UTF-8', 'windows-1252', $infoBilet['tip_bilet']), 'B', 1);
$pdf->Cell(0,10,'Pret: '.$infoBilet['pret'].' lei', 'B', 1);
$pdf->Cell(0,10,'Data: '.$infoBilet['data_start'].' - '.$infoBilet['data_end'], 0, 1);
$pdf->Output('D', 'bilet'.$bilet_id.'.pdf');
?>