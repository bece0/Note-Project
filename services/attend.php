<?php
 
 $sonucObjesi = new stdClass();
 $sonucObjesi->sonuc = false;
 $sonucObjesi->mesaj = "";
 $sonucObjesi->code = 200;
 $sonucObjesi->data = new stdClass();
 
 try{
     include '_api_key_kontrol.php';

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
        $sonucObjesi->mesaj = "Kayıt olundu.";
        LogYaz_DersKayit($KULLANICI_ID, $COURSE_ID);
    }else{
        $statusCode = 500;
        throw new Exception("Derse kayıt olunamadı.");
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