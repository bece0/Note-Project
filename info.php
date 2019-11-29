<?php
  include 'includes/head.php';
?>
 <body>
    <?php
        include 'includes/nav-bar.php';
    ?>
     <div class="row justify-content-sm-center" style="margin-top:25px">
        <?php
            if(isset($_SESSION["info"])){
                echo "<h2>".$_SESSION["info"]."</h2>";
                unset($_SESSION["info"]);
            }
        ?>
    </div>
</body>

</html>