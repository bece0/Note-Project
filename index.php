<?php
$REQUIRE_LOGIN = FALSE;
$page_title = " Anasayfa";
include 'includes/page-common.php';
include 'includes/head.php';

if(isset($_SESSION["kullanici_id"])) 
    header('Location: dashboard.php');
   else    
    header('Location: login.php'); 

?>