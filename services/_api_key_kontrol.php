<?php

class HataliAPIKeyException extends Exception { }
class HataliOturumException extends Exception { }

header('Content-type: application/json');

include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$headers = getallheaders();  

$statusCode = 0;

$KULLANICI_ID = NULL;
$KULLANICI = NULL;
$GIRIS_YAPAN_OGRENCI_MI = FALSE;
$GIRIS_YAPAN_OGRETMEN_MI = FALSE;
$API_ISTEGI = FALSE;

if(isset($headers['x-api-key'])){
    $API_KEY = $headers['x-api-key'];
    $API_ISTEGI = TRUE;

    $KULLANICI = KullaniciBilgileriniGetirByAPI($API_KEY);
    if($KULLANICI != NULL){
        $KULLANICI_ID = $KULLANICI["id"];
    }else{
        $statusCode = 401;
        throw new HataliAPIKeyException("x-api-key değeri geçersiz!");
    }
}else{
    session_start();
    //kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
    if(!isset($_SESSION["kullanici_id"])){
        $statusCode = 401;
        throw new HataliOturumException("oturum geçersiz!");
    }

    $KULLANICI_ID = $_SESSION["kullanici_id"];
    $KULLANICI = KullaniciBilgileriniGetirById($KULLANICI_ID); 
}

if($KULLANICI != NULL){
    if($KULLANICI["admin"] == 1){
        $GIRIS_YAPAN_OGRETMEN_MI = TRUE;
    }
    if($KULLANICI["admin"] == 0){
        $GIRIS_YAPAN_OGRENCI_MI = TRUE;
    }
}

?>