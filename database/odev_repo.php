<?php 

function DersOdevleriniGetir($ders_id){
    $sql = "SELECT o.*, kullanici.adi, kullanici.soyadi FROM odev o
        INNER JOIN kullanici ON kullanici.id=o.olusturan_id
        WHERE o.ders_id = $ders_id ORDER BY o.olusturma_tarih DESC";
    
    return SQLCalistir($sql);
}

function DersOdevKaydet($kod, $ders_id, $olusturan_id, $dosya_id, $isim, $aciklama, $son_tarih, $dosya_gonderme) : bool
{
    $sql = "INSERT INTO odev (kod, ders_id, olusturan_id, dosya_id, isim, aciklama, son_tarih, dosya_gonderme)
    VALUES ('$kod', '$ders_id', '$olusturan_id', '$dosya_id', '$isim', '$aciklama', '$son_tarih', '$dosya_gonderme')";

    return SQLInsertCalistir($sql);
}


function GetOdevByKod($kod){
    $sql = "SELECT * FROM odev where kod='$kod'";
    return SQLTekliKayitGetir($sql);
}

function GetOdevDetailsByKod($kod){
    $sql = "SELECT o.*, kullanici.adi, kullanici.soyadi FROM odev o
        INNER JOIN kullanici ON kullanici.id = o.olusturan_id
        WHERE o.kod='$kod'";

    return SQLTekliKayitGetir($sql);
}

?>