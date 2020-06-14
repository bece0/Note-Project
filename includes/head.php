<?php

//$REQUIRE_LOGIN - giriş gerektiren sayfaların başına eklenir
//$REQUIRE_ADMIN - admin yetkisi gerektiren sayfaların başına eklenir
//$page_title - sayfa başlığını (title) ayarlamak için kullanılır

if (!isset($_SESSION)) {
	session_start();
}

$kullanici_id = 0;
$HIDE_NAVBAR = FALSE;
$API_KEY = "";
$API_ISTEGI = FALSE;

if (isset($REQUIRE_LOGIN) && $REQUIRE_LOGIN == TRUE && !isset($_SESSION["kullanici_id"])) {
	if (isset($_GET['X-Api-Key']) || isset($_GET['x-api-key'])) {
		$API_KEY = "";
		if (isset($_GET['X-Api-Key']))
			$API_KEY = $_GET['X-Api-Key'];
		if ($API_KEY == NULL || $API_KEY == "")
			$API_KEY = $_GET['x-api-key'];

		
		$HIDE_NAVBAR = TRUE;
		include_once dirname(__FILE__) .'/../database/database.php';
		$KULLANICI = KullaniciBilgileriniGetirByAPI($API_KEY);
		if ($KULLANICI != NULL) {
			$kullanici_id = $KULLANICI["id"];
			$API_ISTEGI = TRUE;
		} else {
			echo "X-Api-Key değeri geçersiz, logine gider..";
			die();
			// header('Location: login.php');
		}
	} else {
		echo "logine gider...";
		var_dump($_GET);
		die();
		// header('Location: login.php');
	}
} else {
	if (isset($_SESSION["kullanici_id"]))
		$kullanici_id = $_SESSION["kullanici_id"];
}

if (isset($REQUIRE_ADMIN) && $REQUIRE_ADMIN == TRUE) {
	if (!isset($_SESSION["admin"]) || (isset($_SESSION["admin"]) && $_SESSION["admin"] != 1)) {
		//header('Location: dashboard.php');
	}
} else if (isset($REQUIRE_SYSTEM_ADMIN) && $REQUIRE_SYSTEM_ADMIN == TRUE) {
	if (!isset($_SESSION["admin"]) || (isset($_SESSION["admin"]) && $_SESSION["admin"] != -1)) {
		header('Location: ../index.php');
	}
}


$base_url = getenv('BASE_URL');
if ($base_url == FALSE) {
	$base_url = "/note/";
}

?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<base href="<?php echo  $base_url; ?>">

	<link rel="apple-touch-icon" sizes="57x57" href="files/favicon/kep.png">
	<link rel="apple-touch-icon" sizes="60x60" href="files/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="files/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="files/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="files/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="files/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="files/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="files/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="files/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="files/favicon/android-icon-192x192.png">
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


	<?php
	if (isset($page_title)) {
		echo "<title> note - " . $page_title . "</title>";
	}

	function ToLowerandEnglish($value)
	{
		$turkish = array("ı", "ğ", "ü", "ş", "ö", "ç", "İ", "Ğ", "Ü", "Ş", "Ö", "Ç"); //turkish letters
		$english   = array("i", "g", "u", "s", "o", "c", "I", "G", "U", "S", "O", "C"); //english cooridinators letters
		$value = str_replace($turkish, $english, $value); //replace php function
		$value = strtolower($value);
		return $value;
	}

	include 'includes/ortak.php';
	?>

</head>

<script>
	var API_ISTEGIMI = <?php echo $API_ISTEGI == TRUE ? 'true;': 'false;'; echo "\n"; ?>
	var API_KEY =  <?php echo $API_KEY == "" ? '"";': '"'.$API_KEY.'";'; echo "\n"; ?>
</script>