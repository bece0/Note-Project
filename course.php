<?php
$REQUIRE_LOGIN = TRUE;
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

    //course=veri-yapisi-12  ---> 12
    $COURSE_ID = UrlIdFrom("course");
    
    $COURSE = DersBilgileriniGetir($COURSE_ID);

    if ($COURSE == NULL)
        header('Location: dashboard.php');

    $DUZENLEYEN_ID = KullaniciBilgileriniGetirById($COURSE["duzenleyen_id"]);

    $DERS_HOCA = KullaniciBilgileriniGetirById($kullanici_id);

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $_SESSION["kullanici_id"]);
   
    //ders_id değerini gizli input olarak gömüyoruz, javascript tarafında kullanmak için
    echo "<input type='hidden' id='ders_id' value='$COURSE_ID'/>";
   ?>

    <?php if($GIRIS_YAPAN_DERSIN_HOCASI_MI) { ?>
    <div class="modal fade" id="dersGuncelleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><b>Ders Güncelle</b></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form" action="action/edit_course_action.php" method="POST"
                        enctype="multipart/form-data" style="margin-top:25px;">
                        <input type="hidden" name="ders_id" value="<?php echo $COURSE['id'] ?>">
                        <div class="form-group">
                            <label class="col-form-label"><b>Ders Adı</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                                </div>
                                <input type="text" name="ders_adi" placeholder="" class="form-control" required
                                    value="<?php echo $COURSE['isim'] ?>" maxlength="25">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label"><b>Bölüm Adı</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                                </div>
                                <input type="text" name="bolum_adi" placeholder="" class="form-control" required
                                    value="<?php echo $COURSE['bolum_adi'] ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class=" control-label"><b>Kontenjan</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="number" name="kontenjan" placeholder="" class="form-control"
                                    required="true" value="<?php echo $COURSE['kontenjan'] ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label"><b>Sınıf</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-thumbtack"></i></span>
                                </div>
                                <input type="text" name="sinif" placeholder="" class="form-control" required
                                    value="<?php echo $COURSE['sinif'] ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><b>Açıklama</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pen"></i></span>
                                </div>
                                <textarea rows="3" name="aciklama" placeholder="Ders detayını açıklayın..."
                                    class="form-control" required="true"><?php echo $COURSE['aciklama'] ?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" style="float:right;">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>


    <?php echo "<title>" . $COURSE["isim"] . "</title>" ?>
    <div class="container">
        <div class="detay">
            <div class="row" style="margin-top:25px;">

                <div class="col-md-7 col-sm-12">
                    <img class="etkinlik-resim" src="files/images/event/<?php echo $COURSE["kodu"] ?>.png">

                </div>

                <div class="col-md-5 col-sm-12">

                    <h1 class='e-adi'><?php echo $COURSE["isim"]  ?></h1>
                    <div class="creator">
                        <div>
                            Öğretmen:
                            <a href="profile.php?id=<?php echo $DUZENLEYEN_ID["id"] ?>">
                                <?php echo $DUZENLEYEN_ID["adi"] . " " . $DUZENLEYEN_ID["soyadi"] ?>
                            </a>
                        </div>
                    </div>
                    <div class="course-code">
                        <i class="fas fa-key"></i><?php echo "  Ders Kodu:  ". $COURSE["kodu"] ?>
                    </div>
                    <div class="aciklama">
                        <p>
                            <?php 
                                    $url = '@(http(s)?)(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                                    $aciklama = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $COURSE["aciklama"]);
                                    echo nl2br($aciklama); 
                                    ?>
                        </p>
                        <?php if($GIRIS_YAPAN_DERSIN_HOCASI_MI){ ?>
                        <a class="btn btn-info c-header-action" data-toggle="modal" data-target="#dersGuncelleModal">
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
                <li class="nav-item" id="genel_akis">
                    <a class="nav-link active" data-toggle="tab" href="#genel">Genel Akış</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#calismalar">Sınıf Çalışmaları</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#yorum">Tartışma</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#katılımcı">Katılımcılar</a>
                </li>
                <?php if($OGRETMEN){ ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#notlar">Notlar</a>
                </li>
                <?php } ?>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Genel Akış -->
                <div id="genel" class="container tab-pane active" style="  margin-top: auto;"><br>
                    <h5><b>Genel Akış</b></h5>
                    <div class="detay" id="duyurulistesi">
                        <?php include 'includes/course/genel_akis.php' ?>
                    </div>
                </div>
                <!-- Sınıf Çalışmaları -->
                <div id="calismalar" class="container tab-pane fade" style="  margin-top: auto;"><br>
                    <h5><b>Sınıf Çalışmaları</b></h5>
                    <div class="detay">
                        <?php include 'includes/course/sinif_calismalari.php' ?>
                    </div>
                </div>
                <!-- Tartışma -->
                <div id="yorum" class="container tab-pane fade" style="  margin-top: auto;"><br>
                    <h5><b>Tartışma</b></h5>
                    <div class="detay">
                        <?php include 'includes/comments.php' ?>
                    </div>
                </div>
                <!--  Katılımcılar -->
                <div id="katılımcı" class="container tab-pane fade" style="  margin-top: auto;"><br>
                    <h5><b>Katılımcılar</b></h5>
                    <div class="detay">
                        <?php include 'includes/course/katilimcilar.php' ?>
                    </div>
                </div>
                <!-- Notlar -->
                <?php if($OGRETMEN){ ?>
                <div id="notlar" class="container tab-pane fade" style="  margin-top: auto;"><br>
                    <h5><b>Notlar</b></h5>
                    <div class="detay">
                        <?php include 'includes/course/notlar.php' ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <!-- /nav -->
        </div>

        <div>
            <?php include 'includes/footer.php'; ?>
        </div>
</body>

<script>

// oluştur
$(".OdevOlustur").on("click", function(e) {
    var ders_id = $(e.target).attr("ders-id");
    var ders_name = $(e.target).attr("ders-name");

    Swal.fire({
        title: 'Ödev oluştur',
        text: ders_name,
        input: 'file',
        //inputPlaceholder: ' ',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-paper-plane"></i> Paylaş!',
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
        PaylasimGonder(ders_id, result.value);
    });
})

$(".DokumanOlustur").on("click", function(e) {
    var ders_id = $(e.target).attr("ders-id");
    var ders_name = $(e.target).attr("ders-name");

    Swal.fire({
        title: 'Doküman oluştur',
        text: ders_name,
        input: 'file',
        //inputPlaceholder: ' ',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-paper-plane"></i> Paylaş!',
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
        PaylasimGonder(ders_id, result.value);
    });
})

function PaylasimGonder(ders_id, duyuru) {
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
                    title: 'Katılımcılara paylaşım gönderildi',
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