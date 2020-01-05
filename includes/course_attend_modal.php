<div class="modal fade" id="derseKaydolModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><b>Derse Kaydol</b></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
            <form class="form-signin" action="action/attend_course_action.php" method="post">
              <input type="text" class="form-control mb-2" placeholder="Ders Kodu" name="kod" required autofocus>       
              
              <?php if($OGRETMEN== TRUE) { ?>
                <p class="alert alert-warning">Bu dersi açan öğretmen sizi asistan olarak atadıktan sonra, ders anasayfanızda görüntülenecektir.</p>
              <?PHP } ?>
              <button type="submit" class="btn btn-success" style="float:right;">Kayıt ol</button>
            </form>
      </div>
    </div>
  </div>
</div>