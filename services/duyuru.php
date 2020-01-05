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

include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$mesaj = NULL;
if (isset($_POST["mesaj"]) && $_POST['mesaj'] != NULL) {
    // http_response_code(400);
    // $sonuc["mesaj"] = "post data parametresi eksik!";
    // echo json_encode($sonuc);
    //$data = utf8_encode($_POST['data']);
    $mesaj =  mysqli_real_escape_string($baglanti, $_POST["mesaj"]);
}

$ders_id = $_POST["ders_id"];
 
$kullanici_id = $_SESSION["kullanici_id"];

$islem_sonucu = false;
$islem_mesaj = "";

if ( $mesaj == NULL || $mesaj == "") {
    http_response_code(400);
    $sonuc["mesaj"] = "post data içeriği boş";
    echo json_encode($sonuc);
    die();
}

if ($ders_id == NULL || $ders_id == "") {
    $islem_mesaj = "duyuru içeriği eksik";
} else {
    $ders = DersBilgileriniGetir($ders_id);

    if ($ders != NULL && $ders["duzenleyen_id"] == $kullanici_id) {
        //$duyuru_icerigi = $etkinlik["isim"]." - Duyuru : ".$data->announcement;
        DersDuyuruKaydet($ders_id, $kullanici_id, $mesaj);
        DersDuyuruBildirimiGonder($ders_id, $mesaj);
        $islem_sonucu = true;
    } else {
        var_dump($data);
        $islem_mesaj = "ders bulunamadi " . $ders_id;

        die();
    }
}


$sonuc = array(
    "sonuc" => $islem_sonucu,
    "mesaj" => $islem_mesaj
);

echo json_encode($sonuc);
