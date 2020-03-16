<?php
 
 $sonucObjesi = new stdClass();
 $sonucObjesi->sonuc = false;
 $sonucObjesi->mesaj = "";
 $sonucObjesi->code = 200;
 $sonucObjesi->data = new stdClass();
 
 try{
    include '_api_key_kontrol.php';

    if(!isset($_GET["method"]) || $_GET["method"] == "" ){
        $statusCode = 400;
        throw new Exception("method parametresi eksik!");
    }

    $METOD = $_GET["method"];

    if($METOD == "attend"){
        $COURSE_CODE = mysqli_real_escape_string($baglanti, $_GET["code"]);
        if(!isset($COURSE_CODE)){
            $statusCode = 400;
            throw new Exception("code parametresi eksik!");
        }

        $COURSE = DersDetayGetir_Kod($COURSE_CODE);

        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Hatalı ders kodu girildi ".$COURSE_CODE);
        }

        $COURSE_ID = $COURSE["id"];

        if($COURSE["duzenleyen_id"] == $KULLANICI_ID){
            $statusCode = 400;
            throw new Exception("Dersin öğretmeni derse kaydolamaz!");
        }
    
        $COURSE_ALREADY_REGISTERED = DerseKayitliMi($KULLANICI_ID, $COURSE_ID);
    
        if($COURSE_ALREADY_REGISTERED  == TRUE){
            $statusCode = 400;
            throw new Exception("Derse zaten kayıtlısınız.");
        }
        
        $kayitli = DerseKayitliOgrenciSayisi($COURSE_ID);
        if($kayitli >= $COURSE["kontenjan"]){
            $statusCode = 400;
            throw new Exception("Ders kontenjanı dolu.");
        }
    
        if(DerseKayitOl($KULLANICI_ID,  $COURSE_ID) === TRUE){
            $sonucObjesi->sonuc = true;
            $sonucObjesi->mesaj = "Derse kayıt olundu.";
            LogYaz_DersKayit($KULLANICI_ID, $COURSE_ID);
        }else{
            $statusCode = 500;
            throw new Exception("Derse kayıt olunamadı.");
        }
    }else if($METOD == "unattend"){
        $COURSE_ID = mysqli_real_escape_string($baglanti, $_GET["courseId"]);
        if(!isset($COURSE_ID)){
            $statusCode = 400;
            throw new Exception("courseId parametresi eksik!");
        }

        $COURSE = DersDetayGetir($COURSE_ID);
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Hatalı ders kodu girildi ".$COURSE_CODE);
        }

        $COURSE_ID = $COURSE["id"];

        if($COURSE["duzenleyen_id"] == $KULLANICI_ID){
            $statusCode = 400;
            throw new Exception("Dersin öğretmeni dersten ayrılamaz!");
        }
    
        $COURSE_ALREADY_REGISTERED = DerseKayitliMi($KULLANICI_ID, $COURSE_ID);
    
        if($COURSE_ALREADY_REGISTERED  == FALSE){
            $statusCode = 400;
            throw new Exception("Derse zaten kayıtlı değilsiniz.");
        }

        if(DerstenKayitSil($COURSE_ID, $KULLANICI_ID) === TRUE){
            $sonucObjesi->sonuc = true;
            $sonucObjesi->mesaj = "Dersten kayıt silindi.";
            LogYaz_DersKayitIptal($KULLANICI_ID, $COURSE_ID);
        }else{
            $statusCode = 500;
            throw new Exception("Dersten kayıt silinemedi.");
        }
    }else{
        $statusCode = 400;
        throw new Exception("Bilinmeyen method parametresi : $METOD");
    }
    
}catch(Throwable $exp){
    if($statusCode == 0){
        $statusCode = 500;
    }

    http_response_code($statusCode);

    $sonucObjesi->sonuc = false;
    $sonucObjesi->code = $statusCode;
    $sonucObjesi->hata = $exp->getMessage();
    $sonucObjesi->mesaj = $exp->getMessage();

    if($statusCode == 401 || $statusCode >= 500){
        $sonucObjesi->headers = getallheaders();
        $sonucObjesi->detay = $exp->getTraceAsString();
    }
}


echo json_encode($sonucObjesi);