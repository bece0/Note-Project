<?php

header('Content-type: application/json');


include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$sonucObjesi = new stdClass();;
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";

$statusCode = 0;

function GUIDOlustur()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function generateSalt($max = 64) {
    $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
    $i = 0;
    $salt = "";
    while ($i < $max) {
        $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
        $i++;
    }
    return $salt;
}


try{

    $EMAIL = "";
    $PASSWORD = "";
    $NAME = "";
    $SURNAME = "";

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

    if(isset($data->name)){
        $NAME = mysqli_real_escape_string($baglanti, $data->name );
    }

    if(isset($data->surname)){
        $SURNAME = mysqli_real_escape_string($baglanti, $data->surname );
    }


    $kullanici = KullaniciBilgileriniGetir($EMAIL);

    if ($kullanici != NULL) {
        $statusCode = 400;
        throw new Exception("Bu email adresi başka hesaba ait.");
    }

    $salt = generateSalt();
    $salt_ve_parola = $salt . $PASSWORD; // salt ve kullanıcının belirlediği parola birleştiriliyor.
    $hashlenmis_parola = hash('sha512',$salt_ve_parola); 
    $kodu = GUIDOlustur();

    if(KullaniciKaydet($kodu, $NAME, $SURNAME, $EMAIL, $hashlenmis_parola, $salt, 0) === TRUE){
        $sonucObjesi->mesaj = "Hesabınız oluşturuldu. Giriş yapabilirsiniz.";
        
        $kullanici = KullaniciBilgileriniGetir($EMAIL);

        KullaniciVarsayilanAyarlarKaydet($kullanici["id"]);
        LogYaz_KullaniciKayit($kullanici["id"]);

        $API_KEY = GUIDOlustur();
        KullaniciApiKeyGuncelle($kullanici["id"], $API_KEY);
        $sonucObjesi->api_key = $API_KEY;
        $sonucObjesi->sonuc = true;
    }else {
        throw new Exception("Bir hata oluştu. Lütfen tekrar deneyin.");
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