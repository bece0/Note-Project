<?php
session_start();

header('Content-type: application/json');
$sonuc = array(
    "sonuc" => false
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

$data = NULL;
if (isset($_POST['data']) && $_POST['data'] != NULL) {
    // http_response_code(400);
    // $sonuc["mesaj"] = "post data parametresi eksik!";
    // echo json_encode($sonuc);
    //$data = utf8_encode($_POST['data']);
    $data = json_decode($data);
}

$method = $_GET["method"];


include '../database/database.php';

$kullanici_id = $_SESSION["kullanici_id"];

$islem_sonucu = false;
$mesaj = "";
if ($method == "event_announcement") {
    if ($data == NULL) {
        http_response_code(400);
        $sonuc["mesaj"] = "post data içeriği boş";
        echo json_encode($sonuc);
        die();
    }

    if ($data->ders_id == NULL || $data->ders_id == "" || $data->announcement == NULL || $data->announcement == "") {
        $mesaj = "data eksik";
    } else {
        $ders = DersBilgileriniGetir($data->ders_id);
        if ($ders != NULL && $ders["duzenleyen_id"] == $kullanici_id) {
            //$duyuru_icerigi = $etkinlik["isim"]." - Duyuru : ".$data->announcement;
            DersDuyuruGonder($data->ders_id, $data->announcement);
            $islem_sonucu = true;
        } else {
            var_dump($data);
            $mesaj = "ders bulunamadi " . $data->ders_id;

            die();
        }
    }
} else if ($method == "notification_seen") {
    BildirimlerGorulduYap($kullanici_id);
} else if ($method == "profil") { }

$sonuc = array(
    "sonuc" => $islem_sonucu,
    "method" => $method,
    "mesaj" => $mesaj
);

echo json_encode($sonuc);
