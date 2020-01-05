<?php 

/**
 * Etkinliğe ait ONAYLANMIŞ tüm yorumları getirir.
 */
function GetEventApprovedComments($ders_id){
    $sql = "SELECT yorum.*, kullanici.adi, kullanici.soyadi FROM yorum 
        INNER JOIN kullanici ON kullanici.id=yorum.kullanici_id
        where yorum.ders_id = $ders_id and yorum.onay_durum = 1";
    
    return SQLCalistir($sql);
}

/**
 * Etkinliğe ait tüm yorumları getirir.
 */
function GetEventAllComments($ders_id){
    $sql = "SELECT yorum.*, kullanici.adi, kullanici.soyadi FROM yorum 
        INNER JOIN kullanici ON kullanici.id=yorum.kullanici_id
        where yorum.ders_id = $ders_id";
    
    return SQLCalistir($sql);
}

/**
 * Etkinliğe ait ONAYLANMAMIŞ tüm yorumları getirir.
 */
function GetEventUnApprovedComments($ders_id){
    $sql = "SELECT yorum.*, kullanici.adi, kullanici.soyadi FROM yorum 
        INNER JOIN kullanici ON kullanici.id=yorum.kullanici_id
        where yorum.ders_id = $ders_id and yorum.onay_durum = 0";
    
    return SQLCalistir($sql);
}

function GetUserAllComments($user_id){
    $sql = "SELECT yorum.*, kullanici.adi, kullanici.soyadi FROM yorum 
        INNER JOIN kullanici ON kullanici.id=yorum.kullanici_id
        where yorum.kullanici_id=$user_id";
    
    return SQLCalistir($sql);
}

function GetCommentById($comment_id){
    $sql = "SELECT yorum.*, kullanici.adi, kullanici.soyadi FROM yorum 
        INNER JOIN kullanici ON kullanici.id=yorum.kullanici_id
        where yorum.id=$comment_id";

    return SQLTekliKayitGetir($sql);
}

function AddComment($user_id, $ders_id, $comment_text) : bool{
    $sql = "INSERT INTO yorum (kullanici_id, ders_id, icerik)
    VALUES ('$user_id', '$ders_id', '$comment_text')";
    
    return SQLInsertCalistir($sql); 
}

function DeleteComment($comment_id){
    $sql = "DELETE FROM yorum  WHERE  id=$comment_id";

    return SQLDeleteCalistir($sql);
}

function ApproveComment($comment_id, $approved_by){
    $sql = "UPDATE yorum SET onay_durum = 1, onay_tarih = CURDATE(), onaylayan_id = $approved_by where id=$comment_id";

    return SQLUpdateCalistir($sql);
}

function UnApproveComment($comment_id){
    $sql = "UPDATE yorum SET onay_durum = 0 where id=$comment_id";

    return SQLUpdateCalistir($sql);
}

function UpdateCommentContent($comment_id, $content){
    $sql = "UPDATE yorum SET icerik = '$content' where id=$comment_id";

    return SQLUpdateCalistir($sql);
}

?>