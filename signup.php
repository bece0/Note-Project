<?php
    $page_title = "Üye Ol";
    include 'includes/head.php';
?>

<body>
    <?php
        include 'includes/nav-bar.php';
        //giriş yapılmış ise index'e git
        if(isset($_SESSION["email"])){
            header('Location: dashboard.php');
        }
    ?>
     <br><br>
    <div class="container">
        <div class="container justify-content" style="display: flex;">
            <div class="col-sm-12 col-md-6">
                <div class="card border-info text-center">
                    <div class="card-header"> Öğrenci Üyelik </div>
                    <div class="card-body">
                        <img src="files/images/student.png" style="margin-bottom:30px;     border-radius: 70px">
                        <form class="form-signin" action="action/signup_action.php" method="POST">
                            <input type="hidden" id= "admin" name="admin" value="0">
                            <input type="text" name="name" value="" class="form-control mb-2" placeholder="Ad"
                                required="true" />
                            <input type="text" name="surname" value="" class="form-control mb-2" placeholder="Soyad"
                                required="true" />
                            <input type="text" name="email" value="" class="form-control mb-2"
                                placeholder="E-posta adresi" required="true" />
                            <input type="password" name="password" value="" class="form-control mb-2"
                                placeholder="Parola" required="true" />
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="sozlesme" id="sozlesme"
                                    required="true">
                                <label class="form-check-label" for="sozlesme"><a href="agreement.php"
                                        target="_blank">Üyelik Sözleşmesi</a> şartlarını okudum ve kabul
                                    ediyorum.</label>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block mb-1" type="submit"
                                style="background-color: #1d1a1a">Hesabımı Oluştur</button>
                        </form>
                    </div>
                
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="card border-info text-center">
                    <div class="card-header"> Öğretmen Üyelik </div>
                    <div class="card-body">
                        <img src="files/images/teacher.png" style="margin-bottom:30px;     border-radius: 70px">
                        <form class="form-signin" action="action/signup_action.php" method="POST">
                            <input type="hidden"  id= "admin" name="admin" value="1">
                            <input type="text" name="name" value="" class="form-control mb-2" placeholder="Ad"
                                required="true" />
                            <input type="text" name="surname" value="" class="form-control mb-2" placeholder="Soyad"
                                required="true" />
                            <input type="text" name="email" value="" class="form-control mb-2"
                                placeholder="E-posta adresi" required="true" />
                            <input type="password" name="password" value="" class="form-control mb-2"
                                placeholder="Parola" required="true" />
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="sozlesme" id="sozlesme"
                                    required="true">
                                <label class="form-check-label" for="sozlesme"><a href="agreement.php"
                                        target="_blank">Üyelik Sözleşmesi</a> şartlarını okudum ve kabul
                                    ediyorum.</label>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block mb-1" type="submit"
                                style="background-color: #1d1a1a">Hesabımı Oluştur</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="container justify-content-center" style="display: flex;">
            <h5 class="justify-content-sm-center">
<!-- 
                <button class="btn btn-lg btn-primary btn-block mb-1" type="submit"
                    style="background-color: #1d1a1a"><a href="login.php" class="float-right" style="margin-right: 1px; color:yellow;">Giriş Yap</a></button> -->
            </h5>
        </div>

    </div>

</body>

</html>