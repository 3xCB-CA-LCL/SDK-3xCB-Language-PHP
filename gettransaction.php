<?php

echo "<h4>Script gettransaction</h4>";

$key = '';
$merchantreference = urlencode("");
$top3reference = "";
$refid = urlencode("");

if($key == '')
	die('Erreur : cle vide');

if($merchantreference == '')
	die('Erreur : merchantreference vide');

if($refid == '')
	die('Erreur : refID vide');

$string = "merchantreference=".$merchantreference."&top3reference=".$top3reference."&refid=".$refid;
$checksum = hash_hmac('sha512', $string, $key);
              
$url_gettransaction = "https://rct.3foiscb-sofinco.fr/ws/v1/gettransaction/".$merchantreference."/refid/".$refid;

$data = array("checksum" => $checksum);

$data_string = json_encode($data);  

$path = "/ws/v1/gettransaction/".$merchantreference."/refid/".$refid;
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

echo "<p>url appelée : ".$url_gettransaction."<p/>";
echo "<p>données envoyées : ".$data_string."<p/>";
echo "<p/>réponse plateforme : ".$res."<p/>";