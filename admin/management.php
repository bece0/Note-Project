<?php
    $REQUIRE_LOGIN = TRUE;
    $REQUIRE_ADMIN = TRUE;

    $page_title = "Yönetim";
    
    include '../includes/page-common.php';
    include '../includes/head.php';
    include '../includes/nav-bar.php';


    //bu sayfayı sadece yöneticiler görür.
    // if(!isset($_SESSION["admin"]) || $_SESSION["admin"] != 1){
    //     header('Location: dashboard.php');
    // }
?>
<style>
    .stat-ikon {
        position: absolute;
        left: 40%;
        top: -20px;
        border-radius: 50%;
    }
    .bootstrap-table .fixed-table-container .fixed-table-body {
        height: unset !important;
    }
</style>

<!-- Tablo işleri için kullanılan kütüphane https://bootstrap-table.com/ -->

<!-- <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.14.2/dist/bootstrap-table.min.css">
<script src="https://unpkg.com/bootstrap-table@1.14.2/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.14.2/dist/locale/bootstrap-table-tr-TR.min.js"></script> -->

<link rel="stylesheet" href="assets/css/vendor/bootstrap-table.min.css">
<script src="assets/js/vendor/bootstrap-table.min.js"></script>
<script src="assets/js/vendor/bootstrap-table-tr-TR.min.js"></script>

