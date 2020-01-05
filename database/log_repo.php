<?php 

// $LOG_TIP_KULLANICI = "user";
// $LOG_TIP_ETKINLIK = "event";
// $LOG_TIP_SISTEM = "system";

$USER_LOGIN = "LOGIN";
$USER_LOGOUT = "LOGOUT";

$LOG_COURSE_CREATE = "COURSE_CREATE";
$LOG_COURSE_EDIT = "COURSE_EDIT";
$LOG_COURSE_DELETE = "COURSE_DELETE";
$LOG_COURSE_REGISTER = "COURSE_REGISTER";
$LOG_COURSE_REGISTER_CANCEL = "COURSE_REGISTER_CANCEL";


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

function LogYaz_DersKayit($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu etkinliğine katıldı.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "COURSE_REGISTER", $mesaj);
}

function LogYaz_DersKayitIptal($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu dersten çıktı.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "COURSE_REGISTER_CANCEL", $mesaj);
}

function LogYaz_DersOlusturma($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu ders oluşturdu.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "COURSE_CREATE", $mesaj);
}

function LogYaz_DersDuzenleme($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu dersi düzenledi.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "COURSE_EDIT", $mesaj);
}

function LogYaz_DersSilme($kullanici_id, $etkinlik_id, $mesaj = "")
{   
    if($mesaj == NULL || $mesaj == "")
        $mesaj = $kullanici_id." id nolu kullanıcı $etkinlik_id nolu dersi sildi.";

    return LogYaz($kullanici_id, $etkinlik_id, "event", "COURSE_DELETE", $mesaj);
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
function LogGetir_Ders($limit = 500){
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