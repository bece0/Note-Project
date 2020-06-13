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
        float: right !important;
        margin-right: -15px;
    }
}

.logo-1 {
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

            if($KULLANICI['admin'] != "" && $KULLANICI['admin'] == 0){
                $OGRENCI = TRUE;
                include 'course_attend_modal.php';
            }  
            else if ($KULLANICI['admin'] != "" && $KULLANICI['admin'] == 1){
                $OGRETMEN = TRUE;
                include 'course_modals.php';
                include 'course_attend_modal.php';
            }
        }?>
<nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">

    <a class="navbar-brand" href="index.php"> 
        <!-- <img class="logo-1" src="files/images/note2.png"> -->
        <i class="fa fa-reply"></i>
    </a>

   
</nav>
<script>
$(document).ready(function() {
    $(".dropdown").hover(
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("fast");
            $(this).toggleClass('open');
        },
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("fast");
            $(this).toggleClass('open');
        }
    );
});
</script>

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