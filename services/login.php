<?php

header('Content-type: application/json');


include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$sonucObjesi = new stdClass();;
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->code = "";
$sonucObjesi->id = 0;
$sonucObjesi->type = 0;

$statusCode = 0;

function GUIDOlustur()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


try{

    $EMAIL = "";
    $PASSWORD = "";

    $json = file_get_contents('php://input');
    if($json == NULL){
        $statusCode = 400;
        throw new Exception("Hatalı istek");
    }

    $data = json_decode($json);

    //var_dump($data);


    if(isset($data->email)){
        $EMAIL = mysqli_real_escape_string($baglanti, $data->email );
    }

    if(isset($data->pass)){
        $PASSWORD = mysqli_real_escape_string($baglanti, $data->pass );
    }


    $kullanici = KullaniciBilgileriniGetir($EMAIL);

    if($kullanici == NULL){
        $statusCode = 401;
        throw new Exception("Giris bilgileri hatali (1).");
    }

    $salt = $kullanici['salt'];
    $salt_ve_parola = $salt . $PASSWORD; 
    $hashlenmis_parola = hash('sha512', $salt_ve_parola); 


    if($hashlenmis_parola == $kullanici['parola']){
        $API_KEY = GUIDOlustur();

        KullaniciApiKeyGuncelle($kullanici["id"], $API_KEY);
        $sonucObjesi->api_key = $API_KEY;
        $sonucObjesi->sonuc = true;
        $sonucObjesi->code = $kullanici['kodu'];
        $sonucObjesi->id = $kullanici['id'];
        $sonucObjesi->type = $kullanici['admin'];
        $sonucObjesi->name = $kullanici['adi'];
        $sonucObjesi->surname = $kullanici['soyadi'];

    }else{
        $statusCode = 401;
        throw new Exception("Giris bilgileri hatali (2).");
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