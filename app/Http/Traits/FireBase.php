<?php


namespace App\Http\Traits;

use App\Models\CustomerToken;
use App\Models\Order;
use App\Models\User;
use App\Models\UserGeneralNotify;

trait FireBase
{
    public function HandelDataAndSendNotify($Tokens, $content)
    {
        $Active_Sound = ['payload' => ['aps' => ['sound' => "default"]]];
        $data['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        ////////////////////////
        $notification = [
            'registration_ids' => $Tokens,
            'notification' => $data,
            'data' => $content,
            'apns' => $Active_Sound
        ];

        /////////////////////////////////////////////////////////
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($notification),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'lang: en',
                'Authorization: key=' . config('app.FireBaseKey') . ''
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);


        return true;
    }
}
