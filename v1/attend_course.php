<?php
    $REQUIRE_LOGIN = TRUE;
    $page_title = "Derse Kaydol";

    include 'includes/head.php';
    include 'includes/page-common.php';
    include 'includes/nav-bar.php';

    ?>

    <body>
    <?php
      //  include 'includes/nav-bar.php';
        
        if(isset($_SESSION["kullanici_id"])){    //giriş yapılmış ise index'e git
            header('Location: dashboard.php');
        }
    ?>
    <br><br>
     <div class="container justify-content-sm-center" style="display: flex;">
        <div class="col-sm-6 col-md-4">
            <div class="card border-info text-center">
              
                <div class="card-body">
                    <img src="files/images/logooo.png" style="margin-bottom:30px;border-radius:40px;width:25%">
                    <!-- <h4 class="text-center">Hunger & Debt Ltd</h4> -->
                    <form class="form-signin" action="action/attend_course_action.php" method="post">
                        <input type="text" class="form-control mb-2" placeholder="Ders Kodu" name="kod" required autofocus>
                    
                        <button class="btn btn-lg btn-primary btn-block mb-1" type="submit" style="background-color: #1d1a1a">Kayıt ol</button>

                    </form>
                </div>
           
            </div>
        </div>
    </div>
</body>
