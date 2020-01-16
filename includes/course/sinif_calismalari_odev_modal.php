<div class="modal fade" id="odevOlusturModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Ödev Oluştur</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="odevForm" class="form" style="margin-top:15px;" enctype="multipart/form-data" method="POST"
                    action="action/odev_action.php">
                    <input type="hidden" name="ders_id" value="<?php echo $COURSE_ID;?>">
                    <div class="form-group">
                        <label>Ödev Adı</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            </div>
                            <input id="odev_adi" name="odev_adi" placeholder="Ödev Adı" class="form-control" required
                                type="text" minlength="2" maxlength="255">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Açıklama</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            </div>
                            <textarea rows="3" id="aciklama" name="aciklama" placeholder="Açıklama"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Son Gönderme Tarihi</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-calendar-times"></i></span>
                            </div>
                            <input type="date" id="son_tarih" name="son_tarih" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ödev Dokümanı</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                            </div>
                            <input type="file" id="dosya" name="dosya" placeholder="Ödev dosyası" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ödev tesliminde dosya gönderilecek mi ?</label>
                        <div class="input-group">
                            <input type="checkbox" id="dosya_gonderme" name="dosya_gonderme" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success" style="float:right;"
                        id="btnOdevOlustur">Oluştur</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// $("#btnOdevOlustur").on("click", function(e) {
//     var isim = $("#odevOlusturModal #odev_adi").val();
//     var aciklama = $("#odevOlusturModal #aciklama").val();
//     var son_tarih = $("#odevOlusturModal #son_tarih").val();
//     console.log(isim + " " + aciklama + " " + son_tarih);
//     var form = $('#odevOlusturModal').get(0);
//     var formData = new FormData(form[0]);
//     $.ajax({
//         type: "POST",
//         url: "services/odev.php",
//         //dataType: 'json', //not sure but works for me without this
//         data: formData,
//         contentType: false, //this is requireded please see answers above
//         processData: false, //this is requireded please see answers above
//         //cache: false, //not sure but works for me without this
//         success: function(response) {
//             console.log("ödev oluşturuldu!")
//         },
//         error: function(jqXHR, error, errorThrown) {
//             console.log("ödev yüklenemedi!")
//         }
//     });
// })

$(document).ready(function(e) {
    $("#odevForm").on('submit', (function(e) {
        e.preventDefault();
        $.ajax({
            url: "services/odev.php",
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
                $("#odevOlusturModal").modal("hide");
                Swal.fire({
                    text: 'Ödev başarıyla oluşturuldu.',
                    type: 'success',
                    confirmButtonText: 'Tamam'
                })
            },
            error: function(e) {
                Swal.fire({
                    text: 'Ödev oluşturulamadı.',
                    type: 'error',
                    confirmButtonText: 'Tamam'
                })
            }
        });
    }));
});
</script>