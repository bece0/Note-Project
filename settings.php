<?php
$REQUIRE_LOGIN = TRUE;
$page_title = "Ayarlar";

include 'includes/page-common.php';
include 'includes/head.php';
include 'includes/nav-bar.php';
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
        "yeni_etkinlik_mail" => "no",
        "duyuru_mail" => "no",
        "gecmis_private" => "no",
        "gelecek_private" => "no",
        "dil" => "Türkçe",
    );
}

$DESTEKLENEN_DILLER = array("Türkçe" => "Türkçe", "English" => "English");
?>

<body>
    <div class="container">
    <br>
        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-pills-general-tab" data-toggle="pill" href="#v-pills-general" role="tab" aria-controls="v-pills-general" aria-selected="true">Genel</a>
                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profil</a>
                    <a class="nav-link" id="v-pills-privacy-tab" data-toggle="pill" href="#v-pills-privacy" role="tab" aria-controls="v-pills-privacy" aria-selected="false">Gizlilik</a>
                    <a class="nav-link" id="v-pills-security-tab" data-toggle="pill" href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">Güvenlik</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                        <form id="genel_form">
                            <div class="form-group row">
                                <label for="dil" class="col-sm-2 col-form-label">Arayüz Dili</label>
                                <div class="col-sm-10">
                                    <select class="form-control form-control-sm form-300" name="dil" id="dil">
                                        <?php
                                        foreach ($DESTEKLENEN_DILLER as $key => $value) {
                                            if ($AYARLAR["dil"] == $key)
                                                echo "<option selected value='$key'>$value</option>";
                                            else
                                                echo "<option value='$key'>$value</option>";
                                        }
                                        for ($i = 0; $i < count($TURKIYE_ILLER); $i++) { }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="<?php echo $AYARLAR["duyuru_mail"] ?>" name="duyuru_mail" id="duyuru_mail" required <?php if ($AYARLAR["duyuru_mail"] == "yes") echo "checked" ?>>
                                    <label class="form-check-label" for="duyuru_mail">
                                        Duyurular hakkında bildirim almak istiyorum.
                                    </label>
                                </div>
                            </div>
                            <button id="btn_genel_kaydet" class="btn btn-primary btn-sm" type="button" disabled>Ayarları
                                Kaydet</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <form style="margin-top: 10px;" id="profil_form">
                            <div class="form-group row">
                                <label for="sehir" class="col-sm-2 col-form-label">Şehir</label>
                                <div class="col-sm-10">
                                    <select class="form-control form-control-sm form-300" name="sehir" id="sehir">
                                        <?php
                                        for ($i = 0; $i < count($TURKIYE_ILLER); $i++) {
                                            if ($AYARLAR["sehir"] == $TURKIYE_ILLER[$i])
                                                echo "<option selected value='$TURKIYE_ILLER[$i]'>$TURKIYE_ILLER[$i]</option>";
                                            else
                                                echo "<option value='$TURKIYE_ILLER[$i]'>$TURKIYE_ILLER[$i]</option>";
                                        }
                                        ?>
                                    </select>
                                    <!-- <div class="valid-feedback" style="display: block;">Şehir ayarına göre duyurular
                                        alırsınız.</div> -->
                                </div>
                            </div>
                            <button id="btn_profil_kaydet" class="btn btn-primary btn-sm" type="button" disabled>Güncelle</button>
                        </form>
                        <div>
                            <div class="row" id="image-upload">
                                <div class="profile">
                                    <div class="photo">
                                        <input type="file" accept="image/*">
                                        <div class="photo__helper">
                                            <div class="photo__frame photo__frame--circle">
                                                <canvas class="photo__canvas"></canvas>
                                                <div class="message is-empty">
                                                    <p class="message--desktop">Buraya tıklayarak ya da resmi buraya
                                                        sürükleyip bırakabilirsiniz.</p>
                                                    <p class="message--mobile">Buraya tıklayarak resim
                                                        yükleyebilirsiniz.</p>
                                                </div>
                                                <div class="message is-loading">
                                                    <i class="fa fa-2x fa-spin fa-spinner"></i>
                                                </div>
                                                <div class="message is-dragover">
                                                    <i class="fa fa-2x fa-cloud-upload"></i>
                                                    <p>Resmi bırakın</p>
                                                </div>
                                                <div class="message is-wrong-file-type">
                                                    <p>Sadece resim türündeki dosyalar!.</p>
                                                    <p class="message--desktop">Buraya tıklayarak ya da resmi buraya
                                                        sürükleyip bırakabilirsiniz.</p>
                                                    <p class="message--mobile">Buraya tıklayarak resim
                                                        yükleyebilirsiniz.</p>
                                                </div>
                                                <div class="message is-wrong-image-size">
                                                    <p>Resminiz 150px den büyük olmalı.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="photo__options hide">
                                            <div class="photo__zoom">
                                                <input type="range" class="zoom-handler">
                                            </div><a href="javascript:;" class="remove"><i class="fa fa-trash"></i></a>
                                        </div>
                                        <button type="button" id="uploadBtn">Profil Resmini Güncelle</button>
                                        <img src="" alt="" class="preview">
                                        <img src="" alt="" class="preview preview--rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-privacy" role="tabpanel" aria-labelledby="v-pills-privacy-tab">
                        <form style="margin-top: 10px;" id="gizlilik_form">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="<?php echo $AYARLAR["gecmis_private"] ?>" name="gecmis_private" id="gecmis_private" required <?php if ($AYARLAR["gecmis_private"] == "yes") echo "checked" ?>>
                                    <label class="form-check-label" for="gecmis_private">
                                        Derslerimi  gizle
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="<?php echo $AYARLAR["gelecek_private"] ?>" name="gelecek_private" id="gelecek_private" required <?php if ($AYARLAR["gelecek_private"] == "yes") echo "checked" ?>>
                                    <label class="form-check-label" for="gelecek_private">
                                        Arşivlenmiş derslerimi gizle
                                    </label>
                                </div>
                            </div>
                            <button id="btn_gizlilik_kaydet" class="btn btn-primary btn-sm" type="button" disabled>Ayarları
                                Kaydet</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                        <h4>Parola Değiştir<h4/><br>
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
                //genel_form id'li form içinde bulunan inputlar değişirse kaydet butonu aktif edilecek.
                $("#genel_form :input").on("change", function() {
                    $("#btn_genel_kaydet").prop("disabled", false);
                });

                $("#gizlilik_form :input").on("change", function() {
                    $("#btn_gizlilik_kaydet").prop("disabled", false);
                });

                $("#profil_form :input").on("change", function() {
                    $("#btn_profil_kaydet").prop("disabled", false);
                });

                $("#btn_genel_kaydet").on("click", function() {
                    var ayarlar = {
                        dil: $("#dil").val(),
                        yeni_etkinlik_mail: $('#yeni_etkinlik_mail').is(":checked") ? "yes" : "no",
                        duyuru_mail: $('#duyuru_mail').is(":checked") ? "yes" : "no"
                    }
                    AyarGonder("genel", ayarlar, $("#btn_genel_kaydet"));
                });

                $("#btn_gizlilik_kaydet").on("click", function() {
                    var ayarlar = {
                        gecmis_private: $('#gecmis_private').is(":checked") ? "yes" : "no",
                        gelecek_private: $('#gelecek_private').is(":checked") ? "yes" : "no"
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
                    $.ajax({
                        type: "POST",
                        url: 'services/settings.php?method=' + ayarAdi,
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