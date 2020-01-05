<style>
.comment-avatar{
    max-width : 45px;
    max-height : 45px;
    border-radius: 50%;
}
.comment-date{
    color: #6a737c;
    font-size: 0.8rem;
}
.comment-title{
    margin-bottom: 0.1rem;
}
.comment-content{

}

.no-comment{

}

.comment-action{
    margin-left: 7px;
    margin-top: 5px;
}

.comment-container{
    padding: 5px;
    border-radius: 3px;
    margin-bottom: 0.75rem;
    transition: background-color 1s cubic-bezier(1, 1, 1, 1);
	transition-delay: 0s;
}

.commment-block{
    border-bottom: 1px solid rgba(0,0,0,.125);
}

/* buton içindeki ikona tıklandığında target olarak ikon gitmesin diye */
.comment-action > *{
    pointer-events: none;
}

.comment-deleted{
    justify-content: center;
    margin: 25px;
    border: solid #dbcabe 0.5px;
}

.chighlighted{
    background-color: #58a53754;
}
</style>

<?php 
    include_once dirname(__FILE__) .'/../database/database.php';
?>
<!--Comments-->
<?php 
    $ders_id = UrlIdFrom("course");

    $canApprove = false;
    $canDelete = false;

    $comments = [];
    $giris_yapan_kullanici = NULL;

    if(isset($_SESSION["kullanici_id"]))
        $giris_yapan_kullanici = $_SESSION["kullanici_id"];

    if($COURSE["duzenleyen_id"] == $giris_yapan_kullanici){
        //Giriş yapmış kullanıcı etkinlik sahibi ise tüm yorumları getir
        $comments = GetEventAllComments($ders_id);
        $canApprove = true;
        $canDelete = true;
    }
    else if(isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){
        //Giriş yapmış kullanıcı ADMİN ise tüm yorumları getir
        $comments = GetEventAllComments($ders_id);
        $canApprove = true;
        $canDelete = true;
    }
    else{
        //Sadece onaylanmış yorumları getir
        $comments = GetEventApprovedComments($ders_id);
    }
    
    $comment_count = 0;
    if($comments != NULL)
        $comment_count = count($comments);
?>

