<?php 
define ('SQL_HOSTNAME','localhost');
define ('SQL_DATABASE','pdf-editor-php');
define ('SQL_USERNAME','root');
define ('SQL_PASSWORD',''); 

$UIDPost = $_POST["UID"];
$numContainer = $_POST["numContainer"];
$baseFromJavascript = $_POST["imageRef"];
$title = $_POST["titleRef"];
$description = $_POST["descRef"];

$IDContainer = $_POST["IDContainer"];
$statut = $_POST["statut"];
// 55 = edit
// 22 = creation

if ($UIDPost == 0){
	$UID = uniqid();
}else{
	$UID = $UIDPost;
}

$ipaddress = '';
if (isset($_SERVER['HTTP_CLIENT_IP']))
	$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_X_FORWARDED']))
	$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
	$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
	$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
else if(isset($_SERVER['HTTP_FORWARDED']))
	$ipaddress = $_SERVER['HTTP_FORWARDED'];
else if(isset($_SERVER['REMOTE_ADDR']))
	$ipaddress = $_SERVER['REMOTE_ADDR'];
else
	$ipaddress = 'UNKNOWN';




try {
	$dbh = new PDO('mysql:host='.SQL_HOSTNAME.';dbname='.SQL_DATABASE, SQL_USERNAME, SQL_PASSWORD);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}






// PASSE DE BASE 64 en image + Création dossiers
preg_match("/^data:image\/(.*);base64/i",$baseFromJavascript, $match);
$extension = $match[1];
// We need to remove the "data:image/png;base64,"
$base_to_php = explode(',', $baseFromJavascript);
// the 2nd item in the base_to_php array contains the content of the image
$data = base64_decode($base_to_php[1]);
// here you can detect if type is png or jpg if you want
$date = date('Y-m');
$dossier = "../upload/". $date;
if(is_dir($dossier)){
	// echo "Le dossier existe";
} else{
	mkdir($dossier);
}
// Création du sous dossier 
$dossierSousDos = "../upload/". $date ."/".$UID;
if(is_dir($dossierSousDos)){
	// echo "Le dossier existe";
} else{
	mkdir($dossierSousDos);
}
$filepath = $dossierSousDos ."/image-".$numContainer.".". $extension;
// Save the image in a defined path
file_put_contents($filepath,$data);
//TODO remplacer le nom de l'image par uid_0/1/2/3.jpg
$url      = "http://" . $_SERVER['HTTP_HOST'] .'/upload/'. $date ."/".$UID . "/image-".$numContainer.".". $extension;
// $validURL = str_replace("&", "&amp", $url); 






if($numContainer == 0){
	$strData = '{"image":"'.$url.'","titre":"'.$title.'","description":"'.$description .'"}';
	$finalData = '[{"data":['. $strData .']}]';


	$req = $dbh->prepare("INSERT INTO `data-php-editor` (`uid`, `data`, `ip`) VALUES ('" . $UID ."' , '" . $finalData  ."', '" . $ipaddress."' );");
	$req->execute();
}


else{
	$dataForm = json_decode('{"image":"'.$url.'","titre":"'.$title.'","description":"'.$description .'"}');
	$req = $dbh->prepare("SELECT `data` FROM `data-php-editor` WHERE `uid` LIKE '".$UID."'");
	$req->execute();
	$result = $req->fetch(PDO::FETCH_ASSOC);
	$data =  json_decode($result['data']);
	$data[0]->data[$numContainer] = $dataForm;
	// var_dump($data[0]->data[0]);
	// var_dump($data[0]->data[1]);
	// var_dump($data[0]->data[2]);
	// var_dump($data[0]->data[3]);
	$finalData = json_encode(json_encode($data));
	
	$reqModify = $dbh->prepare("UPDATE `data-php-editor` SET `data`= $finalData WHERE `data-php-editor`.`uid` = '$UID';");
	$reqModify->execute();

}



$data = array('url'=>$url , 'uid'=>$UID);
// var_dump($data);
echo json_encode($data);