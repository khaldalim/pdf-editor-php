<?php 

$uid = $_GET["f"];

define ('SQL_HOSTNAME','localhost');
define ('SQL_DATABASE','pdf-editor-php');
define ('SQL_USERNAME','root');
define ('SQL_PASSWORD','');


try {
	$dbh = new PDO('mysql:host='.SQL_HOSTNAME.';dbname='.SQL_DATABASE, SQL_USERNAME, SQL_PASSWORD);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}

$req = $dbh->prepare("SELECT data FROM `data-php-editor` WHERE `uid` LIKE '".$uid."'");
$req->execute();
$result = $req->fetch(PDO::FETCH_ASSOC);
// var_dump($result['data']);


$dataPDF = json_decode($result['data']);
// echo "<pre>";
// print_r($dataPDF);
// echo "</pre>";

//$dataPDF = json_decode($result['data'], true);

// echo'<pre>'; print_r($dataPDF); echo "</pre>";
// var_dump($dataPDF);

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;

require_once('fpdf/fpdf.php');
require_once('fpdi/src/autoload.php');

$pdf = new Fpdi();
// $pdf = new FPDF( 'P', 'mm', 'A4');*

$pageCount = $pdf->setSourceFile('lorem-ipsum.pdf');

// var_dump($pageCount);
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
	$tplIdx = $pdf->importPage($pageNo);


$pdf->AddPage();
$pdf->useTemplate($tplIdx,0, 0);
}


$pdf->AddPage();
$pdf->SetFont('Arial','B',16);



if(isset($dataPDF[0]->data[0])){
	if(isset($dataPDF[0]->data[0]->image)){
		$pdf->Image( $dataPDF[0]->data[0]->image ,10,10,60);
	}
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[0]->titre)){
		$pdf->Cell(10,10,$dataPDF[0]->data[0]->titre);
	}
	$pdf->Ln(10);
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[0]->description)){
		$pdf->MultiCell(100,8,$dataPDF[0]->data[0]->description,'0', 'L');
	}
}





if(isset($dataPDF[0]->data[1])){
	if(isset($dataPDF[0]->data[1]->image)){
		$pdf->Image($dataPDF[0]->data[1]->image,10,70,60);
	}
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[1]->titre)){
		$pdf->Cell(10,100,$dataPDF[0]->data[1]->titre);
	}
	$pdf->Ln(55);
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[1]->description)){
		$pdf->MultiCell(100,8,$dataPDF[0]->data[1]->description,'0', 'L');
	}
}







if(isset($dataPDF[0]->data[2])){
	if(isset($dataPDF[0]->data[2]->image)){
		$pdf->Image($dataPDF[0]->data[2]->image,10,130,60);
	}
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[2]->titre)){
		$pdf->Cell(10,100,$dataPDF[0]->data[2]->titre);
	}
	$pdf->Ln(55);
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[2]->description)){
		$pdf->MultiCell(100,8,$dataPDF[0]->data[2]->description,'0', 'L');
	}
}









if(isset($dataPDF[0]->data[3])){
	if(isset($dataPDF[0]->data[3]->image)){
		$pdf->Image($dataPDF[0]->data[3]->image,10,190,60);
	}
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[3]->titre)){
		$pdf->Cell(10,100,$dataPDF[0]->data[3]->titre);
	}
	$pdf->Ln(55);
	$pdf->Cell(70);
	if(isset($dataPDF[0]->data[3]->description)){
		$pdf->MultiCell(100,8,$dataPDF[0]->data[3]->description ,'0', 'L');
	}
}

$date = date('Y-m');

$pdf->Output('upload/'.$date.'/'.$uid.'/'.'plaquette-'.$uid.'.pdf','F');
$pdf->Output();