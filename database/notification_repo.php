<?php

/**
 * Kullanıcıya ait son bildirimleri getirir
 * @param $count son kaç bildirim
 * @return array bildirim kayıtları
 */
function GetUserNotifications($kullanici_id, $count = 20)
{
    
    $sql = "SELECT bildirim.*, dersler.isim as ders FROM bildirim 
            INNER JOIN dersler ON dersler.id=bildirim.ders_id
            where kullanici_id = $kullanici_id  
            order by bildirim.id desc LIMIT $count ";
    //echo "SQL : ". $sql;
    return SQLCalistir($sql, FALSE);
}

function BildirimListesineGonder($tip="DUYURU", $katilimcilar, $ders_id, $mesaj = "", $url="", $haricListesi= []){
    for ($i = 0; $i < count($katilimcilar); $i++) {
        $katilimci = $katilimcilar[$i];
        
        if(in_array($katilimci["ogrenci_id"], $haricListesi))
            continue;
       
        BildirimYaz($katilimci["ogrenci_id"], $ders_id, $mesaj, $url, $tip);
    }
}


/**
 * Etkinlikteki tüm kullanıcılarına DUYURU tipinden bildirimi gönderir
 * @param $etkinlik_id etkinlik id değeri
 * @param $mesaj bildirim içeriği
 * @param $url bildirim tıklaması sonucu açılacak adres
 * @return void
 */
function DersKatilimcilarinaDuyuruBildirimiGonder($ders_id, $mesaj = "", $url = "", $haricListesi= [])
{
    $sql_katilimcilar = "SELECT * from katilimci where ders_id = $ders_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);
    $ders = DersBilgileriniGetir($ders_id);
    if($mesaj == NULL || $mesaj == "")
        $mesaj =  $ders["isim"]." dersinde yeni duyuru yapıldı";

    BildirimListesineGonder("DUYURU", $katilimcilar, $ders_id, $mesaj, $url, $haricListesi);
    // for ($i = 0; $i < count($katilimcilar); $i++) {
    //     $katilimci = $katilimcilar[$i];
    //     if(in_array($katilimci["ogrenci_id"], $haricListesi))
    //         continue;
    //     BildirimYaz($katilimci["ogrenci_id"], $ders_id, $mesaj, $url, "DUYURU");
    // }
}

function DersKatilimcilarinaYeniOdevBildirimiGonder($ders_id, $odev_adi, $mesaj = "", $url = "", $haricListesi= [])
{
    $sql_katilimcilar = "SELECT * from katilimci where ders_id = $ders_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);

    $ders = DersBilgileriniGetir($ders_id);
    if($mesaj == NULL || $mesaj == "")
        $mesaj =  $ders["isim"]." dersine yeni ödev eklendi : $odev_adi";

    BildirimListesineGonder("YENI_ODEV", $katilimcilar, $ders_id, $mesaj, $url, $haricListesi);
    // for ($i = 0; $i < count($katilimcilar); $i++) {
    //     $katilimci = $katilimcilar[$i];
    //     if(in_array($katilimci["ogrenci_id"], $haricListesi))
    //         continue;
    //     BildirimYaz($katilimci["ogrenci_id"], $ders_id, $mesaj, $url, "YENI_ODEV");
    // }
}

function DersKatilimcilarinaYeniSinavBildirimiGonder($ders_id, $sinav_adi, $mesaj = "", $url = "", $haricListesi= [])
{
    $sql_katilimcilar = "SELECT * from katilimci where ders_id = $ders_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);

    $ders = DersBilgileriniGetir($ders_id);
    if($mesaj == NULL || $mesaj == "")
        $mesaj =  $ders["isim"]." dersine yeni sınav eklendi : $sinav_adi";

    BildirimListesineGonder("SINAV", $katilimcilar, $ders_id, $mesaj, $url, $haricListesi);
    // for ($i = 0; $i < count($katilimcilar); $i++) {
    //     $katilimci = $katilimcilar[$i];
    //     if(in_array($katilimci["ogrenci_id"], $haricListesi))
    //         continue;
    //     BildirimYaz($katilimci["ogrenci_id"], $ders_id, $mesaj, $url, "SINAV");
    // }
}

