<?php 

// $LOG_TIP_KULLANICI = "user";
// $LOG_TIP_ETKINLIK = "event";
// $LOG_TIP_SISTEM = "system";

$USER_LOGIN = "LOGIN";
$USER_LOGOUT = "LOGOUT";

$LOG_EVENT_CREATE = "EVENT_CREATE";
$LOG_EVENT_EDIT = "EVENT_EDIT";
$LOG_EVENT_DELETE = "EVENT_DELETE";
$LOG_EVENT_REGISTER = "EVENT_REGISTER";
$LOG_EVENT_REGISTER_CANCEL = "EVENT_REGISTER_CANCEL";


function SistemLogYaz($baslik, $mesaj){
    return GenelLogYaz("system", $baslik, $mesaj);
}


function GenelLogYaz($tip, $baslik, $mesaj){
    $sql = "INSERT INTO gunluk (kullanici_id, etkinlik_id, tarih, tip, baslik, mesaj)
    VALUES (0, 0 , CURRENT_TIMESTAMP(), '$tip', '$baslik', '$mesaj')";

    return SQLInsertCalistir($sql);
}


function LogYaz($kullanici_id, $etkinlik_id, $tip, $baslik, $mesaj){
    $sql = "INSERT INTO gunluk (kullanici_id, etkinlik_id, tarih, tip, baslik, mesaj)
    VALUES ('$kullanici_id', '$etkinlik_id', CURRENT_TIMESTAMP(), '$tip', '$baslik', '$mesaj')";

    return SQLInsertCalistir($sql);
}

function LogYaz_KullaniciKayit($kullanici_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id numaralı kullanıcı üye oldu.";

    return LogYaz($kullanici_id, 0, "user", "NEW_USER", $mesaj);
}

function LogYaz_KullaniciGirisi($kullanici_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id numaralı kullanıcı giriş yaptı.";

    return LogYaz($kullanici_id, 0, "user", "LOGIN", $mesaj);
}

function LogYaz_KullaniciCikisi($kullanici_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı çıkış yaptı.";
    
    return LogYaz($kullanici_id, 0, "user", "LOGOUT", $mesaj);
}

function LogYaz_EtkinlikKayit($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu etkinliğine katıldı.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "EVENT_REGISTER", $mesaj);
}

function LogYaz_EtkinlikKayitIptal($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu etkinlikten çıktı.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "EVENT_REGISTER_CANCEL", $mesaj);
}

function LogYaz_EtkinlikOlusturma($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu etkinliği oluşturdu.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "EVENT_CREATE", $mesaj);
}

function LogYaz_EtkinlikDuzenleme($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu etkinliği düzenledi.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "EVENT_EDIT", $mesaj);
}

function LogYaz_EtkinlikSilme($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu etkinliği sildi.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "EVENT_DELETE", $mesaj);
}

/**
 * tipi user olan tüm logları döner
 */
function LogGetir_Kullanici($limit = 500){
    $sql = "SELECT * FROM gunluk WHERE tip = 'user'  order by id desc LIMIT $limit";
    return SQLCalistir($sql);
}

/**
 * tipi event olan tüm logları döner
 */
function LogGetir_Etkinlik($limit = 500){
    $sql = "SELECT * FROM gunluk WHERE tip = 'event' order by id desc LIMIT $limit";
    return SQLCalistir($sql);
}

/**
 * tipi system olan tüm logları döner
 */
function LogGetir_Sistem($limit = 500){
    $sql = "SELECT * FROM gunluk WHERE tip = 'system' order by id desc LIMIT $limit";
    return SQLCalistir($sql);
}

/**
 * hata tablosu kayitlarini döner
 */
function HataGetir($limit = 500){
    $sql = "SELECT * FROM hata WHERE order by id desc LIMIT $limit";
    return SQLCalistir($sql);
}

?>