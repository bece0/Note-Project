<?php

function DersDuyuruKaydet($ders_id, $kullanici_id, $mesaj) : bool
{
    $sql = "INSERT INTO duyuru (ders_id, kullanici_id, mesaj)
    VALUES ('$ders_id', '$kullanici_id', '$mesaj')";

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

?>