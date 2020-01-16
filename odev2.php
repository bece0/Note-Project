<?php
$REQUIRE_LOGIN = TRUE;
include 'includes/page-common.php';
include 'includes/head.php';
?>
<link rel="stylesheet" href="assets/css/odev.css">

<body>


    <?php
    include 'includes/nav-bar.php';
    // include 'includes/page-common.php';

    if (!isset($_GET["kod"]) && $_GET["kod"] != ""){
        header('Location: dashboard.php');
        die();
    }

    if(!isset($baglanti)){
        $baglanti = BAGLANTI_GETIR();
    }

    $KOD = mysqli_real_escape_string($baglanti, $_GET["kod"]);
    $ODEV = GetOdevDetailsByKod($KOD);

    if($ODEV == NULL){
        header('Location: dashboard.php');
        die();
    }

    

    $ODEV_ID = $ODEV["id"];
    $COURSE_ID = $ODEV["ders_id"];
    $COURSE = DersBilgileriniGetir($COURSE_ID);

    echo "<title>".$COURSE["isim"]." - ".$ODEV["isim"]."</title>";

    $DUZENLEYEN_ID = $COURSE["duzenleyen_id"];

    $DERS_HOCA = KullaniciBilgileriniGetirById($DUZENLEYEN_ID);

    $LOGIN_ID = $_SESSION["kullanici_id"];

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_OGRENCISI_MI = FALSE;
    
    if($DUZENLEYEN_ID == $LOGIN_ID)
        $GIRIS_YAPAN_DERSIN_HOCASI_MI = TRUE;

    if($KULLANICI["admin"] == 1 && $GIRIS_YAPAN_DERSIN_HOCASI_MI == FALSE)
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $LOGIN_ID);

    if(!$GIRIS_YAPAN_DERSIN_HOCASI_MI && !$GIRIS_YAPAN_DERSIN_ASISTANI_MI)
        $GIRIS_YAPAN_DERSIN_OGRENCISI_MI = TRUE;

   ?>

    <!-- start container -->
    <div class="container" style="min-height: 500px;">
        <div class="row">
            <?php 
            $DIV_CLASS = "col-md-8";
            if(!$GIRIS_YAPAN_DERSIN_OGRENCISI_MI)
                $DIV_CLASS = "col-md-12"
            ?>
            <div class="<?php echo $DIV_CLASS; ?>">
                <div class="detay">
                    <div class="odev-detay">
                        <h3 class="odev-isim">
                            <i class="fa fa-file-alt"></i>
                            <?php  echo $COURSE["isim"]." - ".$ODEV["isim"]; ?>
                        </h3>
                        <div>
                            <div class="odev-kunye">
                                <div class="odev-tarih"><?php echo zamanOnce($ODEV["olusturma_tarih"]); ?></div>
                                <div class="odev-yukleyen"><?php echo $ODEV["isim"]." ". $ODEV["soyadi"]; ?></div>
                            </div>
                            
                        </div>
                        <hr>
                        <div class="odev-aciklama"><?php  echo $ODEV["aciklama"]; ?></div>
                        <?php if($ODEV["dosya_id"]){
                        $DOSYA = GetDosyaById($ODEV["dosya_id"]);
                        if($DOSYA != NULL){
                    ?>
                        <div class="odev-dosya">
                            <span><i class="fa fa-file"></i> Ödev Dosyası : </span>
                            <a target="blank_" href='dosya_indir.php?type=odev&kod=<?php echo $ODEV["kod"]?>'>
                                <i class="fa fa-download"></i>&nbsp;
                                <?php echo $DOSYA["isim"]?>
                            </a>
                        </div>
                        <?php } }?>
                        <div class="odev-son-tarih">
                            <span><i class="fa fa-clock"></i> Son Gönderim Tarihi : </span>
                            <span class="odev-son-tarih-t"><?php echo $ODEV["son_tarih"]?></span>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($GIRIS_YAPAN_DERSIN_OGRENCISI_MI) {?>
            <div class="col-md-4 col-sm-12">
                <div class="detay">
                    <?php include 'includes/odev/odev_upload.php'?>
                </div>
            </div>
            <?php }?>

        </div>
    </div>
    <!-- end container -->


    <div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>