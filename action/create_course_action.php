<?php
 
    session_start();
    if(!isset($_SESSION["kullanici_id"])){
        header('Location: dashboard.php'); 
    }

    
    include '../includes/page-common.php';
    include '../database/database.php';
    
    $baglanti = BAGLANTI_GETIR();

    //$isim=$_POST["etkinlik_adi"];
    $isim= mysqli_real_escape_string($baglanti, $_POST["etkinlik_adi"]);
    $kontenjan= mysqli_real_escape_string($baglanti, $_POST["kontenjan"]);
    $aciklama=mysqli_real_escape_string($baglanti, $_POST["aciklama"]);
    //$bolum_adi= mysqli_real_escape_string($baglanti, $_POST["kontenjan"]);
    $duzenleyen_id=$_SESSION["kullanici_id"];


    echo "isim : ".$isim. "</br>" ;
    echo "kontenjan : ". $kontenjan. "</br>" ;
    echo "aciklama : ".$aciklama. "</br>" ;
    echo "duzenleyen_id : ". $duzenleyen_id. "</br>" ;

//die();

function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

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
    function ResimYukle($ders_kodu){

        if(!isset($_FILES["etkinlik_resim"]) || $_FILES['etkinlik_resim']['size'] == 0){
            echo $ders_kodu. " koduna sahip dersin resmi yüklenmedi. varsayılan resimlerden eklenecektir";
            
            $yeni_resim =  __DIR__ . "/../files/images/event/" . $ders_kodu . ".png";
            $varsayılan_resim =  __DIR__ . "/../files/images/" . ToEnglish($_POST["tip"]) . ".png";

            echo "<br> yeni resim : ". $yeni_resim . "<br>";
            echo "<br>varsayılan_resim : ". $varsayılan_resim . "<br>";

            copy($varsayılan_resim,  $yeni_resim);
            return;
        }

        $filename = $_FILES["etkinlik_resim"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        $file_ext = substr($filename, strripos($filename, '.')); // get file name

        $file_ext = strtolower($file_ext);

        $filesize = $_FILES["etkinlik_resim"]["size"];
        $allowed_file_types = array('.jpg','.png','.jpeg');	
    
        if (in_array($file_ext, $allowed_file_types) && ($filesize < 3000000))
        {	
            // Rename file
            $newfilename = $ders_kodu . ".png";
            
            $yeni_resim =  __DIR__ . "/../files/images/event/" . $newfilename;
            move_uploaded_file($_FILES["etkinlik_resim"]["tmp_name"],  $yeni_resim);
            echo "File uploaded successfully.";		
        }
        elseif (empty($file_basename))
        {	
            // file selection error
            echo "Please select a file to upload.";
        } 
        elseif ($filesize > 3000000)
        {	
            // file size error
            echo "The file you are trying to upload is too large.";
        }
        else
        {
            // file type error
            echo "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
            unlink($_FILES["etkinlik_resim"]["tmp_name"]);
        }
    }


     $ders_kodu =  random_str(6);

    if(DersKaydet($ders_kodu, $isim, $aciklama, $kontenjan, $duzenleyen_id) === TRUE){
        ResimYukle($ders_kodu);

        $_SESSION["_success"] = "Ders oluşturuldu";

        $etkinlik = EtkinlikBilgileriniGetir_Kod($ders_kodu);
        LogYaz_EtkinlikOlusturma($_SESSION["kullanici_id"], $etkinlik["id"]);

        header('Location: ../index.php'); 
    }else {
        $_SESSION["_error"] = "Ders oluşturulamadı.";
        header('Location: ../index.php'); 
    }
?>

