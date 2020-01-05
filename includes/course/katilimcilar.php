<a class="btn btn-info c-header-action katilimciEkle" ders-id="<?php echo $COURSE["id"]; ?>"
    ders-name="<?php echo $COURSE["isim"]; ?>">
    <i class="fa fa-plus"></i>&nbsp;Katılımcı Ekle
</a>
<?php 

// Giriş yapan kullanıcının kullanıcı listesinden kişi silme yetkisi
$DERSTEN_KULLANICI_SİLEBİLİR = FALSE;

// Giriş yapan kullanıcının kullanıcı listesinden asistan atayabilme yetkisi
$DERSE_ASISTAN_ATAYABILIR = FALSE;

if($GIRIS_YAPAN_DERSIN_HOCASI_MI){
    $DERSTEN_KULLANICI_SİLEBİLİR =  TRUE;
    $DERSE_ASISTAN_ATAYABILIR =  TRUE;
}

?>
<div class="modal-body">
    <!-- KİŞİ LİSTESİ-->
    <h6><b>Öğrenciler</b></h6>
    <?php
        $participant_list = DersKatilimcilariniGetir($COURSE["id"]);
        if ($participant_list != NULL) {
            for ($i = 0; $i < count($participant_list); $i++) {
                $user_detail = $participant_list[$i];
                $ad_soyad =  $user_detail['adi'] . " " . $user_detail['soyadi'];
                $user_url = $user_detail['adi']."-". $user_detail['soyadi']."-".$user_detail['id'];
        ?>

    <div class="modal-user">
        <div>
            <img class="avatar" src="files/profile/<?php echo $user_detail['id'] ?>.png"
                title="<?php echo $user_detail['adi'] ?>" alt="<?php echo $user_detail['adi'] ?>"
                onerror="this.onerror=null; this.src='files/profile/profile.png'">
            <a href="profile.php?user=<?php echo $user_url; ?>"><?php echo $ad_soyad ?></a>
        </div>
        <div>
            <?php if($DERSTEN_KULLANICI_SİLEBİLİR) { ?>
                <button href="#" class="btn btn-sm float-right btn-danger remove-user" 
                title="Derten çıkar" user-id="<?php echo $user_detail['id'] ?>">
                    <i class="fa fa-times"></i>
                </button>
            <?php }?>
        </div>

    </div>

    <?php  }
        } else { ?>
    <div class="alert alert-warning" role="alert">
        Bu derste katılımcı yok.
    </div>
    <?php } ?>
</div>