<?php
session_start();

header('Content-type: application/json');
$sonuc = array(
    "istek_basarili" => false
);

//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
if (!isset($_SESSION["kullanici_id"])) {
    http_response_code(400);
    $sonuc["mesaj"] = "oturum hatasi";
    echo json_encode($sonuc);
    die();
}

if (!isset($_GET["method"]) || $_GET["method"] == "") {
    //echo "method parametresi eksik!";
    http_response_code(400);
    $sonuc["mesaj"] = "method parametresi eksik!";
    echo json_encode($sonuc);
    die();
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
    http_response_code(400);
    $sonuc["mesaj"] = "post data içeriği boş";
    echo json_encode($sonuc);
    die();
}

include '../database/database.php';

$giris_yapan_kullanici = $_SESSION["kullanici_id"];

$islem_sonucu = true;
$mesaj = "";

 if ($method == "gizlilik") {
    if ($data->profil_private != NULL)
        KullaniciAyarGuncelle($giris_yapan_kullanici, "profil_private", $data->profil_private);

} else if ($method == "profil") {
    if ($data->sehir != NULL)
        KullaniciAyarGuncelle($giris_yapan_kullanici, "sehir", $data->sehir);

} else if ($method == "profile-pic") {

    if (!isset($data->base64) || $data->base64 == "") {
        $islem_sonucu = false;
        $mesaj =  "base64 bos";
    } else {
        //$data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data["base64"]));
        $fileName = $giris_yapan_kullanici . ".png";
        $filePath =  __DIR__ . "/../files/profile/" . $fileName;

        if (file_exists($filePath))
            $deleted= unlink($filePath);

        $img = str_replace('data:image/png;base64,', '', $data->base64);
        $img = str_replace(' ', '+', $img);
        $base64Data = base64_decode($img);

        file_put_contents($filePath, $base64Data);
    }
} else if ($method == "password") {
    if ($data->password == NULL || $data->password == "" || strlen($data->password) < 6) {
        $mesaj = "yeni parola hatali";
    } else {
        KullaniciParolaGuncelle($giris_yapan_kullanici, $data->password, TRUE);
    }
}


if ($islem_sonucu == false)
    http_response_code(400);

$sonuc = array(
    "sonuc" => $islem_sonucu,
    "method" => $method,
    "mesaj" => $mesaj
);

echo json_encode($sonuc);
