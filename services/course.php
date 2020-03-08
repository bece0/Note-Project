<?php

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try{
    include '_api_key_kontrol.php';

    if(!isset($_GET["method"]) || $_GET["method"] == ""){
        echo "method parametresi eksik!";
        die();
    }
    
    $METHOD = $_GET["method"];
    
    $COURSE = NULL;
    $COURSE_ID = NULL;
    
    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

    if($METHOD == "finish" || $METHOD == "ayril" || $METHOD == "update_image"){
        if(isset($_GET["ders_id"]) && $_GET["ders_id"] != ""){
            $COURSE_ID = mysqli_real_escape_string($baglanti, $_GET["ders_id"]);
        }else if(isset($_POST["ders_id"]) && $_POST["ders_id"] != ""){
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
    }

    if($METHOD == "finish"){
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
            DersiKapat($COURSE_ID);
        }
        else{
            $statusCode = 401;
            throw new Exception("Dersi kapatmaya yetkiniz yok!");
        }

    }else if($METHOD == "ayril"){
        
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
    }else if($METHOD == "get_active_courses"){
        if($GIRIS_YAPAN_OGRETMEN_MI){
            $sonucObjesi->data->dersler = DuzenledigiAktifDersleriGetir($KULLANICI_ID);
            $sonucObjesi->data->asistan_dersler = AsistanOlunanDersleriGetir($KULLANICI_ID);
        }else if($GIRIS_YAPAN_OGRENCI_MI){
            $sonucObjesi->data->dersler = OgrencininAktifDersleriniGetir($KULLANICI_ID);
        }
    }else{
        $statusCode = 400;
        throw new Exception("Desteklenmeyen metod : $METHOD");
    }

}catch(Throwable $exp){
    if($statusCode == 0){
        $statusCode = 500;
    }

    http_response_code($statusCode);

    $sonucObjesi->code = $statusCode;
    $sonucObjesi->hata = $exp->getMessage();
    $sonucObjesi->mesaj = $exp->getMessage();

    if($statusCode != 401)
        $sonucObjesi->detay = $exp->getTraceAsString();
}

        
echo json_encode($sonucObjesi);