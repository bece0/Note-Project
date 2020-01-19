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

 $dersler = [];
 $asistan_dersler=[];
 $arsiv_dersler= [];

    if($kullanici_detail["admin"]==1){
        $dersler = DuzenledigiAktifDersleriGetir($kullanici_id);
        $asistan_dersler = AsistanOlunanDersleriGetir($kullanici_id); 
        $arsiv_dersler = OgretmeninArsivlenmisDersleriniGetir($kullanici_id);  
    }
    else{
        $dersler = OgrencininAktifDersleriniGetir($kullanici_id);
        $arsiv_dersler = OgrencininArsivlenmisDersleriniGetir($kullanici_id);
    }
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
            <?php if ($kullanici_id==$_SESSION["kullanici_id"] || $ayarlar["dersler_private"] == "no"){ ?>
                <div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-ders-tab" data-toggle="pill" href="#v-pills-ders"
                                role="tab" aria-controls="v-pills-ders" aria-selected="true">
                                <b> <?php if($kullanici_detail["admin"]==1) 
                                    echo "Oluşturduğu Dersler" ;
                                else 
                                    echo "Katıldığı Dersler";
                                ?>
                                </b>
                            </a>
                            <?php if($kullanici_detail["admin"]==1){?>
                            <a class="nav-link" id="v-pills-asistan-tab" data-toggle="pill" href="#v-pills-asistan" role="tab"
                                aria-controls="v-pills-asistan" aria-selected="false">
                                <b> 
                                Asistan Olunan Dersler                        
                                </b>
                            </a> 
                            <?php } ?> 
                            <a class="nav-link" id="v-pills-arsiv-tab" data-toggle="pill" href="#v-pills-arsiv"
                                role="tab" aria-controls="v-pills-arsiv" aria-selected="false">
                                <b>
                                    Arşivlenmiş Dersler
                                </b>
                            </a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">

                            <div class="tab-pane fade show active" id="v-pills-ders" role="tabpanel"
                                aria-labelledby="v-pills-ders-tab">
                                <?php
                                $dersler_count = 0;
                                if ($dersler != NULL)
                                    $dersler_count = count($dersler);

                                if ($dersler != NULL && $dersler_count > 0) {
                                    for ($i = 0; $i < count($dersler); $i++) {
                                        $ders = $dersler[$i];
                                        ?>
                                <div class="card row mx-2 mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                        
                                            <?php
                                                    $isim =  $ders["isim"];
                                                    $id =  $ders["id"];
                                                    echo "<a href='course.php?course=$id'> $isim </a>"
                                                    ?>
                                        </h5>
                                    
                                    </div>
                                </div>
                                <?php }
                                } else { ?>
                                <div class="alert alert-warning" role="alert">
                                    <?php if($kullanici_detail["admin"]==1) 
                                    echo $kullanici_detail["adi"]." herhangi bir ders oluşturmadı.";
                                else echo $kullanici_detail["adi"]." herhangi bir derse kayıtlı değil.";
                                ?>

                                </div>
                                <?php  }  ?>
                            </div>

                            <div class="tab-pane fade" id="v-pills-asistan" role="tabpanel" 
                                aria-labelledby="v-pills-asistan-tab">
                                <?php
                                $asistan_dersler_count = 0;
                                if ($asistan_dersler != NULL)
                                    $asistan_dersler_count = count($asistan_dersler);

                                if ($asistan_dersler != NULL && $asistan_dersler_count > 0) {
                                    for ($i = 0; $i < count($asistan_dersler); $i++) {
                                        $asistan_ders = $asistan_dersler[$i];
                                        ?>
                                <div class="card row mx-2 mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                        
                                            <?php
                                                    $isim =  $asistan_ders["isim"];
                                                    $id =  $asistan_ders["id"];
                                                    echo "<a href='course.php?course=$id'> $isim </a>"
                                                    ?>
                                        </h5>
                                    
                                    </div>
                                </div>
                                <?php }
                            } else { ?>
                                <div class="alert alert-warning" role="alert">
                                    <?php
                                    echo $kullanici_detail["adi"]." herhangi bir dersin asistanı değil.";
                                ?>

                                </div>
                                <?php  }  ?>

                            </div>

                            <div class="tab-pane fade" id="v-pills-arsiv" role="tabpanel" 
                            aria-labelledby="v-pills-arsiv-tab">
                            <?php
                                    $arsiv_dersler_count = 0;
                                    if ($arsiv_dersler != NULL)
                                        $arsiv_dersler_count = count($arsiv_dersler);

                                    if ($arsiv_dersler != NULL && $arsiv_dersler_count > 0) {
                                        for ($i = 0; $i < count($arsiv_dersler); $i++) {
                                            $arsiv_ders = $arsiv_dersler[$i];
                                            ?>
                                    <div class="card row mx-2 mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                            
                                                <?php
                                                        $isim =  $arsiv_ders["isim"];
                                                        $id =  $arsiv_ders["id"];
                                                        echo "<a href='course.php?course=$id'> $isim </a>"
                                                        ?>
                                            </h5>
                                        
                                        </div>
                                    </div>
                                    <?php }
                                    } else { ?>
                                    <div class="alert alert-warning" role="alert">
                                        <?php if($kullanici_detail["admin"]==1) 
                                        echo $kullanici_detail["adi"]." herhangi bir ders arşivlemedi.";
                                        else echo $kullanici_detail["adi"]." herhangi bir arşivlenen dersi yok.";
                                    ?>

                                    </div>
                                    <?php  }  ?>                          
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php }

        // var_dump($kullanici_id);
        // echo "<br>";
        // var_dump($_SESSION["kullanici_id"]);

        ?>
    </div>
   

</body>