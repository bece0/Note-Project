<?php 
    session_start();

    //bu sayfayı sadece yöneticiler istek gönderebilir.
    if(!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1){
        die();
    }

    if(!isset($_GET["method"]) || $_GET["method"] == ""){
        echo "method parametresi eksik!";
        die();
    }

    $method = $_GET["method"];

    include '../database/database.php';

    header('Content-type: application/json');

    $sonuc = [];
    if($method == "user_logs"){
        $sonuc = LogGetir_Kullanici();
    }
    if($method == "event_logs"){
        $sonuc = LogGetir_Etkinlik();
    }
    if($method == "system_logs"){
        $sonuc = LogGetir_Sistem();
    }
    if($method == "errors"){
        $sonuc = HataGetir();
    }

    if($sonuc == NULL)
        $sonuc = [];
            
    echo json_encode($sonuc);
?>