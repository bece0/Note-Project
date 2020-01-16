<?php 

function DosyaSil($dosya_id)
{
    $sql = "DELETE FROM dosya where id = " . $dosya_id . "";
    return SQLDeleteCalistir($sql);
}

function DosyaEkle($kod, $yukleyen_id, $isim, $dosya_adi, $indirme_link)
{
    $sql = "INSERT INTO dosya (kod, isim, yukleyen_id, dosya_adi, indirme_link)
    VALUES ('$kod', '$isim', '$yukleyen_id', '$dosya_adi', '$indirme_link')";

    if(SQLInsertCalistir($sql)){
        $result = SQLTekliKayitGetir("SELECT LAST_INSERT_ID() as id");
        if($result != NULL)
            return $result["id"];
    }

    return NULL;
}

function GetDosyaById($id){
    $sql = "SELECT * FROM dosya where id='$id'";
    return SQLTekliKayitGetir($sql);
}



?>