<?php
 
    session_start();
    if(!isset($_SESSION["kullanici_id"])){
        header('Location: dashboard.php'); 
    }

    include '../includes/page-common.php';
    include '../database/database.php';
    
    $baglanti = BAGLANTI_GETIR();

    $isim=$_POST["etkinlik_adi"];
    $adres= mysqli_real_escape_string($baglanti, $_POST["adres"]);
    $sehir= mysqli_real_escape_string($baglanti, $_POST["sehir"]);
    $seviye=$_POST["seviye"];
    $aciklama=mysqli_real_escape_string($baglanti, $_POST["aciklama"]);
    $tel=$_POST["telefon"];
    $tarih=$_POST["etkinlik_tarihi"];
    $k_aciklama=mysqli_real_escape_string($baglanti, $_POST["k_aciklama"]);
    $tip=$_POST["tip"];
    $usedefault = $_POST["use_default"];

    $event_id=$_POST["event_id"];
/*
    echo $event_id. "</br>" ;
    echo $isim. "</br>" ;
    echo $adres. "</br>" ;
    echo $sehir. "</br>" ;
    echo $seviye. "</br>" ;
    echo $aciklama. "</br>" ;
    echo $tel. "</br>" ;
    echo $tarih. "</br>" ;
*/
    
    function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    /**
     * 
     */
    function ResimYukle($etkinlik_kodu, $usedefault = "0"){

        if((!isset($_FILES["etkinlik_resim"]) || $_FILES['etkinlik_resim']['size'] == 0) && $usedefault == "1" ){
            echo $etkinlik_kodu. " koduna sahip etkinliğin resmi yüklenmedi. varsayılan resimlerden tipine göre olan eklenecektir";
            
            // $yeni_resim =  __DIR__ . "\\..\\files\\images\\event\\" . $etkinlik_kodu . ".png";
            // $varsayılan_resim =  __DIR__ . "\\..\\files\\images\\" . ToEnglish($_POST["tip"]) . ".png";
           
            $yeni_resim =  __DIR__ . "/../files/images/event/" .   $etkinlik_kodu . ".png";
            $varsayılan_resim =  __DIR__ . "/../files/images/" . ToEnglish($_POST["tip"]) . ".png";

            echo "<br> yeni resim : ". $yeni_resim . "<br>";
            echo "<br>varsayılan_resim : ". $varsayılan_resim . "<br>";

            copy($varsayılan_resim,  $yeni_resim);

            $_SESSION["_log"] = "varsailan resim kullanildi";
            return;
        }

        $filename = $_FILES["etkinlik_resim"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        $file_ext = substr($filename, strripos($filename, '.')); // get file name

        $file_ext = strtolower($file_ext);

        $filesize = $_FILES["etkinlik_resim"]["size"];
        $allowed_file_types = array('.jpg','.png','.jpeg');	
    
        if (in_array($file_ext,$allowed_file_types) && ($filesize < 3000000))
        {	
            $newfilename = $etkinlik_kodu . ".png";
            if (file_exists($newfilename))
                $deleted= unlink($newfilename);

            // $yeni_resim =  __DIR__ . "\\..\\files\\images\\event\\" . $newfilename;
            $yeni_resim =  __DIR__ . "/../files/images/event/" . $newfilename;
            move_uploaded_file($_FILES["etkinlik_resim"]["tmp_name"],  $yeni_resim);
            echo "File uploaded successfully.";	
            
            $_SESSION["_log"] = "File uploaded successfully.";
        }
        elseif (empty($file_basename))
        {	
            // file selection error
            echo "Please select a file to upload.";
            $_SESSION["_log"] = "Please select a file to upload.";
        } 
        elseif ($filesize > 3000000)
        {	
            // file size error
            echo ".";
             
            $_SESSION["_log"] = "The file you are trying to upload is too large";
        }
        else
        {
            // file type error
            echo "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
            $_SESSION["_log"] = "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
            unlink($_FILES["etkinlik_resim"]["tmp_name"]);
        }
    }

    $event_detail = EtkinlikBilgileriniGetir($event_id);
    $etkinlik_kodu =  $event_detail["kodu"];

    if($event_detail["duzenleyen_id"] != $_SESSION["kullanici_id"] && $_SESSION["admin"] != 1){
        //giriş yapmış olan kullanıcı etkinliği oluşturan kişi değilse ve admin değilse
        die();
    }

    if(EtkinlikDuzenle($isim, $aciklama, $tarih, $adres, $seviye, $tel, $sehir, $k_aciklama, $tip, $event_id)=== TRUE){
        $_SESSION["_success"] = "Etkinlik düzenlendi.";

        LogYaz_EtkinlikDuzenleme($_SESSION["kullanici_id"], $event_id);

        $eski_tarih =  $event_detail["tarih"];

        $time_eski = strtotime($eski_tarih);
        $time_yeni = strtotime($tarih);
 
        $newformat_eski = date('Y-m-d',$time_eski);
        $newformat_yeni = date('Y-m-d',$time_yeni);

        echo $newformat_eski."<br>";
        echo $newformat_yeni."<br>";

        if ($newformat_eski != $newformat_yeni) {
            $mesaj = "";
            EtkinlikKatilimcilarinaBildirimGonder($event_id, $mesaj, "ETKINLIK_TARIH_UPDATE");
        }

        ResimYukle($etkinlik_kodu,$usedefault);
        header('Location: ../event.php?event='.$event_id); 
    }else {
        $_SESSION["_error"] = "Bir hata oluştu.Etkinlik düzenlenemedi.";
        $_SESSION["_log"] = "Bir hata oluştu.Etkinlik düzenlenemedi." ;

        header('Location: ../index.php'); 
    }
