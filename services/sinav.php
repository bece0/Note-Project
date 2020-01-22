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
$sinav_adi = NULL;
$sinav_gun = NULL;
$sinav_saat = NULL;

try{
    include '../includes/ortak.php';

    if(isset($_POST) && $METHOD == "add"){

        if (!isset($_POST["ders_id"]) && $_POST['ders_id'] == "") {
            $statusCode = 400;
            throw new Exception("ders_id parametresi eksik!");
        }

        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
        $COURSE = DersBilgileriniGetir($COURSE_ID);
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }

        if(isset($COURSE_ID) && $COURSE_ID != NULL){
            $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
            $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
        }

        if(!isset($_POST["sinav_adi"]) || $_POST["sinav_adi"] == ""){
            $statusCode = 400;
            throw new Exception("sinav_adi parametresi eksik!");
        }

        if(!isset($_POST["sinav_gun"]) || $_POST["sinav_gun"] == ""){
            $statusCode = 400;
            throw new Exception("sinav_gun parametresi eksik!");
        }

        if(!isset($_POST["sinav_saat"]) || $_POST["sinav_saat"] == ""){
            $statusCode = 400;
            throw new Exception("sinav_saat parametresi eksik!");
        }

       
        $sinav_adi = mysqli_real_escape_string($baglanti, $_POST["sinav_adi"]);
        $sinav_gun = mysqli_real_escape_string($baglanti, $_POST["sinav_gun"]);
        $sinav_saat = mysqli_real_escape_string($baglanti, $_POST["sinav_saat"]);

        $tarih = $sinav_gun." ".$sinav_saat;

        $mesaj = "";
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
            DersDuyuruKaydet_Takvim($COURSE_ID, $KULLANICI_ID, $sinav_adi, $tarih, "SINAV");
            DersKatilimcilarinaYeniSinavBildirimiGonder($COURSE_ID, $sinav_adi, "", "", [$KULLANICI_ID]);
        }else{
            $statusCode = 401;
            throw new Exception("Sınav oluşturmaya yetkiniz bulunmamakta.");
        }

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
    //TODO
    //DersKatilimcilarinaYeniSinavBildirimiGonder($COURSE_ID, $sinav_adi, "", "", [$KULLANICI_ID]);
} catch (\Throwable $th) {
    
}
        
echo json_encode($sonucObjesi);

?>