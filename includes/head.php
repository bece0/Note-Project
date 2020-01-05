<?php 

  //$REQUIRE_LOGIN - giriş gerektiren sayfaların başına eklenir
  //$REQUIRE_ADMIN - admin yetkisi gerektiren sayfaların başına eklenir
  //$page_title - sayfa başlığını (title) ayarlamak için kullanılır

if(!isset($_SESSION))
{
  session_start();
}

  $kullanici_id = 0;
  //isset($REQUIRE_LOGIN) && $REQUIRE_LOGIN == TRUE && !isset($_SESSION["kullanici_id"])
  if(isset($REQUIRE_LOGIN) && $REQUIRE_LOGIN == TRUE && !isset($_SESSION["kullanici_id"])){
    echo "logine gider..";
    header('Location: login.php');
  }else {
    if(isset($_SESSION["kullanici_id"]))
      $kullanici_id = $_SESSION["kullanici_id"];
  }

  if(isset($REQUIRE_ADMIN) && $REQUIRE_ADMIN == TRUE )
    if(!isset($_SESSION["admin"]) || (isset($_SESSION["admin"]) && $_SESSION["admin"] != 1)){
      //header('Location: dashboard.php');
    }
  

    $base_url = getenv('MOVE_BASE_URL');
    if($base_url == FALSE){
      $base_url = "/classroom/";
    }

?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo  $base_url;?>">

    <link rel="apple-touch-icon" sizes="57x57" href="files/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="files/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="files/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="files/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="files/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="files/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="files/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="files/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="files/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="files/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="files/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="files/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="files/favicon/favicon-16x16.png">
    <link rel="manifest" href="files/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="files/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">


    <script src="assets/js/vendor/jquery-3.4.1.min.js"></script>
    <script src="assets/js/vendor/popper.min.js"></script>
    <script src="assets/js/vendor/bootstrap.min.js"></script>
    <script src="assets/js/vendor/moment.min.js"></script>
    <script src="assets/js/vendor/tempusdominus-bootstrap-4.min.js"></script>
    <script src="assets/js/vendor/sweetalert2@8.js"></script>

    <script src="assets/js/genel.js"></script>

    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/fontawesome.all.css">
    <link rel="stylesheet" href="assets/css/vendor/tempusdominus-bootstrap-4.min.css" />

    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"> -->

    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
      window.jQuery || document.write('<script src="assets/js/vendor/jquery-3.4.1.min.js"><\/script>')
    </script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="assets/lib/momentjs/moment.js"></script>
    <script src="assets/lib/momentjs/tr.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
      window.Swal || document.write('<script src="assets/js/vendor/sweetalert2.min.js"><\/script>')
    </script> -->

  <?php 
    //echo dirname(__FILE__);
    if(isset($page_title)){
      echo "<title> note - " .$page_title."</title>";
    }

    function ToLowerandEnglish($value){

      //echo $value;

      $turkish = array("ı", "ğ", "ü", "ş", "ö", "ç","İ","Ğ","Ü","Ş","Ö","Ç");//turkish letters
      $english   = array("i", "g", "u", "s", "o", "c","I","G","U","S","O","C");//english cooridinators letters

      $value = str_replace($turkish, $english, $value);//replace php function
      $value=strtolower($value);

      return $value;
    }
  ?>

</head>