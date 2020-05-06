<?php 

$sonucObjesi = new stdClass();
$sonucObjesi->sonuc = false;
$sonucObjesi->mesaj = "";
$sonucObjesi->data = new stdClass();

try{
    
    include '_api_key_kontrol.php';

    if(!isset($_GET["method"]) || $_GET["method"] == ""){
        $statusCode = 400;
        throw new Exception("method parametresi eksik!");
    }
    $METHOD = $_GET["method"];

    $comment_id = NULL;

    if(isset($_GET["comment_id"]) && $_GET["comment_id"] != ""){
        $comment_id = $_GET["comment_id"];
    }

    $COURSE = NULL;
    $COURSE_ID = NULL;

    $GIRIS_YAPAN_DERSIN_HOCASI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_ASISTANI_MI = FALSE;
    $GIRIS_YAPAN_DERSIN_OGRENCISI_MI = FALSE;
   
    if(isset($_POST["ders_id"]) && $_POST["ders_id"] != ""){
        $COURSE_ID = $_POST["ders_id"];
    }
    if(isset($_GET["courseId"]) && $_GET["courseId"] != ""){
        $COURSE_ID = $_GET["courseId"];
    }
    
    if($comment_id != NULL && $COURSE_ID == NULL){
        //Comment id üzerinde course_id bulmaca
        $COMMENT = GetSingleCommentById($comment_id);
        if($COMMENT == NULL){
            $statusCode = 404;
            throw new Exception("Yorumu bulunamadi!");
        }

        $COURSE_ID = $COMMENT["ders_id"];
        $COURSE = DersBilgileriniGetir($COURSE_ID);
        
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }
    }
    
    if(isset($COURSE_ID) && $COURSE_ID != NULL){
        if($COURSE == NULL)
            $COURSE = DersBilgileriniGetir($COURSE_ID);
        
        if($COURSE == NULL){
            $statusCode = 404;
            throw new Exception("Ders bulunamadi!");
        }

        $GIRIS_YAPAN_DERSIN_HOCASI_MI = ($COURSE["duzenleyen_id"] == $KULLANICI_ID);
        $GIRIS_YAPAN_DERSIN_ASISTANI_MI = DersinAsistanıMı($COURSE_ID, $KULLANICI_ID);
    }

    $json = file_get_contents('php://input');
    if($json == NULL){
        $statusCode = 400;
        throw new Exception("Hatalı istek");
    }

    $data = json_decode($json);
    if($data && isset($data->courseId)){
        $COURSE_ID = mysqli_real_escape_string($baglanti, $data->courseId);
    }
    
    if($METHOD == "list"){
        $comments = [];
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
            $comments = GetCourseAllComments($COURSE_ID);
        }else{
            if(DerseKayitliMi($KULLANICI_ID, $COURSE_ID)){
                //Sadece onaylanmış yorumları getir
                $comments = GetEventApprovedComments($COURSE_ID);
            }else{
                $statusCode = 401;
                throw new Exception("Bu derse ait kayıtları görme yetkiniz bulunmuyor.");
            }
        }
        $sonucObjesi->data = $comments;
        $sonucObjesi->sonuc = true;

    }else if($METHOD == "add"){
        $comment =  mysqli_real_escape_string($baglanti, $data->comment);
        
        if($GIRIS_YAPAN_DERSIN_HOCASI_MI || $GIRIS_YAPAN_DERSIN_ASISTANI_MI){
            $sonuc = AddComment($KULLANICI_ID, $COURSE_ID, $comment, 1);
        }
        else{
            $sonuc = AddComment($KULLANICI_ID, $COURSE_ID, $comment);
            DersHocalarinaYorumBildirimiGonder($COURSE_ID, $KULLANICI["adi"]." ".$KULLANICI["soyadi"], $comment);
        }

        $sonucObjesi->sonuc = true;
    }else if($METHOD == "delete"){
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
    }else if($METHOD == "approve"){
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
    if($statusCode == 0){
        $statusCode = 500;
    }

    http_response_code($statusCode);

    $sonucObjesi->code = $statusCode;
    $sonucObjesi->hata = $exp->getMessage();
    $sonucObjesi->mesaj = $exp->getMessage();

    if($statusCode == 401 || $statusCode >= 500){
        $sonucObjesi->headers = getallheaders();
        $sonucObjesi->detay = $exp->getTraceAsString();
    }
}
    
echo json_encode($sonucObjesi);