<?php
include 'includes/page-common.php';
include 'includes/head.php';
?>
<link rel="stylesheet" href="assets/css/event.css">

<link rel="stylesheet" href="assets/css/social-share-kit.css" type="text/css">
<script type="text/javascript" src="assets/js/vendor/social-share-kit.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<body>
    <?php
    include 'includes/nav-bar.php';

    if (!isset($_GET["course"]))
        header('Location: dashboard.php');

    $event_id = UrlIdFrom("course");
    //event=istanbul-bilmem-ne-etkinligi-12

    $course = DersBilgileriniGetir($event_id);

    if ($course == NULL)
        header('Location: dashboard.php');

    $event_creator = KullaniciBilgileriniGetirById($course["duzenleyen_id"]);
    //var_dump($course)
    ?>

    <?php echo "<title>" . $course["isim"] . "</title>" ?>
    <div class="container">
    
        <div class="row" style="margin-top:25px;">
     
        <div class="col-md-6 col-sm-12">
                <img class="etkinlik-resim" src="files/images/event/<?php echo $course["kodu"] ?>.png">
                <div class="aciklama">
                    <p>
                        <?php 
                        $url = '@(http(s)?)(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                        $aciklama = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $course["aciklama"]);
                        echo nl2br($aciklama); 
                        ?>
                    </p>
                </div>
                <div>
                    <?php include 'includes/comments.php' ?>
                </div>
            </div>

            <div class="col-md-5 col-sm-12">
                <h1 class='e-adi'><?php echo $course["isim"]  ?></h1>
                <div class="creator"> Öğretmen:
                    <a
                        href="profile.php?id=<?php echo $event_creator["id"] ?>"><?php echo $event_creator["adi"] . " " . $event_creator["soyadi"] ?></a>
                        <br><i class="fas fa-key"></i><?php echo "  Ders Kodu:  ". $course["kodu"] ?>
                </div>

                <hr>
                <div class="detay">
                    <h4>Katılımcılar</h4>
                    <hr>
                    <div>
                        <?php
                        $participant_list = DersKatilimcilariniGetir($course["id"]);
                        if ($participant_list != NULL) {
                            echo "<div class='katilimciler'>";
                            $katilimci_sayisi = count($participant_list);
                            if ($katilimci_sayisi > 5) {
                                $katilimci_sayisi = 5;
                            }
                            for ($i = 0; $i < $katilimci_sayisi; $i++) {
                                $user_detail = $participant_list[$i];
                                ?>
                        <img class="avatar" src="files/profile/<?php echo $user_detail['id'] ?>.png"
                            title="<?php echo $user_detail['adi'] ?>" alt="<?php echo $user_detail['adi'] ?>"
                            onerror="this.onerror=null; this.src='files/profile/profile.png'">
                        <?php   } ?>

                        <div class=" n-kisi-daha">
                            <a data-toggle="modal" data-target="#myModal" href="#">Tümünü gör</a>
                        </div>

                        <?php    } else { ?>
                        <div class="alert alert-warning" role="alert">
                            Bu derste katılımcı yok.
                        </div>
                        <?php  } ?>
                    </div>
                </div>
  
                <hr>

               

                    <!-- Katılımcı listesi modal -->
                    <div class=" modal fade" id="myModal" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4>Katılımcılar</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <!-- KİŞİ LİSTESİ-->
                                    <?php
                                    //$user_list = EtkinlikKatilimcilariniGetir($course["id"]); 
                                    if ($participant_list != NULL) {
                                        for ($i = 0; $i < count($participant_list); $i++) {
                                            $user_detail = $participant_list[$i];
                                            $ad_soyad =  $user_detail['adi'] . " " . $user_detail['soyadi'];
                                            $user_url = $user_detail['adi']."-". $user_detail['soyadi']."-".$user_detail['id'];
                                            ?>

                                    <div class="modal-user">
                                        <img class="avatar" src="files/profile/<?php echo $user_detail['id'] ?>.png"
                                            title="<?php echo $user_detail['adi'] ?>"
                                            alt="<?php echo $user_detail['adi'] ?>"
                                            onerror="this.onerror=null; this.src='files/profile/profile.png'">
                                        <a href="profile.php?user=<?php echo $user_url; ?>"><?php echo $ad_soyad ?></a>
                                    </div>

                                    <?php  }
                                } else { ?>
                                    <div class="alert alert-warning" role="alert">
                                        Bu derste katılımcı yok.
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
           
        </div>
     
    </div>    
  

    <div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>