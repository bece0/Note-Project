function TurkceKarakterKaldir(deger) {

    String.prototype.replaceAll = function(str1, str2, ignore) 
    {
        return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
    } 

    var turkish = ["ı", "ğ", "ü", "ş", "ö", "ç", "İ", "Ğ", "Ü", "Ş", "Ö", "Ç"];//turkish letters
    var english = ["i", "g", "u", "s", "o", "c", "I", "G", "U", "S", "O", "C"];//english cooridinators letters

    turkish.forEach((element, index) => {
        deger = deger.replaceAll(element, english[index]);
    });

    return deger;
}


function BilgiMesajiGoster(mesaj){
    mesaj = mesaj || 'İşlem tamamlandı';
    Swal.fire({
        title: mesaj,
        type: 'info',
        confirmButtonText: 'Tamam'
    });
}

function HataMesajiGoster(mesaj){
    mesaj = mesaj || 'Bir hata oluştu';
    Swal.fire({
        title: mesaj,
        type: 'error',
        confirmButtonText: 'Tamam'
    });
}

function ajaxGenelHataCallback(jqXHR, error, errorThrown){
    var mesaj = "Bir hata oluştu!";
    var response = jQuery.parseJSON(jqXHR.responseText);
    if (response && response.mesaj)
        mesaj = response.mesaj;

    HataMesajiGoster(mesaj);
}

Date.prototype.monthNames = [
    "Ocak", "Şubat", "Mart",
    "Nisan", "Mayıs", "Haziran",
    "Temmuz", "Ağustos", "Eylül",
    "Ekim", "Kasım", "Aralık"
];

Date.prototype.getMonthName = function() {
    return this.monthNames[this.getMonth()];
};

Date.prototype.getShortMonthName = function () {
    if( this.getMonthName())
    return this.getMonthName().substr(0, 3);
    else return ""
};

Date.prototype.getHourWithZero = function () {
    var hour = this.getHours();
    if(hour < 10)
        return "0" + hour;
    return hour;
};

Date.prototype.getMinutesWithZero = function () {
    var min = this.getMinutes();
    if(min < 10)
        return "0" + min;
    return min;
};

// usage:
// var d = new Date();
// alert(d.getMonthName()); 
// alert(d.getShortMonthName()); 