function DersKatilimcilarinaYeniDokumanBildirimiGonder($ders_id, $dokuman_adi, $mesaj = "", $url = "", $haricListesi= [])
{
    $sql_katilimcilar = "SELECT * from katilimci where ders_id = $ders_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);

    $ders = DersBilgileriniGetir($ders_id);
    if($mesaj == NULL || $mesaj == "")
        $mesaj =  $ders["isim"]." dersine yeni doküman eklendi : $dokuman_adi";

    BildirimListesineGonder("YENI_DOKUMAN", $katilimcilar, $ders_id, $mesaj, $url, $haricListesi);
    // for ($i = 0; $i < count($katilimcilar); $i++) {
    //     $katilimci = $katilimcilar[$i];
    //     if(in_array($katilimci["ogrenci_id"], $haricListesi))
    //         continue;
    //     BildirimYaz($katilimci["ogrenci_id"], $ders_id, $mesaj, $url, "YENI_DOKUMAN");
    // }
}

function DersHocalarinaYorumBildirimiGonder($ders_id, $yorum_yapan_adi_soyadi, $mesaj = "", $url = "", $haricListesi= [])
{
    $sql_asistanlar = "SELECT * from katilimci where ders_id = '$ders_id' AND tip = 1";
    $asistan_katilimcilar = SQLCalistir($sql_asistanlar, FALSE);

    $ders = DersBilgileriniGetir($ders_id);

    if($mesaj == NULL || $mesaj == "")
        $mesaj =  $ders["isim"]." dersine $yorum_yapan_adi_soyadi tarafından yeni yorum eklendi.";
    else {
        $mesaj_preview = "\"".substr($mesaj, 0, 100)."\"";
        $mesaj =  $ders["isim"]." dersine $yorum_yapan_adi_soyadi tarafından yeni yorum eklendi. $mesaj_preview";
    }

    BildirimListesineGonder("YENI_YORUM", $katilimcilar, $ders_id, $mesaj, $url, $haricListesi);
    // for ($i = 0; $i < count($asistan_katilimcilar); $i++) {
    //     $asistan = $asistan_katilimcilar[$i];
    //     if(in_array($asistan["ogrenci_id"], $haricListesi))
    //         continue;
    //     BildirimYaz($asistan["ogrenci_id"], $ders_id, $mesaj, $url, "YENI_YORUM");
    // }
    if(!in_array($ders["duzenleyen_id"], $haricListesi)){
        BildirimYaz($ders["duzenleyen_id"], $ders_id, $mesaj, $url, "YENI_YORUM");
    }
}

function DersDuyuruGonder($ders_id, $mesaj, $url = "")
{
    $sql_katilimcilar = "SELECT * from katilimci where ders_id = $ders_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);

    BildirimListesineGonder("DUYURU", $katilimcilar, $ders_id, $mesaj, $url);
    // for ($i = 0; $i < count($katilimcilar); $i++) {
    //     $katilimci = $katilimcilar[$i];
    //     BildirimYaz($katilimci["kullanici_id"], $ders_id, $mesaj, $url, "DUYURU");
    // }
}

/**
 * Etkinlik katılımcılarına bildirim gönderir
 * @param $etkinlik_id etkinlik id değeri
 * @param $mesaj bildirim içeriği
 * @param $tip bildirim tipi, varsayılan değer: "NORMAL"
 * @param $url bildirim tıklaması sonucu açılacak adres
 * @return void
 */
function EtkinlikKatilimcilarinaBildirimGonder($ders_id, string $mesaj, string $tip = "NORMAL", string $url = "")
{
    $sql_katilimcilar = "SELECT * from katilimci where ders_id = $ders_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);

    for ($i = 0; $i < count($katilimcilar); $i++) {
        $katilimci = $katilimcilar[$i];
        BildirimYaz($katilimci["kullanici_id"], $ders_id, $mesaj, $url, $tip);
    }
}

/**
 * bildirim tablosuna veri yazar
 * @param $kullanici_id kullanici id değeri
 * @param $etkinlik_id etkinlik id değeri
 * @param $mesaj bildirim mesajı
 * @param $tip bildirim tipi, varsayılan değer: "NORMAL"
 * @param $url bildirim tıklaması sonucu açılacak adres
 * @return bool işlem başarılı ise TRUE, değil ise FALSE döner.
 */
function BildirimYaz($kullanici_id, $ders_id, $mesaj, $url = "", $tip = "NORMAL") : bool
{
    $sql = "INSERT INTO bildirim (kullanici_id, ders_id, mesaj, url, tip)
    VALUES ('$kullanici_id', '$ders_id', '$mesaj', '$url', '$tip')";

    return SQLInsertCalistir($sql);
}

/**
 * Kullanciya ait tüm bildirimleri okundu olarak işaretler
 * @param $kullanici_id kullanici id değeri
 * @return bool işlem başarılı ise TRUE, değil ise FALSE döner.
 */
function BildirimlerGorulduYap($kullanici_id)
{
    $sql = "UPDATE bildirim SET goruldu = 1 where kullanici_id = $kullanici_id";

    return SQLUpdateCalistir($sql);
}
