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



<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->


<body>

   
</body>