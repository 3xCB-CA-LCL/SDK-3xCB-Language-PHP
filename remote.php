<?php

echo "<h4>Script remote control</h4>";

$key = '';
$merchantreference = urlencode("");
$top3reference = "";
$final_amount = '';

//action possibles : validatetransaction ou canceltransaction ou partiallyvalidatetransaction ou cancelprecedentorder
$action = "";

if($key == '')
	die('Erreur : clé vide');

if($merchantreference == '')
	die('Erreur : merchantreference vide');

if($top3reference == '')
	die('Erreur : référence TOP 3 vide');

if($action == '')
	die('Erreur : action vide');

$string = "merchantreference=".urlencode($merchantreference)."&top3reference=".urlencode($top3reference);

if ($action == 'partiallyvalidatetransaction')
	$string .= "&montantfinal=".urlencode($final_amount);
		
$string .= "&action=".urlencode($action);

$checksum = hash_hmac('sha512', $string, $key);
              
$url_remote = "https://rct.3foiscb-sofinco.fr/ws/v1/".$action."/".$merchantreference."/top3reference/".$top3reference;

$data = array("checksum" => $checksum);

$data_string = json_encode($data);  

$path = "/ws/v1/".$action."/".$merchantreference."/top3reference/".$top3reference;
$host = "rct.3foiscb-sofinco.fr";
$port = 443;
$errno = '';
$errstr = '';
$header = "POST " . $path . " HTTP/1.0\r\n";
$header .= "Host: " . $host . "\r\n";
$header .= "Content-Type: application/json\r\n";
$header .= "Content-length: " . strlen($data_string) . "\r\n\r\n";
$header .= $data_string;

$socket = fsockopen('tls://' . $host, $port, $errno, $errstr, 5);

$res = '';
if (@fputs($socket, $header))
        while (!feof($socket))
          $res .= fgets($socket, 128);

echo "<p>url appelée : ".$url_remote."<p/>";
echo "<p>données envoyées : ".$data_string."<p/>";
echo "<p/>réponse plateforme : ".$res."<p/>";