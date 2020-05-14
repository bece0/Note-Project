<?php

function endswith($string, $test)
{
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, $strlen - $testlen, $testlen) === 0;
}

/**
 * 
 */
function KatilimciVarMi($ders_id, $kullanici_id)
{
    $sql = "SELECT * FROM katilimci where ders_id = $ders_id and ogrenci_id = $kullanici_id";

    $con = BAGLANTI_GETIR();
    $result = $con->query($sql);
    //  var_dump($sql);
    if ($result != NULL && $result->num_rows > 0)
        return TRUE;
    else
        return FALSE;
}

function DersKaydet($ders_kodu, $isim, $aciklama, $kontenjan,$bolum, $sinif, $duzenleyen_id)
{
    $sql = "INSERT INTO dersler (kodu, isim, aciklama, kontenjan,bolum_adi, sinif, duzenleyen_id)
    VALUES ('$ders_kodu','$isim','$aciklama', '$kontenjan','$bolum','$sinif','$duzenleyen_id')";

    return SQLInsertCalistir($sql);
}


/**    

 * TODO
 */
function DersIdBul($ders_kod){
    $sql = "SELECT id FROM dersler where kodu='".$ders_kod."' ";
    return SQLTekliKayitGetir($sql);
}

function DerseKayitOl($ogrenci_id, $ders_id, $tip = 0)
{
    $sql = "INSERT INTO katilimci (ogrenci_id, ders_id, tip, kayit_tarihi)
    VALUES ('$ogrenci_id', '$ders_id', $tip, CURDATE())";

    return SQLInsertCalistir($sql);
}

function DersinAsistanıMı($ders_id, $ogrenci_id){
    $sql = "SELECT * FROM katilimci WHERE 
        ders_id = $ders_id and 
        ogrenci_id = $ogrenci_id and
        tip = 1";

    $con = BAGLANTI_GETIR();
    $result = $con->query($sql);
    if ($result != NULL && $result->num_rows > 0)
        return TRUE;
    else
        return FALSE;
}

function DersKoduKontrol($ders_kod)
{
    $sql = "SELECT CASE WHEN EXISTS (
        SELECT *
        FROM dersler
        WHERE kodu = ".$ders_kod."
    )
        THEN CAST(1 AS BIT)
        ELSE CAST(0 AS BIT) END";
  //  $sql = "SELECT ders_kod FROM dersler WHERE kodu=".ders_kod." ";

    return SQLInsertCalistir($sql);
}

/**
 * Verilen ders is ve öğrenci id değerine sahip katılımcı kaydı varsa TRUE yoksa FALSE döner
 */
function DerseKayitliMi($ogrenci_id, $ders_id)
{
    $sql = "SELECT * FROM katilimci WHERE ders_id = $ders_id and ogrenci_id = $ogrenci_id";
    $sonuc = SQLCountCalistir($sql) > 0;

    // echo $sql;
    // var_dump($sonuc);
    // die();

    return  $sonuc;
}

function DerseKayitliOgrenciSayisi($ders_id)
{
    $sql = "SELECT COUNT(*) as toplam FROM katilimci 
        WHERE ders_id = $ders_id AND tip = 0";
    $sonuc = SQLTekliKayitGetir($sql);
    return $sonuc["toplam"];
}

/**
 * TODO
 */
function EtkinligiIptalEt($kullanici_id, $ders_id)
{
    if(!isset($kullanici_id) || !isset($ders_id))
        return false;

    if($kullanici_id == NULL || $ders_id == NULL)
        return false;

    $sql = "DELETE FROM katilimci 
    WHERE ogrenci_id = '$kullanici_id' AND ders_id = '$ders_id' ";

    return SQLDeleteCalistir($sql);
}

/**
 * Databasedeki tüm Dersleri döner
 *  @return array gelen sonucu döner,sonuç boş ise NULL döner 
 */
function DersleriGetir($limit = 50)
{
    $sql = "SELECT * FROM etkinlik order by id desc LIMIT $limit ";

    return SQLCalistir($sql);
}

/**
 * 
 */
