<?php

function DersDuyuruKaydet($ders_id, $kullanici_id, $mesaj) : bool
{
    $sql = "INSERT INTO duyuru (ders_id, kullanici_id, mesaj)
    VALUES ('$ders_id', '$kullanici_id', '$mesaj')";

    return SQLInsertCalistir($sql);
}

function DersDuyuruKaydet_Takvim($ders_id, $kullanici_id, $mesaj, $tarih, $tip = "DUYURU") : bool
{
    $sql = "INSERT INTO duyuru (ders_id, kullanici_id, mesaj, takvim_tarih, tip)
    VALUES ($ders_id, $kullanici_id, '$mesaj', '$tarih', '$tip')";

    return SQLInsertCalistir($sql);
}

function DersDuyurulariniGetir($ders_id, $count = 50)
{
    $sql = "SELECT duyuru.*, ogre.isim as etkinlik FROM bildirim 
            INNER JOIN kullanici ON kullanici.id=duyuru.kullanici_id
            where ders_id = $ders_id  
            order by duyuru.id desc LIMIT $count ";
    
    return SQLCalistir($sql, FALSE);
}

function DersDuyurulariniTipeGoreGetir($ders_id, $tip, $count = 50)
{
    $sql = "SELECT duyuru.*, ogre.isim as etkinlik FROM bildirim 
            INNER JOIN kullanici ON kullanici.id=duyuru.kullanici_id
            where ders_id = $ders_id  AND tip = '$tip'
            order by duyuru.id desc LIMIT $count ";
    
    return SQLCalistir($sql, FALSE);
}

function DuyuruBilgileriniGetir($duyuru_id)
{
    $sql = "SELECT * FROM duyuru where id = $duyuru_id ";
    return SQLTekliKayitGetir($sql);
}

function DuyuruSil($duyuru_id)
{
    $sql = "DELETE FROM duyuru where id = $duyuru_id ";
    return SQLDeleteCalistir($sql);
}


function OgrenciSinavDuyurulariniGetir($ogrenci_id){
    $sql = "SELECT d.id, d.mesaj, d.takvim_tarih, d.ders_id, dr.isim as ders_adi from duyuru d 
        INNER join dersler dr on d.ders_id=dr.id
        INNER join katilimci k on k.ders_id=dr.id 
        where k.ogrenci_id = $ogrenci_id AND d.tip= 'SINAV'";

    return SQLCalistir($sql, FALSE);
}

function OgretmenSinavDuyurulariniGetir($ogrenci_id){
    //TODO - değiştir..
    $sql = "SELECT d.* from duyuru d 
        INNER join dersler dr on d.ders_id=dr.id
        INNER join katilimci k on k.ders_id=dr.id 
        where k.ogrenci_id = $ogrenci_id AND d.tip= 'SINAV'";

    return SQLCalistir($sql, FALSE);
}

?>