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
    $kullanici_detail = KullaniciBilgileriniGetirById($kullanici_id);
    ?>

    <?php echo "<title>" . $course["isim"] . "</title>" ?>
    <div class="container">
        <div class="detay">
             <div class="row" style="margin-top:25px;">
      
                    <div class="col-md-7 col-sm-12">
                        <img class="etkinlik-resim" src="files/images/event/<?php echo $course["kodu"] ?>.png">

                    </div>

                    <div class="col-md-5 col-sm-12">
                        
                            <h1 class='e-adi'><?php echo $course["isim"]  ?></h1>
                            <div class="creator"> Öğretmen:
                                <a
                                    href="profile.php?id=<?php echo $event_creator["id"] ?>"><?php echo $event_creator["adi"] . " " . $event_creator["soyadi"] ?></a>
                                    <br><i class="fas fa-key"></i><?php echo "  Ders Kodu:  ". $course["kodu"] ?>
                            </div>
                            <div class="aciklama">
                                <p>
                                    <?php 
                                    $url = '@(http(s)?)(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                                    $aciklama = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $course["aciklama"]);
                                    echo nl2br($aciklama); 
                                    ?>
                                </p>
                                <?php if($OGRETMEN){ ?> 
                                    <a class="btn btn-info c-header-action" href="edit_course.php?course=<?php echo $course["id"]; ?>">
                                                <i class="fa fa-edit"></i>&nbsp;Düzenle
                                            </a>
                                <?php } ?>            
                           </div>
                    </div>
        
            </div>

             
           
        </div>
             <!--  nav -->
             <div> 
                  <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#genel">Genel Akış</a>
                            </li>
                            <li class="nav-item">
                             <a class="nav-link" data-toggle="tab" href="#calismalar">Sınıf Çalışmaları</a>
                            </li>
                            <?php if($OGRETMEN){ ?> 
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#notlar">Notlar</a>
                                        </li>
                            <?php } ?>
                                    
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#yorum">Yorumlar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#katılımcı">Katılımcılar</a>
                            </li>
                  </ul>
                        <!-- Tab panes -->
                    <div class="tab-content">
                            <div id="genel" class="container tab-pane active" style="  margin-top: auto;"><br>
                                 <h5><b>Genel Akış</b></h5>
                                  <div class="detay"> 
                                  <div class="alert alert-warning" role="alert">
                                         Bu derste etkinlik yok .
                                    </div>
                                     
                                  </div>

                            </div>
                            <div id="calismalar" class="container tab-pane fade" style="  margin-top: auto;"><br>

                                 <h5><b>Sınıf Çalışmaları</b></h5>
                                  <div class="detay">
                                  <?php if($OGRETMEN){ ?> 
                                  <div>
                                        <a class="btn btn-info c-header-action duyuru-yap" ders-id="<?php echo $course["id"]; ?>" ders-name="<?php echo $course["isim"]; ?>">
                                                    <i class="fa fa-bell"></i>&nbsp;Duyuru Yap
                                                </a>
                                        <a class="btn btn-success c-header-action olustur" ders-id="<?php echo $course["id"]; ?>" ders-name="<?php echo $course["isim"]; ?>">
                                                    <i class="fa fa-plus"></i>&nbsp;Oluştur
                                        </a>  
                                    <?php } ?>   
                                  </div> 
                                  <br>   
                                     <!-- calismalar -->

                                     <div class="alert alert-warning" role="alert">
                                        Bu derste çalışma yok.
                                    </div>
                                         
                                  </div>

                            </div>
                            <div id="notlar" class="container tab-pane fade" style="  margin-top: auto;"><br>
                                 <h5><b>Notlar</b></h5>
                                  <div class="detay">
                                                    
                                     <!-- Notlar -->
                                     <div class="alert alert-warning" role="alert">
                                        Bu derste notlandırılmış ödev yok.
                                    </div>
                                         
                                  </div>
                          
                            </div>
                            <div id="yorum" class="container tab-pane fade" style="  margin-top: auto;"><br>
                             <h5><b>Yorumlar</b></h5>
                             <div class="detay">
                                <?php include 'includes/comments.php' ?>
                                </div>
                          
                            </div>
                            <!--  katılımcı -->
                            <div id="katılımcı" class="container tab-pane fade" style="  margin-top: auto;"><br>
                            <h5><b>Katılımcılar</b></h5>
                            
                                <div class="detay">
                                <div class="modal-body">
                                                <!-- KİŞİ LİSTESİ-->
                                            <?php
                                                 $participant_list = DersKatilimcilariniGetir($course["id"]);
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
                      
                             <!-- / katılımcı -->   


             </div>  
             <!-- /nav -->
    </div>    
  
