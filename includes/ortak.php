<?php 

function GUIDOlustur()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function ToEnglish2($value)
{
    $turkish = array("ı", "ğ", "ü", "ş", "ö", "ç", "İ", "Ğ", "Ü", "Ş", "Ö", "Ç"); //turkish letters
    $english   = array("i", "g", "u", "s", "o", "c", "I", "G", "U", "S", "O", "C"); //english cooridinators letters

    $value = str_replace($turkish, $english, $value); //replace php function
    return $value;
}

function make_links_clickable($text) {
    // The Regular Expression filter
    $pattern = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    $text = preg_replace($pattern, "<a href=\"\\0\" rel=\"nofollow\">\\0</a>", $text);

    return $text;
}

function DosyaUpload($path = "../files/uploads/", $dosyaIsimOnEk = "", $dosya_adi = NULL, $extensions = NULL){
    
    if($_FILES['dosya'] == NULL || $_FILES['dosya']['name'] == NULL)
        return NULL;

    if(!isset($_FILES['dosya']['name']))
        return NULL;
 
    $valid_extensions = array('pdf', 'doc', 'docx', 'zip', 'txt'); // geçerli uzantılar
    if($extensions != NULL){
        $valid_extensions = $extensions;
    }

    // $path = '../files/uploads/'; // yükleme klasörü
    if(!is_dir($path)){
        mkdir(rtrim($path,"/"), 0777, true);
    }

    // echo rtrim($path,"/")."----";

    $dosya_name = $_FILES['dosya']['name'];
    $tmp = $_FILES['dosya']['tmp_name'];
   
    $ext = strtolower(pathinfo($dosya_name, PATHINFO_EXTENSION));

    $dosya_name_temiz = preg_replace('/\s+/', '', $dosya_name);
    $dosya_name_temiz = ToEnglish2($dosya_name_temiz);

    $final_dosya_adi = $dosyaIsimOnEk."_".rand(1000,1000000)."_".$dosya_name_temiz;

    if(!in_array($ext, $valid_extensions)) 
        throw new Exception("Desteklenmeyen dosya formatı : ".$ext);

   

    if($dosya_adi != NULL){
        if($ext == "jpeg" || $ext == "jpg")
            $ext = "png";
             
        $path = $path."".$dosya_adi.".".$ext;
    }
    else {
        $path = $path."".strtolower($final_dosya_adi);
    }

    if(file_exists($path)){
        unlink($path);
    }
    
    if(move_uploaded_file($tmp, $path)){
        return ["indirme_link" => $path , "isim" => $dosya_name, "dosya_adi" => $final_dosya_adi]; 
    }else{
        throw new Exception("Dosya yüklenemedi!");
    }

    return NULL;
}

?>