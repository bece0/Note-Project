<?php

    final class Connection
    {
        // Hold an instance of the class
        private static $instance;

        public static $CONNECTION;
    
        // The singleton method
        public static function getInstance() : Connection
        {
            if (null === static::$instance) {
                static::$instance = new static();

                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "classroom";

                //DATABASE_CONNECTION isimli ortam değişkeni değeri kontrol ediliyor.
                $database_conn = getenv('DATABASE_CONNECTION');

                /*
                if($database_conn != FALSE){
                    //bu değer var ise bağlantı bilgileri bu değer üzerinden ayarlanıyor...
                    //localhost;username;password;dbname
                    $database_arr = explode(";", $database_conn);
                    $servername = $database_arr[0];
                    $username = $database_arr[1];
                    $password = $database_arr[2];
                    $dbname = $database_arr[3];
                }
                */
            
                static::$CONNECTION = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if (static::$CONNECTION->connect_error) {
                    die("Connection failed: " . static::$CONNECTION->connect_error);
                } 
                static::$CONNECTION->set_charset("utf8");
            }

            return static::$instance;
        }

        public static function GetConnection(){
            return static::$CONNECTION;
        }

        private function __construct()
        {
        }

    }

    /**
     * 
     */
    function BAGLANTI_GETIR(){

        $connectionClass = Connection::getInstance();
        $connection = $connectionClass->GetConnection();
        // var_dump($connection);

        return $connection;
        
        // $servername = "movedb";
        // $username = "root2";
        // $password = "986753421asdf";
        // $dbname = "move";
    
        // $CONNECTION = new mysqli($servername, $username, $password, $dbname);
        // // Check connection
        // if ($CONNECTION->connect_error) {
        //     die("Connection failed: " . $CONNECTION->connect_error);
        // } 
        // $CONNECTION->set_charset("utf8");
        
        // return $CONNECTION;
    }

    /**
     * SQL sorgusunu çalıştırır ve sonucu döner
     * @param  $sql_sorgusu sql sorgu cümlesi
     * @param  $returnNull sonuç boş işe bu paramtreye göre NULL ya da boş array dönmesini sağlar
     * @return array sonuç dizisi ya da NULL
     */
    function SQLCalistir(string $sql_sorgusu, bool $returnNull = TRUE){
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql_sorgusu);
    
        if ($result != NULL && $result->num_rows > 0) {
            $sonuc = array();
            while($row = $result->fetch_assoc()) 
                array_push($sonuc, $row);
            
            return $sonuc;
        }
        else{
            if($returnNull == FALSE)
                return [];
                
            return NULL;
        }
            
    }

    function SQLInsertCalistir($sql_sorgusu) : bool{
        $con = BAGLANTI_GETIR();

        if ($con->query($sql_sorgusu) === TRUE) {
            return TRUE;
        } else {
            echo "Error at insert : " . $sql_sorgusu . "<br>" . $con->error;
            return FALSE;
        }
    }

    function SQLUpdateCalistir($sql_sorgusu){
        $con = BAGLANTI_GETIR();

        if ($con->query($sql_sorgusu) === TRUE) {
            return TRUE;
        } else {
            echo "Error at update : " . $sql_sorgusu . "<br>" . $con->error;
            return FALSE;
        }
    }

    function SQLDeleteCalistir($sql_sorgusu){
        $con = BAGLANTI_GETIR();

        if ($con->query($sql_sorgusu) === TRUE) {
            return TRUE;
        } else {
            echo "Error at delete : " . $sql_sorgusu . "<br>" . $con->error;
            return FALSE;
        }
    }

    function SQLTekliKayitGetir($sql_sorgusu){
        $con = BAGLANTI_GETIR();
        $result = $con->query($sql_sorgusu);
        
        if ($result != NULL && $result->num_rows > 0) 
            return mysqli_fetch_assoc($result);
        else
            return NULL;
    }

    include 'log_repo.php';
    include 'user_repo.php';
    include 'event_repo.php';
    include 'comment_repo.php';
    include 'stats_repo.php';
    include 'settings_repo.php';
    include 'notification_repo.php';

?>