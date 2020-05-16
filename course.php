<?php
$REQUIRE_LOGIN = TRUE;
include 'includes/page-common.php';
include 'includes/head.php';
?>
<link rel="stylesheet" href="assets/css/course.css">

<!-- <link rel="stylesheet" href="assets/css/social-share-kit.css" type="text/css">
<script type="text/javascript" src="assets/js/vendor/social-share-kit.min.js"></script> -->

<body>

    <?php
    include 'includes/nav-bar.php';

    if (!isset($_GET["course"])){
        header('Location: dashboard.php');
        die();
    }

    //course=veri-yapisi-12  ---> 12
    $COURSE_ID = UrlIdFrom("course");
    
    $COURSE = DersBilgileriniGetir($COURSE_ID);

    $Ders_Aktif_Mi = FALSE;
    if($COURSE["status"] ==1) 
        $Ders_Aktif_Mi=TRUE;
   
    
    if ($COURSE == NULL){
        header('Location: dashboard.php');
        die();
    }
        

    $DUZENLEYEN_ID = $COURSE["duzenleyen_id"];

    $DERS_HOCA = KullaniciBilgileriniGetirById($DUZENLEYEN_ID);

    $LOGIN_ID = $_SESSION["kullanici_id"];

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;
    $KAYITLIMI = DerseKayitliMi($LOGIN_ID, $COURSE_ID);
    
    if($DUZENLEYEN_ID == $LOGIN_ID)
        $GIRIS_YAPAN_DERSIN_HOCASI_MI = TRUE;

    if($KULLANICI["admin"] == 1 && $GIRIS_YAPAN_DERSIN_HOCASI_MI == FALSE)
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $LOGIN_ID);

    $ODEV_EKLEYEBILIR = FALSE;
    $DOKUMAN_EKLEYEBILIR = FALSE;
    $DUYURU_YAPABILIR = FALSE;
    $DUYURU_SILEBILIR = FALSE;

    $SINAV_EKLEYEBILIR = FALSE;
    $SINAV_SILEBILIR = FALSE;

    $YORUMDAN_SONRA_YENILE = FALSE;

    $DERS_RESMI_GUNCELLEYEBILIR = FALSE;
    
    if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
        $ODEV_EKLEYEBILIR = TRUE;
        $DOKUMAN_EKLEYEBILIR = TRUE;
        $DUYURU_YAPABILIR = TRUE;
        $DUYURU_SILEBILIR = TRUE;

        $SINAV_EKLEYEBILIR = TRUE;
        $DERS_RESMI_GUNCELLEYEBILIR = TRUE;

        $YORUMDAN_SONRA_YENILE = TRUE;
    }
    
    if($GIRIS_YAPAN_DERSIN_ASISTANI_MI){
        $ODEV_EKLEYEBILIR = TRUE;
        $DOKUMAN_EKLEYEBILIR = TRUE;
        $DUYURU_YAPABILIR = TRUE;
        $DUYURU_SILEBILIR = TRUE;

        $YORUMDAN_SONRA_YENILE = TRUE;
    }

    echo "<script>";
    echo "var ODEV_EKLEYEBILIR = ".($ODEV_EKLEYEBILIR ? "true" : "false").";";
    echo "var DOKUMAN_EKLEYEBILIR = ".($DOKUMAN_EKLEYEBILIR ? "true" : "false").";";
    echo "var DUYURU_SILEBILIR = ".($DUYURU_SILEBILIR ? "true" : "false").";";
    echo "var DUYURU_YAPABILIR = ".($DUYURU_YAPABILIR ? "true" : "false").";";
    echo "var SINAV_EKLEYEBILIR = ".($SINAV_EKLEYEBILIR ? "true" : "false").";";
    echo "var YORUMDAN_SONRA_YENILE = ".($YORUMDAN_SONRA_YENILE ? "true" : "false").";";
    echo "</script>";

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
    <div class="modal fade" id="dersResimDegisModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><b>Ders Resim Güncelle</b></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="dersResimForm" class="form" action="action/edit_course_action.php" method="POST"
                        enctype="multipart/form-data" style="margin-top:25px;">
                        <input type="hidden" name="ders_id" value="<?php echo $COURSE['id'] ?>">
                        <div class="form-group">
                            <label class="col-form-label"><b>Yeni Resim Seçiniz</b></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                                </div>
                                <input type="file" name="dosya" placeholder="" class="form-control"
                                accept = "image/*" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" style="float:right;">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php echo "<title>" . $COURSE["isim"] . "</title>" ;   
    if ($KAYITLIMI == TRUE || $GIRIS_YAPAN_DERSIN_HOCASI_MI){ ?>
    <div class="container">
      <?php if(!$Ders_Aktif_Mi){ ?>
          <div class="alert alert-danger" role="alert" style="text-align:center">BU DERS ARŞİVLENDİ.</div>
          <hr>
      <?php }   ?>

        <div class="detay">
            <div class="row">
                <div class="col-md-7 col-sm-12 ders-resim-container">
                    <?php if($DERS_RESMI_GUNCELLEYEBILIR) {?>
                    <button id="dersResimDegis" class="btn btn-info ders-resim-edit"><i class="fa fa-cog"></i></button>
                    <?php } ?>
                    <img class="ders-resim" src="files/images/course/<?php echo $COURSE["kodu"] ?>.png">
                </div>
                <div class="col-md-5 col-sm-12">
                    <h1 class='e-adi'><?php echo $COURSE["isim"]  ?></h1>
                    <div class="creator">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <b>Öğretmen: </b>
                        <a href="profile.php?id=<?php echo $DERS_HOCA["id"] ?>">
                            <?php echo $DERS_HOCA["adi"]." ".$DERS_HOCA["soyadi"] ?>
                        </a>
                    </div>
                    <div class="course-code">
                        <i class="fas fa-key"></i>
                        <b>Ders Kodu:</b><?php echo " ".$COURSE["kodu"] ?>
                    </div>
                    <div class="course-code">
                        <i class="fas fa-building"></i>
                        <b>Bölüm/Sınıf:</b><?php echo " ".$COURSE["bolum_adi"]." / ".$COURSE["sinif"] ?>
                    </div>
                    <div class="course-aciklama">
                        <p>
                            <?php 
                                $url = '@(http(s)?)(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                                $aciklama = preg_replace($url, '<a href="http$2://$4" target="_blank" title="$0">$0</a>', $COURSE["aciklama"]);
                                echo nl2br($aciklama); 
                                ?>
                        </p>
                        <?php if($GIRIS_YAPAN_DERSIN_HOCASI_MI && $Ders_Aktif_Mi){ ?>
                        <!-- <a class="btn btn-warning c-header-action" data-toggle="modal" data-target="#dersGuncelleModal">
                                <i class="fa fa-edit"></i>&nbsp;Düzenle
                            </a> -->
                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cog"></i> Düzenle
                        </button>

                        <div class="dropdown-menu">
                            <a class="dropdown-item" id="btnDersiKapat">Dersi Kapat</a>
                            <a class="dropdown-item c-header-action" id="btnDersiGuncelle" data-toggle="modal"
                                data-target="#dersGuncelleModal">Güncelle</a>
                        </div>

                        <?php } if($OGRENCI && $Ders_Aktif_Mi){  ?>
                        <button type="button" class="btn btn-danger" id="btnDerstenAyril">
                            <i class="fas fa-times"></i> Ayrıl
                        </button>
                        <?php } ?>
                    </div>
                </div>

            </div>


        </div>
        <!--  nav -->
        <div>
            <ul class="nav nav-tabs course-tabs" role="tablist">
                <li class="nav-item" id="genel_akis" tab-name="genel">
                    <a class="nav-link active" data-toggle="tab" href="#genel">Duyurular</a>
                </li>
                <li class="nav-item" tab-name="calismalar">
                    <a class="nav-link" data-toggle="tab" href="#calismalar">Sınıf Çalışmaları</a>
                </li>
                <li class="nav-item" tab-name="yorum">
                    <a class="nav-link" data-toggle="tab" href="#yorum">Tartışma</a>
                </li>
                <li class="nav-item" tab-name="katılımcı">
                    <a class="nav-link" data-toggle="tab" href="#katılımcı">Katılımcılar</a>
                </li>

            </ul>
            <!-- Tab panes -->
            <div class="tab-content ">
                <!-- Genel Akış -->
                <div id="genel" class="container tab-pane active" style="  margin-top: auto;"><br>
                    <h5><b>Duyurular</b></h5>
                    <div class="detay">
                        <?php include 'includes/course/duyuru.php' ?>
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
                        <?php include 'includes/course/comments.php' ?>
                    </div>
                </div>
                <!--  Katılımcılar -->
                <div id="katılımcı" class="container tab-pane fade" style="  margin-top: auto;"><br>
                    <h5><b>Katılımcılar</b></h5>
                    <div class="detay">
                        <?php include 'includes/course/katilimcilar.php' ?>
                    </div>
                </div>
               
            </div>
            <!-- /nav -->
        </div>
        <?php }else{ ?>
        <div class="container">
            <div class="detay">

                <div class="alert alert-danger" role="alert" style="text-align:center">Ders içeriği görüntülemek için
                    kayıt olun.</div>
                <?php   } ?>

            </div>
        </div>
        <div>
            <?php include 'includes/footer.php';  ?>
        </div>
    </div>


