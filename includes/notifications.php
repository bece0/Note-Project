<style>
    .notify-nav-link {
        margin-top: 5px;
    }

    .notify-cpunt-pill {
        float: right;
        margin-top: -8px;
        position: fixed;
        margin-left: 5px;
    }

    .bildirim-mesaj {
        font-style: normal;
        font-weight: 300;
        font-size: 12px;
        color: unset;
    }

    .bildirim-mesaj:hover {
        color: #0056b3;
        text-decoration: unset;
    }

    .navbar .dropdown-menu.notify-drop {
        min-width: 330px;
        background-color: #fff;
        /* min-height: 360px; */
        max-height: 360px;
    }

    .navbar .dropdown-menu.notify-drop .notify-drop-title {
        border-bottom: 1px solid #e2e2e2;
        padding: 5px 15px 10px 15px;
    }

    .navbar .dropdown-menu.notify-drop .drop-content {
        /* min-height: 280px; */
        max-height: 280px;
        overflow-y: scroll;
    }

    .navbar .dropdown-menu.notify-drop .drop-content::-webkit-scrollbar-track {
        background-color: #F5F5F5;
    }

    .navbar .dropdown-menu.notify-drop .drop-content::-webkit-scrollbar {
        width: 8px;
        background-color: #F5F5F5;
    }

    .navbar .dropdown-menu.notify-drop .drop-content::-webkit-scrollbar-thumb {
        background-color: #ccc;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li {
        display: flex;
        flex-wrap: wrap;
        padding: 10px 0px 1px 0px;
        border-bottom: 1px solid #e2e2e2;
        font-weight: bold;
        font-size: 14px;
        cursor: pointer;
        /* padding: 10px 0px 5px 0px; */
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li>div {
        padding-right: 10px;
        padding-left: 10px;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li:nth-child(2n+0) {
        background-color: #fafafa;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li:after {
        content: "";
        clear: both;
        display: block;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li:hover {
        background-color: #fcfcfc;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li:last-child {
        border-bottom: none;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li .notify-img {
        float: left;
        display: inline-block;
        width: 45px;
        height: 45px;
        margin: 0px 0px 8px 0px;
    }

    .navbar .dropdown-menu.notify-drop .allRead {
        margin-right: 7px;
    }

    .navbar .dropdown-menu.notify-drop .rIcon {
        float: right;
        color: #999;
    }

    .navbar .dropdown-menu.notify-drop .rIcon:hover {
        color: #333;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li a {
        font-size: 12px;
        font-weight: normal;
    }



    .navbar .dropdown-menu.notify-drop .drop-content>li hr {
        margin: 2px 0;
        /* width: 70%; */
        border-color: #e2e2e2;
    }

    .navbar .dropdown-menu.notify-drop .drop-content .pd-l0 {
        padding-left: 0;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li p {
        font-size: 11px;
        color: #666;
        font-weight: normal;
        margin: 3px 0;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li p.time {
        font-size: 10px;
        font-weight: 600;
        float: right;
        top: -6px;
        margin: 8px 0px 0px 0px;
        padding: 0px 3px;
        border: 1px solid #e2e2e2;
        position: relative;
        background-image: linear-gradient(#fff, #f2f2f2);
        display: inline-block;
        border-radius: 2px;
        color: #B97745;
    }

    .navbar .dropdown-menu.notify-drop .drop-content>li p.time:hover {
        background-image: linear-gradient(#fff, #fff);
    }

    .navbar .dropdown-menu.notify-drop .notify-drop-footer {
        border-top: 1px solid #e2e2e2;
        bottom: 0;
        position: relative;
        padding: 4px 15px;
    }

    .navbar .dropdown-menu.notify-drop .notify-drop-footer a {
        color: #777;
        text-decoration: none;
    }

    .navbar .dropdown-menu.notify-drop .notify-drop-footer a:hover {
        color: #333;
    }
</style>

<?php
$notifications = GetUserNotifications($kullanici_id, 5);
$notification_count = count($notifications);
$new_notification_count = 0;

function BildirimUygunIconGetir($bildirim_tipi)
{
    $icon = "envelope";
    if ($bildirim_tipi == "NORMAL")
        $icon = "envelope";
    else if ($bildirim_tipi == "DUYURU")
        $icon = "bullhorn";
    else if ($bildirim_tipi == "ETKINLIK_IPTAL")
        $icon = "window-close";
    else if ($bildirim_tipi == "ETKINLIK_TARIH_UPDATE")
        $icon = "calendar-check";
    else if ($bildirim_tipi == "ETKINLIK_YORUM_ONAY")
        $icon = "thumbs-up";

    return $icon;
}


function BildirimUygunIcerikGetir($bildirim)
{
    //var_dump($bildirim);
    $bildirim_tipi = $bildirim["tip"];
    $icerik = $bildirim["mesaj"];
    $etkinlik_adi = $bildirim["etkinlik"];

    if ($bildirim_tipi == "NORMAL")
        $icerik =  $icerik;
    else if ($bildirim_tipi == "DUYURU")
        $icerik = $etkinlik_adi . " - Duyuru : ".  $icerik;
    else if ($bildirim_tipi == "ETKINLIK_IPTAL")
        $icerik = $etkinlik_adi . " etkinliği iptal edildi";
    else if ($bildirim_tipi == "ETKINLIK_TARIH_UPDATE")
        $icerik = $etkinlik_adi . " etkinliği tarihi güncellendi";
    else if ($bildirim_tipi == "ETKINLIK_YORUM_ONAY")
        $icerik = $icerik;

    return $icerik;
}

foreach ($notifications as $key => $value) {
    if ($value["goruldu"] == "0")
        $new_notification_count++;
}
//var_dump($notifications);
?>

<li class="nav-item dropdown" style="margin-right: 3px;">
    <a href="#" class="nav-link notify-nav-link" count="<?php echo $new_notification_count; ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        <?php if ($new_notification_count > 0) { ?>
            <span id="notification_badge" class="badge badge-pill badge-primary notify-cpunt-pill">
                <?php echo $new_notification_count; ?>
            </span>
        <?php } ?>
        <i class="fas fa-bell"></i>
        <!-- <i class="fa fa-bell-o"></i> -->
    </a>
    <ul class="dropdown-menu dropdown-menu-right notify-drop">
        <?php if ($notification_count > 0) { ?>
            <div class="drop-content">
                <?php for ($i = 0; $i < $notification_count; $i++) {
                    $notification = $notifications[$i];
                    ?>
                    <li>
                        <!-- <div class="col-md-3 col-sm-3 col-xs-3">
                                                                                    <div class="notify-img"><img src="http://placehold.it/45x45" alt=""></div>
                                                                                </div> -->
                        <div class="col-1" style="font-size: 20px;">
                            <i class="fa fa-<?php echo BildirimUygunIconGetir($notification["tip"]) ?>"></i>
                        </div>
                        <div class="col-11">
                            <div>
                                <i class="fa fa-dot-circle-o"></i>
                                <a href="event.php?event=<?php echo $notification["etkinlik_id"] ?>" class="bildirim-mesaj">
                                    <?php echo BildirimUygunIcerikGetir($notification); ?>
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div style="width: 100%;">
                            <p class="time"><?php echo zamanOnce($notification["tarih"]); ?></p>
                        </div>
                    </li>
                <?php } ?>
            </div>
            <div class="notify-drop-footer text-center">
                <a href=""><i class="fa fa-eye"></i> Tümünü Göster</a>
            </div>
        <?php } else { ?>
            <div class="drop-content">
            </div>
            <div class="notify-drop-footer text-center">
                <i class="fa fa-times-circle"></i> Bildiriminiz bulunmamaktadır.
            </div>
        <?php } ?>
    </ul>
</li>
<script>
    $(".notify-nav-link").on("click", function(e) {
        var $btn = $(this);
        var count = $btn.attr("count");
        if (Number(count) && Number(count) > 0) {
            $.ajax({
                type: "GET",
                url: 'services/notification.php?method=notification_seen',
                success: function(response) {
                    console.log(response);
                    // $("#notification_badge").remove();
                    $("#notification_badge").hide('slow', function() {
                        $("#notification_badge");
                    });
                }
            })
        }
    })
</script>