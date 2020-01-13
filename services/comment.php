<?php 
session_start();
header('Content-type: application/json');

//kullanici oturumu açık değil ise bu servise gelen istekeler işlenmez.
if(!isset($_SESSION["kullanici_id"])){
    die();
}

if(!isset($_GET["method"]) || $_GET["method"] == ""){
    echo "method parametresi eksik!";
    die();
}

$METHOD = $_GET["method"];
$KULLANICI_ID = $_SESSION["kullanici_id"];
$comment_id = NULL;

if(isset($_GET["comment_id"]) && $_GET["comment_id"] != ""){
    $comment_id = $_GET["comment_id"];
}

// if($comment_id == NULL){
//     echo "$comment_id yoq";
//     die();
// }

include '../database/database.php';
$baglanti = BAGLANTI_GETIR();

$sonucObjesi = new stdClass();;
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";

//isteği yapan kullanıcı
$KULLANICI = KullaniciBilgileriniGetirById($KULLANICI_ID); 
$COURSE = null;
$COURSE_ID = null;

$GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
$GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;

$statusCode = 0;

try{

    if(isset($_POST["ders_id"]) && $_POST["ders_id"] != ""){
        $COURSE_ID = $_POST["ders_id"];
        $COURSE = DersBilgileriniGetir($COURSE_ID);
        
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }

        $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
    }
    
    if($comment_id != NULL && $COURSE_ID == NULL){
        //Comment id üzerinde course_id bulmaca
        $COMMENT = GetSingleCommentById($comment_id);
        if($COMMENT == NULL){
            $statusCode = 404;
            throw new Exception("Yorumu bulunamadi!");
        }

        $COURSE_ID  =$COMMENT["ders_id"];
        $COURSE = DersBilgileriniGetir($COURSE_ID);
        
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }
    }
    
    if(isset($COURSE_ID) && $COURSE_ID != NULL){
        $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
    }

    if($METHOD == "add"){
        $comment =  mysqli_real_escape_string($baglanti, $_POST["comment"]);
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI)
            $sonuc = AddComment($KULLANICI_ID, $COURSE_ID, $comment, 1);
        else
            $sonuc = AddComment($KULLANICI_ID, $COURSE_ID, $comment);

        $sonucObjesi->sonuc = true;
    }
    else if($METHOD == "delete"){
        if($comment_id != NULL){

            $COMMENT = GetSingleCommentById($comment_id);
            if($COMMENT == NULL){
                $statusCode = 404;
                throw new Exception("Yorumu bulunamadi!");
            }
            
            $COURSE_ID  =$COMMENT["ders_id"];
            $COURSE = DersBilgileriniGetir($COURSE_ID);
            if($COURSE == NULL){
                $statusCode = 404;
                throw new Exception("Ders bulunamadi!");
            }

            $GIRIS_YAPAN_YORUM_SAHIBI = FALSE;
            if($COMMENT["kullanici_id"] ==  $KULLANICI_ID){
                $GIRIS_YAPAN_YORUM_SAHIBI = TRUE;
            }else{
                $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
                $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
            }

            if($GIRIS_YAPAN_YORUM_SAHIBI || $GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
                $sonuc = DeleteComment($comment_id);
                $sonucObjesi->sonuc = $sonuc;
                $sonucObjesi->mesaj = "Yorum başarıyla silindi";
            }else{
                $statusCode = 401;
                throw new Exception("Bu yorumu silmeye yetkiniz yok!");
            }
        }else{
            $statusCode = 400;
            throw new Exception("comment_id parametresi eksik!");
        }
    }
    else if($METHOD == "approve"){
        if($comment_id != NULL){
            //TODO - check if current user can delete
            
            if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
                $sonucObjesi->sonuc = ApproveComment($comment_id, $KULLANICI_ID);
                $sonucObjesi->mesaj = "Yorum başarıyla onaylandı";
            }else{
                $statusCode = 401;
                throw new Exception("Bu yorumu onaylama yetkiniz yok!");
            }
            
        }else{
            $statusCode = 400;
            throw new Exception("comment_id parametresi eksik!");
        }
    }else{
        $statusCode = 400;
        throw new Exception("Desteklenmeyen metod : $METHOD");
    }

}catch(Throwable $exp){
    if($statusCode == 0)
        $statusCode = 500;

    http_response_code($statusCode);

    $sonucObjesi->code = $statusCode;
    $sonucObjesi->hata = $exp->getMessage();
    $sonucObjesi->mesaj = $exp->getMessage();
    $sonucObjesi->detay = $exp->getTraceAsString();
}

        
echo json_encode($sonucObjesi);