<hr>
    <div>
        <?php include 'includes/footer.php'; ?>
    </div>
</body>

<script>
// duyuru
        $(".duyuru-yap").on("click", function(e) {
            var ders_id = $(e.target).attr("ders-id");
            var ders_name = $(e.target).attr("ders-name");

            Swal.fire({
                title: 'Ders Duyurusu',
                text: ders_name,
                input: 'textarea',
                inputPlaceholder: 'Öğrencilere gönderilecek olan mesajı buraya yazın...',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-paper-plane"></i> Gönder!',
                cancelButtonText: '<i class="fa fa-times"></i> İptal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Duyuru içeriği girmelisiniz!'
                    }
                    if (value.length < 15) {
                        return 'Duyuru içeriği çok kısa!'
                    }

                    // var regex = new RegExp("^[a-zA-Z0-9]+$");
                    // var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                    // if (!regex.test(value.length)) {
                    //     return 'Sadece alfanumeric değerler kabul edilmektedir.'
                    // }
                }
            }).then((result) => {
                if (!result.value)
                    return;
                DuyuruGonder(ders_id, result.value);
            });
        })

        function DuyuruGonder(ders_id, duyuru) {
            var data = {
                ders_id: ders_id,
                announcement: duyuru
            }
            $.ajax({
                type: "POST",
                url: 'services/notification.php?method=event_announcement',
                data: {
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    if (response && response.sonuc) {
                        Swal.fire({
                            type: 'success',
                            title: 'Katılımcılara duyuru gönderildi',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    } else {
                        console.log(response);
                        Swal.fire({
                            title: 'Hata',
                            text: 'Duyuru gönderilemedi, lütfen daha sonra tekrar deneyin.',
                            type: 'warning',
                            timer: 2000,
                            showConfirmButton: false,
                        })
                    }
                },
                error: function(jqXHR, error, errorThrown) {
                    console.log(error);
                    Swal.fire({
                        title: 'Hata',
                        text: 'Duyuru gönderilemedi, lütfen daha sonra tekrar deneyin.',
                        type: 'warning',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                }
            });
        }

// oluştur

$(".olustur").on("click", function(e) {
            var ders_id = $(e.target).attr("ders-id");
            var ders_name = $(e.target).attr("ders-name");

            Swal.fire({
                title: 'Çalışma oluştur',
                text: ders_name,
                input: 'textarea',
                inputPlaceholder: 'Öğrencilere gönderilecek olan mesajı buraya yazın...',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-paper-plane"></i> Gönder!',
                cancelButtonText: '<i class="fa fa-times"></i> İptal',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Duyuru içeriği girmelisiniz!'
                    }
                    if (value.length < 15) {
                        return 'Duyuru içeriği çok kısa!'
                    }
                }
            }).then((result) => {
                if (!result.value)
                    return;
                DuyuruGonder(ders_id, result.value);
            });
        })

        function DuyuruGonder(ders_id, duyuru) {
            var data = {
                ders_id: ders_id,
                announcement: duyuru
            }
            $.ajax({
                type: "POST",
                url: 'services/notification.php?method=event_announcement',
                data: {
                    data: JSON.stringify(data)
                },
                success: function(response) {
                    if (response && response.sonuc) {
                        Swal.fire({
                            type: 'success',
                            title: 'Katılımcılara duyuru gönderildi',
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    } else {
                        console.log(response);
                        Swal.fire({
                            title: 'Hata',
                            text: 'Duyuru gönderilemedi, lütfen daha sonra tekrar deneyin.',
                            type: 'warning',
                            timer: 2000,
                            showConfirmButton: false,
                        })
                    }
                },
                error: function(jqXHR, error, errorThrown) {
                    console.log(error);
                    Swal.fire({
                        title: 'Hata',
                        text: 'Duyuru gönderilemedi, lütfen daha sonra tekrar deneyin.',
                        type: 'warning',
                        timer: 2000,
                        showConfirmButton: false,
                    })
                }
            });
        }





    </script>