<?php  if($comment_count == 0){ ?>
    <div class="alert alert-secondary no-comment" role="alert" > 
        Bu ders hakkında yorum bulunmamaktadır. 
    </div>
<?php } else { ?>
   
    <div class="card card-comments mb-3 wow fadeIn">
        <div class="card-header font-weight-bold">
            <?php  echo "$comment_count yorum"; ?>
        </div>
        <div class="card-body">
            <?php for ($i=0; $i < $comment_count ; $i++) { 
                $current_comment = $comments[$i];
                $comment_id = $current_comment["id"];
                $onay_durum = $current_comment["onay_durum"];
                if($current_comment["kullanici_id"] == $giris_yapan_kullanici)
                    $canDelete = true;

                $adi_soyadi = $current_comment["adi"]." ".$current_comment["soyadi"];
            ?>
                <div class="media d-block d-md-flex comment-container" id="comment-<?php echo $comment_id ?>">
                    <img class="comment-avatar d-flex mb-3 mx-auto" src="files/profile/<?php echo $current_comment["kullanici_id"] ?>.png"
                    alt="<?php echo $adi_soyadi ?>" title="<?php echo $adi_soyadi ?>" 
                    onerror="this.onerror=null; this.src='files/profile/profile.png'">
                    <div class="media-body text-center text-md-left ml-md-3 ml-0 commment-block">
                        <h5 class="mt-0 font-weight-bold comment-title">
                            <?php echo $adi_soyadi ?>

                            <?php if(($onay_durum == NULL || $onay_durum == 0) && $canApprove) { ?>
                                <button href="#" class="btn btn-sm comment-action float-right btn-success approve-comment" 
                                title="Onayla" comment-id="<?php echo $comment_id ?>">
                                    <i class="fa fa-thumbs-up"></i>
                                </button>
                            <?php }?>

                            <?php if($canDelete) { ?>
                                <button href="#" class="btn btn-sm comment-action float-right btn-danger delete-comment" 
                                title="Sil" comment-id="<?php echo $comment_id ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                            <?php }?>
                        </h5>
                        <div class="comment-date"><?php echo turkcetarih_formati('d M Y', $current_comment["tarih"]) ?></div>
                        <p class="comment-content">
                            <?php echo $current_comment["icerik"]  ?>
                        </p>
                    </div>
                </div>
            <?php }  ?>
            <!-- sample commment -->
            <!-- <div class="media d-block d-md-flex mt-3">
                <img class="comment-avatar d-flex mb-3 mx-auto " src="https://mdbootstrap.com/img/Photos/Avatars/img (30).jpg" alt="Generic placeholder image">
                <div class="media-body text-center text-md-left ml-md-3 ml-0">
                    <h5 class="mt-0 font-weight-bold">Caroline Horwitz
                        <a href="" class="float-right">
                            <i class="fa fa-reply"></i>
                        </a>
                    </h5>
                    <div class="comment-date">tarih</div>
                    <p class="comment-content">
                    At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti
                    quos dolores et quas molestias excepturi sint occaecati cupiditate non provident,
                    similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum
                    fuga.
                    </p>
                </div>
            </div> -->
        </div>
    </div>
 
<!--/.Comments-->
<?php }  ?>

<?php 
    $canAddComment = false;
    $whyCantComment = "";

    if($giris_yapan_kullanici == NULL){
        $canAddComment = false;
    }
    else if($COURSE["duzenleyen_id"] == $giris_yapan_kullanici){
        //Etkinlik sahibi yorum ekleyebilir
        $canAddComment = true;
    }
    else if(isset($_SESSION["admin"]) && $_SESSION["admin"] == 1){
         //Admin kullanıcı yorum ekleyebilir
         $canAddComment = true;
    }else{
       //Giriş yapmış kullanici bu etkinliğe katılmış mı
       $katilimciMi = KatilimciVarMi($ders_id, $giris_yapan_kullanici);
       if($katilimciMi == TRUE)
            $canAddComment = true;
    }
?>

<?php if($canAddComment == true) { ?>
    <div class="card mb-3 wow fadeIn" id="comment_form">
        <div class="card-header font-weight-bold">
            <?php  
                if($comment_count == 0)
                    echo "İlk yorumu sen ekle";
                else
                    echo "Yorum Ekle";
            ?>
        </div>
        <div class="card-body">
            <form id="comment-form">
                <input type="hidden" name="ders_id" id="ders_id" value="<?php echo $ders_id?>">
                <div class="form-group">
                    <label for="txt_comment">Not : Yorumunun onaylandıktan sonra yayınlanacaktır.</label>
                    <textarea class="form-control" name="comment" id="txt_comment" rows="6"
                        maxlength="500" placeholder="Yorumunuz buraya yazabilirsiniz..."></textarea>
                </div>
                <div class="text-right mt-4">
                    <button id="btn-comment-send" class="btn btn-info btn-md" type="button" disabled>Gönder</button>
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<script>
    function isEmptyOrSpaces(str){
        return str === null || str.match(/^ *$/) !== null;
    }

    $(function(){
        $("#txt_comment").bind("input propertychange", function(){
            var comment = $("#txt_comment").val();
            
            if(isEmptyOrSpaces(comment) || comment.length < 15)
                $("#btn-comment-send").prop( "disabled", true );
            else
                $("#btn-comment-send").prop( "disabled", false );
        });

        $("#btn-comment-send").on("click",function(){
            var comment = $("#txt_comment").val();
            if(isEmptyOrSpaces(comment)|| comment.length < 15){
                return alert("Yorum içeriği girin!");
            }

            $("#btn-comment-send").prop( "disabled", true);
            $.ajax({
                type: "POST",
                url: 'services/comment.php?method=add',
                // data: JSON.stringify(comment_data),
                data: $("#comment-form").serialize(),
                success: function(response)
                {
                    if(response && response.sonuc){
                        $("#txt_comment").val("");
                        Swal.fire({
                            title: 'Yorum gönderildi',
                            text: 'Yorumunun onaylandıktan sonra yayınlanacaktır.',
                            type: 'success',
                            confirmButtonText: 'Tamam'
                        })
                    }else{
                        Swal.fire({
                            title: 'Hata',
                            text: 'Yorumunuz gönderilemedi, lütfen daha sonra tekrar deneyin.',
                            type: 'warning',
                            confirmButtonText: 'Tamam'
                        })
                    }
                },
                error : function(jqXHR,error, errorThrown){
                    console.log(error);
                    Swal.fire({
                        title: 'Hata',
                        text: 'Yorumunuz gönderilemedi, lütfen daha sonra tekrar deneyin.',
                        type: 'warning',
                        confirmButtonText: 'Tamam'
                    })
                }
            });
        })

        $(".approve-comment").on("click",function(e){
            var comment_id = $(e.target).attr("comment-id");
            if(!comment_id) return;

            $.ajax({
                type: "POST",
                url: 'services/comment.php?method=approve&comment='+comment_id,
                success: function(response){
                    $(e.target).remove();
                    $('#comment-' + comment_id).addClass('chighlighted');
                    setTimeout(function () {
                        $('#comment-' + comment_id).removeClass('chighlighted');
                    }.bind(this), 2000);
                },
                error : function(jqXHR,error, errorThrown){
                    console.log("yorum onaylanamadı!")
                }
            })
        })

        $(".delete-comment").on("click",function(e){
            var comment_id = $(e.target).attr("comment-id");
            if(!comment_id) return;

            $.ajax({
                type: "POST",
                url: 'services/comment.php?method=delete&comment='+comment_id,
                success: function(response){
                    $('#comment-' + comment_id).text("Yorum silindi...")
                    $('#comment-' + comment_id).addClass("comment-deleted");
                    $('#comment-' + comment_id).fadeOut(1200, function(){ $(this).remove();});
                },
                error : function(jqXHR,error, errorThrown){
                    console.log("yorum silinemedi!")
                }
            })
        })

    })
</script>
<!--/.Reply-->