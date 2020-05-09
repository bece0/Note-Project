<?php 

    /**
     * Verilen bilgileri kullanıcı tablosuna kaydeder.
     *
     * @param string $name kullanıcının adı
     * @param string $surname kullanıcının soyadı
     * @param string $email  kullanıcının mail adresi
     * @param string $password kullanıcının parolası
     * @return bool kaydetme işlemi başarılı ise true değil ise false döner
     */
    function KullaniciKaydet($kodu, $name, $surname, $email, $password, $salt, $kullaniciTip){
        
        //$password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO kullanici (kodu, adi, soyadi, email, parola, salt, kayit_tarihi, admin)
        VALUES ('$kodu', '$name', '$surname', '$email', '$password', '$salt', CURDATE(), '$kullaniciTip' )";

        $con = BAGLANTI_GETIR();

        if ($con->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
            return FALSE;
        }
    }

    /**
     * Maili verilen kullanıcının databasede olup olmadığını kontrol eder.
     *
     * @param string $email kontrol edilecek mail adresi
     * @return bool var ise true yok ise false döner
     */
    function KullaniciVarmi($email) {
        $sql = "SELECT * FROM kullanici where email = '$email'";

        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) 
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Giriş bilgileri alınan kullanıcının databasede olup olmadığını kontrol eder.
     *
     * @param string $email kontrol edilecek kullanıcının mail adresi
     * @param string $password kontrol edilecek kullanıcının parolası
     * @return bool bilgiler dogru ise true yanlış ise false döner
     */
    function KullaniciGirisKontrol($email, $password) {
       // $password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "SELECT * FROM kullanici where email = '$email' and parola = '$password'";
        
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) 
            return TRUE;
        else
            return FALSE;
    }

      
    /**
     * Emaili parametre olarak verilen kullanıcının bilgilerini getirir
     * @param string $email kullanıcının maili
     * @return string gelen sonucu döner,sonuç boş ise NULL döner  
     */
    function KullaniciBilgileriniGetir($email){
        $sql = "SELECT * FROM kullanici where email = '$email'";
        
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;
    }

    function TumKullaniciBilgileriniGetir(){
        $sql = "SELECT * FROM kullanici";
        
        return SQLCalistir($sql, FALSE);
    }


    /**
     * Id parametre olarak verilen kullanıcının bilgilerini getirir
     * @param string $id kullanıcının id'si
     * @return string  gelen sonucu döner,sonuç boş ise NULL döner 
     */
    function KullaniciBilgileriniGetirById($kullanici_id){
        $sql = "SELECT * FROM kullanici where id = '$kullanici_id'";
        
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;
    }

    function KullaniciBilgileriniGetirByAPI($api_key){
        $sql = "SELECT * FROM kullanici where api_key = '$api_key'";
        
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;
    }

    function KullaniciSonGirisGuncelle($kullanici_id){
        $sql = "UPDATE kullanici SET son_giris_tarihi = CURDATE() where id = '$kullanici_id'";
        
        $con = BAGLANTI_GETIR();
        if ($con->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
            return FALSE;
        }
    }
    
   function KullaniciApiKeyGuncelle($kullanici_id, $API_KEY){
        $sql = "UPDATE kullanici SET api_key = '$API_KEY' where id = '$kullanici_id'";
            
        $con = BAGLANTI_GETIR();
        if ($con->query($sql) === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
   }
     

    /**
     * kullanıcının parolasını günceller
     * @param $kullanici_id parolası güncellenecek olan kullanıcı kaydı
     * @param $parola yani parola değeri, hashlenmemiş yalın hal olamlı. çünkü bu değer içeride hashlenecek.
     * @param $salt_uret kullanıcının salt değeri güncellensin mi güncellenmesin mi. TRUE olursa yeni salt üretilir.
     * FALSE olursa veritabanındaki salt değeri kullanilir.
     */
    function KullaniciParolaGuncelle($kullanici_id, $parola, $salt_uret = FALSE){
        $sql = "";

        if($salt_uret == TRUE){
            $salt = SaltUret();

            $salt_ve_parola = $salt . $parola;
            $hashlenmis_parola = hash('sha512',$salt_ve_parola); 

            $sql = "UPDATE kullanici SET parola = '$hashlenmis_parola', salt = '$salt' where id = '$kullanici_id'";
        }else {
            $kullanici = KullaniciBilgileriniGetirById($kullanici_id);

            $salt_ve_parola = $kullanici['salt'] . $parola;
            $hashlenmis_parola = hash('sha512',$salt_ve_parola); 

            $sql = "UPDATE kullanici SET parola = '$hashlenmis_parola' where id = '$kullanici_id'";
        }
        
        $con = BAGLANTI_GETIR();
        if ($con->query($sql) === TRUE) {
            return TRUE;
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
            return FALSE;
        }
    }

    function SaltUret($max = 64) {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i = 0;
        $salt = "";
        while ($i < $max) {
            $salt .= $characterList[mt_rand(0, (strlen($characterList) - 1))];
            $i++;
        }
        return $salt;
    }
?>