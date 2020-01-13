<style>
.katilimci-controls button {
    margin-right: 2px;
}

.user-type-head {
    font-weight: bold;
}
</style>


<?php 

// Giriş yapan kullanıcının kullanıcı listesinden kişi silme yetkisi
$DERSTEN_KULLANICI_SILEBILIR = FALSE;
$DERSE_KULLANICI_EKLEYEBILIR = FALSE;

// Giriş yapan kullanıcının kullanıcı listesinden asistan atayabilme yetkisi
$DERSE_ASISTAN_ATAYABILIR = FALSE;
$DERSTEN_ASISTAN_SILEBILIR = FALSE;

if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
    $DERSTEN_KULLANICI_SILEBILIR =  TRUE;
    $DERSE_KULLANICI_EKLEYEBILIR =  TRUE;
    $DERSE_ASISTAN_ATAYABILIR =  TRUE;
    $DERSTEN_ASISTAN_SILEBILIR = TRUE;
}

if($GIRIS_YAPAN_DERSIN_ASISTANI_MI){
    $DERSTEN_KULLANICI_SILEBILIR =  TRUE;
    $DERSE_KULLANICI_EKLEYEBILIR =  TRUE;
}

$KATILIMCILAR = DersKatilimcilariniGetir($COURSE["id"]);
$OGRENCILER = [];
$ASISTANLAR = [];
for ($i = 0; $i < count($KATILIMCILAR); $i++) {
    if($KATILIMCILAR[$i]["tip"] == 0)
        array_push($OGRENCILER, $KATILIMCILAR[$i]);
    else if($KATILIMCILAR[$i]["tip"] == 1)
        array_push($ASISTANLAR, $KATILIMCILAR[$i]);
}

?>

<div class="tab-detay-controls">
    <?php if($DERSE_KULLANICI_EKLEYEBILIR) {?>
    <button class="btn btn-info c-header-action katilimciEkle" ders-id="<?php echo $COURSE["id"]; ?>"
        ders-name="<?php echo $COURSE["isim"]; ?>" type="ogrenci">
        <i class="fa fa-plus"></i>&nbsp;Öğrenci Ekle
    </button>
    <?php } ?>

    <?php if($DERSE_ASISTAN_ATAYABILIR) {?>
    <button class="btn btn-secondary c-header-action katilimciEkle" ders-id="<?php echo $COURSE["id"]; ?>"
        ders-name="<?php echo $COURSE["isim"]; ?>" type="asistan">
        <i class="fa fa-plus"></i>&nbsp;Asistan Ekle
    </button>
    <?php } ?>
</div>

<div class="modal-body">
    <h6 class="user-type-head">Asistanlar</h6>
    <div id="asistan-liste">
        <?php
        if ($ASISTANLAR != NULL) {
            for ($i = 0; $i < count($ASISTANLAR); $i++) {
                $KATILIMCI = $ASISTANLAR[$i];
                $KATILIMCI_AD_SOYAD =  $KATILIMCI['adi'] . " " . $KATILIMCI['soyadi'];
                $KATILIMCI_URL = $KATILIMCI['adi']."-". $KATILIMCI['soyadi']."-".$KATILIMCI['id'];
        ?>

        <div class="media d-block d-md-flex modal-user" id="asistan-<?php echo $KATILIMCI['id'] ?>">
            <img class="avatar" src="files/profile/<?php echo $KATILIMCI['id'] ?>.png"
                title="<?php echo $KATILIMCI['adi'] ?>" alt="<?php echo $KATILIMCI['adi'] ?>"
                onerror="this.onerror=null; this.src='files/profile/profile.png'">
            <div class="media-body text-center text-md-left ml-md-3 ml-0 katilimci-block">
                <div class="mt-0 font-weight-bold katilimci-title">
                    <a href="profile.php?user=<?php echo $KATILIMCI_URL; ?>"><?php echo $KATILIMCI_AD_SOYAD ?></a>
                </div>
                <div class="katilimci-controls">
                    <?php if($DERSTEN_ASISTAN_SILEBILIR && $DERSTEN_KULLANICI_SILEBILIR) { ?>
                    <button href="#" class="btn btn-sm float-right btn-danger remove-user" title="Dersten çıkar"
                        user-id="<?php echo $KATILIMCI['id'] ?>" type="asistan">
                        <i class="fa fa-times"></i>
                    </button>
                    <?php }?>

                    <?php if($DERSTEN_ASISTAN_SILEBILIR) { ?>
                    <button href="#" class="btn btn-sm float-right btn-warning remove-assistant"
                        title="Derse asistanlığından çıkar" user-id="<?php echo $KATILIMCI['id'] ?>">
                        <i class="fa fa-check-circle"></i>
                    </button>
                    <?php }?>
                </div>
            </div>
        </div>
        <?php  }
        } else { ?>
        <div class="alert alert-warning" role="alert" id="asistan-yok">
            Bu derste asistan bulunmuyor.
        </div>
        <?php } ?>
    </div>
    <!-- KİŞİ LİSTESİ-->
    <h6 class="user-type-head">Öğrenciler</h6>
    <div id="ogrenci-liste">
        <?php
        if ($OGRENCILER != NULL) {
            for ($i = 0; $i < count($OGRENCILER); $i++) {
                $KATILIMCI = $OGRENCILER[$i];
                $KATILIMCI_AD_SOYAD =  $KATILIMCI['adi'] . " " . $KATILIMCI['soyadi'];
                $KATILIMCI_URL = $KATILIMCI['adi']."-". $KATILIMCI['soyadi']."-".$KATILIMCI['id'];
        ?>

        <div class="media d-block d-md-flex modal-user" id="katilimci-<?php echo $KATILIMCI['id'] ?>">
            <img class="avatar" src="files/profile/<?php echo $KATILIMCI['id'] ?>.png"
                title="<?php echo $KATILIMCI['adi'] ?>" alt="<?php echo $KATILIMCI['adi'] ?>"
                onerror="this.onerror=null; this.src='files/profile/profile.png'">
            <div class="media-body text-center text-md-left ml-md-3 ml-0 katilimci-block">
                <div class="mt-0 font-weight-bold katilimci-title">
                    <a href="profile.php?user=<?php echo $KATILIMCI_URL; ?>"><?php echo $KATILIMCI_AD_SOYAD ?></a>
                </div>
                <div class="katilimci-controls">
                    <?php if($DERSTEN_KULLANICI_SILEBILIR) { ?>
                    <button href="#" class="btn btn-sm float-right btn-danger remove-user" title="Derten çıkar"
                        user-id="<?php echo $KATILIMCI['id'] ?>">
                        <i class="fa fa-times"></i>
                    </button>
                    <?php }?>

                    <?php if($KATILIMCI["admin"] == 1 && $DERSE_ASISTAN_ATAYABILIR) { ?>
                    <button href="#" class="btn btn-sm float-right btn-info make-assistant"
                        title="Derse asistan olarak ata" user-id="<?php echo $KATILIMCI['id'] ?>">
                        <i class="fa fa-check-circle"></i>
                    </button>
                    <?php }?>
                </div>
            </div>
        </div>
        <?php  }
        } else { ?>
        <div class="alert alert-warning" role="alert" id="ogrenci-yok">
            Bu derste katılımcı bulunmuyor.
        </div>
        <?php } ?>
    </div>
