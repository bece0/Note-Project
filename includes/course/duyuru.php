

<div class="tab-detay-controls">
    <?php if($DUYURU_YAPABILIR && $Ders_Aktif_Mi["status"]==1){ ?>
    <a class="btn btn-success c-header-action duyuru-yap" ders-id="<?php echo $COURSE["id"]; ?>"
        ders-name="<?php echo $COURSE["isim"]; ?>">
        <i class="fa fa-bell"></i>&nbsp;Duyuru Yap
    </a>
    <?php } ?>
</div>

<div class="card card-duyuru mb-3 wow fadeIn">
    <div class="card-header font-weight-bold"></div>
    <div class="card-body">
        <div id="duyurulistesi">
            <div class="alert alert-warning" role="alert">
                Bu derste henüz duyuru yapılmadı.
            </div>
        </div>
    </div>
</div>

<script>
$(function() {

    $("body").on("click", ".duyuru-yap", function(e) {
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

    $("body").on("click", ".delete-duyuru", function(e) {
        var duyuru_id = $(e.target).attr("duyuru-id");
        if (!duyuru_id) return;

        $.ajax({
            type: "POST",
            url: 'services/duyuru.php?method=delete&duyuru_id=' + duyuru_id,
            success: function(response) {
                $('#duyuru-' + duyuru_id).text("Duyuru silindi...")
                $('#duyuru-' + duyuru_id).addClass("duyuru-deleted");
                $('#duyuru-' + duyuru_id).fadeOut(1200, function() {
                    $(this).remove();
                });
            },
            error: function(jqXHR, error, errorThrown) {
                console.log("duyuru silinemedi!")
            }
        })
    })

    //duyuru ajax ile çek
    //duyuruları html olarak akışa ekle..
    DersDuyurulariGetir();
})

function DersDuyurulariGetir() {
    var dersId = $("#ders_id").val();
    $.ajax({
        type: "GET",
        url: 'services/duyuru_getir.php?ders=' + dersId,
        success: function(response) {
            if (response) {
                DersDuyurulariniYazdir(response);
            }
        },
        error: function(jqXHR, error, errorThrown) {
            console.log(error);
            console.log("ders duyurulari getirilemedi");
        }
    });
}

function DersDuyurulariniYazdir(duyurular) {
    if (!duyurular || duyurular.length == 0)
        return;

    $("#duyurulistesi").empty();

    for (let i = 0; i < duyurular.length; i++) {
        var duyuru = duyurular[i];
        var html = "";
        html += `<div class='media d-block d-md-flex duyuru-container' id='duyuru-${duyuru.id}' >`;
        html +=
            `<img class="comment-avatar d-flex mb-3 mx-auto" src="files/profile/${duyuru.kullanici_id}.png">`
        html += '<div class="media-body text-center text-md-left ml-md-3 ml-0 duyuru-block">';
        html += '<h6 class="mt-0 font-weight-bold duyuru-title">';
        html +=  duyuru.isim + " " + duyuru.soyisim;
        
        if (DUYURU_SILEBILIR) {
            html +=
                `<button class="btn btn-sm duyuru-action float-right btn-danger delete-duyuru" duyuru-id="${duyuru.id}">`;
            html += "<i class='fa fa-trash'></i></button>";
        }
        html += "</h6>";

        let date = new Date(duyuru.tarih)
        let formatted_date = date.getDate() + " " + (date.getShortMonthName()) + " " + date.getFullYear()

        html += ("<div class='duyuru-date'> " + formatted_date + "</div>");
        html += ("<p>" + duyuru.mesaj + "</p>");
        html += "</div>";
        html += "</div>";

        $("#duyurulistesi").append(html);
    }
}

function DuyuruGonder(ders_id, mesaj) {
    var data = {
        ders_id: ders_id,
        mesaj: mesaj
    }
    $.ajax({
        type: "POST",
        url: 'services/duyuru.php',
        // data: {
        //     data: JSON.stringify(data)
        // },
        data: data,
        success: function(response) {
            Swal.fire({
                type: 'success',
                title: 'Katılımcılara duyuru gönderildi',
                timer: 2000,
                showConfirmButton: false,
            });
            DersDuyurulariGetir();
            $('#genel_akis a')[0].click();

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