</body>

<script>
$(function() {
    $("#btnDersiKapat").on("click", function() {
        var dersId = $("#ders_id").val();
        if (!dersId)
            return;

        Swal.fire({
            title: 'Emin misiniz?',
            text: "Ders devre dışı bırakılacak",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, Bitir!',
            cancelButtonText: "Hayır"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: 'services/course.php?method=finish&ders_id=' + dersId,
                    success: function(response) {
                        location.reload();
                    },
                    error: ajaxGenelHataCallback
                })
            }
        });

    });

    $("#btnDerstenAyril").on("click", function() {
        var dersId = $("#ders_id").val();
        if (!dersId)
            return;

        Swal.fire({
            title: 'Emin misiniz?',
            text: "Dersten ayrılmak üzeresiniz",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Evet, Ayrıl!',
            cancelButtonText: "Hayır"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: 'services/course.php?method=ayril&ders_id=' + dersId,
                    success: function(response) {
                        Swal.fire({
                            text: 'Dersten Ayrıldınız',
                            type: 'success',
                            confirmButtonText: 'Tamam'
                        }).then((result) => {
                            location.reload();
                        });
                    },
                    error: ajaxGenelHataCallback
                })

            }
        });

    });

    $("#dersResimDegis").on("click", function() {
        $("#dersResimDegisModal").modal("show");
    });

    $("#dersResimForm").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "services/course.php?method=update_image",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                // location.reload();
                location.reload(true);
            },
            error: function(e) {
                Swal.fire({
                    text: 'Resim güncellenemedi!',
                    type: 'error',
                    confirmButtonText: 'Tamam'
                })
            }
        });
    });

    $(".course-tabs").on('click','.nav-item',function(e){
        var tab_name = $(e.target).attr("href");
  
        if(tab_name)
            localStorage.setItem("son-tab", tab_name);
    });

    var son_tab = localStorage.getItem("son-tab");
    var son_tab_element = $('a[href="'+ son_tab + '"]');
    if(son_tab && son_tab_element){
        //console.log("son tıklanan tab : "+ son_tab);
        $('a[href="'+ son_tab + '"]').trigger("click")
    }

})
</script>