</div>

<script>
$(".remove-user").on("click", function(e) {
    var user_id = $(e.target).attr("user-id");
    var type = $(e.target).attr("type");
    var course_id = '<?php echo $COURSE["id"] ?>';

    if (!user_id || !course_id) return;

    Swal.fire({
        title: 'Emin misiniz?',
        text: "Kullanıcı dersten çıkarılsın mı?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet, çıkart!',
        cancelButtonText: "Hayır"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: 'services/course_attendance.php?method=removeuser&user=' + user_id +
                    '&course=' + course_id,
                success: function(response) {
                    type = type || 'katilimci';
                    $('#katilimci-' + user_id).fadeOut(1200, function() {
                        $(this).remove();
                    });
                },
                error: ajaxGenelHataCallback
            })
        }
    })


});

$(".make-assistant").on("click", function(e) {
    var user_id = $(e.target).attr("user-id");
    var course_id = '<?php echo $COURSE["id"] ?>';

    if (!user_id || !course_id) return;

    Swal.fire({
        title: 'Emin misiniz?',
        text: "Kullanıcı derse asistan olarak eklensin mi?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet',
        cancelButtonText: "Hayır"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: 'services/course_attendance.php?method=addassistant&user=' + user_id +
                    '&course=' + course_id,
                success: function(response) {
                    // var $klon = ('#katilimci-' + user_id).clone().prop('id', 'asistan-' +
                    //     user_id);
                    // $klon.remove(".katilimci-controls");
                    $('#katilimci-' + user_id).fadeOut(800, function() {
                        $(this).remove();
                    });
                    // $("#asistan-liste").append($klon);
                    // $("#asistan-yok").remove();
                },
                error: ajaxGenelHataCallback
            })
        }
    })
});

$(".remove-assistant").on("click", function(e) {
    var user_id = $(e.target).attr("user-id");
    var course_id = '<?php echo $COURSE["id"] ?>';

    if (!user_id || !course_id) return;

    Swal.fire({
        title: 'Emin misiniz?',
        text: "Kullanıcı ders asistanı olmaktan çıkarılsın mı?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet',
        cancelButtonText: "Hayır"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: 'services/course_attendance.php?method=removeassistant&user=' + user_id +
                    '&course=' + course_id,
                success: function(response) {

                    // var $klon = ('#asistan-' + user_id).clone().prop('id', 'kullanici-' +
                    //     user_id);
                    // $klon.remove(".katilimci-controls");
                    $('#asistan-' + user_id).fadeOut(800, function() {
                        $(this).remove();
                    });
                    // $("#ogrenci-liste").append($klon);
                    // $("#ogrenci-yok").remove();
                },
                error: ajaxGenelHataCallback
            })
        }
    })
});

$(".katilimciEkle").on("click", function(e) {
    var user_id = $(e.target).attr("user-id");
    var course_id = '<?php echo $COURSE["id"] ?>';
    var type = $(e.target).attr("type");

    var title = "Öğrenci Ekle";

    if (type == "asistan")
        title = "Asistan Ekle"

    Swal.fire({
        title: title,
        text: "Eklenecek olan mail adresi sisteme kayıtlı olmalıdır!",
        input: 'text',
        inputPlaceholder: 'Mail adresi',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-paper-plane"></i> Ekle!',
        cancelButtonText: '<i class="fa fa-times"></i> İptal',
        // confirmButtonColor: '#3085d6',
        // cancelButtonColor: '#d33',
        inputValidator: (value) => {
            if (!value) {
                return 'Mail adresi girmelisiniz!'
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

        DerseKisiEkle(course_id, type, result.value);
    });
})

function DerseKisiEkle(course_id, type, mail) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: 'services/course_attendance.php?method=adduser' +
            '&course=' + course_id + '&type=' + type + '&mail=' + mail,
        success: function(response) {
            location.reload();
        },
        error: ajaxGenelHataCallback
    })
}
</script>