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
                <form class="form" action="action/create_course_action.php" method="POST" enctype="multipart/form-data"
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
                            <input id="bolum_adi" name="bolum_adi" placeholder="Bölüm Adı" class="form-control" required
                                type="text">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input id="kontenjan" name="kontenjan" placeholder="Kontenjan" class="form-control"
                                required="true" value="" type="number">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-thumbtack"></i></span>
                            </div>
                            <input id="sinif" name="sinif" placeholder="Sınıf" class="form-control" required="true"
                                value="" type="text">
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            </div>
                            <textarea rows="2" id="aciklama" name="aciklama" placeholder="Açıklama" class="form-control"
                                required="true"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success" style="float:right;">Oluştur</button>
                </form>

            </div>

        </div>
    </div>
</div>

<?php   
    // $ders_id = $_GET["course"];
    // $ders_detail = DersBilgileriniGetir($ders_id);
?>