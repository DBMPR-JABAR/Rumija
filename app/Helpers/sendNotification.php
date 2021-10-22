<?php

use App\Model\Push\UserPushNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

function sendNotification($users, $title, $body)
    {
        $firebaseToken = UserPushNotification::whereNotNull('device_token')->whereIn('user_id',$users)->pluck('device_token');
        $SERVER_API_KEY = 'AAAAKK9GKAE:APA91bELNXXSrX8VS-g7stPhlSLM_JP6JtzgFgkL0EyvPtk2qlCGWB0lAOteWN8SelfYoql5JuTI00bcD4ACcW2aHRr1WXudiwR9mtaEMwOehhtyCfMqIABa3PcijBDJbsyn-u9jPE1V';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => [
                "click_action" => "FLUTTER_NOTIFICATION_CLICK"
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
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        //dd($response);
        $publishDate = Carbon\Carbon::now();
        $utilsNotifikasi = DB::table('utils_notifikasi');
        $usersDb = DB::table('users');
        foreach($users as $user) {
            $utilsNotifikasi->insert([
                "title" => $title,
                "subtitle"=> $body,
                "role"=> $usersDb->where('id', $user)->first()->role,
                "user_id"=> $user,
                "created_at" => $publishDate
            ]);
        }
    }

    function sendNotificationPengumuman($users, $title, $body)
    {
        $firebaseToken = UserPushNotification::whereNotNull('device_token')->whereIn('user_id',$users)->pluck('device_token');
        $SERVER_API_KEY = 'AAAAKK9GKAE:APA91bELNXXSrX8VS-g7stPhlSLM_JP6JtzgFgkL0EyvPtk2qlCGWB0lAOteWN8SelfYoql5JuTI00bcD4ACcW2aHRr1WXudiwR9mtaEMwOehhtyCfMqIABa3PcijBDJbsyn-u9jPE1V';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => [
                "click_action" => "FLUTTER_NOTIFICATION_CLICK"
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
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        //dd($response);
      
    }


    function sendEmailHelpers($data, $to_email, $to_name, $subject){
      
        return Mail::send('mail.notificationprim', $data, function ($message) use ($to_name, $to_email,$subject) {
            $message->to($to_email, $to_name)->subject($subject);

            $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
        });
        // dd($mail);
    }
    function setSendEmailHelpers($name, $title, $content, $to_email, $to_name,$subject){
       
        $temporari = [
            'name' =>Str::title($name),
            
            'title' => Str::title($title),
           
            'content' => $content
            ];
        
            // dd($subject);
            // dd($item);
            sendEmailHelpers($temporari, $to_email, $to_name, $subject);
    }