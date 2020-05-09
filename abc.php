<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/ortak.php';
include 'database/database.php';


echo "parolalar değiştiriliyor <br>";

$all =  TumKullaniciBilgileriniGetir();

$count = count($all);
for ($i = 0; $i < $count; $i++) {
    $user = $all[$i];
    KullaniciParolaGuncelle($user["id"], "123", TRUE);
}

echo "parolalar değiştirildi <br>";


// echo $result["id"]
?>