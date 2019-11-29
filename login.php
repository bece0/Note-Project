<?php
    $page_title = "Giriş Yap";
    include 'includes/head.php';
?>
 <body>
    <?php
        include 'includes/nav-bar.php';
        
        if(isset($_SESSION["kullanici_id"])){    //giriş yapılmış ise index'e git
            header('Location: dashboard.php');
            die();
        }

        /*
        if($GirisYapildiMi && basename(__FILE__) == "login.php"){
            header('Location: dashboard.php');
            die();
        }
        */
    ?>
    
<section class="aboutus bg-dark text-white py-5" id="aboutus">
    <div class="container py-5 my-5">
        <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-12 ">
                    <div class="pb-3"></div>
                    <h2>Kolay ve Ücretsiz, Eğitim ve Soru-Cevap Platformu!</h2>
                    <div class="py-2"></div>
                    <p>Zaman kazanın ve öğrencilerin topluluğun gücünü kullanarak öğrenmelerine yardımcı olun</p>
                    <div class="py-2"></div>
                    Üye değil misiniz?  
                    <button type="button" class="btn btn-outline-light"><a href="signup.php" class="float-right" style="margin-right: 1px; color:yellow;">Hesap oluştur</a></button>
                </div>
                 <div class="clearfix visible-sm"></div>
                <!-- <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="card bg-danger">
                        <div class="card-body">
                            <form action="/action_page.php">
                          <div class="form-group">
                            <div class="input-group-addon">
                              <i class="fa fa-address-card"></i>
                            </div>   
                            <input type="email" class="form-control" id="email" placeholder="User ID">
                          </div>
                          <div class="form-group">
                            <input type="password" class="form-control" id="pwd" placeholder="Password">
                          </div>
                          <div class="form-group form-check">
                            <label class="form-check-label">
                              <input class="form-check-input" type="checkbox"> Remember me
                            </label>
                          </div>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </form> 
                        </div>
                    </div> 
                </div> -->
        
                <div class="col-sm-6 col-md-4">
                <div class="card border-info text-center">
              
                    <div class="card-body">
                        <img src="files/images/logooo.png" style="margin-bottom:30px;border-radius:40px;width:25%">
                        <!-- <h4 class="text-center">Hunger & Debt Ltd</h4> -->
                        <form class="form-signin" action="action/login_action.php" method="post">
                            <input type="text" class="form-control mb-2" placeholder="Email" name="email" required autofocus>
                            <input type="password" class="form-control mb-2" placeholder="Parola"  name="pwd" required>
                            <?php
                                if(isset($_GET["event"])){
                            ?>       <input type="hidden" name="event" value="<?php echo $_GET["event"] ?>">
                                <?php   } 
                            ?>
                            <button class="btn btn-lg btn-primary btn-block mb-1" type="submit" style="background-color: #1d1a1a">Giriş Yap</button>
                            <!-- <label class="checkbox float-left">
                            <input type="checkbox" value="remember-me">
                            Remember me
                            </label> -->
                            <!-- <a href="#" class="float-right">Need help?</a> -->
                        </form>
                    </div><br>
                    <!-- <h5 class="justify-content-sm-center">
                        <a href="signup.php" class="float-right" style="margin-right: 13px; color: #312c2c; ">Hesap oluştur </a>
                    </h5> -->
                </div>
        </div>
        </div>
    </div>
</section>




    <!-- <br><br>
     <div class="container justify-content-sm-center" style="display: flex;">
        <div class="col-sm-6 col-md-4">
            <div class="card border-info text-center">
              
                <div class="card-body">
                    <img src="files/images/logooo.png" style="margin-bottom:30px;border-radius:40px;width:25%">
           
                    <form class="form-signin" action="action/login_action.php" method="post">
                        <input type="text" class="form-control mb-2" placeholder="Email" name="email" required autofocus>
                        <input type="password" class="form-control mb-2" placeholder="Parola"  name="pwd" required>
                      
                        <button class="btn btn-lg btn-primary btn-block mb-1" type="submit" style="background-color: #1d1a1a">Giriş Yap</button>
                       
             
                    </form>
                </div>
                <h5 class="justify-content-sm-center">
                    <a href="signup.php" class="float-right" style="margin-right: 13px; color: #312c2c; ">Hesap oluştur </a>
                </h5>
            </div>
        </div>
    </div> -->
</body>

</html>