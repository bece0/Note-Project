<?php
$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try{
    
    include '_api_key_kontrol.php';


    if (!isset($_GET["method"]) || $_GET["method"] == "") {
        $statusCode = 400;
        throw new Exception("method parametresi eksik!");
    }

    if (!isset($_POST['data']) || $_POST['data'] == NULL) {
        http_response_code(400);
        $sonuc["mesaj"] = "post data parametresi eksik!";
        echo json_encode($sonuc);
        die();
    }

    $method = $_GET["method"];
    $data = utf8_encode($_POST['data']);
    $data = json_decode($data);

    if ($data == NULL) {
        $statusCode = 400;
        throw new Exception("post data içeriği boş");
    }

    $mesaj = "";

    if ($method == "gizlilik") {
        if ($data->profil_private != NULL)
            KullaniciAyarGuncelle($KULLANICI_ID, "profil_private", $data->profil_private);
        $sonucObjesi->sonuc = true;
    } else if ($method == "profil") {
        if ($data->sehir != NULL)
            KullaniciAyarGuncelle($KULLANICI_ID, "sehir", $data->sehir);
        $sonucObjesi->sonuc = true;
    } else if ($method == "profile-pic") {

        if (!isset($data->base64) || $data->base64 == "") {
            $statusCode = 400;
            throw new Exception("base64 parametresi eksik!");
        } else {
            //$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data["base64"]));
            $fileName = $KULLANICI_ID . ".png";
            $filePath =  __DIR__ . "/../files/profile/" . $fileName;

            if (file_exists($filePath))
                $deleted= unlink($filePath);

            $img = str_replace('data:image/png;base64,', '', $data->base64);
            $img = str_replace(' ', '+', $img);
            $base64Data = base64_decode($img);

            file_put_contents($filePath, $base64Data);
            $sonucObjesi->sonuc = true;
        }
    } else if ($method == "password") {
        if ($data->password == NULL || $data->password == "" || strlen($data->password) < 3) {
            $statusCode = 400;
            throw new Exception("yeni parola hatali");
        } else {
            KullaniciParolaGuncelle($KULLANICI_ID, $data->password, TRUE);
            $sonucObjesi->sonuc = true;
        }
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

?>
