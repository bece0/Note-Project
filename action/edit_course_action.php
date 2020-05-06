<?php
 
    session_start();
    if(!isset($_SESSION["kullanici_id"])){
        header('Location: dashboard.php'); 
    }

    include '../includes/page-common.php';
    include '../database/database.php';
    
    $baglanti = BAGLANTI_GETIR();
    
    $ders_adi   = mysqli_real_escape_string($baglanti, $_POST["ders_adi"]);
    $bolum_adi  = mysqli_real_escape_string($baglanti, $_POST["bolum_adi"]);
    $kontenjan  = mysqli_real_escape_string($baglanti, $_POST["kontenjan"]);
    $sinif      = mysqli_real_escape_string($baglanti, $_POST["sinif"]);
    $aciklama   = mysqli_real_escape_string($baglanti, $_POST["aciklama"]);
    $ders_id    = mysqli_real_escape_string($baglanti, $_POST["ders_id"]);

    // echo "ders_id : ".$ders_id. "</br>" ;
    // echo "isim : ".$ders_adi. "</br>" ;
    // echo "kontenjan : ". $kont   enjan. "</br>" ;
    // echo "aciklama : ".$aciklama. "</br>" ;
    // echo "sinif : ".$sinif. "</br>" ;
    // echo "bolum : ". $bolum_adi. "</br>" ;

    // die();
    
    /**
     * 
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
    function ResimYukle($ders_kodu, $usedefault = "0"){

        if((!isset($_FILES["ders_resim"]) || $_FILES['ders_resim']['size'] == 0) && $usedefault == "1" ){
            echo $ders_kodu. " koduna sahip dersin resmi yüklenmedi. varsayılan resim eklenecektir";
            
            // $yeni_resim =  __DIR__ . "\\..\\files\\images\\course\\" . $ders_kodu . ".png";
            // $varsayılan_resim =  __DIR__ . "\\..\\files\\images\\" . ToEnglish($_POST["tip"]) . ".png";
           
            $yeni_resim =  __DIR__ . "/../files/images/course/" .   $ders_kodu . ".png";
            $varsayılan_resim =  __DIR__ . "/../files/images/default.png";

            echo "<br> yeni resim : ". $yeni_resim . "<br>";
            echo "<br>varsayılan_resim : ". $varsayılan_resim . "<br>";

            copy($varsayılan_resim,  $yeni_resim);

            $_SESSION["_log"] = "varsayilan resim kullanildi";
            return;
        }

        $filename = $_FILES["ders_resim"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        $file_ext = substr($filename, strripos($filename, '.')); // get file name

        $file_ext = strtolower($file_ext);

        $filesize = $_FILES["ders_resim"]["size"];
        $allowed_file_types = array('.jpg','.png','.jpeg');	
    
        if (in_array($file_ext,$allowed_file_types) && ($filesize < 3000000))
        {	
            $newfilename = $ders_kodu . ".png";
            if (file_exists($newfilename))
                $deleted= unlink($newfilename);

            // $yeni_resim =  __DIR__ . "\\..\\files\\images\\event\\" . $newfilename;
            $yeni_resim =  __DIR__ . "/../files/images/course/" . $newfilename;
            move_uploaded_file($_FILES["ders_resim"]["tmp_name"],  $yeni_resim);
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
            unlink($_FILES["ders_resim"]["tmp_name"]);
        }
    }

    $ders_detail = DersDetayGetir($ders_id);
    $ders_kodu =  $ders_detail["kodu"];

    // var_dump($ders_detail);
    // echo "<br>".$_SESSION["kullanici_id"];
    // die();

    if($ders_detail["duzenleyen_id"] != $_SESSION["kullanici_id"]){
        //giriş yapmış olan kullanıcı etkinliği oluşturan kişi değilse
        echo "giriş yapmış olan kullanıcı etkinliği oluşturan kişi değil";
        die();
    }

    if(DersDuzenle($ders_id, $ders_adi, $aciklama, $kontenjan, $bolum_adi, $sinif)=== TRUE){
        $_SESSION["_success"] = "Ders düzenlendi.";

        LogYaz_DersDuzenleme($_SESSION["kullanici_id"], $ders_id);

        /*$eski_tarih =  $event_detail["tarih"];

        $time_eski = strtotime($eski_tarih);
        $time_yeni = strtotime($tarih);
 
        $newformat_eski = date('Y-m-d',$time_eski);
        $newformat_yeni = date('Y-m-d',$time_yeni);

        echo $newformat_eski."<br>";
        echo $newformat_yeni."<br>";

        if ($newformat_eski != $newformat_yeni) {
            $mesaj = "";
            EtkinlikKatilimcilarinaBildirimGonder($ders_id, $mesaj, "ETKINLIK_TARIH_UPDATE");
        }
*/
        // ResimYukle($ders_kodu,$usedefault);
        header('Location: ../course.php?course='.$ders_id); 
    }else {
        $_SESSION["_error"] = "Bir hata oluştu.Ders düzenlenemedi.";
        $_SESSION["_log"] = "Bir hata oluştu.Ders düzenlenemedi." ;

        header('Location: ../index.php'); 
    }
