<?php
$REQUIRE_LOGIN = TRUE;
$page_title = "Derslerim";

include 'includes/page-common.php';
include 'includes/head.php';
?>
<style>
    .event-date {
        float: right;
        font-size: medium;
        margin-right: 25px;
    }

    .c-header-action {
        margin-left: 5px;
        cursor: pointer;
    }
</style>

<body>
    <?php
    setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'trk', 'turkish');
    include 'includes/nav-bar.php';

    $kullanici_id  = $_SESSION["kullanici_id"];
    $kullanici_detail = KullaniciBilgileriniGetirById($kullanici_id);

    $duzenledigi_dersler = DuzenledigiDersleriGetir($kullanici_id);
    $gecmis_dersler = DuzenledigiGecmisDersleriGetir($kullanici_id);
    ?>

    <div class="container">
        <h4>Derslerim</h4>
        <hr>
        <div class="row">
            <div class="col-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                    <?php if($kullanici_detail["admin"]=1) 
                                 echo " Oluşturulan Dersler";
                               else echo "Kayıtlı dersler";
                             ?>
                    </a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                       Arşivlenmiş Dersler
                    </a>
                </div>
            </div>
            <div class="col-9">
                <div class="tab-content" id="v-pills-tabContent">

                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                        <?php
                        if ($duzenledigi_dersler != NULL && count($duzenledigi_dersler) > 0) {
                            for ($i = 0; $i < count($duzenledigi_dersler); $i++) {
                                $ders = $duzenledigi_dersler[$i];
                                ?>
                                <div class="card row mx-2 mb-3">
                                    <div class="card-header">
                                        <a class="btn btn-warning c-header-action" href="edit_course.php?course=<?php echo $ders["id"]; ?>">
                                            <i class="fa fa-edit"></i>&nbsp;Düzenle
                                        </a>
                                        <a class="btn btn-warning c-header-action duyuru-yap" event-id="<?php echo $ders["id"]; ?>" event-name="<?php echo $ders["isim"]; ?>">
                                            <i class="fa fa-bell"></i>&nbsp;Duyuru Yap
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="">
                                            <?php
                                            $isim =  $ders["isim"];
                                            $id =  $ders["id"];
                                            echo "<a href='course.php?course=$id'> $isim </a>"
                                            ?>
                                            <!-- <p class="card-text event-date">
                                                <i class="fas fa-clock"></i>
                                                <?php //echo turkcetarih_formati("d M Y", $etkinlik["tarih"]); ?>
                                            </p> -->
                                        </h5>
                                    </div>
                                </div>
                            <?php }
                    } else { ?>
                            <div class="alert alert-warning" role="alert">
                            <?php if($kullanici_detail["admin"]=1) 
                                 echo " Daha önce ders oluşturmadınız";
                               else echo "Daha önce derse kaydolmadınız";
                             ?>
                            </div>
                        <?php  }  ?>
                    </div>

                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <?php
                        if ($gecmis_dersler != NULL && count($gecmis_dersler) > 0) {
                            for ($i = 0; $i < count($gecmis_dersler); $i++) {
                                $etkinlik = $gecmis_dersler[$i];
                                ?>
                                <div class="card row mx-2 mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?php
                                            $isim =  $ders["isim"];
                                            $id =  $ders["id"];
                                            echo "<a href='course.php?course=$id'> $isim </a>"
                                            ?>
                                            <p class="card-text event-date">
                                                <i class="fas fa-clock"></i>
                                                <?php echo turkcetarih_formati("d M Y", $ders["tarih"]); ?>
                                            </p>
                                        </h5>
                                    </div>
                                </div>
                            <?php }
                    } else { ?>
                            <div class="alert alert-warning" role="alert">
                                Daha önce bir ders arşivlemediniz.
                            </div>
                        <?php  }  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".duyuru-yap").on("click", function(e) {
            var event_id = $(e.target).attr("event-id");
            var event_name = $(e.target).attr("event-name");

            Swal.fire({
                title: 'Ders Duyurusu',
                text: event_name,
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
                DuyuruGonder(event_id, result.value);
            });
        })

        function DuyuruGonder(event_id, duyuru) {
            var data = {
                event_id: event_id,
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
</body>