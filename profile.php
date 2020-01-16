<?php
$REQUIRE_LOGIN = TRUE;
include 'includes/page-common.php';
include 'includes/head.php';
?>
<link rel="stylesheet" href="assets/css/profile.css">
<!-- TODO - https://codepen.io/dsalvagni/pen/BLapab KULLAN -->

<body>
    <?php
    setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'trk', 'turkish');
    include 'includes/nav-bar.php';

    $kullanici_id = 0;

    if (isset($_GET["id"])) {
        $kullanici_id = UrlIdFrom("id");
    } else if (isset($_GET["user"])) {
        $kullanici_id = UrlIdFrom("user");
    }

    if ($kullanici_id == NULL || $kullanici_id == 0) {
        header('Location: dashboard.php');
    }

    echo $kullanici_id;


    $ayarlar = KullaniciAyarlariniGetirById($kullanici_id);
    $kullanici_detail = KullaniciBilgileriniGetirById($kullanici_id);

    if($kullanici_detail == NULL){
        header('Location: dashboard.php');
        die();
    }

    //$yeni_Dersler=KullaniciYeniDersleriniGetir($kullanici_id);
    //$eski_Dersler=KullaniciEskiDersleriniGetir($kullanici_id);

    $eski_Dersler = [];
    if ($ayarlar["gecmis_private"] == "no")
        $eski_Dersler = KullaniciEskiDersleriniGetir($kullanici_id);

    $gelecek_Dersler = [];
    if ($ayarlar["gelecek_private"] == "no")
        $gelecek_Dersler = KullaniciYeniDersleriniGetir($kullanici_id);
    ?>

    <div class="container">
        <div class="profile-detail">
            <div class="row">
                <div class="col-md-9 col-sm-12">
                    <div class="container" style="border-bottom:1px solid black">
                        <h2 class="profile-name">
                            <?php echo $kullanici_detail["adi"] . " " . $kullanici_detail["soyadi"]  ?>
                        </h2>
                    </div>
                    <br />
                    <ul class="container details" style="list-style: none;">
                        <li>
                            <p>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo "Konum: " . $ayarlar["sehir"]  ?>
                            </p>
                        </li>
                        <li>
                            <p>
                                <i class="fas fa-calendar-alt"></i>
                                <?php
                                echo "Üyelik Tarihi: ";
                                echo turkcetarih_formati("d M Y ", $kullanici_detail["kayit_tarihi"]);
                                ?>
                            </p>
                        </li>
                        <li>
                            <p>
                                <i class="fas fa-clock"></i>
                                <?php
                                echo "Son Ziyaret : ";
                                echo turkcetarih_formati("d M Y ", $kullanici_detail["son_giris_tarihi"]);
                                ?>
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="profile-pic">
                        <img src="files/profile/<?php echo $kullanici_detail["id"] ?>.png"
                            alt="<?php echo $kullanici_detail["adi"] . " " . $kullanici_detail["soyadi"] ?>"
                            class="profile-img" onerror="this.onerror=null; this.src='files/profile/profile.png'">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div>
            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home"
                            role="tab" aria-controls="v-pills-home" aria-selected="true">
                            <?php if($kullanici_detail["admin"]=1) 
                                echo "Oluşturduğu Dersler" ;
                               else 
                                echo "Katıldığı Dersler";
                             ?>
                        </a>
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile"
                            role="tab" aria-controls="v-pills-profile" aria-selected="false">
                            Arşivlenmiş Dersler
                        </a>
                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="v-pills-tabContent">

                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                            aria-labelledby="v-pills-home-tab">
                            <?php
                            $gelecek_Dersler_count = 0;
                            if ($gelecek_Dersler != NULL)
                                $gelecek_Dersler_count = count($gelecek_Dersler);

                            if ($gelecek_Dersler != NULL && $gelecek_Dersler_count > 0) {
                                for ($i = 0; $i < count($gelecek_Dersler); $i++) {
                                    $etkinlik = $gelecek_Dersler[$i];
                                    ?>
                            <div class="card row mx-2 mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span
                                            class="badge badge-secondary event-type"><?php echo $etkinlik["tip"] ?></span>
                                        <?php
                                                $isim =  $etkinlik["isim"];
                                                $id =  $etkinlik["id"];
                                                echo "<a href='event.php?event=$id'> $isim </a>"
                                                ?>
                                        <p class="card-text"
                                            style="     float: right; font-size: medium; margin-right: 25px;">
                                            <i class="fas fa-clock"></i>
                                            <?php
                                                    echo turkcetarih_formati("d M Y", $etkinlik["tarih"]);
                                                    ?></p>
                                    </h5>
                                    <!-- <p class="card-text"> <?php echo $etkinlik["k_aciklama"] ?></p> -->
                                </div>
                            </div>
                            <?php }
                        } else { ?>
                            <div class="alert alert-warning" role="alert">
                                <?php if($kullanici_detail["admin"]=1) 
                                 echo $kullanici_detail["adi"]." herhangi bir ders oluşturmadı.";
                               else echo $kullanici_detail["adi"]." herhangi bir derse kayıtlı değil.";
                             ?>

                            </div>
                            <?php  }  ?>
                        </div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab">
                            <?php
                            if ($eski_Dersler != NULL  && count($eski_Dersler) > 0) {
                                for ($i = 0; $i < count($eski_Dersler); $i++) {
                                    $etkinlik = $eski_Dersler[$i];
                                    ?>
                            <div class="card row mx-2 mb-3">
                                <div class="card-body" style="">
                                    <h5 class="card-title">

                                        <span
                                            class="badge badge-secondary event-type"><?php echo $etkinlik["tip"] ?></span>
                                        <?php
                                                $isim =  $etkinlik["isim"];
                                                $id =  $etkinlik["id"];
                                                echo "<a href='event.php?event=$id'> $isim </a>"
                                                ?>
                                        <p class="card-text"
                                            style="     float: right; font-size: medium; margin-right: 25px;">
                                            <i class="fas fa-clock"></i>
                                            <?php echo turkcetarih_formati("d M Y", $etkinlik["tarih"]); ?>
                                        </p>
                                    </h5>
                                    <!-- <p class="card-text"> <?php echo $etkinlik["k_aciklama"] ?></p> -->
                                </div>
                            </div>
                            <?php }
                        } else { ?>
                            <div class="alert alert-warning" role="alert">
                                <?php echo $kullanici_detail["adi"] ?> herhangi bir ders arşivlemedi.
                            </div>
                            <?php  }  ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>