<?php

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try{

    include '_api_key_kontrol.php';

    $METHOD = "add";

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

    include '../includes/ortak.php';

    if(isset($_POST) && $METHOD == "add"){

        $COURSE_ID = NULL;
        $sinav_adi = NULL;
        $sinav_gun = NULL;
        $sinav_saat = NULL;

        $json = file_get_contents('php://input');
        if($json == NULL){
            throw new Exception("İstek hatalı");
        }

        $data = json_decode($json);
 
        if(isset($data->courseId)){
            $COURSE_ID = mysqli_real_escape_string($baglanti, $data->courseId);
        }else{
            $statusCode = 400;
            throw new Exception("courseId parametresi eksik!");
        }

        if(isset($data->examName)){
            $sinav_adi = mysqli_real_escape_string($baglanti, $data->examName);
        }else{
            $statusCode = 400;
            throw new Exception("sinav_adi parametresi eksik!");
        }

        if(isset($data->examDay)){
            $sinav_gun = mysqli_real_escape_string($baglanti, $data->examDay);
        }else{
            $statusCode = 400;
            throw new Exception("examDay parametresi eksik!");
        }

        if(isset($data->examTime)){
            $sinav_saat = mysqli_real_escape_string($baglanti, $data->examTime);
        }else{
            $statusCode = 400;
            throw new Exception("examTime parametresi eksik!");
        }
 
        $COURSE = DersBilgileriniGetir($COURSE_ID);
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }
 
        $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
 
        $tarih = $sinav_gun." ".$sinav_saat;

        if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
            DersDuyuruKaydet_Takvim($COURSE_ID, $KULLANICI_ID, $sinav_adi, $tarih, "SINAV");
            DersKatilimcilarinaYeniSinavBildirimiGonder($COURSE_ID, $sinav_adi, "", "", [$KULLANICI_ID]);
        }else{
            $statusCode = 401;
            throw new Exception("Sınav oluşturmaya yetkiniz bulunmamakta.");
        }

        $sonucObjesi->sonuc = true;
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

?>