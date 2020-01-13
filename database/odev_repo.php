<?php 

function DersOdevleriniGetir($ders_id){
    $sql = "SELECT o.*, kullanici.adi, kullanici.soyadi FROM odev o
        INNER JOIN kullanici ON kullanici.id=o.olusturan_id
        where o.ders_id = $ders_id";
    
    return SQLCalistir($sql);
}

?>