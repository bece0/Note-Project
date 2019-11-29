<div class="kayit">

    <?php 
        $eventTime = strtotime($event_detail["tarih"]);
        $nowTime = time();
// etkinlik bitmiş ise
        if($eventTime < $nowTime){?>  
        <div class="alert alert-warning" role="alert">
            Bu etkinlik bitti.
        </div>
 <!--etkinlik bitmemiş ise  --> 
    <?php } else {  ?>

         <!--giriş yapılmışsa  --> 
        <?php   if(isset($_SESSION["kullanici_id"])){   ?>
                
                <!--etkinliği düzenleyen ise  -->
                <?php  if( $_SESSION["kullanici_id"] == $event_detail["duzenleyen_id"] ){ ?>         
                    <a class="btn btn-primary edit-button" href="edit_event.php?event=<?php  echo $event_id ?>">Düzenle</a>                                                                   
                <?php   }else if($_SESSION["admin"] != "1"){ ?>

                    <!--etkinliğe kayıtlıysa  -->  
                    <?php if(KatilimciVarMi($event_id, $_SESSION["kullanici_id"]) === TRUE){ ?>                                      
                        <form class="form" action="action/cancel_event_action.php" method="POST">
                            <input type="hidden" id="event_id" name ="event_id" value=<?php echo $event_id ?>>
                            <button type="submit" class="btn btn-danger" title="Etkinlikten vazgeç">Vazgeç</button>
                        </form>   
                    <!--etkinliğe kayıtlı değilse  -->                                                                
                    <?php } else { ?>   
                        <form class="form" action="action/register_event_action.php" method="POST">
                            <input type="hidden" id="event_id" name ="event_id" value=<?php echo $event_id ?>>
                            <button type="submit" class="btn btn-success" title="Etkinliğe kaydol">Katıl</button>
                        </form>
                    <?php  } 


        }} else { ?>  <!--giriş yapılmamışsa  -->
                <a class="btn btn-success" href="login.php?event=<?php  echo $event_id ?>">Kayıt İçin Giriş Yapın</a>
        <?php } ?>

    <?php } ?>



</div>