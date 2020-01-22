<?php

session_start();
//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
if(!isset($_SESSION["kullanici_id"])){
    die();
}

if(!isset($_GET["method"]) || $_GET["method"] == ""){
    echo "method parametresi eksik!";
    die();
}

if(!isset($_GET["course"]) || $_GET["course"] == ""){
    echo "course parametresi eksik!";
    die();
}

$METOD = $_GET["method"];
$COURSE_ID = $_GET["course"];
$KULLANICI_ID = $_SESSION["kullanici_id"];

include '../database/database.php';

$baglanti = BAGLANTI_GETIR();

header('Content-type: application/json');

$sonucObjesi = new stdClass();;
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";

//isteği yapan kullanıcı
$KULLANICI = KullaniciBilgileriniGetirById($KULLANICI_ID); 

//üzerinde işlem yapılmak istenen ders
$COURSE = DersBilgileriniGetir($COURSE_ID);

$GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
$GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);

$statusCode = 0;

try{
    if($METOD == "adduser"){
        
        if(!isset($_GET["mail"]) || $_GET["mail"] == ""){
            $statusCode = 400;
            throw new Exception('Mail parametresi eksik!');
        }

        $EKLENECEK_KULLANICI = KullaniciBilgileriniGetir($_GET["mail"]);
        if($EKLENECEK_KULLANICI == NULL){
            $statusCode = 400;
            throw new Exception('Bu mail adresine sahip bir kullanıcı yok!');
        }

        if($EKLENECEK_KULLANICI["id"] == $COURSE["duzenleyen_id"]){
            $statusCode = 400;
            throw new Exception('Dersin öğretmeni derse kaydolamaz!');
        }
        
        $KAYITLIMI = DerseKayitliMi($EKLENECEK_KULLANICI["id"], $COURSE_ID);
        if($KAYITLIMI  == TRUE){
            $statusCode = 400;
            $ad_soyad = $EKLENECEK_KULLANICI["adi"]." ".$EKLENECEK_KULLANICI["soyadi"];
            throw new Exception("Bu mail adresi derse zaten kayıtlı : $ad_soyad ");
        }
            
        $KAYITLI_OGRENCI_SAYISI = DerseKayitliOgrenciSayisi($COURSE_ID);

        if($KAYITLI_OGRENCI_SAYISI >= $COURSE["kontenjan"]){
            $statusCode = 400;
            throw new Exception("Ders kontenjanı dolu! ($KAYITLI_OGRENCI_SAYISI)"); 
        }

        $type = "ogrenci";//varsayılan ekleme tipi
        if(!isset($_GET["type"]) || $_GET["type"] == "asistan")
            $type = "asistan";
        
        if($type == "ogrenci"){
            //Katilimci tablosuna öğrenci tipi(1) olarak kayıt ekle
            if(DerseKayitOl($EKLENECEK_KULLANICI["id"],  $COURSE_ID, 0) === TRUE){
                $sonucObjesi->mesaj = "Öğrenci kaydı başarıyla yapıldı.";
                
                //Bildirim gönder
                $mesaj = $COURSE["isim"]." dersine öğrenci olarak eklendiniz";
                BildirimYaz($EKLENECEK_KULLANICI["id"], $COURSE_ID, $mesaj, "", $tip = "OGRENCI_KAYIT");
            }else{
                throw new Exception("Öğrenci kaydı yapılamadı.");
            }
        }else if($type == "asistan"){
            //Katilimci tablosuna asistan tipi(1) olarak kayıt ekle
            if(DerseKayitOl($EKLENECEK_KULLANICI["id"],  $COURSE_ID, 1) === TRUE){
                $sonucObjesi->mesaj = "Asistan kaydı başarıyla yapıldı.";

                //Bildirim gönder
                $mesaj = $COURSE["isim"]." dersine asistan olarak eklendiniz";
                BildirimYaz($EKLENECEK_KULLANICI["id"], $COURSE_ID, $mesaj, "", $tip = "ASISTAN_KAYIT");
            }else{
                throw new Exception("Asistan kaydı yapılamadı.");
            }
        }else{
            throw new Exception("Desteklenmeyen kayıt tipi : $type"); 
        }
    
    }else if($METOD == "removeuser"){
        
        if(!isset($_GET["user"]) || $_GET["user"] == ""){
            $statusCode = 400;
            throw new Exception('Kullanıcı parametresi eksik!');
        }

        $KAYDI_SILINECEK_USER_ID = $_GET["user"];

        if(DerstenKayitSil($COURSE_ID, $KAYDI_SILINECEK_USER_ID) === TRUE){
            $sonucObjesi->mesaj = "Kullanıcı kaydı başarıyla silindi.";
        }else{
            throw new Exception("Kullanıcı kaydı silinirken hata oluştu.");
        }

    }else if($METOD == "addassistant"){
        
        if(!isset($_GET["user"]) || $_GET["user"] == ""){
            $statusCode = 400;
            throw new Exception('Kullanıcı parametresi eksik!');
        }

        $ASISTAN_OLACAK_USER_ID = $_GET["user"];

        if($ASISTAN_OLACAK_USER_ID == $COURSE["duzenleyen_id"]){
            $statusCode = 400;
            throw new Exception('Dersin öğretmeni derse asistan olarak eklenemez!');
        }

        if(DersKayitTipiGüncelle($COURSE_ID, $ASISTAN_OLACAK_USER_ID, 1) === TRUE){
            $sonucObjesi->mesaj = "Kullanıcı asistan olarak ayarlandı.";

            //Bildirim gönder
            $mesaj = $COURSE["isim"]." dersine asistan olarak eklendiniz";
            BildirimYaz($EKLENECEK_KULLANICI["id"], $COURSE_ID, $mesaj, "", $tip = "ASISTAN_KAYIT");
        }else{
            throw new Exception("Kullanıcı asistan olarak ayarlanırken hata oluştu.");
        }

    }else if($METOD == "removeassistant"){
        
        if(!isset($_GET["user"]) || $_GET["user"] == ""){
            $statusCode = 400;
            throw new Exception('Kullanıcı parametresi eksik!');
        }

        $ASISTANLIKTAN_CIKARILACAK_USER_ID = $_GET["user"];

        if(DersKayitTipiGüncelle($COURSE_ID, $ASISTANLIKTAN_CIKARILACAK_USER_ID, 0) === TRUE){
            $sonucObjesi->mesaj = "Kullanıcı asistanlığı başarıyla kaldırıldı.";
        }else{
            throw new Exception("Kullanıcı asistanlığı kaldırılırken hata oluştu.");
        }

    }else{
        $statusCode = 400;
        throw new Exception("Desteklenmeyen metod : $METOD");
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