<?php

echo "<h4>Script eligibility</h4>";

$key = '';
$merchantreference = '';

if($key == '')
	die('Erreur : cle vide');

if($merchantreference == '')
	die('Erreur : merchantreference vide');

$url_eligibility = "https://rct.3foiscb-sofinco.fr/ws/v1/eligible/".$merchantreference;

$country = urlencode("FRA");
$amount = urlencode("50000");
$string = "merchantreference=".$merchantreference."&commandamount=".$amount."&country=".$country;

$checksum = hash_hmac('sha512', $string, $key);

$data = array("commandamount" => $amount, "country" => $country, "checksum" => $checksum);                                                                    
$data_string = json_encode($data);                                                                                   

$path = "/ws/v1/eligible/".$merchantreference;
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

echo "<p/>url appelée : ".$url_eligibility."<p/>";
echo "<p/>données envoyées : ".$data_string."<p/>";
echo "<p/>réponse plateforme : ".$res."<p/>";