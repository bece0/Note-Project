<?php

    session_start();
    // if(!isset($_SESSION["email"])){
    //     header('Location: dashboard.php'); 
    // }

    $kullanici_id=$_SESSION["kullanici_id"];
    $etkinlik_id=$_POST["event_id"];

    var_dump($etkinlik_id);

    if(!isset($kullanici_id) || !isset($etkinlik_id)){
        $_SESSION["_error"] = "Eksik bilgi.";
        header('Location: ../info.php'); 
    }

    include '../database/database.php';

    if(EtkinligiIptalEt($kullanici_id, $etkinlik_id) === TRUE){
        $_SESSION["_success"]="İptal edildi..";

        LogYaz_EtkinlikKayitIptal($kullanici_id, $etkinlik_id);

        header('Location: ../event.php?event='.$etkinlik_id); 
    }else {
        $_SESSION["_error"]="Bir hata oluştu. Lütfen tekrar deneyin.";
    }
?>