<?php
    $page_title = "Ders Düzenle";
    include 'includes/head.php';
?>
<?php
    include 'includes/page-common.php';
    include 'includes/nav-bar.php';

    if(!isset($_GET["course"]) || !isset($_SESSION["kullanici_id"]))
        header('Location: dashboard.php'); 

    $ders_id = $_GET["course"];
   $ders_detail = DersBilgileriniGetir($ders_id);

    if($ders_detail == NULL)
        header('Location: dashboard.php'); 

    //editleme sayfasını açan ile etkinlik düzenleyicisi aynı kullanıcı değil ise
    if($ders_detail["duzenleyen_id"] != $_SESSION["kullanici_id"])
        header('Location: dashboard.php');
?>


<!-- <script src="assets/js/autocomplete.min.js"></script> -->
<style>
    #preview{
        border: solid 1px;
        height: 240px;
        border-radius: 15px;
    }
    .preview-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin-top: 20px;
    }

    .preview-container img {
        width: 100%;
        height: auto;
    }

    .preview-container .btn {
        position: absolute;
        top: 10%;
        right: 0%;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        /* background-color: #555; */
        color: white;
        font-size: 27px;
        padding: 3px 3px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        text-align: center;
        outline: none;
    }

    .preview-container .btn:hover {
        color: black;
    }
</style>
<body>
    <?php
       
        //giriş yapılmamış ise girişe yönlendir
        if(!isset($_SESSION["email"])){
          header('Location: login.php'); 
        }
    ?>
    <div class="container">
    <form class="form" action="action/edit_course_action.php" method="POST" enctype="multipart/form-data" style="margin-top:25px;">
            <div class="form-group">
                <label class="col-form-label">Ders Adı</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                    </div>
                    <input type="text" name="etkinlik_adi" placeholder="" class="form-control" required 
                            value="<?php echo $ders_detail['isim'] ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label">Bölüm Adı</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                    </div>
                    <input id="bolum_adi" name="bolum_adi" placeholder="" class="form-control" required
                        type="text">
                </div>
            </div>
   
          
            <div class="form-group">
                <label class=" control-label">Kontenjan</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-thumbtack"></i></span>
                    </div>
                    <input id="kontenjan" name="kontenjan" placeholder="" class="form-control" required="true" value="<?php echo $ders_detail['kontenjan'] ?>"
                        type="number">
                </div>
            </div>
    
            <div class="form-group">
                <label class="control-label">Açıklama</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-pen"></i></span>
                    </div>
                    <textarea rows="8" id="aciklama" name="aciklama" placeholder="Ders detayını açıklayın..."
                            class="form-control" required="true" ><?php echo $ders_detail['aciklama'] ?></textarea>
                </div>
            </div>
       
            <div class="form-group row">
                <label class="col-md-2 control-label">Ders Kapak Resmi</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <input type="file" name="etkinlik_resim" id="etkinlik_resim" accept="image/x-png,image/jpeg">
                    </div>
                </div>
                <div class="preview-container">
                        <!-- <img id="preview" src="files/images/event/bffa5a93-f097-4665-bc2b-0a9e8f25a128.png"> -->
                        <img id="preview" src="files/images/event/<?php echo $ders_detail["kodu"]?>.png"
                        onerror="this.onerror=null; this.src='files/images/<?php echo ToEnglish($ders_detail["tip"]); ?>.png'">
                        <button type="button" class="btn" id="resim_kaldir"  title="Resmi kaldır"><i class="fa fa-trash-alt"></i></button>
                    </div>
            </div>
            
            <button type="submit" class="btn btn-success" style="float:right;">Güncelle</button>
        </form>
    </div>
    <script>

        var maxFilesize = 3;
        function readURL(input) {
            if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
            }
        }

        $("#etkinlik_resim").change(function() {
            console.log("etkinlik_resim inputu değişti");

            var dosyaBoyutMB = this.files[0].size/1024/1024;
            dosyaBoyutMB = parseFloat(dosyaBoyutMB).toFixed(2);

            if(dosyaBoyutMB > maxFilesize){

                document.getElementById("etkinlik_resim").value = "";
                this.value = null;

                $('#preview').attr('src', '');

                var secilen_tip = $("#tip").val();
                $('#preview').attr('src', "files/images/" + secilen_tip + ".png");

                Swal.fire({
                    title : "Uyarı",
                    type : "warning",
                    text : "Dosya boyutu 3 MB'tan fazla olamaz. Yüklediğiniz resim "+ parseFloat(dosyaBoyutMB).toFixed(2) + " MB boyutundadır",
                });
            }else{
                readURL(this);
                $("#use_default").val("0");
            }
        });

        $('#btn-event-delete').on('click',function(e){
            e.preventDefault();
            var form = $(this).parents('form');
            Swal.fire({
                title: "Uyarı",
                text: "Etkinliği silmek istediğinize emin misiniz?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Evet, Sil",
                cancelButtonText: "Vazgeç",
            }).then((result) => {
                if (result.value) {
                    form.submit();
                }
            });
        });

        $("#resim_kaldir").on("click",function(){
            //resim_kaldir id'li bir elemente tıklandığında çalışacak olan fonksiyon
            document.getElementById("etkinlik_resim").value = "";
            $('#preview').attr('src', '');

            var secilen_tip = $("#tip").val();
            secilen_tip = TurkceKarakterKaldir(secilen_tip);
            $('#preview').attr('src', "files/images/" + secilen_tip + ".png");
            $("#use_default").val("1");
        });
    </script>
</body>