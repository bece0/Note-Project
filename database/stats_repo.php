<?php
    /**
     * @return int Databasedeki aktif kullanıcı sayısını döner.
     */
    function AktifKullaniciSayisi(){
        $sql = "SELECT count(*) as toplam FROM kullanici" ;
     
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
   
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;   

    }
    
     /**
     * @return int Databasedeki geçmiş etkinlik sayısını döner.
     */
    function ToplamGecmisEtkinlikSayisi(){
        $sql = "SELECT count(*) as toplam FROM etkinlik WHERE tarih<CURDATE()" ;
     
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;     

    }

     /**
     * @return int Databasedeki gelecek etkinlik sayısını döner.
     */
    function ToplamGelecekEtkinlikSayisi(){
        $sql = "SELECT count(*) as toplam FROM etkinlik WHERE tarih>CURDATE()" ;
     
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;     
    }

     /**
     * @return int Databasedeki katılımcı sayısını döner.
     */
    function ToplamKatılımSayisi(){
        $sql = "SELECT count(kullanici_id) as toplam FROM katilimci";
     
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;     
    }

?>