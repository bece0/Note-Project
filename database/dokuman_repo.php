<?php 

function DersDokumanlariniGetir($ders_id){
    $sql = "SELECT d.*, kullanici.adi, kullanici.soyadi FROM dokuman d
        INNER JOIN kullanici ON kullanici.id=d.olusturan_id
        where d.ders_id = $ders_id";
    
    return SQLCalistir($sql);
}

?>