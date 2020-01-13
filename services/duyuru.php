<?php
session_start();
header('Content-type: application/json');

//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
if(!isset($_SESSION["kullanici_id"])){
    die();
}

$METHOD = "add";

if(isset($_GET["method"]) && $_GET["method"] != ""){
    $METHOD = $_GET["method"];
}

$KULLANICI_ID = $_SESSION["kullanici_id"];
$DUYURU_ID = NULL;

include '../database/database.php';
$baglanti = BAGLANTI_GETIR();


if(isset($_GET["duyuru_id"]) && $_GET["duyuru_id"] != ""){
    $DUYURU_ID = $_GET["duyuru_id"];
    $DUYURU_ID =  mysqli_real_escape_string($baglanti, $_GET["duyuru_id"]);
}

$sonucObjesi = new stdClass();;
$sonucObjesi->mesaj = "";

//isteği yapan kullanıcı
$KULLANICI = KullaniciBilgileriniGetirById($KULLANICI_ID); 
$COURSE = null;
$COURSE_ID = null;

$GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
$GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

$statusCode = 0;

try {
   
    if($METHOD == "add"){
        $mesaj = "";

        if (isset($_POST["ders_id"]) && $_POST['ders_id'] != NULL) {
            $COURSE_ID =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
        }else{
            $statusCode = 400;
            throw new Exception("ders_id parametresi eksik!");
        }

        $COURSE = DersBilgileriniGetir($COURSE_ID);
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }

        if (isset($_POST["mesaj"]) && $_POST['mesaj'] != NULL) {
            $mesaj =  mysqli_real_escape_string($baglanti, $_POST["mesaj"]);
        }else{
            $statusCode = 400;
            throw new Exception("mesaj parametresi eksik!");
        }

        if(isset($COURSE_ID) && $COURSE_ID != NULL){
            $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
            $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
        }
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
            DersDuyuruKaydet($COURSE_ID, $KULLANICI_ID, $mesaj);
            DersDuyuruBildirimiGonder($COURSE_ID, $mesaj, "", [$KULLANICI_ID]);
        }
        else{
            $statusCode = 401;
            throw new Exception("Duyuru ekleme yetkisiniz bulunmuyor!");
        }
    }else if($METHOD == "delete"){
        // TODO - duyuru id al, duyuru id üzerinden ders bul,
        //  derrs üzerinden silme yetkisi kontrolü yap..

        if ($DUYURU_ID == NULL) {
            $statusCode = 400;
            throw new Exception("duyuru_id parametresi eksik!");
        }

        $DUYURU = DuyuruBilgileriniGetir($DUYURU_ID);
        if($DUYURU == NULL){
            $statusCode = 404;
            throw new Exception("Duyuru bulunamadi!");
        }

        $COURSE = DersBilgileriniGetir($DUYURU["ders_id"]);
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }

        $COURSE_ID = $DUYURU["ders_id"];

        if(isset($COURSE_ID) && $COURSE_ID != NULL){
            $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
            $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
        }
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
            DuyuruSil($DUYURU_ID);
        }
        else{
            $statusCode = 401;
            throw new Exception("Duyuru silme yetkisiniz bulunmuyor!");
        }
    }else{
        $statusCode = 400;
        throw new Exception("Desteklenmeyen metod : $METHOD");
    }

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