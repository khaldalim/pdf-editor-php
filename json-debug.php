<?php 

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

$req = $dbh->prepare("SELECT data FROM `data-php-editor` WHERE `uid` LIKE '5cc71748acde0'");
$req->execute();
$result = $req->fetch(PDO::FETCH_ASSOC);
// var_dump($result);


$dataPDF = json_decode(json_decode($result['data'], true));

echo'<pre>'; print_r($dataPDF); echo "</pre>";

?>