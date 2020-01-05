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
            return 0;   
    }

    function DurumaGoreDersSayisi($DURUM = 1){
        $sql = "SELECT count(*) as toplam FROM dersler WHERE status = $DURUM" ;
     
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return 0;     
    }
    

     /**
     * @return int Databasedeki katılımcı sayısını döner.
     */
    function ToplamKatılımSayisi(){
        $sql = "SELECT count(ogrenci_id) as toplam FROM katilimci";
     
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return 0;     
    }

?>