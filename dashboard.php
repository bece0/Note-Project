<?php
    $REQUIRE_LOGIN = FALSE;
    $page_title = " Anasayfa";
    include 'includes/page-common.php';
    include 'includes/head.php';
    include 'includes/nav-bar.php';
    if($OGRETMEN) 
        $etkinlikler= DuzenledigiDersleriGetir($kullanici_id);
    else
        $etkinlikler = OgrenciDersleriniGetir($kullanici_id);
   
?>

<link rel="stylesheet" href="assets/css/index.css">

<body>
    <hr>
    <hr>

    <div class="container">
        <!-- Ders Kartları -->
        <div class="row justify-content-center" style="margin-top:30px">
            <?php
            if ($etkinlikler != NULL && !is_null($etkinlikler)) {
                $etkinlikler_count = count($etkinlikler);

                for ($i = 0; $i < $etkinlikler_count; $i++) {
                    $etkinlik = $etkinlikler[$i];

                    $isim =  $etkinlik["isim"];
                    $id =  $etkinlik["id"];

                    $meaningFullUrl = ToMeaningfullUrl( $etkinlik["isim"], $id)
            ?>

            <div class="col-sm-3">
                <div class="card-section">
                    <div class="card-section-image">
                        <a class='cat-image-link' href='course.php?course=<?php echo $meaningFullUrl; ?>'>
                            <img class="etkinlik-resim" src="files/images/event/<?php echo $etkinlik["kodu"] ?>.png"
                                onerror="this.onerror=null; this.src='files/images/<?php echo ToEnglish($etkinlik["tip"]); ?>.png'">
                        </a>
                    </div>
                    <div class="card-desc">
                        <div class="event-title">
                            <a href='course.php?course=<?php echo $meaningFullUrl; ?>'>
                                <h3><?php echo $isim ?></h3>
                            </a>
                        </div>
                        <div class="card-info">
                            <ul class="list-unstyle">
                                <li>
                                    <i class="fas fa-user"></i>
                                    <?php
                                        if($OGRENCI)
                                            echo " ".$etkinlik["ogretmen_adi"]." ".$etkinlik["ogretmen_soyadi"];
                                            //ÖĞRETMEN İSE TOPLAM KAYITLI ÖĞRENCİ SAYISI.... 
                                        else  echo "<b>Kontenjan: </b> ".$etkinlik["kontenjan"];
                                     ?>
                                </li>
                            </ul>

                        </div>
                        
                        <!-- <a href='event.php?event=<?php echo $id ?>' class="cart_btn btn btn-dark">Detay</a> -->
                    </div>
                </div>
            </div>

            <?php }
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