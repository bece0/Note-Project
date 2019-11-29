
<link rel="stylesheet" href="assets/lib/cookiealert/cookiealert.css" />
<div class="alert alert-dismissible text-center cookiealert" role="alert">
    <div class="cookiealert-container">
        <b>Çerez Kullanımı</b> &#x1F36A; Çerezler (cookie), move.com web sitesini ve hizmetlerimizi daha etkin bir
        şekilde sunmamızı sağlamaktadır.
        Çerezlerle ilgili detaylı bilgi için <a href="cerez-politikasi.php" target="_blank">Gizlilik Politikamızı</a>
        ziyaret edebilirsiniz.
        <button type="button" class="btn btn-primary btn-sm acceptcookies" aria-label="Close">
            Tamam
        </button>
    </div>
</div>
<?php 
        if($GirisYapildiMi){ 
			$KULLANICI = KullaniciBilgileriniGetirById($_SESSION["kullanici_id"]);
		}
    ?>
<script src="assets/lib/cookiealert/cookiealert.js"></script>
<link rel="stylesheet" href="assets/css/footer.css" />
	
<section id="footer">
		<div class="container">
			<div class="row text-center text-xs-center text-sm-left text-md-left">
				<div class="col-xs-12 col-sm-4 col-md-4">
					<h5>Hızlı Erişim</h5>
					<ul class="list-unstyled quick-links">
								<?php if($KULLANICI['admin'] != "" && $KULLANICI['admin'] == 0) {?>
									<li><a href="attend_course.php"><i class="fa fa-angle-double-right"></i>Derse Kaydol</a></li>
								<?php }else{ ?>					
									<li><a href="create_course.php"><i class="fa fa-angle-double-right"></i>Ders Oluştur</a></li>
								<?php } ?>
								<li><a href="takvim.php"><i class="fa fa-angle-double-right"></i>Takvim</a></li>
								<li><a  href="profile.php?id=<?php echo $KULLANICI["id"]?>"><i class="fa fa-angle-double-right"></i>Profil</a></li>
								<li><a href="my_course.php"><i class="fa fa-angle-double-right"></i>Derslerim</a></li>
						
					</ul>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4">
					<h5>Bilgi</h5>
					<ul class="list-unstyled quick-links">
						<li><a href="agreement.php"><i class="fa fa-angle-double-right"></i>Kullanıcı Sözleşmesi</a></li>
						<li><a href="cerez-politikasi.php"><i class="fa fa-angle-double-right"></i>Çerez Politikası</a></li>
						<li><a href="sss.php"><i class="fa fa-angle-double-right"></i>Sık Sorulan Sorular</a></li>
					</ul>
				</div>
				<!-- <div class="col-xs-12 col-sm-4 col-md-4">
					<h5>Etkinlikleri Keşfet</h5>
					<ul class="list-unstyled quick-links">
						<li><a href="find.php?tip=Koşu"><i class="fa fa-angle-double-right"></i>Koşu</a></li>
						<li><a href="find.php?tip=Bisiklet"><i class="fa fa-angle-double-right"></i>Bisiklet</a></li>
						<li><a href="find.php?tip=Yüzme"><i class="fa fa-angle-double-right"></i>Yüzme</a></li>
						<li><a href="find.php?tip=Tırmanış"><i class="fa fa-angle-double-right"></i>Tırmanış</a></li>
						<li><a href="find.php?tip=Yürüyüş"><i class="fa fa-angle-double-right"></i>Yürüyüş</a></li>
						<li><a href="find.php?tip=Quidditch"><i class="fa fa-angle-double-right"></i>Quidditch</a></li>
						<li><a href="find.php?tip=Futbol"><i class="fa fa-angle-double-right"></i>Futbol</a></li>
						<li><a href="find.php?tip=Basketbol"><i class="fa fa-angle-double-right"></i>Basketbol</a></li>
						<li><a href="find.php?tip=Voleybol"><i class="fa fa-angle-double-right"></i>Voleybol</a></li>
				
					</ul>
				</div> -->
			</div>
			<!-- <div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<ul class="list-unstyled list-inline social text-center">
						<li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
						<li class="list-inline-item"><a href="#"><i class="fa fa-instagram"></i></a></li>
						<li class="list-inline-item"><a href="#" target="_blank"><i class="fa fa-envelope"></i></a></li>
					</ul>
				</div>
				</hr>
			</div>	 -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center">
					<p class="h6">&copy Tüm Hakları Saklıdır.<a class="text-green ml-2" href="#" target="_blank"> NOTE</a></p>
				</div>
				</hr>
			</div>	
		</div>

	</section>
