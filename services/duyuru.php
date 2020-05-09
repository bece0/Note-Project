<?php

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try{

    include '_api_key_kontrol.php';

    if(!isset($_GET["method"]) || $_GET["method"] == ""){
        $statusCode = 400;
        throw new Exception("method parametresi eksik!");
    }
    $METHOD = $_GET["method"];

    $COURSE = null;
    $COURSE_ID = null;

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

    $DUYURU_ID = NULL;
    if(isset($_GET["duyuru_id"]) && $_GET["duyuru_id"] != ""){
        $DUYURU_ID = $_GET["duyuru_id"];
        $DUYURU_ID =  mysqli_real_escape_string($baglanti, $_GET["duyuru_id"]);
    }

    if(isset($_GET["ders"]) && $_GET["ders"] != ""){
        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_GET["ders"]);
    }

    if(isset($_GET["courseId"]) && $_GET["courseId"] != ""){
        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_GET["courseId"]);
    }

    if(isset($_POST["ders_id"]) && $_POST["ders_id"] != ""){
        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
    }

    if($METHOD == "list"){
        if ($COURSE_ID  == NULL) {
            $statusCode = 400;
            throw new Exception("courseId parametresi eksik!");
        }

        $duyurular = DersDuyurulariGetir($COURSE_ID);

        $sonucObjesi->data  = $duyurular;
        $sonucObjesi->sonuc = true;
    }else if($METHOD == "add"){
        $mesaj = "";

        $json = file_get_contents('php://input');
        if($json != NULL){
            $data = json_decode($json);
            if($data && isset($data->courseId)){
                $COURSE_ID = mysqli_real_escape_string($baglanti, $data->courseId);
            }
            if($data && isset($data->mesaj)){
                $mesaj = mysqli_real_escape_string($baglanti, $data->mesaj);
            }
        }

        if ($COURSE_ID  == NULL) {
            $statusCode = 400;
            throw new Exception("courseId parametresi eksik!");
        }

        if ($mesaj == "" || $mesaj == NULL){  
            $statusCode = 400;
            throw new Exception("mesaj parametresi eksik!");
        }

        $COURSE = DersBilgileriniGetir($COURSE_ID);
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }


        if(isset($COURSE_ID) && $COURSE_ID != NULL){
            $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
            $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
        }
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
            DersDuyuruKaydet($COURSE_ID, $KULLANICI_ID, $mesaj);
            DersKatilimcilarinaDuyuruBildirimiGonder($COURSE_ID, $mesaj, "", [$KULLANICI_ID]);
            $sonucObjesi->sonuc = true;
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
            $sonucObjesi->sonuc = true;
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