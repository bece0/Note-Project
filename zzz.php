<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/ortak.php';
include 'database/database.php';

function GUID()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
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

function KullaniciOlustur($count, $name_mail_prefix, $type = 0){
    for ($i=0; $i < $count; $i++) { 
        $name=$name_mail_prefix."".$i;
        $surname="".$i;
        $email=$name_mail_prefix."-".$i."@gmail.com";
        $password="123";
        $admin=$type;

        $kullanici = KullaniciBilgileriniGetir($email);
        
        $kodu = GUID();

        if ($kullanici != NULL) {
            KullaniciParolaGuncelle($kullanici["id"], $password,TRUE);
            echo($email." parola resetlendi <br>");
        }else{

            $salt = generateSalt();
            $salt_ve_parola = $salt . $password; // salt ve kullanıcının belirlediği parola birleştiriliyor.
            $hashlenmis_parola = hash('sha512',$salt_ve_parola); 

            if(KullaniciKaydet($kodu, $name, $surname, $email, $hashlenmis_parola, $salt, $admin) === TRUE){
                echo($email." olusturuldu <br>");
                $kullanici = KullaniciBilgileriniGetir($email);
            }else {
                echo($email." Bir hata oluştu. <br>");
            }
        }
    }
}

echo "ogrenciler <br>";
KullaniciOlustur(10, "ogrenci", 0);

echo "ogretmenler <br>";
KullaniciOlustur(10, "ogretmen", 0);


// echo $result["id"]
