<?php 

    $OGRENCI_ODEVLERI = OgrenciOdev_TumOdevleriGetirByOdevId($ODEV_ID);
    

?>


<?php if($OGRENCI_ODEVLERI != NULL && count($OGRENCI_ODEVLERI) > 0) { 

?>
<table class="table table-bordered table-sm table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Öğrenci</th>
            <th>Gönderim Tarihi</th>
            <th>Not</th>
            <th></th>
        </tr>
    </thead>
    <?php
    $GONDERILEN_ODEV_SAYISI = count($OGRENCI_ODEVLERI);
    for ($i=0; $i < $GONDERILEN_ODEV_SAYISI; $i++) { 
        $OGRENCI_ODEV_ITEM = $OGRENCI_ODEVLERI[$i];
?>

    <tr class="ogrenci-odev-tr">
        <td>
            <?php echo ($i + 1);?>
        </td>
        <td class="ogrenci-odev-ogrenci-adi">
            <?php echo $OGRENCI_ODEV_ITEM["ogrenci_adi"]." ".$OGRENCI_ODEV_ITEM["ogrenci_soyadi"]?>
        </td>
        <td>
            <?php echo $OGRENCI_ODEV_ITEM["gonderim_tarih"];?>
        </td>
        <td>
            <?php echo $OGRENCI_ODEV_ITEM["not"];?>
        </td>
        <td>
            <?php if($ODEV["dosya_gonderme"] == 1){?>
            <a class="btn btn-sm btn-warning" href='dosya_indir.php?type=ogrenci_odev&kod=<?php echo $OGRENCI_ODEV_ITEM["kod"]?>'
                class=""><i class="fa fa-download"></i> İndir</a>
            <?php }?>
            
            <a class="btn btn-sm btn-info"><i class="fa fa-count"></i> Not Ver</a>
        
        </td>
    </tr>

    <?php } //for bitti ?>
</table>

<?php } else { ?>
<div class="alert alert-warning" role="alert">
    Henüz hiç bir öğrenci ödevi teslim etmedi!
</div>
<?php } ?>