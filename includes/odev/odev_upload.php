<?php
    include 'odev_upload_modal.php';
    $OGRENCI_ODEV = OgrenciOdev_OgrencininOdeviniGetir($LOGIN_ID, $ODEV_ID);
    
?>
<style>
.odev-durum {
    margin: 6px 0px;
}

.odev-durum-mesaj {
    margin-bottom: 5px;
}

.odev-gonderim-tarih {
    font-style: italic;
}

.odev-upload-btns {
    margin-top: 10px;
}

.odev-not {
    font-style: italic;
    font-weight: bold;
}
</style>

<?php if($OGRENCI_ODEV == NULL && $ODEV_TARIHI_GECTI == FALSE) {?>

<div class="odev-durum">
    <div class="odev-durum-mesaj">
        <div class="alert alert-warning" role="alert" style="text-align:center">Ödev henüz göndermediniz</div>
    </div>
</div>


<?php if($Ders_Aktif_Mi){ ?>
<div class="odev-upload-btns">
    <?php if($ODEV["dosya_gonderme"] == 1 ){?>
    <button class="btn btn-info" data-toggle="modal" data-target="#odevYukleModal">Ödev Yükle</button>
    <?php }else { ?>
    <button class="btn btn-info" onclick="odevTeslimEt(<?php echo $ODEV['id']?>, <?php echo $ODEV['ders_id']?>)">Ödev
        Teslim Et</button>
    <?php }?>
</div>
<?php } ?>

<?php }  else if($OGRENCI_ODEV == NULL && $ODEV_TARIHI_GECTI == TRUE) {?>
<div class="odev-durum">
    <div class="odev-durum-mesaj">
        <div class="alert alert-danger" role="alert" style="text-align:center">
                Ödev teslim tarihi geçti
        </div>
    </div>
    <?php } else {?>
    <div class="odev-durum-mesaj">
        <div class="alert alert-info" role="alert" style="text-align:center">Ödevi Teslim Ettiniz</div>
    </div>
    <hr>
    <div>
        Gönderim Tarihi : <span class="odev-gonderim-tarih"><?php echo $OGRENCI_ODEV["gonderim_tarih"]?></span>
    </div>

    <?php if($OGRENCI_ODEV["durum"] == 1){?>
    <div>
        Notunuz : <span class="odev-not"><?php echo $OGRENCI_ODEV["not"]?></span>
    </div>
    <?php }?>

    <div class="odev-upload-btns">
        <?php if($ODEV["dosya_gonderme"] == 1 &&  $Ders_Aktif_Mi){?>
        <a href='dosya_indir.php?type=ogrenci_odev&kod=<?php echo $OGRENCI_ODEV["kod"]?>' class="btn btn-primary"><i
                class="fa fa-download"></i> İndir</a>

        <?php if($OGRENCI_ODEV["durum"] == 0){?>
        <button class="btn btn-danger" onclick="odevSil(<?php echo $OGRENCI_ODEV['id']?>)">
            <i class="fa fa-trash-alt"></i> Ödevi Dosyasını Sil
        </button>
        <?php }?>
        <?php }?>
    </div>
</div>
<?php }?>


<script>
function odevSil(ogrenciOdevId) {
    if (!ogrenciOdevId)
        return;

    var data = {
        ogrenci_odev_id: ogrenciOdevId
    }

    Swal.fire({
        title: 'Emin misiniz?',
        text: "Ödev silinsin mi?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet, sil!',
        cancelButtonText: "Hayır"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "services/odev_upload.php?method=delete",
                type: "POST",
                data: data,
                success: function(data) {
                    Swal.fire({
                        text: 'Ödev başarıyla silindi.',
                        type: 'success',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(e) {
                    Swal.fire({
                        text: 'Ödev dosyası silinemedi.',
                        type: 'error',
                        confirmButtonText: 'Tamam'
                    })
                }
            });
        }
    })


}

function odevTeslimEt(odevId, dersId) {
    if (!odevId || !dersId)
        return;

    var data = {
        odev_id: odevId,
        ders_id: dersId
    }

    Swal.fire({
        title: 'Emin misiniz?',
        text: "Ödev teslim edilsin mi?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Evet, teslim et!',
        cancelButtonText: "Hayır"
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: "services/odev_upload.php?method=teslim",
                type: "POST",
                data: data,
                success: function(data) {
                    Swal.fire({
                        text: 'Ödev başarıyla teslim edildi.',
                        type: 'success',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(e) {
                    Swal.fire({
                        text: 'Ödev teslim edilemedi!',
                        type: 'error',
                        confirmButtonText: 'Tamam'
                    })
                }
            });
        }
    })
}
</script>