<?php

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try{
    
    include '_api_key_kontrol.php';

    $TIP = "hepsi";

    if(isset($_GET["tip"]) && $_GET["tip"] != ""){
        $TIP = $_GET["tip"];
    }

    $DUYURULAR = [];
    $ODEVLER = [];

    if($TIP == "hepsi"){
        if($KULLANICI["admin"] == 0){
            $DUYURULAR =  OgrenciSinavDuyurulariniGetir($KULLANICI_ID);
            $ODEVLER =  OgrenciOdevleriGetir($KULLANICI_ID);
        }else if($KULLANICI["admin"] == 1){
            // $DUYURULAR =  OgretmenSinavDuyurulariniGetir($KULLANICI_ID);
            // $ODEVLER =  OgretmenOdevleriGetir($KULLANICI_ID);
        }
        $sonucObjesi->sonuc = true;
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