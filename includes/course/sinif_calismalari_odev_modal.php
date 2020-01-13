<div class="modal fade" id="odevOlusturModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><b>Ödev Oluştur</b></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form class="form" style="margin-top:15px;">
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
                        <label>Ödev Son Gönderme Tarihi</label>
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
                            <input type="checkbox" id="dosya_gonderme" name="dosya_gonderme" class="form-control"
                                required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" style="float:right;"
                        id="btnOdevOlustur">Oluştur</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$("#btnOdevOlustur").on("click", function(e) {
    var isim = $("#odevOlusturModal #odev_adi").val();
    var aciklama = $("#odevOlusturModal #aciklama").val();
    var son_tarih = $("#odevOlusturModal #son_tarih").val();

    console.log(isim + " " + aciklama + " " + son_tarih);
})
</script>