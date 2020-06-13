<?php

session_start();

//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
// if(!isset($_SESSION["kullanici_id"])){
//     echo "bu dosyayı indirebilmek için giriş yapmanız gerekiyor!";
//     die();
// }


if(!isset($_GET["type"]) || $_GET["type"] == ""){
    $statusCode = 400;
    echo "type parametresi eksik!";
}

if(!isset($_GET["kod"]) || $_GET["kod"] == ""){
    $statusCode = 400;
    echo "kod parametresi eksik!";
}

include 'database/database.php';
$baglanti = BAGLANTI_GETIR();

$type = mysqli_real_escape_string($baglanti, $_GET["type"]);
$kod = mysqli_real_escape_string($baglanti, $_GET["kod"]);

$statusCode = 0;

function dosyaIndir($dosya_id){
    $dosya = GetDosyaById($dosya_id);

    if($dosya == NULL)
        throw new Exception("dosya bulunamadi");


    $path = $dosya["indirme_link"];

    if(substr( $path, 0, 3 ) === "../"){
        $path = substr($path, 3);
    }

    header("Location: ".$path);
}

try{
    if($type == "dokuman"){

        $dokuman = GetDokumanByKod($kod);
        if($dokuman == NULL)
            throw new Exception("ilgili kayit bulunamadi");
        // var_dump($dokuman);
        $dosya_id = $dokuman["dosya_id"];
        dosyaIndir($dosya_id);

    }else if($type == "odev"){

        $odev = GetOdevByKod($kod);
        if($odev == NULL)
            throw new Exception("ilgili kayit bulunamadi (odev)");
        
        $dosya_id = $odev["dosya_id"];
        dosyaIndir($dosya_id);

    }else if($type == "ogrenci_odev"){

        $ogrenci_odev = OgrenciOdev_OgrencininOdeviniGetirByKod($kod);
        if($ogrenci_odev == NULL)
            throw new Exception("ilgili kayit bulunamadi (ogrenci_odev) -> $kod");

        $dosya_id = $ogrenci_odev["dosya_id"];
        dosyaIndir($dosya_id);
    }else{
        $statusCode = 400;
        throw new Exception("type parametresi yanlis!");
    }

}catch(Throwable $exp){
    if($statusCode == 0)
        $statusCode = 500;

    http_response_code($statusCode);

    echo $exp->getMessage();
}



?>