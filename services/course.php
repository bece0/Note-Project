<?php 
session_start();
header('Content-type: application/json');

//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
if(!isset($_SESSION["kullanici_id"])){
    die();
}

if(!isset($_GET["method"]) || $_GET["method"] == ""){
    echo "method parametresi eksik!";
    die();
}

$METHOD = $_GET["method"];
$KULLANICI_ID = $_SESSION["kullanici_id"];

// if($comment_id == NULL){
//     echo "$comment_id yoq";
//     die();
// }

include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$sonucObjesi = new stdClass();;
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";

//isteği yapan kullanıcı
$KULLANICI = KullaniciBilgileriniGetirById($KULLANICI_ID); 
$COURSE = NULL;
$COURSE_ID = NULL;

$GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
$GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

$statusCode = 0;

try{

    if(isset($_GET["ders_id"]) && $_GET["ders_id"] != ""){
        $COURSE_ID = mysqli_real_escape_string($baglanti, $_GET["ders_id"]);
    }

    if(isset($_POST["ders_id"]) && $_POST["ders_id"] != ""){
        $COURSE_ID = mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
    }

    if($COURSE_ID == NULL){
        $statusCode = 400;
        throw new Exception("ders_id parametresi eksik!");
    }

    $COURSE = DersBilgileriniGetir($COURSE_ID);

    if($COURSE == NULL){
        $statusCode = 404;
        throw new Exception("Böyle bir ders bulunmuyor!!");
    }

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);

    if($METHOD == "finish"){
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
            DersiKapat($COURSE_ID);
        }
        else{
            $statusCode = 401;
            throw new Exception("Dersi kapatmaya yetkiniz yok!");
        }

    }
    else if($METHOD == "ayril"){
        
        if(!$GIRIS_YAPAN_DERSIN_HOCASI_MI){
            DerstenKayitSil($COURSE_ID,$KULLANICI_ID);
        }
        else{
            $statusCode = 401;
            throw new Exception("Dersten ayrılamazsınız!");
        }

    }else if($METHOD == "update_image"){
        if(!$GIRIS_YAPAN_DERSIN_HOCASI_MI){
            $statusCode = 401;
            throw new Exception("Bu işlem için yetkiniz bulunmuyor!");
        }

        include '../includes/ortak.php';

        DosyaUpload("../files/images/event/", "", $COURSE["kodu"], ["png", "jpg", "jpeg"]);
    }
    else{
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