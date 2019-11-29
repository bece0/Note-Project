<?php

session_start();
if (!isset($_SESSION["kullanici_id"])) {
    header('Location: dashboard.php');
}

if (!isset($_POST["event_id"]))
    header('Location: ../index.php');

$event_id = $_POST["event_id"];

include '../database/database.php';

$event_detail = EtkinlikBilgileriniGetir($event_id);
$etkinlik_kodu =  $event_detail["kodu"];

if ($event_detail["duzenleyen_id"] != $_SESSION["kullanici_id"] && $_SESSION["admin"] != 1) {
    //giriş yapmış olan kullanıcı etkinliği oluşturan kişi değilse ve admin değilse
    die();
}

if (EtkinlikSil($event_id) === TRUE) {

    $resim_adi = $etkinlik_kodu . ".png";
    $resim_path =  __DIR__ . "\\..\\files\\images\\event\\" . $resim_adi;
    if (file_exists($resim_path))
        $deleted = unlink($resim_path); //etkinlik resmi de siliniyor.

    // $icerik =  $event_detail["isim"] . " etkinliği iptal edildi";
    EtkinlikKatilimcilarinaBildirimGonder($event_id, "", "ETKINLIK_IPTAL");
    
    LogYaz_EtkinlikSilme($_SESSION["kullanici_id"], $event_id);

    $_SESSION["_success"] = "Etkinlik silindi";
    header('Location: ../index.php');
} else {
    $_SESSION["_error"] = "Etkinlik düzenlenirken bir hata oluştu";
    header('Location: ../index.php');
}
