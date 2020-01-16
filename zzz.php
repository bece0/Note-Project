<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



include 'includes/ortak.php';
include 'database/database.php';

// echo date_default_timezone_get();

$kod = GUIDOlustur();
$result = DosyaEkle($kod, 1, "odev.pdf", "Deneme test", "/files/uploads/");
// $result = SQLTekliKayitGetir("SELECT LAST_INSERT_ID()");

var_dump($result);
// echo $result["id"]
?>