<?php
function zamanOnce($zaman)
{
    // echo $zaman." - ";
    $zaman =  strtotime($zaman);
    $zaman_farki = time() - $zaman;

    
    // echo $zaman." - ".$zaman_farki." ";

    $saniye = $zaman_farki;
    $dakika = round($zaman_farki / 60);
    $saat = round($zaman_farki / 3600);
    $gun = round($zaman_farki / 86400);
    $hafta = round($zaman_farki / 604800);
    $ay = round($zaman_farki / 2419200);
    $yil = round($zaman_farki / 29030400);
    if ($saniye < 60) {
        if ($saniye == 0) {
            return "az önce";
        } else {
            return $saniye . ' saniye önce';
        }
    } else if ($dakika < 60) {
        return $dakika . ' dakika önce';
    } else if ($saat < 24) {
        return $saat . ' saat önce';
    } else if ($gun < 7) {
        return $gun . ' gün önce';
    } else if ($hafta < 4) {
        return $hafta . ' hafta önce';
    } else if ($ay < 12) {
        return $ay . ' ay önce';
    } else {
        return $yil . ' yıl önce';
    }
}

function turkcetarih_formati($format, $datetime = 'now')
{
    $z = date("$format", strtotime($datetime));
    $gun_dizi = array(
        'Monday'    => 'Pazartesi',
        'Tuesday'   => 'Salı',
        'Wednesday' => 'Çarşamba',
        'Thursday'  => 'Perşembe',
        'Friday'    => 'Cuma',
        'Saturday'  => 'Cumartesi',
        'Sunday'    => 'Pazar',
        'January'   => 'Ocak',
        'February'  => 'Şubat',
        'March'     => 'Mart',
        'April'     => 'Nisan',
        'May'       => 'Mayıs',
        'June'      => 'Haziran',
        'July'      => 'Temmuz',
        'August'    => 'Ağustos',
        'September' => 'Eylül',
        'October'   => 'Ekim',
        'November'  => 'Kasım',
        'December'  => 'Aralık',
        'Mon'       => 'Pts',
        'Tue'       => 'Sal',
        'Wed'       => 'Çar',
        'Thu'       => 'Per',
        'Fri'       => 'Cum',
        'Sat'       => 'Cts',
        'Sun'       => 'Paz',
        'Jan'       => 'Oca',
        'Feb'       => 'Şub',
        'Mar'       => 'Mar',
        'Apr'       => 'Nis',
        'Jun'       => 'Haz',
        'Jul'       => 'Tem',
        'Aug'       => 'Ağu',
        'Sep'       => 'Eyl',
        'Oct'       => 'Eki',
        'Nov'       => 'Kas',
        'Dec'       => 'Ara',
    );
    foreach ($gun_dizi as $en => $tr) {
        $z = str_replace($en, $tr, $z);
    }
    if (strpos($z, 'Mayıs') !== false && strpos($format, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
    return $z;
}

function EtkinlikTurleri()
{
    return array("Bisiklet", "Koşu", "Yürüyüş", "Yüzme", "Tırmanış", "Futbol", "Basketbol", "Voleybol", "Quidditch");
}

function EtkinlikZorlukSeviyeleri()
{
    return array("Başlangıç", "Orta", "İleri", "Profesyonel");
}

$ARAMA_ZAMANLARI = array(
    // "0" => "Tüm Zamanlar",
    "1" => "Gelecek",
    "2" => "Bugün",
    "3" => "Bu Hafta",
    "4" => "Bu Ay",
    // "5" => "Önümüzdeki Ay",
    // "6" => "Önümüzdeki 3 Ay",
    // "7" => "Geçen Hafta",
    // "8" => "Geçen Ay",
    "9" => "Geçmiş",
);

/**
 * $ARAMA_ZAMANLARI dizine ait keyi parametre olarak alıp şimdiki tarihe göre 
 * aranacak tarihi hesaplar...
 * gönüş olarak arama operatorü(>,<,=) ve arama tarihini birlikte döner.
 */
function AramaZamanıParametresiAyarla($find_time)
{
    $operator = "";
    $tarih = "";
    $tarih_son = "";

    if ($find_time == 0) {
        //BİŞEY YAPMA
    } else if ($find_time == 1) {
        //"1" => "Gelecek",
        $operator =  ">=";
        $tarih = date('Y-m-d');
    } else if ($find_time == 2) {
        //"2" => "Bugün",
        $operator =  "=";
        $tarih = date('Y-m-d');
    } else if ($find_time == 3) {
        //"3" => "Bu Hafta",
        $operator =  "<>";
        $tarih = date('Y-m-d');
        $tarih_son = date('Y-m-d', strtotime($tarih . " + 7 day"));
    } else if ($find_time == 4) {
        // "4" => "Bu Ay",
        $operator =  "<>";
        $tarih = date('Y-m-d');
        $tarih = date('Y-m-01', strtotime($tarih));
        $tarih_son = date('Y-m-t', strtotime($tarih));
    } else if ($find_time == 5) {
        $operator =  "<>";
        $tarih = date('Y-m-d');
        $tarih_son = date('Y-m-d', strtotime($tarih . " + 7 day"));
    } else if ($find_time == 9) {
        $operator =  "<";
        $tarih = date('Y-m-d');
    }

    return [$operator, $tarih, $tarih_son];
}

$TURKIYE_ILLER = array(
    'Adana', 'Adıyaman', 'Afyon', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Artvin',
    'Aydın', 'Balıkesir', 'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa', 'Çanakkale',
    'Çankırı', 'Çorum', 'Denizli', 'Diyarbakır', 'Edirne', 'Elazığ', 'Erzincan', 'Erzurum', 'Eskişehir',
    'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkari', 'Hatay', 'Isparta', 'Mersin', 'İstanbul', 'İzmir',
    'Kars', 'Kastamonu', 'Kayseri', 'Kırklareli', 'Kırşehir', 'Kocaeli', 'Konya', 'Kütahya', 'Malatya',
    'Manisa', 'Kahramanmaraş', 'Mardin', 'Muğla', 'Muş', 'Nevşehir', 'Niğde', 'Ordu', 'Rize', 'Sakarya',
    'Samsun', 'Siirt', 'Sinop', 'Sivas', 'Tekirdağ', 'Tokat', 'Trabzon', 'Tunceli', 'Şanlıurfa', 'Uşak',
    'Van', 'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman', 'Kırıkkale', 'Batman', 'Şırnak',
    'Bartın', 'Ardahan', 'Iğdır', 'Yalova', 'Karabük', 'Kilis', 'Osmaniye', 'Düzce'
);

function UrlIdFrom($parametreAdi)
{
    if(!isset($_GET[$parametreAdi]) || empty($parametreAdi))
        $parametreAdi = "id";

    if(!isset($_GET[$parametreAdi]) )
        return null;

    $parameterUrl = $_GET[$parametreAdi];
    $parameterUrl = trim($parameterUrl);

    $arr = explode('-', $parameterUrl);

    return end($arr);
}

/**
 * verilen stringi "-" değerine göre parçalar ilk elemanı döner
 */
function FirstItemFrom($deger, $varsayilan = "")
{
    $parameterUrl = trim($deger);
    $arr = explode('-', $parameterUrl);

    if (count($arr) > 0)
        return  $arr[0];
    else
        return $varsayilan;
}

function ToMeaningfullUrl($str, $id)
{
    //echo $value;
    $turkish = array("ı", "ğ", "ü", "ş", "ö", "ç", "İ", "Ğ", "Ü", "Ş", "Ö", "Ç"); //turkish letters
    $english   = array("i", "g", "u", "s", "o", "c", "I", "G", "U", "S", "O", "C"); //english cooridinators letters

    $remove_chars = array(".", ":");
    $replace_chars = array("", "");

    $str = str_replace($turkish, $english, $str); //replace turkish chars
    $str = str_replace($remove_chars, $replace_chars, $str); //replace unwanted chars
    $str = strtolower($str);

    $str = str_replace(" ", "-", $str);

    return $str . "-" . $id;
}

function ToEnglish($value)
{
    $turkish = array("ı", "ğ", "ü", "ş", "ö", "ç", "İ", "Ğ", "Ü", "Ş", "Ö", "Ç"); //turkish letters
    $english   = array("i", "g", "u", "s", "o", "c", "I", "G", "U", "S", "O", "C"); //english cooridinators letters

    $value = str_replace($turkish, $english, $value); //replace php function
    return $value;
}
