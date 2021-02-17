<?php
define ('SQL_HOSTNAME','localhost');
define ('SQL_DATABASE','pdf-editor-php');
define ('SQL_USERNAME','root');
define ('SQL_PASSWORD',''); 


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

$data = $_POST["JsonString"];


try {
	$dbh = new PDO('mysql:host='.SQL_HOSTNAME.';dbname='.SQL_DATABASE, SQL_USERNAME, SQL_PASSWORD);
} catch (PDOException $e) {
	print "Erreur !: " . $e->getMessage() . "<br/>";
	die();
}

// [{\"data\":[{\"image\":\"https://www.wikichat.fr/wp-content/uploads/sites/2/comment-soigner-une-plaie-dun-chat.jpg\",\"titre\":\"utyuyryr\",\"description\":\"yurtyurtu\"},{\"image\":\"https://www.wikichat.fr/wp-content/uploads/sites/2/comment-soigner-une-plaie-dun-chat.jpg\",\"titre\":\"jhgjgh\",\"description\":\"jhgjh\"},{\"image\":\"https://www.wikichat.fr/wp-content/uploads/sites/2/comment-soigner-une-plaie-dun-chat.jpg\",\"titre\":\"jhgj\",\"description\":\"jhgjg\"},{\"image\":\"https://www.wikichat.fr/wp-content/uploads/sites/2/comment-soigner-une-plaie-dun-chat.jpg\",\"titre\":\"jhgjg\",\"description\":\"jhgj\"}]}]

// var_dump($data);
// var_dump(json_encode($data));
//var_dump(json_encode($data));
//var_dump(json_encode($data));

$req = $dbh->prepare("INSERT INTO `data-php-editor` (`uid`, `data`, `ip`) VALUES ('" . $UID ."' , '" . $data ."', '" . $ipaddress."' );");
$req->execute();
$lastId = $dbh->lastInsertId(); 

$req = $dbh->prepare("SELECT `uid` FROM `data-php-editor` WHERE `id` LIKE '".$lastId."'");
$req->execute();
$result = $req->fetch(PDO::FETCH_ASSOC);

echo($result["uid"]);

// echo "INSERT INTO `data-php-editor` (`uid`, `data`, `ip`) VALUES ('" . $UID ."' , '" . $data ."', '" . $ipaddress."' );";


?>