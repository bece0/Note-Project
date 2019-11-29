<?php

/**
 * Kullanıcıya ait son bildirimleri getirir
 * @param $count son kaç bildirim
 * @return array bildirim kayıtları
 */
function GetUserNotifications($kullanici_id, $count = 5)
{
    $sql = "SELECT bildirim.*, etkinlik.isim as etkinlik FROM bildirim 
            INNER JOIN etkinlik ON etkinlik.id=bildirim.etkinlik_id
            where kullanici_id = $kullanici_id  
            order by bildirim.id desc LIMIT $count ";
    //echo "SQL : ". $sql;
    return SQLCalistir($sql, FALSE);
}


/**
 * Etkinlikteki tüm kullanıcılarına DUYURU tipinden bildirimi gönderir
 * @param $etkinlik_id etkinlik id değeri
 * @param $mesaj bildirim içeriği
 * @param $url bildirim tıklaması sonucu açılacak adres
 * @return void
 */
function EtkinlikDuyuruGonder($etkinlik_id, $mesaj, $url = "")
{
    $sql_katilimcilar = "SELECT * from katilimci where etkinlik_id = $etkinlik_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);

    for ($i = 0; $i < count($katilimcilar); $i++) {
        $katilimci = $katilimcilar[$i];
        BildirimYaz($katilimci["kullanici_id"], $etkinlik_id, $mesaj, $url, "DUYURU");
    }
}

/**
 * Etkinlik katılımcılarına bildirim gönderir
 * @param $etkinlik_id etkinlik id değeri
 * @param $mesaj bildirim içeriği
 * @param $tip bildirim tipi, varsayılan değer: "NORMAL"
 * @param $url bildirim tıklaması sonucu açılacak adres
 * @return void
 */
function EtkinlikKatilimcilarinaBildirimGonder($etkinlik_id, string $mesaj, string $tip = "NORMAL", string $url = "")
{
    $sql_katilimcilar = "SELECT * from katilimci where etkinlik_id = $etkinlik_id";
    $katilimcilar = SQLCalistir($sql_katilimcilar, FALSE);

    for ($i = 0; $i < count($katilimcilar); $i++) {
        $katilimci = $katilimcilar[$i];
        BildirimYaz($katilimci["kullanici_id"], $etkinlik_id, $mesaj, $url, $tip);
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
function BildirimYaz($kullanici_id, $etkinlik_id, $mesaj, $url = "", $tip = "NORMAL") : bool
{
    $sql = "INSERT INTO bildirim (kullanici_id, etkinlik_id, mesaj, url, tip)
    VALUES ('$kullanici_id', '$etkinlik_id', '$mesaj', '$url', '$tip')";

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
