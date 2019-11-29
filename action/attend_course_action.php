<?php
 
    session_start();
    if(!isset($_SESSION["email"])){
        header('Location: dashboard.php'); 
    }

    $kullanici_id=$_SESSION["kullanici_id"];
    $ders_kod=$_POST["kod"];

    

    if(!isset($kullanici_id) || !isset($ders_kod)){
        $_SESSION["_error"] = "Eksik bilgi.";
        header('Location: ../info.php'); 
    }

    include '../database/database.php';
    $ders_id = DersIdBul($ders_kod);
    if(DerseKayitOl($kullanici_id, $ders_id["id"]) === TRUE){
        $_SESSION["_success"]="Kayıt olundu.";
        LogYaz_EtkinlikKayit($kullanici_id, $etkinlik_id);
        
        header('Location: ../course.php?course='.$ders_id["id"]); 
    }else {
        $_SESSION["_error"]="Bir hata oluştu. Lütfen tekrar deneyin.";
    }