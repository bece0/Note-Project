<?php
session_start();
header('Content-type: application/json');

//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
if(!isset($_SESSION["kullanici_id"])){
    die();
}

$TIP = "hepsi";

if(isset($_GET["tip"]) && $_GET["tip"] != ""){
    $TIP = $_GET["tip"];
}

$KULLANICI_ID = $_SESSION["kullanici_id"];

include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$sonucObjesi = new stdClass();;
$sonucObjesi->mesaj = "";

//isteği yapan kullanıcı
$KULLANICI = KullaniciBilgileriniGetirById($KULLANICI_ID); 

$statusCode = 0;

$DUYURULAR = [];
$ODEVLER = [];

try {
   
    if($TIP == "hepsi"){
        $mesaj = "";
        
        if($KULLANICI["admin"] == 0){

            $DUYURULAR =  OgrenciSinavDuyurulariniGetir($KULLANICI_ID);
            $ODEVLER =  OgrenciOdevleriGetir($KULLANICI_ID);

        }else if($KULLANICI["admin"] == 0){

            $DUYURULAR =  OgrenciSinavDuyurulariniGetir($KULLANICI_ID);
            $ODEVLER =  OgrenciOdevleriGetir($KULLANICI_ID);
            
        }
       

    }else{
        $statusCode = 400;
        throw new Exception("Desteklenmeyen metod : $METHOD");
    }

    $sonucObjesi->data = [
        "duyurular" => $DUYURULAR,
        "odevler" => $ODEVLER
    ];

}catch(Throwable $exp){
    if($statusCode == 0)
        $statusCode = 500;

    http_response_code($statusCode);

    $sonucObjesi->code = $statusCode;
    $sonucObjesi->hata = $exp->getMessage();
    $sonucObjesi->mesaj = $exp->getMessage();
    $sonucObjesi->detay = $exp->getTraceAsString();
}

echo json_encode($sonucObjesi);