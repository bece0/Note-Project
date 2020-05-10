<div class="modal fade" id="sinavOlusturModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Sınav Oluştur</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="sinavForm" class="form" style="margin-top:15px;" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="ders_id" value="<?php echo $COURSE_ID;?>">
                    <div class="form-group">
                        <label>Sınav Adı</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            </div>
                            <input id="sinav_adi" name="sinav_adi" placeholder="Sınav Adı" class="form-control" required
                                type="text" minlength="2" maxlength="255">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tarih</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                            </div>
                            <input type="date" id="sinav_gun" name="sinav_gun" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Saat</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                            </div>
                            <input type="time" id="sinav_saat" name="sinav_saat" class="form-control" value="08:00"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" style="float:right;"
                        id="btnSinavOlustur">Oluştur</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(e) {
    $("#sinavForm").on('submit', (function(e) {
        e.preventDefault();

        var data = {
            courseId: $("#ders_id").val(),
            examName: $("#sinav_adi").val(),
            examDay: $("#sinav_gun").val(),
            examTime: $("#sinav_saat").val()
        }

        $.ajax({
            url: "services/sinav.php",
            type: "POST",
            data: JSON.stringify(data),
            // contentType: false,
            // cache: false,
            // processData: false,
            success: function(data) {
                $("#sinavOlusturModal").modal("hide");
                Swal.fire({
                    text: 'Sınav başarıyla oluşturuldu.',
                    type: 'success',
                    confirmButtonText: 'Tamam'
                })
                DersDuyurulariGetir();
            },
            error: function(e) {
                Swal.fire({
                    text: 'Sınav oluşturulamadı.',
                    type: 'error',
                    confirmButtonText: 'Tamam'
                })
            }
        });
    }));
});
</script>