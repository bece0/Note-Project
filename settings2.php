<?php
$REQUIRE_LOGIN = TRUE;
$page_title = "Ayarlar";

include 'includes/page-common.php';
include 'includes/head.php';

if (isset($HIDE_NAVBAR) && $HIDE_NAVBAR == TRUE) {
    echo "<link rel='stylesheet' href='assets/css/mobile-web-view.css'>";
} else {
    include 'includes/nav-bar.php';
}

?>

<link rel="stylesheet" href="assets/css/settings.css">
<script src="assets/js/vendor/imageupload.js"></script>

<style>
    .settings-header {
        margin-bottom: 1rem;
    }

    .preview-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin-top: 20px;
    }

    #preview {
        border: solid 1px;
        height: 240px;
        border-radius: 15px;
    }

    .form-300 {
        width: 300px;
    }
</style>
<?php
$KULLANICI = KullaniciBilgileriniGetirById($kullanici_id);
$AYARLAR = KullaniciAyarlariniGetirById($kullanici_id);

//ayarlar null ise varsayılan ayarları göster
if ($AYARLAR == NULL) {
    $AYARLAR = array(
        "id" => 0,
        "kullanici_id" => $kullanici_id,
        "profil_private" => "no",
    );
}

if (!isset($AYARLAR["profil_private"]))
    $AYARLAR["profil_private"]  = "no";

?>

<body>
    <div class="container">
        <!-- <div class="row"> -->
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <!-- <li class="nav-item"> -->
                <!-- </li> -->
                <!-- <li class="nav-item"> -->
                <a class="nav-item nav-link active" id="v-pills-security-tab" data-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">Güvenlik</a>
                <!-- </li> -->
                <a class="nav-item nav-link " id="v-pills-privacy-tab" data-toggle="pill" href="#v-pills-privacy" role="tab" aria-controls="v-pills-privacy" aria-selected="false">Gizlilik</a>

            </div>
        </nav>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel" aria-labelledby="v-pills-privacy-tab">
                <form style="margin-top: 10px;" id="gizlilik_form">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="<?php echo $AYARLAR["profil_private"] ?>" name="profil_private" id="profil_private" required <?php if ($AYARLAR["profil_private"] == "yes") echo "checked" ?>>
                            <label class="form-check-label" for="profil_private">
                                Profilimi gizle
                            </label>
                        </div>
                    </div>

                    <button id="btn_gizlilik_kaydet" class="btn btn-primary btn-sm" type="button" disabled>Güncelle</button>
                </form>
            </div>
            <div class="tab-pane fade show active" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                <h5 style="margin-top:10px">Parola Değiştir</h5><br>
                <div>
                    <form>
                        <div class="form-group">
                            <label for="password" style="font-size: smaller;">Yeni Parola</label>
                            <input type="password" class="form-control" id="password" placeholder="Yeni Parola">
                        </div>
                        <div class="form-group">
                            <label for="password_repeat" style="font-size: smaller;">Yeni Parola Tekrar</label>
                            <input type="password" class="form-control" id="password_repeat" placeholder="Yeni Parola Tekrar">
                        </div>
                        <button id="password_update" type="button" class="btn btn-primary">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $("#gizlilik_form :input").on("change", function() {
            $("#btn_gizlilik_kaydet").prop("disabled", false);
        });

        $("#profil_form :input").on("change", function() {
            $("#btn_profil_kaydet").prop("disabled", false);
        });

        $("#btn_gizlilik_kaydet").on("click", function() {
            var ayarlar = {
                profil_private: $('#profil_private').is(":checked") ? "yes" : "no"
            }
            AyarGonder("gizlilik", ayarlar, $("#btn_gizlilik_kaydet"));
        });

        $("#btn_profil_kaydet").on("click", function() {
            var ayarlar = {
                sehir: $("#sehir").val()
            }
            AyarGonder("profil", ayarlar, $("#btn_profil_kaydet"));
        });

        $("#password_update").on("click", function() {
            var password = $("#password").val();
            var password_repeat = $("#password_repeat").val();

            if (!password || !password_repeat || (password != password_repeat)) {
                alert("parolalar eşleşmiyor!");
                return;
            }

            var ayarlar = {
                password: password
            }
            AyarGonder("password", ayarlar, $("#password_update"), "Parola güncellendi", function() {
                $("#password").val("");
                $("#password_repeat").val("");
            });
        });

        function AyarGonder(ayarAdi, data, button, succesMessage, succesCallback) {
            if (!ayarAdi || !data)
                return;

            var url = 'services/settings.php?method=' + ayarAdi;
            if (API_ISTEGIMI && API_KEY) {
                url = url + "&X-Api-Key=" + API_KEY;
            }

            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: {
                    data: JSON.stringify(data)
                },
                // data: $("#comment-form").serialize(),
                success: function(response) {
                    if (response && response.sonuc) {
                        $("#txt_comment").val("");
                        Swal.fire({
                            title: succesMessage || 'Ayarlar güncellendi!',
                            type: 'success',
                            confirmButtonText: 'Tamam'
                        })
                    }
                    if (button)
                        $(button).prop("disabled", true);
                    if (succesCallback && typeof succesCallback == "function")
                        succesCallback();
                },
                error: function(jqXHR, error, errorThrown) {
                    console.log(error);
                    Swal.fire({
                        title: 'Ayarlar güncellenemedi!',
                        type: 'error',
                        confirmButtonText: 'Tamam'
                    })
                }
            });
        }

        $(function() {
            var p = new profilePicture('.profile', null, {
                imageHelper: true,
                onRemove: function(type) {
                    $('.preview').hide().attr('src', '');
                },
                onError: function(type) {
                    console.log('Error type: ' + type);
                }
            });


            $('#previewBtn').on('click', function() {
                $('.preview').show().attr('src', p.getAsDataURL());
            });

            // $('#uploadBtn').on('click', function() {
            //     $("#uploadExample").show();
            // });

            $('#uploadBtn').on('click', function() {
                var imageData = {
                    base64: p.getAsDataURL()
                }

                var formData,
                    data = p.getData();

                formData = new FormData();
                formData.append('file', data.file);
                // Crop size ( Final image size )
                // formData.append('cropHeight', data.cropHeight);
                // formData.append('cropWidth', data.cropWidth);
                // // Image X and Y position
                // formData.append('x', data.x);
                // formData.append('y', data.y);
                // // New image size (the size needed to position the image)
                // formData.append('newWidth', data.width);
                // formData.append('newHeight', data.height);
                // // Zoom/Scale; 0.1 = 10%
                // formData.append('zoom', data.zoom);
                // // Original image size (if needed)
                // formData.append('originalWidth', data.originalWidth);
                // formData.append('originalHeight', data.originalHeight);

                $.ajax({
                    url: "services/settings.php?method=profile-pic",
                    data: {
                        data: JSON.stringify(imageData)
                    },
                    type: 'POST',
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        Swal.fire({
                            title: 'Profil fotoğrafı güncellendi!',
                            type: 'success',
                            timer: 2000
                        })
                    }
                });

            });

        })
    </script>
</body>