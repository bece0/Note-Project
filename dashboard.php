<?php
    $REQUIRE_LOGIN = FALSE;
    $page_title = " Anasayfa";
    include 'includes/page-common.php';
    include 'includes/head.php';
    include 'includes/nav-bar.php';
    if($OGRETMEN) 
        $dersler= DuzenledigiDersleriGetir($kullanici_id);
        // asistan olunan dersler
    else
        $dersler = OgrenciDersleriniGetir($kullanici_id);
      
?>

<link rel="stylesheet" href="assets/css/index.css">
<?php 
    function badgeYazdir($icerik){
        echo  "<span class='badge'>".$icerik."</span>";
    }
?>
<body>
    <hr>
    <hr>

    <div class="container">
        <!-- Ders Kartları -->
        <div class="row justify-content-center" style="margin-top:30px">
            <?php
            if ($dersler != NULL && !is_null($dersler)) {
                $dersler_count = count($dersler);

                for ($i = 0; $i < $dersler_count; $i++) {
                    $ders = $dersler[$i];

                    $isim =  $ders["isim"];
                    $id =  $ders["id"];

                    $meaningFullUrl = ToMeaningfullUrl( $ders["isim"], $id)
            ?>

            <div>
                <div class="course-card" style="background:url(files/images/event/<?php echo $ders["kodu"] ?>.png) no-repeat 0 0;">
                    <div class="course-card-desc">
                        <div class="course-card-title">
                            <a href='course.php?course=<?php echo $meaningFullUrl; ?>'>
                                <h4><?php echo $isim ?></h4>
                            </a>
                        </div>
                        <div class="card-info">
                            <?php  
                                if(!$OGRENCI) { 
                                    $ogrenci_sayisi= DerseKayitliKisiSayisi($ders["id"]);
                                   badgeYazdir($ogrenci_sayisi." Öğrenci");
                                }else{
                    
                                    badgeYazdir("<i class='fas fa-user'></i>"." ".$ders["ogretmen_adi"]." ".$ders["ogretmen_soyadi"]);
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
                } //for-loop ends

                } else {  
            ?>
            
            <div class="alert alert-warning" style="margin-top:30px">Sistemde kayıtlı ders bulunamadı!</div>
            <?php }   ?>
        </div>

    </div>


    <hr>
    <div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>