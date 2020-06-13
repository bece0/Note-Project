<?php
$REQUIRE_LOGIN = TRUE;
include 'includes/page-common.php';
include 'includes/head.php';
?>
<link rel="stylesheet" href="assets/css/odev.css">

<body>


    <?php
    include 'includes/nav-bar2.php';
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

    $Ders_Aktif_Mi = FALSE;
    if($COURSE["status"] ==1) 
        $Ders_Aktif_Mi=TRUE;
        
    echo "<title>".$COURSE["isim"]." - ".$ODEV["isim"]."</title>";

    $DUZENLEYEN_ID = $COURSE["duzenleyen_id"];

    $DERS_HOCA = KullaniciBilgileriniGetirById($DUZENLEYEN_ID);

    $LOGIN_ID = $_SESSION["kullanici_id"];

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_OGRENCISI_MI = FALSE;
    $GONDERILEN_ODEVLERI_LISTELEYEBILIR = FALSE;
    $ODEV_SILEBILIR = FALSE;
    
    if($DUZENLEYEN_ID == $LOGIN_ID){
        $GIRIS_YAPAN_DERSIN_HOCASI_MI = TRUE;
        $ODEV_SILEBILIR = TRUE;
    }

    if($KULLANICI["admin"] == 1 && $GIRIS_YAPAN_DERSIN_HOCASI_MI == FALSE)
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $LOGIN_ID);

    if(!$GIRIS_YAPAN_DERSIN_HOCASI_MI && !$GIRIS_YAPAN_DERSIN_ASISTANI_MI)
        $GIRIS_YAPAN_DERSIN_OGRENCISI_MI = TRUE;

    if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
        $GONDERILEN_ODEVLERI_LISTELEYEBILIR = TRUE;
    }

    $ODEV_TARIHI_GECTI = FALSE;
    if(strtotime($ODEV["son_tarih"]) < time()){
        $ODEV_TARIHI_GECTI = TRUE;
    }

    $dersUrl = ToMeaningfullUrl($COURSE["isim"], $COURSE["id"])
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
                            <span>
                                <a href='course.php?course=<?php echo $dersUrl;?>'>
                                    <i class="fa fa-file-alt"></i>
                                    <?php echo $COURSE["isim"] ?>
                                </a>
                            </span>
                            <span style="font-size: 1.45rem;"><?php echo " | ".$ODEV["isim"]; ?><span>
                        </h3>
                        <div>
                            <div class="odev-kunye">
                                <div class="odev-tarih" title='<?php echo $ODEV["olusturma_tarih"];?>'>
                                    <?php echo zamanOnce($ODEV["olusturma_tarih"]); ?>
                                </div>
                                <div class="odev-yukleyen">
                                    <?php echo $ODEV["ogretmen_adi"]." ". $ODEV["ogretmen_soyadi"]; ?>
                                </div>
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

                <?php if($ODEV_SILEBILIR ) {?>
                <div class="odev-detay-controller" style="margin-top:6px">
                    <button id="odevIptalEt" odev_kod="<?php echo $ODEV["kod"];?>" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                        Ödevi İptal Et
                    </button>
                </div>
                <?php }?>

            </div>

            <!-- COL-MD-8 SONU -->


            <?php if($GIRIS_YAPAN_DERSIN_OGRENCISI_MI ) {?>
            <div class="col-md-4 col-sm-12">
                <div class="detay">
                    <?php include 'includes/odev/odev_upload.php'; ?>
                </div>
            </div>
            <?php }?>



        </div>

        </hr>
        <div class="row" style="margin-top: 20px;">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <?php if($GONDERILEN_ODEVLERI_LISTELEYEBILIR){ ?>
                    <a class="nav-link active" id="v-pills-gonderilen-odevler-tab" data-toggle="pill"
                        href="#v-pills-gonderilen-odevler" role="tab" aria-controls="v-pills-gonderilen-odevler"
                        aria-selected="true">
                        Teslim Eden Öğrenciler
                    </a>
                    <?php }?>
                </div>
            </div>
            <div class="col-9"> 
                <div class="tab-content" id="v-pills-tabContent">

                    <?php if($GONDERILEN_ODEVLERI_LISTELEYEBILIR){ ?>
                    <div class="tab-pane fade show active" id="v-pills-gonderilen-odevler" role="tabpanel"
                        aria-labelledby="v-pills-gonderilen-odevler-tab">
                        <?php include 'includes/odev/odev_gonderilen_listesi.php'; ?>
                    </div>
                    <?php }?>

                </div>
            </div>
        </div>

    </div>

    <script>
    $(function() {

        $("#odevIptalEt").on("click", function(e) {
            var odev_kod = $(e.target).attr("odev_kod");

            if (!odev_kod)
                return;

            Swal.fire({
                title: 'Emin misiniz?',
                text: "Ödev sistemden tamamen silinecektir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Evet, Sil!',
                cancelButtonText: "Hayır"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: 'services/odev.php?method=delete&odev_kod=' + odev_kod,
                        success: function(response) {
                            // location.reload();
                            // window.location.replace("/");
                            document.location.href = document.getElementsByTagName('base')[0].href
                        },
                        error: ajaxGenelHataCallback
                    })
                }
            });

        });



    })
    </script>

</body>