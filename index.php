<?php
$REQUIRE_LOGIN = FALSE;
$page_title = " Anasayfa";
include 'includes/page-common.php';
include 'includes/head.php';

if(isset($_SESSION["kullanici_id"])){
    
    if($_SESSION["admin"] == -1)
        header('Location: admin/management.php');
    else
        header('Location: dashboard.php');
        
}else{
    header('Location: login.php'); 
}
    

?>