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
$dokuman_adi = NULL;
$dokuman_aciklama = NULL;

try{
    include '../includes/ortak.php';

    if(isset($_POST) && $METHOD == "add"){

        if (!isset($_POST["ders_id"]) && $_POST['ders_id'] == "") {
            $statusCode = 400;
            throw new Exception("ders_id parametresi eksik!");
        }

        if(!isset($_POST["dokuman_adi"]) || $_POST["dokuman_adi"] == ""){
            $statusCode = 400;
            throw new Exception("dokuman_adi parametresi eksik!");
        }

        if(!isset($_POST["dokuman_aciklama"]) || $_POST["dokuman_aciklama"] == ""){
            $statusCode = 400;
            throw new Exception("dokuman_aciklama parametresi eksik!");
        }

        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
        $dokuman_adi = mysqli_real_escape_string($baglanti, $_POST["dokuman_adi"]);
        $dokuman_aciklama = mysqli_real_escape_string($baglanti, $_POST["dokuman_aciklama"]);

        $DOSYA_DETAY = DosyaUpload("../files/uploads/dokuman/", $COURSE_ID);

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

        $dokuman_kod = GUIDOlustur();
        //DersDokumanKaydet($kod, $COURSE_ID, $olusturan_id, $dosya_id, $isim, $aciklama)
        DersDokumanKaydet($dokuman_kod, $COURSE_ID, $KULLANICI_ID, $DOSYA_ID, $dokuman_adi, $dokuman_aciklama);

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
    DersKatilimcilarinaYeniDokumanBildirimiGonder($COURSE_ID, $dokuman_adi, "", "", [$KULLANICI_ID]);
} catch (\Throwable $th) {
    
}

        
echo json_encode($sonucObjesi);



?>