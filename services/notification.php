<?php

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try {
    include '_api_key_kontrol.php';

    if (!isset($_GET["method"]) || $_GET["method"] == "") {
        $statusCode = 400;
        throw new Exception("method parametresi eksik!");
    }

    $method = $_GET["method"];

    $islem_sonucu = false;
    $mesaj = "";

    if ($method == "event_announcement") {
        $data = NULL;

        if (isset($_POST['data']) && $_POST['data'] != NULL) {
            $data = json_decode($data);
        }

        if ($data == NULL) {
            $statusCode = 400;
            throw new Exception("post içeriği boş olamaz!");
        }

        if ($data->ders_id == NULL || $data->ders_id == "" || $data->announcement == NULL || $data->announcement == "") {
            $statusCode = 400;
            throw new Exception("post içeriği eksik!");
        } else {
            $ders = DersBilgileriniGetir($data->ders_id);
            if ($ders != NULL && $ders["duzenleyen_id"] == $KULLANICI_ID) {
                //$duyuru_icerigi = $etkinlik["isim"]." - Duyuru : ".$data->announcement;
                DersDuyuruGonder($data->ders_id, $data->announcement);
                $sonucObjesi->sonuc = true;
            } else {
                $statusCode = 404;
                throw new Exception("ders bulunamadi!");
            }
        }
    } else if ($method == "notification_seen") {
        BildirimlerGorulduYap($KULLANICI_ID);
        $sonucObjesi->sonuc = true;
    } else if ($method == "get_notification") {
        $limit = 10;
        if (isset($_GET["limit"]) && $_GET["limit"] != "" && is_numeric($_GET["limit"])) {
            $limit = intval($_GET["limit"]);
        }

        $sonucObjesi->data = GetUserNotifications($KULLANICI_ID, $limit);
        $sonucObjesi->sonuc = true;
    } else if ($method == "set_firebase_token") {
        if (!isset($_GET["token"]) || $_GET["token"] == "") {
            $statusCode = 400;
            throw new Exception("token parametresi eksik!");
        }

        $token  = mysqli_real_escape_string($baglanti,  $_GET["token"]);

        KullaniciFirebaseTokenGuncelle($KULLANICI_ID, $token);
        $sonucObjesi->sonuc = true;
    } else {
        $statusCode = 400;
        throw new Exception("Desteklenmeyen metod " . $metod);
    }
} catch (Throwable $exp) {
    if ($statusCode == 0) {
        $statusCode = 500;
    }

    http_response_code($statusCode);

    $sonucObjesi->code = $statusCode;
    $sonucObjesi->hata = $exp->getMessage();
    $sonucObjesi->mesaj = $exp->getMessage();

    if ($statusCode == 401 || $statusCode >= 500) {
        $sonucObjesi->headers = getallheaders();
        $sonucObjesi->detay = $exp->getTraceAsString();
    }
}

echo json_encode($sonucObjesi);

?>