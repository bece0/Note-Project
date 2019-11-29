<?php 
    session_start();

    //kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
    if(!isset($_SESSION["kullanici_id"])){
        die();
    }

    $giris_yapan_kullanici = $_SESSION["kullanici_id"];

    $isAdmin = false;
    if(isset($_SESSION["admin"]) || $_SESSION["admin"] == 1){
        $isAdmin = true;
    }

    $user_id = $_SESSION["kullanici_id"];

    if(!isset($_GET["method"]) || $_GET["method"] == ""){
        echo "method parametresi eksik!";
        die();
    }

    $method = $_GET["method"];

    include '../database/database.php';

    $baglanti = BAGLANTI_GETIR();

    header('Content-type: application/json');

    $sonucObjesi = new stdClass();;
    $sonucObjesi->sonuc = false;
    $sonucObjesi->mesaj = "";

    // var_dump($_POST);
    $sonuc = false;

    if($method == "add"){
        $comment =  mysqli_real_escape_string($baglanti,$_POST["comment"]);
        $event_id = $_POST["event_id"];
        
        $sonuc = AddComment($user_id, $event_id, $comment);

        $sonucObjesi->sonuc = true;
    }
    if($method == "delete"){
        $comment = $_GET["comment"];

        if($comment != NULL){
            //TODO - check if current user can delete
            if($isAdmin){
                $sonuc = DeleteComment($comment);
                $sonucObjesi->sonuc = $sonuc;
            }else{
                //silmeye yetkisi yok
            }
            
        }else{
            //comment parametresi eksik
        }
    }
    if($method == "approve"){
        $comment = $_GET["comment"];

        if($comment != NULL){
            //TODO - check if current user can delete
            if($isAdmin){
                $sonuc = ApproveComment($comment, $giris_yapan_kullanici);
                $sonucObjesi->sonuc = $sonuc;
            }else{
                //silmeye yetkisi yok
            }
            
        }else{
            //comment parametresi eksik
        }
    }
        
    echo json_encode($sonucObjesi);
