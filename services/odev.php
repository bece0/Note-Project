<?php

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try{
    
    include '_api_key_kontrol.php';

    $METHOD = "add";
    if(!isset($_GET["method"]) || $_GET["method"] == ""){
        // die();
    }else {
        $METHOD = $_GET["method"];
    }

    $comment_id = NULL;
 

    $COURSE = null;
    $COURSE_ID = null;
    if(isset($_GET["courseId"]) && $_GET["courseId"] != ""){
        $COURSE_ID = $_GET["courseId"];
    }
    
    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

    $odev_adi = NULL;
    $aciklama = NULL;
    $son_tarih = NULL;
    $dosya_gonderme = 0;//dosya gönderme mecburi mi
 
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

        if(!isset($_POST["odev_aciklama"]) || $_POST["odev_aciklama"] == ""){
            $statusCode = 400;
            throw new Exception("odev_aciklama parametresi eksik!");
        }

        if(!isset($_POST["son_tarih"]) || $_POST["son_tarih"] == ""){
            $statusCode = 400;
            throw new Exception("son_tarih parametresi eksik!");
        }

        if(isset($_POST["dosya_gonderme"]) && $_POST["dosya_gonderme"] != "ON"){
            $dosya_gonderme = 1;
        }

        $COURSE_ID =  mysqli_real_escape_string($baglanti, $_POST["ders_id"]);
        $odev_adi = mysqli_real_escape_string($baglanti, $_POST["odev_adi"]);
        $aciklama = mysqli_real_escape_string($baglanti, $_POST["odev_aciklama"]);
        $son_tarih = mysqli_real_escape_string($baglanti, $_POST["son_tarih"])." 23:59:59";


        $COURSE = DersBilgileriniGetir($COURSE_ID);
        $DUZENLEYEN_ID = $COURSE["duzenleyen_id"];


        $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;
        
        if($DUZENLEYEN_ID == $KULLANICI_ID)
            $GIRIS_YAPAN_DERSIN_HOCASI_MI = TRUE;

        if($KULLANICI["admin"] == 1 && $GIRIS_YAPAN_DERSIN_HOCASI_MI == FALSE)
            $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);

        if($GIRIS_YAPAN_DERSIN_HOCASI_MI == FALSE && $GIRIS_YAPAN_DERSIN_ASISTANI_MI == FALSE)
            throw new Exception("Bu ders için ödev oluşturma yetkiniz bulunmuyor!");

        $DOSYA_DETAY = DosyaUpload("../files/uploads/odev/", $COURSE_ID);

        $DOSYA_ID = NULL;

        if($DOSYA_DETAY != NULL){
            // $statusCode = 500;
            // throw new Exception("Dosya yüklenemdi (hata-1)!");

            $dosya_kod = GUIDOlustur();
            // DosyaEkle($kod, $yukleyen_id, $isim, $dosya_adi, $indirme_link)
            $DOSYA_ID = DosyaEkle($dosya_kod, $KULLANICI_ID, $DOSYA_DETAY["isim"], $DOSYA_DETAY["dosya_adi"], $DOSYA_DETAY["indirme_link"]);
    
            if($DOSYA_ID  == NULL){
                $statusCode = 500;
                throw new Exception("Dosya yüklenemdi (hata-2)!");
            }
        }else{
            $DOSYA_ID = 0;
        }

        $odev_kod = GUIDOlustur();
        // DersOdevKaydet($COURSE_ID, $olusturan_id, $dosya_id, $isim, $aciklama, $son_tarih, $dosya_gonderme)
        DersOdevKaydet($odev_kod, $COURSE_ID, $KULLANICI_ID, $DOSYA_ID, $odev_adi, $aciklama, $son_tarih, $dosya_gonderme);
        
        DersKatilimcilarinaYeniOdevBildirimiGonder($COURSE_ID, $odev_adi, "", "", [$KULLANICI_ID]);
    }else if(isset($_POST) && $METHOD == "notver"){
        
        if (!isset($_GET["ogrenci_odev_id"]) && $_GET['ogrenci_odev_id'] == "") {
            $statusCode = 400;
            throw new Exception("ogrenci_odev_id parametresi eksik!");
        }

        if(!isset($_GET["not"]) || $_GET["not"] == ""){
            $statusCode = 400;
            throw new Exception("odev_adi parametresi eksik!");
        }

        $ogrenci_odev_id =  mysqli_real_escape_string($baglanti, $_GET["ogrenci_odev_id"]);
        $not = mysqli_real_escape_string($baglanti, $_GET["not"]);

        $OGRENCI_ODEV = OgrenciOdev_GetirById($ogrenci_odev_id);
        if($OGRENCI_ODEV == NULL){
            $statusCode = 404;
            throw new Exception("Öğrenci ödevi bulunamadı!");
        }
        //TODO - giriş yapan kullanıcı not verme yetkisini kontrol et....

        OgrenciOdevNotGuncelle($ogrenci_odev_id, $not);
        
        $sonucObjesi->mesaj = "Ödev notlandırıldı.";
    }else if(isset($_POST) && $METHOD == "delete"){
        
        if (!isset($_GET["odev_kod"]) && $_GET['odev_kod'] == "") {
            $statusCode = 400;
            throw new Exception("odev_kod parametresi eksik!");
        }

        $odev_kod =  mysqli_real_escape_string($baglanti, $_GET["odev_kod"]);

        $ODEV = GetOdevDetailsByKod($odev_kod);
        if($ODEV == NULL){
            $statusCode = 404;
            throw new Exception("Ödev bulunamadı!");
        }


        //TODO - giriş yapan kullanıcı silme yetkisini kontrol et....

        DeleteOdevByKod($odev_kod);
        
        $sonucObjesi->mesaj = "Ödev silindi.";
    }else if($METHOD == "list"){
        $sonucObjesi->data = DersOdevleriniGetir($COURSE_ID);
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