<?php

   //bu sayfa giriş bilgilerini kontrol eder
   //giriş bilgileri doğruysa kullanıcı bilgilerini cookieye yazar ve anasayfaya yönlendirir
    //değilse login sayfasına yönlendirir
    session_start();
    if(isset($_SESSION["email"])){
        header('Location: index.php'); 
    }

    function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    $name=$_POST["name"];
    $surname=$_POST["surname"];
    $email=$_POST["email"];
    $password=$_POST["password"];
    $admin=$_POST["admin"];

    $kodu = GUID();

    if(!isset($name) || !isset($surname)|| !isset($email)|| !isset($password)){
        $_SESSION["_error"] = "Eksik bilgi girdiniz.";
        header('Location: ../info.php'); 
    }

    function generateSalt($max = 64) {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
    }

    include '../database/database.php';

    $kullanici = KullaniciBilgileriniGetir($email);

    if ($kullanici != NULL) {
        $_SESSION["_error"]="Bu email adresi başka hesaba ait.";
    }else{

        $salt = generateSalt();
        $salt_ve_parola = $salt . $password; // salt ve kullanıcının belirlediği parola birleştiriliyor.
        $hashlenmis_parola = hash('sha512',$salt_ve_parola); 

        if(KullaniciKaydet($kodu, $name, $surname, $email, $hashlenmis_parola, $salt, $admin) === TRUE){
            $_SESSION["_success"]="Hesabınız oluşturuldu. Giriş yapabilirsiniz.";
            
            $kullanici = KullaniciBilgileriniGetir($email);

            KullaniciVarsayilanAyarlarKaydet($kullanici["id"]);
            LogYaz_KullaniciKayit($kullanici["id"]);
        }else {
            $_SESSION["_error"]="Bir hata oluştu. Lütfen tekrar deneyin.";
        }
    }

    header('Location: ../login.php');
