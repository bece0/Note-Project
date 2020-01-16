<?php 

function GUIDOlustur()
{
    if (function_exists('com_create_guid') === true)
    {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}


function DosyaUpload($path = "../files/uploads/", $dosyaIsimOnEk = ""){
    
    $valid_extensions = array('pdf', 'doc', 'docx'); // geçerli uzantılar
    // $path = '../files/uploads/'; // yükleme klasörü
    if(!is_dir($path)){
        mkdir(rtrim($path,"/"), 0777, true);
    }

    // echo rtrim($path,"/")."----";

    $dosya_name = $_FILES['dosya']['name'];
    $tmp = $_FILES['dosya']['tmp_name'];
   
    $ext = strtolower(pathinfo($dosya_name, PATHINFO_EXTENSION));

    $dosya_name_temiz = preg_replace('/\s+/', '', $dosya_name);

    $final_dosya_adi = $dosyaIsimOnEk."_".rand(1000,1000000).$dosya_name_temiz;

    if(!in_array($ext, $valid_extensions)) 
        throw new Exception("Desteklenmeyen dosya formatı : ".$ext);

    $path = $path."".strtolower($final_dosya_adi);
    
    if(move_uploaded_file($tmp, $path)){
        return ["indirme_link" => $path , "isim" => $dosya_name, "dosya_adi" => $final_dosya_adi]; 
    }else{
        throw new Exception("Dosya yüklenemedi!");
    }

    return NULL;
}

?>