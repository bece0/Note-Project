<div class="modal fade" id="dokumanOlusturModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Doküman Oluştur</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="dokumanForm" class="form" style="margin-top:15px;" enctype="multipart/form-data" method="POST"
                    action="action/odev_action.php">
                    <input type="hidden" name="ders_id" value="<?php echo $COURSE_ID;?>">
                    <div class="form-group">
                        <label>Doküman Adı</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            </div>
                            <input type="text" id="dokuman_adi" name="dokuman_adi" placeholder="Doküman Adı"
                                class="form-control" required minlength="2" maxlength="80">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Doküman Açıklaması</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            </div>
                            <textarea rows="3" id="dokuman_aciklama" name="dokuman_aciklama" placeholder="Açıklama"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Doküman Dosyası</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="file" id="dosya" name="dosya" placeholder="Ödev dosyası" class="form-control"
                                required>
                        </div>
                    </div>
                    <button type="summit" class="btn btn-success" style="float:right;">Paylaş</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(e) {
    $("#dokumanForm").on('submit', (function(e) {
        e.preventDefault();
        $.ajax({
            url: "services/dokuman.php",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                //$("#preview").fadeOut();
                $("#err").fadeOut();
            },
            success: function(data) {
                $("#dokumanOlusturModal").modal("hide");
                Swal.fire({
                    text: 'Doküman başarıyla oluşturuldu.',
                    type: 'success',
                    confirmButtonText: 'Tamam'
                })
            },
            error: function(e) {
                Swal.fire({
                    text: 'Doküman oluşturulamadı.',
                    type: 'error',
                    confirmButtonText: 'Tamam'
                })
            }
        });
    }));
});
</script>