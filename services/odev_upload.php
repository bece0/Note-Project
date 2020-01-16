<?php

session_start();
header('Content-type: application/json');

//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
if(!isset($_SESSION["kullanici_id"])){
    die();
}

$METHOD = "add";
if(!isset($_GET["method"]) || $_GET["method"] == ""){
    // echo "method parametresi eksik!";
    // die();
}else {
    $METHOD = $_GET["method"];
}


$KULLANICI_ID = $_SESSION["kullanici_id"];
$comment_id = NULL;

// if(isset($_GET["comment_id"]) && $_GET["comment_id"] != ""){
//     $comment_id = $_GET["comment_id"];
// }

include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$sonucObjesi = new stdClass();;
$sonucObjesi->mesaj = "";

//isteği yapan kullanıcı
$KULLANICI = KullaniciBilgileriniGetirById($KULLANICI_ID); 
$COURSE = null;
$COURSE_ID = null;

$GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
$GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

$statusCode = 0;

$COURSE_ID = NULL;
$odev_id = NULL;

try{
    include '../includes/ortak.php';

    if(isset($_POST) && $METHOD == "add"){

        if (!isset($_POST["ders_id"]) && $_POST['ders_id'] == "") {
            $statusCode = 400;
            throw new Exception("ders_id parametresi eksik!");
        }

        if(!isset($_POST["odev_id"]) || $_POST["odev_id"] == ""){
            $statusCode = 400;
            throw new Exception("odev_id parametresi eksik!");
        }

        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
        $odev_id = mysqli_real_escape_string($baglanti, $_POST["odev_id"]);

        $DOSYA_DETAY = DosyaUpload("../files/uploads/ogrenci_odev/", $odev_id);

        if($DOSYA_DETAY == NULL){
            $statusCode = 500;
            throw new Exception("Dosya yüklenemdi (hata-1)!");
        }

        $dosya_kod = GUIDOlustur();
        // DosyaEkle($kod, $yukleyen_id, $isim, $dosya_adi, $indirme_link)
        $DOSYA_ID = DosyaEkle($dosya_kod, $KULLANICI_ID, $DOSYA_DETAY["isim"], $DOSYA_DETAY["dosya_adi"], $DOSYA_DETAY["indirme_link"]);

        if($DOSYA_ID  == NULL){
            $statusCode = 500;
            throw new Exception("Dosya yüklenemdi (hata-2)!");
        }

        $ogrenci_odev_kod = GUIDOlustur();
        // OgrenciDersOdevEkle($kod, $ogrenci_id, $odev_id, $ders_id, $dosya_id)
        OgrenciDersOdevEkle($ogrenci_odev_kod, $KULLANICI_ID, $odev_id, $COURSE_ID, $DOSYA_ID);

    }else if(isset($_POST) && $METHOD == "delete"){
        
        if(!isset($_POST["ogrenci_odev_id"]) || $_POST["ogrenci_odev_id"] == ""){
            $statusCode = 400;
            throw new Exception("ogrenci_odev_id parametresi eksik!");
        }

        $ogrenci_odev_id =  mysqli_real_escape_string($baglanti, $_POST["ogrenci_odev_id"]);

        OgrenciOdevSil($ogrenci_odev_id);

    }else if(isset($_POST) && $METHOD == "teslim"){
        
        if(!isset($_POST["odev_id"]) || $_POST["odev_id"] == ""){
            $statusCode = 400;
            throw new Exception("odev_id parametresi eksik!");
        }

        if (!isset($_POST["ders_id"]) && $_POST['ders_id'] == "") {
            $statusCode = 400;
            throw new Exception("ders_id parametresi eksik!");
        }

        $odev_id =  mysqli_real_escape_string($baglanti, $_POST["odev_id"]);
        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);

        $ogrenci_odev_kod = GUIDOlustur();
        OgrenciDersOdevEkle($ogrenci_odev_kod, $KULLANICI_ID, $odev_id, $COURSE_ID, 0);

    }else{
        $statusCode = 400;
        throw new Exception("Desteklenmeyen metod : $METHOD");
    }

    // var_dump($_POST);

}catch(Throwable $exp){
    if($statusCode == 0)
        $statusCode = 500;

    http_response_code($statusCode);

    $sonucObjesi->code = $statusCode;
    $sonucObjesi->hata = $exp->getMessage();
    $sonucObjesi->mesaj = $exp->getMessage();
    $sonucObjesi->detay = $exp->getTraceAsString();
}

try {
    // DersHocalarinaYeniOdevYuklendiBildirimiGonder(....);
} catch (\Throwable $th) {
    
}

        
echo json_encode($sonucObjesi);



?>