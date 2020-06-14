<div class="modal fade" id="odevYukleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Ödev Gönder</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="dokumanForm" class="form" style="margin-top:15px;" enctype="multipart/form-data" method="POST" action="action/odev_action.php">
                    <input type="hidden" name="ders_id" value="<?php echo $COURSE_ID; ?>">
                    <input type="hidden" name="odev_id" value="<?php echo $ODEV_ID; ?>">
                    <div class="form-group">
                        <label>Ödev Dosyası</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-file"></i></span>
                            </div>
                            <input type="file" id="dosya" name="dosya" placeholder="Ödev dosyası" class="form-control" accept=".pdf,.doc,.docx,.zip" required>
                        </div>
                    </div>
                    <button type="summit" class="btn btn-success" style="float:right;">Gönder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(e) {
        $("#dokumanForm").on('submit', (function(e) {
            e.preventDefault();

            var url = "services/odev_upload.php";
            if (API_ISTEGIMI && API_KEY) {
                url = url + "?X-Api-Key=" + API_KEY;
            }

            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $("#odevYukleModal").modal("hide");
                    Swal.fire({
                        text: 'Ödev başarıyla gönderildi.',
                        type: 'success',
                        confirmButtonText: 'Tamam'
                    }).then((result) => {
                        location.reload();
                    });
                },
                error: function(e) {
                    Swal.fire({
                        text: 'Ödev gönderilemedi.',
                        type: 'error',
                        confirmButtonText: 'Tamam'
                    })
                }
            });
        }));
    });
</script>