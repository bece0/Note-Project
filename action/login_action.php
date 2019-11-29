<?php

   //bu sayfa giriş bilgilerini kontrol eder
   //giriş bilgileri doğruysa kullanıcı bilgilerini cookieye yazar ve anasayfaya yönlendirir
   //değilse login sayfasına yönlendirir
    session_start();

    $email_value=$_POST["email"];
    $pwd_value=$_POST["pwd"];

    include '../database/database.php';

    //kullanıcı adı-mail ve parola doğrulama
    if(isset($email_value) && isset($pwd_value)){
        
        $kullanici = KullaniciBilgileriniGetir($email_value);

        if($kullanici != NULL){

            $salt = $kullanici['salt'];
            $salt_ve_parola = $salt . $pwd_value; 
            $hashlenmis_parola = hash('sha512', $salt_ve_parola); 

            if($hashlenmis_parola == $kullanici['parola']){
                KullaniciSonGirisGuncelle($kullanici["id"]);
                
                $_SESSION["email"]= $email_value;
                $_SESSION["kullanici_id"]= $kullanici["id"];
                $_SESSION["admin"]= $kullanici["admin"];
                
                $log_mesaj = $kullanici["adi"]." ".$kullanici["soyadi"]." giriş yaptı";
                LogYaz_KullaniciGirisi($kullanici["id"], $log_mesaj);

                if(isset($_POST["event"])) {
                    header('Location: ../event.php?event='.$_POST["event"]);
                }
                else{
                    header('Location: ../index.php');   // giriş yapılır ve indexe yönlendiririr
                }
            }else{     // kullanıcı parolası yanlış
                //echo "kullanıcı parolası yanlış";
                header('Location: ../login.php'); 
                $_SESSION["_error"] = "Giriş bilgileri geçersiz!";
            }
        }
        else{      // böyle bir mailli kullanıcı yok
            //echo "böyle bir mailli kullanıcı yok";
            header('Location: ../login.php');
            $_SESSION["_error"] =  "Giriş bilgileri geçersiz!";
        }
    }
    else{     // mail ya da parola gönderilmedi
        //echo "mail ya da parola gönderilmedi";
        header('Location: ../login.php');
    }

?>