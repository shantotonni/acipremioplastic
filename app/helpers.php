<?php
function send_notification_FCM($notification_id, $title, $message, $id,$type) {

    $accesstoken = "AAAARjim7cA:APA91bHyoZHn6HwJbdvhaMDlfE4xzMQX8mwwp53Ab2Hea10yM1TAg7ZyGQN2geNyWUY__GI_fJ_Cy8YBfYyiM89ODpfU9ASJz1Tvd1xxXTyxSFt3po_iYH2v-b7zrNmV-u6dgwfG-vnT";
    $URL = 'https://fcm.googleapis.com/fcm/send';

    $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },
          }';

    $crl = curl_init();

    $headers = [
        'Authorization: key=' . $accesstoken,
        'Content-Type: application/json',
    ];

    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

    $rest = curl_exec($crl);

    if ($rest === false) {
        $result_noti = 0;
    } else {
        $result_noti = 1;
    }
    return $result_noti;
}

function send_notification_FCM_backup($notification_id, $title, $message, $id, $type) {
    $firebaseToken = User::whereNotNull('device_token')->where('device_token',$notification_id)->pluck('device_token')->all();
    $SERVER_API_KEY = 'AAAARjim7cA:APA91bHyoZHn6HwJbdvhaMDlfE4xzMQX8mwwp53Ab2Hea10yM1TAg7ZyGQN2geNyWUY__GI_fJ_Cy8YBfYyiM89ODpfU9ASJz1Tvd1xxXTyxSFt3po_iYH2v-b7zrNmV-u6dgwfG-vnT';

    $data = [
        "registration_ids" => $firebaseToken,
        "notification" => [
            "title" => "Please check your job list",
            "body" => "Please check your job list",
        ]
    ];
    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);

    return $response;
}

function objectToArray($result){
    $arrayData = array_map(function($item) {
        return (array)$item;
    }, $result);
    return $arrayData;
}