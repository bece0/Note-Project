<?php
 
    session_start();
    if(!isset($_SESSION["kullanici_id"])){
        header('Location: dashboard.php'); 
    }

    include '../database/database.php';
    $baglanti = BAGLANTI_GETIR();

    $kullanici_id   = $_SESSION["kullanici_id"];
    $ders_kod       = mysqli_real_escape_string($baglanti, $_POST["kod"]);

    // echo $ders_kod ;
    // die();

    if(!isset($ders_kod)){
        $_SESSION["_error"] = "Eksik bilgi...";
        header('Location: ../dashboard.php'); 
    }

    $ders = DersDetayGetir_Kod($ders_kod);

    //if ders var mı
    if($ders == NULL){
        $_SESSION["_error"] = "Hatalı ders kodu girildi - ".$ders_kod;
        header('Location: ../dashboard.php'); 
    }else{
        $ders_id = $ders["id"];

          //derse kayıtlı mı
        $derse_kayitlimi = DerseKayitliMi($kullanici_id, $ders_id);
        // var_dump($derse_kayitlimi);
        // die();

        if($derse_kayitlimi  == TRUE){
            $_SESSION["_error"] = "Derse zaten kayıtlısınız.";
            header('Location: ../dashboard.php'); 
        }else{

            $kayitli = DerseKayitliKisiSayisi($ders_id);
            if($kayitli >= $ders["kontenjan"]){
                $_SESSION["_alert"]="Ders kontenjanı dolu.";
                header('Location: ../course.php?course='. $ders_id); 
            }else{
                if(DerseKayitOl($kullanici_id,  $ders_id) === TRUE){
                    $_SESSION["_success"]="Kayıt olundu.";
                    //LogYaz_DersKayit($kullanici_id, $etkinlik_id);
                    
                    header('Location: ../course.php?course='. $ders_id); 
                }else{
                    $_SESSION["_error"] = "Derse kayıt olunamadı.";
                    header('Location: ../dashboard.php'); 
                }
            }
        }

    }