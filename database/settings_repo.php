<?php

/**
 * 
 */
function KullaniciAyarlariniGetirById($kullanici_id){
    $sql = "SELECT * FROM ayarlar where kullanici_id = $kullanici_id";
    
    return SQLTekliKayitGetir($sql);
}

/**
 * ayarlar tablosuna yeni üye olan kullanıcı için bir kayır ekler.
 * Varsayılan ayarllar tablodaki varsayılan kolon değerleri ile dolar
 */
function KullaniciVarsayilanAyarlarKaydet($kullanici_id, $sehir = "Ankara"){
    $sql = "INSERT INTO ayarlar (kullanici_id, sehir) VALUES ('$kullanici_id', '$sehir')";
    return SQLInsertCalistir($sql);
}

function KullaniciAyarGuncelle($kullanici_id, $kolon_adi, $deger){
    //todo kolon degerine(string, sayi vs) göre sorgu degisebilir

    $ayarlar = KullaniciAyarlariniGetirById($kullanici_id);

    if($ayarlar == NULL)
        KullaniciVarsayilanAyarlarKaydet($kullanici_id);

    $sql = "UPDATE ayarlar SET $kolon_adi = '$deger' where kullanici_id = $kullanici_id";
    // echo $sql;
    // $sql2 = escape_string ($sql);

    return SQLUpdateCalistir($sql);
}
