<div class="modal fade" id="dersOlusturModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><b>Ders Oluştur</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form id="courseAddForm" class="form" action="action/create_course_action.php" method="POST" enctype="multipart/form-data"
                    style="margin-top:15px;">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                            </div>
                            <input id="ders_adi" name="ders_adi" placeholder="Ders Adı" class="form-control" required
                                type="text" maxlength="25">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen-nib"></i></span>
                            </div>
                            <input id="ders_bolum_adi" name="ders_bolum_adi" placeholder="Bölüm Adı" class="form-control" required
                                type="text">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input id="ders_kontenjan" name="ders_kontenjan" placeholder="Kontenjan" class="form-control"
                                required="true" value="" type="number">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-thumbtack"></i></span>
                            </div>
                            <input id="ders_sinif" name="ders_sinif" placeholder="Sınıf" class="form-control" required="true"
                                value="" type="text">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            </div>
                            <textarea rows="2" id="ders_aciklama" name="ders_aciklama" placeholder="Açıklama" class="form-control"
                                required="true"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success" style="float:right;">Oluştur</button>
                </form>

            </div>

        </div>
    </div>
</div>
<script>

$("#courseAddForm").on('submit', function(e) {
    e.preventDefault();
    var data = {
        name : $("#ders_adi").val(),
        desc : $("#ders_aciklama").val(),
        department : $("#ders_bolum_adi").val(),
        quota : $("#ders_kontenjan").val(),
        class : $("#ders_sinif").val(),
    }
    $.ajax({
        url: "services/course.php?method=add",
        type: "POST",
        data: JSON.stringify(data),
        success: function(data) {
            // location.reload();
            location.reload(true);
        },
        error: function(e) {
            Swal.fire({
                text: 'Ders eklenemedi!',
                type: 'error',
                confirmButtonText: 'Tamam'
            })
        }
    });
});
</script>
<?php   
    // $ders_id = $_GET["course"];
    // $ders_detail = DersBilgileriniGetir($ders_id);
?>