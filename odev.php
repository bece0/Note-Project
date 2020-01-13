

<?php
$REQUIRE_LOGIN = TRUE;
include 'includes/page-common.php';
include 'includes/head.php';
?>
<link rel="stylesheet" href="assets/css/course.css">

<!-- <link rel="stylesheet" href="assets/css/social-share-kit.css" type="text/css">
<script type="text/javascript" src="assets/js/vendor/social-share-kit.min.js"></script> -->

<body>


    <?php
    include 'includes/nav-bar.php';

    if (!isset($_GET["kod"])){
        header('Location: dashboard.php');
        die();
    }

   ?>
    
        <div>
            <?php include 'includes/footer.php'; ?>
        </div>
</body>