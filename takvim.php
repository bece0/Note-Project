<?php
$REQUIRE_LOGIN = FALSE;
$page_title = " Takvim";
include 'includes/page-common.php';
include 'includes/head.php';
include 'includes/nav-bar.php';

?>
<link rel="stylesheet" href="assets/css/takvim.css">
<script src="assets/lib/takvim.js"></script>

<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i"
    rel="stylesheet">

<style>
.takvim-aciklama{
    margin-bottom: 20px;
}

.takvim-aciklama button{
    margin-bottom: 5px;
}
</style>

<body>
    <div class="container" id='wrap'>

        <div class="row">
            <div class="col-md-2 col-sm-12">
                <div id="takvim-aciklama" class="takvim-aciklama" style="display:none;">
                    <button class="btn" style="background-color:orange;">Sınavlar</button>
                    <button class="btn" style="background-color:rgb(194, 228, 139);">Ödevler</button>
                </div>
            </div>
            <div class="col-md-10 col-sm-12">
                <div id="takvim-yukleniyor" class="alert alert-info">Takvim yükleniyor...</div>
                <div id='calendar'></div>
            </div>
        </div>


        <div style='clear:both'></div>
    </div>
</body>

<script>
$(document).ready(function() {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();


    var DATA = [];

    // DATA = [{
    //         title: 'All Day Event',
    //         start: new Date(y, m, 1)
    //     },
    //     {
    //         id: 999,
    //         title: 'Repeating Event',
    //         start: new Date(y, m, d - 3, 16, 0),
    //         allDay: false,
    //         className: 'info'
    //     },
    //     {
    //         id: 999,
    //         title: 'Repeating Event',
    //         start: new Date(y, m, d + 4, 16, 0),
    //         allDay: false,
    //         className: 'info'
    //     },
    //     {
    //         title: 'Meeting',
    //         start: new Date(y, m, d, 10, 30),
    //         allDay: false,
    //         className: 'important'
    //     },
    //     {
    //         title: 'Lunch',
    //         start: new Date(y, m, d, 12, 0),
    //         end: new Date(y, m, d, 14, 0),
    //         allDay: false,
    //         className: 'important'
    //     },
    //     {
    //         title: 'Birthday Party',
    //         start: new Date(y, m, d + 1, 19, 0),
    //         end: new Date(y, m, d + 1, 22, 30),
    //         allDay: false,
    //     },
    //     {
    //         title: 'Ödev 3',
    //         start: new Date(y, m, 28),
    //         end: new Date(y, m, 29),
    //         url: 'odev.php?kod=124124124124124124',
    //         className: 'success'
    //     }
    // ];

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

    });

    $.ajax({
        type: "GET",
        url: 'services/takvim.php?tip=hepsi',
        success: function(response) {
            if (response && response.data) {
                TakvimiYukle(response.data);
            } else {
                TakvimiYukle(null);
            }
        },
        error: function(jqXHR, error, errorThrown) {
            console.log(error);
            console.log("ders duyurulari getirilemedi");
        }
    });


    /* initialize the calendar
    -----------------------------------------------------------------*/

    var TAKVIM;
    var DATA = [];

    function TakvimiYukle(data) {
        $("#takvim-yukleniyor").hide();
        $("#takvim-aciklama").show();

        if (data && data.duyurular) {
            for (var i = 0; i < data.duyurular.length; i++) {
                var duyuru = data.duyurular[i];

                var takvimData = {};

                if (duyuru.ders_adi && duyuru.ders_id)
                    takvimData.url = 'course.php?course=' + duyuru.ders_adi + '-' + duyuru.ders_id;

                takvimData.title = duyuru.ders_adi + ' - ' + duyuru.mesaj;
                takvimData.className = 'orange'

                takvimData.start = new Date(duyuru.takvim_tarih);
                takvimData.end = new Date(duyuru.takvim_tarih);

                DATA.push(takvimData);
            }
        }

        if (data && data.odevler) {
            for (var i = 0; i < data.odevler.length; i++) {
                var odev = data.odevler[i];

                var odevData = {};

                if (odev.kod)
                    odevData.url = 'odev.php?kod=' + odev.kod;

                // odevData.title = odev.ders_adi + ' - ' + odev.isim;
                odevData.title = odev.isim;
                odevData.className = 'odev'

                odevData.start = new Date(odev.son_tarih);
                odevData.end = new Date(odev.son_tarih);

                DATA.push(odevData);
            }
        }

        TAKVIM = $('#calendar').fullCalendar({
            header: {
                left: 'title',
                // center: 'agendaDay,agendaWeek,month',
                right: 'prev,next today'
            },
            dayNames: ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'],
            dayNamesShort: ['Pzr', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt'],
            monthNames: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz',
                'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
            ],
            buttonText: {
                day: 'gün',
                week: 'hafta',
                month: 'ay',
                year: 'yıl',
                today: 'bugün'
            },
            editable: false,
            firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
            selectable: false,
            defaultView: 'month',
            axisFormat: 'h:mm',
            columnFormat: {
                month: 'ddd', // Mon
                week: 'ddd d', // Mon 7
                day: 'dddd M/d', // Monday 9/7
                agendaDay: 'dddd d'
            },
            titleFormat: {
                month: 'MMMM yyyy', // September 2009
                week: "MMMM yyyy", // September 2009
                day: 'MMMM yyyy' // Tuesday, Sep 8, 2009
            },
            allDaySlot: false,
            selectHelper: true,
            select: function(start, end, allDay) {
                //TODO - ödeve tıkladıysa ödev detayına gitsin..
                calendar.fullCalendar('unselect');
            },
            events: DATA,
        });
    }


});
</script>


</html>