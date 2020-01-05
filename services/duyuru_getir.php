<?php 

    session_start();

    //bu sayfaya sadece giriş yapan kullanıcılar istek gönderebilir.
    // if(!isset($_SESSION["kullanaci_id"])){
    //     die();
    // }

    if(!isset($_GET["ders"]) || $_GET["ders"] == ""){
        echo "ders parametresi eksik!";
        die();
    }

    include '../database/database.php';
    $baglanti = BAGLANTI_GETIR();

    header('Content-type: application/json');


    $ders_id =  mysqli_real_escape_string($baglanti, $_GET["ders"]);

    $sonuc = [];
     
    $sonuc = DersDuyurulariGetir($ders_id);
     
    if($sonuc == NULL)
        $sonuc = [];
            
    echo json_encode($sonuc);
?>