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