<body>
    <div class="container">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-system-tab" data-toggle="tab" href="#nav-system" role="tab"
                    aria-controls="nav-system" aria-selected="true">Sistem Özeti</a>
                <a class="nav-item nav-link" id="nav-users-tab" data-toggle="tab" href="#nav-users" role="tab"
                    aria-controls="nav-users" aria-selected="true">Kullanıcı Günlükleri</a>
                <a class="nav-item nav-link" id="nav-events-tab" data-toggle="tab" href="#nav-events" role="tab"
                    aria-controls="nav-events" aria-selected="false">Etkinlik Günlükleri</a>
                <a class="nav-item nav-link" id="nav-systemlog-tab" data-toggle="tab" href="#nav-systemlog" role="tab"
                    aria-controls="nav-systemlog" aria-selected="false">Sistem Günlükleri</a>
                <a class="nav-item nav-link" id="nav-errors-tab" data-toggle="tab" href="#nav-errors" role="tab"
                    aria-controls="nav-errors" aria-selected="false">Hatalar</a>
            </div>
        </nav>
        <?php 
            $aktif_kullanıcı= AktifKullaniciSayisi();
            $GECMIS_DERS_SAYISI= DurumaGoreDersSayisi(0);
            $ACIK_DERS_SAYISI= DurumaGoreDersSayisi(1);
            $katilim_sayisi= ToplamKatılımSayisi();
            // var_dump($aktif_kullanıcı);
        ?>
        <div class="tab-content" id="nav-tabContent">
            <!-- sistem özeti -->
            <div class="tab-pane fade show active" id="nav-system" role="tabpanel" aria-labelledby="nav-system-tab">
                <div class="row w-100" style="margin-top:21px;">
                    <div class="col-md-3">
                        <div class="card border-info mx-sm-1 p-3">
                            <div class="card border-info shadow text-info p-3 stat-ikon"><span class="fa fa-users"
                                    aria-hidden="true"></span></div>
                            <div class="text-info text-center mt-3">
                                <h5>Aktif Kullanıcı</h5>
                            </div>
                            <div class="text-info text-center mt-2">
                                <h1 class="count"><?php echo $aktif_kullanıcı["toplam"]?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success mx-sm-1 p-3">
                            <div class="card border-success shadow text-success p-3 stat-ikon"><span
                                    class="fa fa-calendar-alt" aria-hidden="true"></span></div>
                            <div class="text-success text-center mt-3">
                                <h5>Arşivlenen Dersler</h5>
                            </div>
                            <div class="text-success text-center mt-2">
                                <h1 class="count"><?php echo $GECMIS_DERS_SAYISI["toplam"]  ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger mx-sm-1 p-3">
                            <div class="card border-danger shadow text-danger p-3 stat-ikon"><span
                                    class="fa fa-calendar" aria-hidden="true"></span></div>
                            <div class="text-danger text-center mt-3">
                                <h5>Açık Dersler</h5>
                            </div>
                            <div class="text-danger text-center mt-2">
                                <h1 class="count"><?php echo $ACIK_DERS_SAYISI["toplam"]  ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning mx-sm-1 p-3">
                            <div class="card border-warning shadow text-warning p-3 stat-ikon"><span class="fa fa-child"
                                    aria-hidden="true"></span></div>
                            <div class="text-warning text-center mt-3">
                                <h5>Toplam Katılım</h5>
                            </div>
                            <div class="text-warning text-center mt-2">
                                <h1 class="count"><?php echo $katilim_sayisi["toplam"] ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- kullanıcı hareketleri -->
            <div class="tab-pane fade show" id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">
                <!-- Kullanıcı günlükleri
                <p>kim ne zaman üye oldu</p>
                <p>kim ne zaman parolasını yeniledi vs</p> -->
                <table class="table-sm"
                    data-toggle="table"
                    data-url="services/management.php?method=user_logs"
                    data-pagination="true" data-search="true">
                    <thead>
                        <tr>
                        <th data-sortable="true" data-field="id">ID</th>
                        <th data-sortable="true" data-field="baslik">Başlık</th>
                        <th data-sortable="true" data-field="kullanici_id">Kullanıcı</th>
                        <th data-field="mesaj">Mesaj</th>
                        <th data-sortable="true" data-field="tarih">Tarih</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- etkinlik hareketleri -->
            <div class="tab-pane fade" id="nav-events" role="tabpanel" aria-labelledby="nav-events-tab">
                <!-- Etkinlik hareketleri
                <p>kim hangi etkinliği ne zaman oluşturdu/iptal etti vs</p>
                <p>kim hangi etkinliğe ne zaman katıldı/çıktı vs</p> -->
                <table class="table-sm"
                    data-toggle="table"
                    data-url="services/management.php?method=course_logs"
                    data-pagination="true" data-search="true">
                    <thead>
                        <tr>
                        <th data-sortable="true" data-field="id">ID</th>
                        <th data-sortable="true" data-field="baslik">Başlık</th>
                        <th data-sortable="true" data-field="etkinlik_id">Etkinlik</th>
                        <th data-field="mesaj">Mesaj</th>
                        <th data-sortable="true" data-field="tarih">Tarih</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="tab-pane fade" id="nav-systemlog" role="tabpanel" aria-labelledby="nav-systemlog-tab">
                <table class="table-sm"
                    data-toggle="table"
                    data-url="services/management.php?method=system_logs"
                    data-pagination="true" data-search="true">
                    <thead>
                        <tr>
                        <th data-sortable="true" data-field="id">ID</th>
                        <th data-sortable="true" data-field="tip">Tip</th>
                        <th data-sortable="true" data-field="baslik">Başlık</th>
                        <th data-field="icerik">Mesaj</th>
                        <th data-sortable="true" data-field="tarih">Tarih</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <!-- hatalar vs. -->
            <div class="tab-pane fade" id="nav-errors" role="tabpanel" aria-labelledby="nav-errors-tab">
                <table class="table-sm"
                    data-toggle="table"
                    data-url="services/management.php?method=errors"
                    data-pagination="true" data-search="true">
                    <thead>
                        <tr>
                        <th data-sortable="true" data-field="id">ID</th>
                        <th data-sortable="true" data-field="tip">Önem</th>
                        <th data-sortable="true" data-field="baslik">Başlık</th>
                        <th data-field="icerik">Mesaj</th>
                        <th data-sortable="true" data-field="tarih">Tarih</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script>

            $('.count').each(function () {
                $(this).prop('Counter',0).animate({
                    Counter: $(this).text()
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function (now) {
                        $(this).text(Math.ceil(now));
                    }
                });
            });

    </script>
</body>