function EtkinlikAra($search, $tip, $city, $timeOperator, $time_start, $time_end, $skip = 0, $limit = 50)
{
    $sql = "SELECT * FROM etkinlik where";
    if (!empty($time_start) && !empty($time_end)) {
        $sql = $sql . " etkinlik.tarih > '$time_start' and etkinlik.tarih < '$time_end' and";
    } else {
        if (!empty($time_start) && !empty($timeOperator)) {
            $sql = $sql . " etkinlik.tarih $timeOperator '$time_start' and";
        }
    }

    if (!empty($tip))
        $sql = $sql . " etkinlik.tip = '$tip' and";


    if (!empty($city))
        $sql = $sql . " etkinlik.sehir = '$city' and";

    if (!empty($search))
        $sql = $sql . " (etkinlik.isim LIKE '%$search%' or etkinlik.aciklama LIKE '%$search%') and";

    $sql = trim($sql);

    // echo "<br>" . $sql . "  " . endsWith($sql, "and") . "<br>";
    if (endsWith($sql, "and") == 1)
        $sql = substr($sql, 0, -3);

    // echo "<br>" . $sql . "<br>";
    if (endsWith($sql, "where") == 1)
        return GelecekDersleriGetir($skip, $limit);

    // echo "<br> order öncesi : " . $sql . "<br>";
    $sql = $sql . " order by id desc LIMIT $limit ";

    // echo "<br> <br>  <br>  SQL : " . $sql . "<br>";

    return SQLCalistir($sql);
}


function EtkinlikAra_Text($search, $onlyFeature = false, $skip = 0, $limit = 50)
{
    if (empty($search))
        return GelecekDersleriGetir($skip, $limit);

    $sql = "SELECT * FROM etkinlik where";
    if ($onlyFeature)
        $sql = $sql . " etkinlik.tarih > CURDATE() and";

    $sql = $sql . " etkinlik.isim LIKE '%$search%' or etkinlik.aciklama LIKE '%$search%'  or etkinlik.adres LIKE '%$search%')";

    if (endsWith($sql, "and") == 1) {
        $sql = substr($$sql, 0, -3);
    }

    if (endsWith($sql, "where") == 1) {
        return GelecekDersleriGetir($skip, $limit);
    }

    $sql = $sql . " order by id desc LIMIT $limit ";

    return SQLCalistir($sql);
}

/**
 * Id parametre olarak verilen etkinliğin bilgilerini getirir
 * @param string $event_id etkinliğin id'si
 * @return string gelen sonucu döner,sonuç boş ise NULL döner 
 */
function DersDetayGetir($id)
{
    // var_dump($event_id);
    $sql = "SELECT * FROM dersler where id = " . $id . "";

    return SQLTekliKayitGetir($sql);
}

function DersBilgileriniGetir($ders_id)
{
    // var_dump($event_id);
    $sql = "SELECT * FROM dersler where id = " . $ders_id . "";

    return SQLTekliKayitGetir($sql);
}

/**
 * Id parametre olarak verilen etkinliğin bilgilerini getirir
 * @param string $kodu etkinliğin kodu
 * @return string gelen sonucu döner,sonuç boş ise NULL döner 
 */
function DersDetayGetir_Kod($kodu)
{
    $sql = "SELECT * FROM dersler where kodu ='" . $kodu . "'";
    return SQLTekliKayitGetir($sql);
}



/**
 * 
 */


//($ders_kodu, $ders_adi, $aciklama, $kontenjan, $bolum_adi, $sinif
function DersDuzenle($ders_id, $ders_adi, $aciklama, $kontenjan, $bolum_adi, $sinif)
{
    $sql = "UPDATE dersler SET isim = '$ders_adi', aciklama = '$aciklama' , kontenjan ='$kontenjan', bolum_adi = '$bolum_adi',
    sinif = '$sinif' WHERE id=$ders_id";

    return SQLUpdateCalistir($sql);
}

function EtkinlikSil($ders_id)
{
    if(!isset($ders_id))
        return false;

    if($ders_id == NULL)
        return false;

    $sql = "DELETE FROM dersler  WHERE  kodu=$ders_id";

    return SQLDeleteCalistir($sql);
}

function DerstenKayitSil($ders_id, $ogrenci_id)
{
    if(!isset($ders_id) || !isset($ogrenci_id))
        return false;

    if($ders_id == NULL || $ogrenci_id == NULL)
        return false;

    $sql = "DELETE FROM katilimci 
    WHERE ders_id = $ders_id AND ogrenci_id = $ogrenci_id";

    return SQLDeleteCalistir($sql);
}

function DersKayitTipiGüncelle($ders_id, $ogrenci_id, $tip = 0){
    // echo "ders_id: $ders_id, ogrenci_id: $ogrenci_id, tip: $tip";

    if(!isset($ders_id) || !isset($ogrenci_id)){
        return false;
    }
    if($ders_id == NULL || $ogrenci_id == NULL){
        return false;
    }

    $sql = "UPDATE katilimci SET tip = $tip
    WHERE ders_id = $ders_id AND ogrenci_id = $ogrenci_id";

    return SQLUpdateCalistir($sql);
}

function DersiKapat($ders_id){
    if(!isset($ders_id)){
        return false;
    }

    $sql = "UPDATE dersler SET status = 0
    WHERE id = $ders_id";

    return SQLUpdateCalistir($sql);
}

/**
 * Açık Dersleri etkinlik tarihi sırasına göre getirir.
 * @param int $limit sorgu sonucu kayıt limiti
 */
