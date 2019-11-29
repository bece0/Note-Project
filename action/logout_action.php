<?php
    session_start();

    if(isset($_SESSION["kullanici_id"])){ //giriş yapılmış ise login'e git
        $kullanici_id = $_SESSION["kullanici_id"];
        if($kullanici_id != NULL) {
            include '../database/database.php';
            $KULLANICI = KullaniciBilgileriniGetirById($kullanici_id);
    
            $log_mesaj = $KULLANICI["adi"]." ".$KULLANICI["soyadi"]." çıkış yaptı.";
            LogYaz_KullaniciCikisi($kullanici_id, $log_mesaj);
        }
    }

    session_unset();  
    header('Location: ../login.php'); //Çıkış yapılır,Login sayfasına yönlendirir
?>