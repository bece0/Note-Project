<?php

final class FirebaseService
{
    private static $instance;

    private static $FIREBASE_SERVER_TOKEN;

    public static function getInstance(): FirebaseService
    {
        if (null === static::$instance) {
            static::$instance = new static();

            $firebase_token = getenv('FIREBASE_SERVER_TOKEN');

            if ($firebase_token != FALSE) {
                static::$FIREBASE_SERVER_TOKEN = $firebase_token;
            } else {
                static::$FIREBASE_SERVER_TOKEN = "AAAApXRRlS0:APA91bFfUqnCsafPpJ_7HzlIDXND-fi7sFapaZSwuPYcVBch3WJgEtxtTwuU9bhwmPqHBcDcDsCqLKnNzmsKNovnZ-gZnzAwme6bu9dFKazmsKnsVPVpMfMu-Mcvvg-eOZPJKXp4sebY";
            }
        }

        return static::$instance;
    }

    public static function getServerToken()
    {
        return static::$FIREBASE_SERVER_TOKEN;
    }

    private function __construct()
    {
    }
}


function sendNotificationToUser(string $user_token = null, object $data = null)
{
}

class Push
{
    // push message title
    private $title;
    private $message;
    private $image;
    // push message payload
    private $data;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;

    function __construct()
    {
    }

    public function setTip($tip)
    {
        $this->tip = $tip;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setImage($imageUrl)
    {
        $this->image = $imageUrl;
    }

    public function setPayload($data)
    {
        $this->data = $data;
    }

    public function setIsBackground($is_background)
    {
        $this->is_background = $is_background;
    }

    public function getPush()
    {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['message'] = $this->message;
        $res['data']['image'] = $this->image;
        $res['data']['payload'] = $this->data;
        $res['data']['timestamp'] = date('Y-m-d G:i:s');
        return $res;
    }

    public function getNotification($tip)
    {
        $title = "Note";
        // echo "tip : ".$tip."<br>";
        if ($tip == "NORMAL")
            $title = "Note Bildirim";
        else if ($tip == "DUYURU")
            $title = "Duyuru";
        else if ($tip ==  "SINAV")
            $title = "Yeni Sınav Duyurusu";
        else if ($tip ==  "YENI_ODEV")
            $title = "Yeni Ödev Eklendi";
        else if ($tip ==  "YENI_DOKUMAN")
            $title = "Yeni Döküman Eklendi";
        else if ($tip ==  "YENI_YORUM")
            $title = "Yeni Yorum Yapıldı";
        else if ($tip ==  "YENI_YORUM")
            $title = "Yeni Yorum Yapıldı";
        else
            $title = "Note Bildirim";

        $res = array();
        $res['title'] = $title ;
        $res['body'] = $this->message;
        return $res;
    }
}

class FirebaseSender
{
    public function send($to, $data, $notification)
    {
        $fields = array(
            'to' => $to,
            'data' => $data,
            'notification' => $notification,
        );
        return $this->sendPushNotification($fields);
    }

    // Sending message to a topic by topic name
    public function sendToTopic($to, $message)
    {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }

    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message)
    {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );

        return $this->sendPushNotification($fields);
    }

    function sendPushNotification(array $fields = null)
    {
        $firebase_service = FirebaseService::getInstance();
        $firebase_token = $firebase_service->getServerToken();

        if (!isset($firebase_token))
            return;

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' . $firebase_service->getServerToken(),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return $result;
    }
}
