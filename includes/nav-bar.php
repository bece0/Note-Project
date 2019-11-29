<?php 
  //session_start();
  include dirname(__FILE__) .'/../database/database.php';
  $GirisYapildiMi = isset($_SESSION["kullanici_id"]);
   // echo  $_SERVER['PHP_SELF'];
   //echo basename(__FILE__);
  
?>

<style>
@media (min-width: 768px) {
    .right-nav {
        margin-right: 2vw;
    }
 
    .navbar-right {
        float: right!important;
        margin-right: -15px;
    }
}

.logo-1{
    margin-bottom: -5px;
    margin-right: 5px;
    width: 100px;
    padding: 1px;
    border-radius: 50px;
    margin-top: -6px;
}
</style>
   <?php 
        if($GirisYapildiMi){ 
            $KULLANICI = KullaniciBilgileriniGetirById($_SESSION["kullanici_id"]);

            $OGRETMEN = FALSE;
            $OGRENCI = FALSE;

            if($KULLANICI['admin'] != "" && $KULLANICI['admin'] == 0)
                $OGRENCI = TRUE;
            else if ($KULLANICI['admin'] != "" && $KULLANICI['admin'] == 1)
                $OGRETMEN = TRUE;
        }?>
<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">
    
    <a class="navbar-brand" href="index.php"> 
        <img class="logo-1" src="files/images/note2.png">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <?php if($GirisYapildiMi){ ?>
            <li class="nav-item">
                <a class="nav-link" href="takvim.php">Takvim</a>
            </li>
            <?php if($OGRENCI) {?>
                <li class="nav-item">
                    <a class="nav-link" href="attend_course.php">Derse Kayıt Ol</a>
                </li>
            <?php } ?>

            <?php if($OGRETMEN) {?>
            <li class="nav-item">
                <a class="nav-link" href="create_course.php" data-toggle="modal" data-target="#myModal">Ders Oluştur</a>
            </li>
            <?php } ?>
        <?php } ?>
        </ul>
        <ul class="navbar-nav ml-auto right-nav">
            <!-- Giriş yapılmadıysa NavBarda User var-->
            <?php if($GirisYapildiMi){ ?>
                <?php include 'notifications.php';?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $KULLANICI["adi"]?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="menuDropdown">
                        <?php if($KULLANICI['admin'] != "" && $KULLANICI['admin'] == 1) {?>
                            <!-- öğretmen menusu -->
                        <a class="dropdown-item" href="profile.php?id=<?php echo $KULLANICI["id"]?>">Profil</a>
                        
                            <a class="dropdown-item" href="my_course.php">Dersler</a>
                            <a class="dropdown-item" href="settings.php">Ayarlar</a>
                        <?php } else {?>
                            <!-- öğrenci menusu-->
                            <a class="dropdown-item" href="profile.php?id=<?php echo $KULLANICI["id"]?>">Profil</a>
                            <!-- <a class="dropdown-item" href="my_course.php">Dersler</a> -->
                            <a class="dropdown-item" href="settings.php">Ayarlar</a>
                        <?php } ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="action/logout_action.php">Çıkış</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <!-- <a class="nav-link" href="login.php">Giriş</a> -->
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Ders Oluştur</h4>
        </div>
        <div class="modal-body">

        <form class="form" action="action/create_course_action.php" method="POST" enctype="multipart/form-data"
            style="margin-top:15px;">
            <div class="form-group">
                <!-- <label class="col-form-label">Ders Adı</label> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                    </div>
                    <input id="etkinlik_adi" name="etkinlik_adi" placeholder="Ders Adı" class="form-control" required
                        type="text">
                </div>
            </div>

            <div class="form-group">
                <!-- <label class="col-form-label">Bölüm Adı</label> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                    </div>
                    <input id="bolum_adi" name="bolum_adi" placeholder="Bölüm Adı" class="form-control" required
                        type="text">
                </div>
            </div>

            <div class="form-group">
                <!-- <label class=" control-label">Kontenjan</label> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-thumbtack"></i></span>
                    </div>
                    <input id="kontenjan" name="kontenjan" placeholder="Kontenjan" class="form-control" required="true" value=""
                        type="number">
                </div>
            </div>

            <div class="form-group">
                <!-- <label class="col-form-label">Sınıf</label> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                    </div>
                    <input id="sinif" name="sinif" placeholder="Sınıf" class="form-control" required
                        type="text">
                </div>
            </div>
    
            <div class="form-group">
                <!-- <label class="control-label">Konu</label> -->
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                    </div>
                    <textarea rows="2" id="aciklama" name="aciklama" placeholder="Ders Konu"
                        class="form-control" required="true"></textarea>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success" style="float:right;">Oluştur</button>
        </form>
    </div>

      </div>
      
    </div>
  </div>

<?php  
  $type = "info";
  $mesaj = "";
  $title = "";

  if(isset($_SESSION["_success"])){
    $mesaj =  $_SESSION["_success"];
    $type =  "success";
    $title = "İşlem tamamlandı";
    unset($_SESSION["_success"]);
  }else if(isset($_SESSION["_info"])){
    $mesaj =  $_SESSION["_info"];
    $type =  "info";
    $title = "Bilgi";
    unset($_SESSION["_info"]);
  }else if(isset($_SESSION["_error"])){
    $mesaj =  $_SESSION["_error"];
    $type =  "error";
    $title = "Hata";
    unset($_SESSION["_error"]);
  }else if(isset($_SESSION["_warning"])){
    $mesaj =  $_SESSION["_warning"];
    $type =  "warning";
    $title = "Uyarı";
    unset($_SESSION["_warning"]);
  } 
  
  $log = "";
  if(isset($_SESSION["_log"])){
    $log = $_SESSION["_log"];
  }
  
  ?>


  <?php   if($mesaj != ""){ ?>
  <script>
    Swal.fire({
        title: '<?php echo $title?>',
        text: '<?php echo $mesaj?>',
        type: '<?php echo $type?>',
        confirmButtonText: 'Tamam'
    })
  </script>
  <?php } ?>

  <?php   if($log != ""){ ?>
  <script>
     console.log('<?php echo $log?>');
  </script>
  <?php } ?>