function GelecekDersleriGetir($skip = 0, $limit = 50)
{
    $sql = "SELECT etkinlik.* FROM etkinlik WHERE etkinlik.tarih > CURDATE() 
    order by etkinlik.tarih LIMIT $limit";

    return SQLCalistir($sql);
}

function AktifDersleriGetir($skip = 0, $limit = 50)
{
    $sql = "SELECT dersler.*, kullanici.adi as ogretmen_adi, kullanici.soyadi as ogretmen_soyadi FROM dersler 
    INNER JOIN kullanici ON dersler.duzenleyen_id=kullanici.id
    LIMIT $limit";

    return SQLCalistir($sql);
}

function OgrencininAktifDersleriniGetir($kullanici_id)
{   
    $sql = "SELECT d.* , ogretmenler.adi as ogretmen_adi, ogretmenler.soyadi as ogretmen_soyadi  FROM katilimci 
    INNER JOIN dersler d ON katilimci.ders_id=d.id 
    INNER JOIN kullanici ON katilimci.ogrenci_id=kullanici.id
    INNER JOIN kullanici as ogretmenler ON ogretmenler.id=d.duzenleyen_id
    where katilimci.ogrenci_id = $kullanici_id AND d.status = 1";

    return SQLCalistir($sql);
}

function OgrencininArsivlenmisDersleriniGetir($kullanici_id)
{   
    $sql = "SELECT d.* , ogretmenler.adi as ogretmen_adi, ogretmenler.soyadi as ogretmen_soyadi  FROM katilimci 
    INNER JOIN dersler d ON katilimci.ders_id=d.id 
    INNER JOIN kullanici ON katilimci.ogrenci_id=kullanici.id
    INNER JOIN kullanici as ogretmenler ON ogretmenler.id=d.duzenleyen_id
    where katilimci.ogrenci_id = $kullanici_id AND d.status = 0";

    return SQLCalistir($sql);
}

function DuzenledigiAktifDersleriGetir($kullanici_id)
{       
     $sql = "SELECT d.*, k.adi as ogretmen_adi, k.soyadi as ogretmen_soyadi , 
        (SELECT COUNT(*) FROM katilimci  WHERE ders_id = d.id AND tip = 0) as toplam
        FROM dersler d
        INNER JOIN kullanici k on k.id = d.duzenleyen_id 
        WHERE duzenleyen_id = $kullanici_id AND status = 1";

    return SQLCalistir($sql);
}

function OgretmeninArsivlenmisDersleriniGetir($kullanici_id)
{       
    $sql = "SELECT DISTINCT d.* FROM dersler d INNER JOIN katilimci k ON k.ders_id=d.id
     where d.status = 0 and (d.duzenleyen_id=$kullanici_id or (k.ogrenci_id =$kullanici_id and k.tip=1))";

    return SQLCalistir($sql);
}

function AsistanOlunanDersleriGetir($kullanici_id)
{       
     $sql = "SELECT d.*, k.adi as ogretmen_adi, k.soyadi as ogretmen_soyadi  
     FROM dersler d,katilimci kt,kullanici k 
     WHERE kt.ders_id = d.id AND d.duzenleyen_id=k.id 
     AND kt.tip = '1' AND d.status='1' AND kt.ogrenci_id='".$kullanici_id."'";
    // echo $sql;
    return SQLCalistir($sql);
}

function  DuzenledigiGecmisDersleriGetir($kullanici_id)
{
    $sql = "SELECT dersler.* FROM dersler WHERE duzenleyen_id=" . $kullanici_id . " AND etkinlik.tarih < CURDATE()";

    return SQLCalistir($sql);
}

function DersKatilimcilariniGetir($ders_id)
{
    $sql = "SELECT kul.id, kul.admin, kul.adi, kul.soyadi, kul.kodu, kul.email, k.tip
    FROM kullanici kul INNER JOIN katilimci k
    ON k.ogrenci_id=kul.id 
    where k.ders_id=" . $ders_id . "";

    return SQLCalistir($sql, FALSE);
}

function KayitliOgrenciSayisiGetir($ders_id)
{
    $sql = "SELECT count(*) FROM katilimci where katilimci.ders_id=" . $ders_id . "";

    return SQLTekliKayitGetir($sql);
}

function DersDuyurulariGetir($ders_id)
{
    $sql = "SELECT d.* ,k.adi as isim , k.soyadi as soyisim 
        FROM duyuru d 
        inner join kullanici k on k.id = d.kullanici_id 
        where d.ders_id = $ders_id
        order by tarih desc ";

    return SQLCalistir($sql);
}

function DersAktifMi($ders_id)
{
    $sql = "SELECT status FROM dersler where id=".$ders_id."";

    return SQLTekliKayitGetir($sql);
}


