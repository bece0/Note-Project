<?php 



if($ODEV_EKLEYEBILIR)
    include 'sinif_calismalari_odev_modal.php';

if($DOKUMAN_EKLEYEBILIR)
    include 'sinif_calismalari_doc_modal.php';

?>

<?php if($OGRETMEN){ ?>
<div class="tab-detay-controls">

  

    <?php if($ODEV_EKLEYEBILIR ||$DOKUMAN_EKLEYEBILIR ){ ?>
    <a class="btn btn-success dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-plus"></i>&nbsp;Oluştur
    </a>
    <div class="dropdown-menu">
        <?php if($ODEV_EKLEYEBILIR){ ?>
        <a class="dropdown-item c-header-action" data-toggle="modal" data-target="#odevOlusturModal">Ödev</a>
        <?php } ?>
        <?php if($DOKUMAN_EKLEYEBILIR){ ?>
        <a class="dropdown-item c-header-action" data-toggle="modal" data-target="#dokumanOlusturModal">Döküman</a>
        <?php } ?>
    </div>
    <?php } ?>

</div>
<?php } ?>

<?php

$ODEVLER = DersOdevleriniGetir($COURSE["id"]);
$DOKUMANLAR = DersDokumanlariniGetir($COURSE["id"]);

?>

<div class="row">
    <div class="col-md-6 col-sm12">
        <?php if($ODEVLER !=NULL && count($ODEVLER) >0) {?>
        <h6>Ödevler</h6>
        <div id="odevListesi" class="odev-liste">
            <?php for ($i = 0; $i < count($ODEVLER); $i++) {
                $ODEV = $ODEVLER[$i];
                $ODEV_YUKLEYEN =  $ODEV['adi'] . " " . $ODEV['soyadi'];
            ?>
            <div class="odev-item">
                <div>
                    <div class="odev-isim">
                        <a target="blank_" href='odev.php?kod=<?php echo $ODEV["id"]?>'>
                            <i class="fa fa-link"></i>&nbsp;
                            <?php echo $ODEV["isim"]?>
                        </a>
                    </div>
                </div>
                <div class="odev-kunye">
                    <div class="odev-tarih"><?php echo $ODEV["olusturma_tarih"]?></div>
                    <div class="odev-yukleyen"> <?php echo $ODEV_YUKLEYEN ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            Bu derste henüz ödev verilmedi.
        </div>
        <?php } ?>
    </div>
    <div class="col-md-6 col-sm12">
        <?php if($DOKUMANLAR !=NULL && count($DOKUMANLAR) >0) {?>
        <h6>Dokümanlar</h6>
        <div id="dokumanListesi" class="dokuman-liste">
            <?php for ($i = 0; $i < count($DOKUMANLAR); $i++) {
                $DOKUMAN = $DOKUMANLAR[$i];
                $DOKUMAN_YUKLEYEN =  $DOKUMAN['adi'] . " " . $DOKUMAN['soyadi'];
            ?>
            <div class="dokuman-item">
                <div>
                    <div class="dokuman-isim">
                        <a target="blank_" href='dosya.php?kod=<?php echo $DOKUMAN["kod"]?>'>
                            <i class="fa fa-download"></i>&nbsp;
                            <?php echo $DOKUMAN["isim"]?>
                        </a>
                    </div>
                </div>
                <div class="dokuman-kunye">
                    <div class="dokuman-tarih"><?php echo $DOKUMAN["olusturma_tarih"]?></div>
                    <div class="dokuman-yukleyen"> <?php echo $DOKUMAN_YUKLEYEN ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            Bu derste henüz doküman paylaşılmadı.
        </div>
        <?php } ?>
    </div>
</div>


<script>


$(function() {
    $("#odevOlusturBtn").on("click", function(e) {

    });
})
</script>