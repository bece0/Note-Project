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

$ders_id = NULL;
$odev_adi = NULL;
$aciklama = NULL;
$son_tarih = NULL;
$dosya_gonderme = FALSE;//dosya gönderme mecburi mi

try{
    include '../includes/ortak.php';

    if(isset($_POST) && $METHOD == "add"){
        
        if (!isset($_POST["ders_id"]) && $_POST['ders_id'] == "") {
            $statusCode = 400;
            throw new Exception("ders_id parametresi eksik!");
        }

        if(!isset($_POST["odev_adi"]) || $_POST["odev_adi"] == ""){
            $statusCode = 400;
            throw new Exception("odev_adi parametresi eksik!");
        }

        if(!isset($_POST["aciklama"]) || $_POST["aciklama"] == ""){
            $statusCode = 400;
            throw new Exception("aciklama parametresi eksik!");
        }

        if(!isset($_POST["son_tarih"]) || $_POST["son_tarih"] == ""){
            $statusCode = 400;
            throw new Exception("son_tarih parametresi eksik!");
        }

        if(isset($_POST["dosya_gonderme"]) && $_POST["dosya_gonderme"] != "ON"){
            $dosya_gonderme = TRUE;
        }

        $ders_id =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
        $odev_adi = mysqli_real_escape_string($baglanti, $_POST["odev_adi"]);
        $aciklama = mysqli_real_escape_string($baglanti, $_POST["aciklama"]);
        $son_tarih = mysqli_real_escape_string($baglanti, $_POST["son_tarih"])." 23:59:59";

        $DOSYA_DETAY = DosyaUpload("../files/uploads/odev/", $ders_id);

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

        $odev_kod = GUIDOlustur();
        // DersOdevKaydet($ders_id, $olusturan_id, $dosya_id, $isim, $aciklama, $son_tarih, $dosya_gonderme)
        DersOdevKaydet($odev_kod, $ders_id, $KULLANICI_ID, $DOSYA_ID, $odev_adi, $aciklama, $son_tarih, $dosya_gonderme);
        
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
    DersKatilimcilarinaYeniOdevBildirimiGonder($ders_id, $odev_adi, "", "", [$KULLANICI_ID]);
} catch (\Throwable $th) {
    
}

        
echo json_encode($sonucObjesi);



?>