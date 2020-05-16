<?php

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();


function random_str(int $length = 64){
    $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

try{
    include '_api_key_kontrol.php';

    if(!isset($_GET["method"]) || $_GET["method"] == ""){
        $statusCode = 400;
        throw new Exception("method parametresi eksik!");
    }
    
    $METHOD = $_GET["method"];
    
    $COURSE = NULL;
    $COURSE_ID = NULL;
    
    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

    if(isset($_GET["courseId"]) && $_GET["courseId"] != ""){
        $COURSE_ID = mysqli_real_escape_string($baglanti, $_GET["courseId"]);
    }

    if($METHOD == "finish" || $METHOD == "ayril" || $METHOD == "update_image"){
        if(isset($_GET["courseId"]) && $_GET["courseId"] != ""){
            $COURSE_ID = mysqli_real_escape_string($baglanti, $_GET["courseId"]);
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

    if($METHOD == "add" || $METHOD == "edit"){
        if(!$GIRIS_YAPAN_OGRETMEN_MI){
            $statusCode = 401;
            throw new Exception("Ders açmaya yetkiniz yok!");
        }

        $name = "";
        $desc = "";
        $class = "";
        $department = "";
        $quota = 0;

        $json = file_get_contents('php://input');
        if($json != NULL){
            $data = json_decode($json);

            if(!$data){
                $statusCode = 400;
                throw new Exception("Ders oluşturulamadı, hatalı istek!");
            }

            if(isset($data->name) && $data->name != ""){
                $name = mysqli_real_escape_string($baglanti, $data->name);
            }else{
                throw new Exception("Ders adı boş olamaz");
            }

            if(isset($data->desc) && $data->desc != ""){
                $desc = mysqli_real_escape_string($baglanti, $data->desc);
            }else{
                throw new Exception("Ders açıklaması boş olamaz");
            }

            if(isset($data->class) && $data->class != ""){
                $class = mysqli_real_escape_string($baglanti, $data->class);
            }else{
                throw new Exception("Ders sınıf adı boş olamaz");
            }

            if(isset($data->department) && $data->department != ""){
                $department = mysqli_real_escape_string($baglanti, $data->department);
            }else{
                throw new Exception("Ders bölüm adı boş olamaz");
            }

            if(isset($data->quota) && $data->quota != ""){
                $quota = mysqli_real_escape_string($baglanti, $data->quota);
            }else{
                throw new Exception("Ders kotası boş olamaz");
            }
        }

        $course_code =  random_str(6);

        if($METHOD == "add"){
            if(DersKaydet($course_code, $name, $desc, $quota, $department, $class, $KULLANICI_ID) === TRUE){
                $sonucObjesi->mesaj = "Ders oluşturuldu";
                $sonucObjesi->sonuc = true;
            }else{
                throw new Exception("Ders oluşturulamadı!");
            }
        }

        if($METHOD == "edit"){
            $COURSE = DersBilgileriniGetir($COURSE_ID);
            if($COURSE == NULL){
                $statusCode = 404;
                throw new Exception("Ders bulunamadı!");
            }
            if($COURSE["duzenleyen_id"] !=  $KULLANICI_ID){
                $statusCode = 401;
                throw new Exception("Dersi düzenlemeye yetkiniz bulunmamaktadir!");
            }

            if(DersDuzenle($COURSE_ID, $name, $desc, $quota, $department, $class)=== TRUE){
                $sonucObjesi->mesaj = "Ders düzenlendi";
                $sonucObjesi->sonuc = true;
            }else{
                throw new Exception("Ders düzenlenemedi!");
            }
        }
    }
    else if($METHOD == "finish"){
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
            DersiKapat($COURSE_ID);
            $sonucObjesi->sonuc = true;
        }
        else{
            $statusCode = 401;
            throw new Exception("Dersi kapatmaya yetkiniz yok!");
        }

    }else if($METHOD == "ayril"){
        
        if(!$GIRIS_YAPAN_DERSIN_HOCASI_MI){
            DerstenKayitSil($COURSE_ID,$KULLANICI_ID);
            $sonucObjesi->sonuc = true;
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

        DosyaUpload("../files/images/course/", "", $COURSE["kodu"], ["png", "jpg", "jpeg"]);
        $sonucObjesi->sonuc = true;
    }else if($METHOD == "get_active_courses"){
        if($GIRIS_YAPAN_OGRETMEN_MI){
            $sonucObjesi->data->dersler = DuzenledigiAktifDersleriGetir($KULLANICI_ID);
            $sonucObjesi->data->asistan_dersler = AsistanOlunanDersleriGetir($KULLANICI_ID);
        }else if($GIRIS_YAPAN_OGRENCI_MI){
            $sonucObjesi->data->dersler = OgrencininAktifDersleriniGetir($KULLANICI_ID);
        }
        $sonucObjesi->sonuc = true;
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

    if($statusCode == 401 || $statusCode >= 500){
        $sonucObjesi->headers = getallheaders();
        $sonucObjesi->detay = $exp->getTraceAsString();
    }
}
        
echo json_encode($sonucObjesi);