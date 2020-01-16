<?php 

function DersDokumanlariniGetir($ders_id){
    $sql = "SELECT o.*, kullanici.adi, kullanici.soyadi FROM dokuman o
        INNER JOIN kullanici ON kullanici.id=o.olusturan_id
        where o.ders_id = $ders_id ORDER BY o.olusturma_tarih DESC";
    
    return SQLCalistir($sql);
}

function DersDokumanKaydet($kod, $ders_id, $olusturan_id, $dosya_id, $isim, $aciklama) : bool
{
    $sql = "INSERT INTO dokuman (kod, ders_id, olusturan_id, dosya_id, isim, aciklama)
    VALUES ('$kod', '$ders_id', '$olusturan_id', '$dosya_id', '$isim', '$aciklama')";

    return SQLInsertCalistir($sql);
}

function GetDokumanByKod($kod){
    $sql = "SELECT * FROM dokuman where kod='$kod'";
    return SQLTekliKayitGetir($sql);
}

?>