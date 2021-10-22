<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\Push\UserPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PushNotifController extends Controller
{

    private $response;
    public function __construct() {
        $this->response = [
            'status' => 'false',
            'data' => []
        ];
    }

    public function index()
    {
        return view('debug.push-notif');
    }

    public function saveToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'token' => 'required',
            ]);
            if($validator->fails()){
                $this->response['data']['error'] = $validator->errors();
                return response()->json($this->response, 200);
            }
            $userPushNotif = UserPushNotification::updateOrCreate(
                ['user_id' => $request->user_id],
                ['device_token' => $request->token]
            );

            $this->response['status'] = 'success';
            $this->response['data']['message'] =  "Token Saved";

            return response()->json($this->response, 200);
        } catch (\Exception $th) {
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }
    }

    // TODO: Let's Keep this in helper
    public static function sendNotification($users, $title, $body)
    {
        $firebaseToken = UserPushNotification::whereNotNull('device_token')->whereIn('user_id',$users)->pluck('device_token')->get();
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
        dd($response);
    }

    public function sendNotificationUser(Request $request)
    {
        try{
            $user = [$request->user_id];

            sendNotification($user, $request->title, $request->body);

            $this->response['status'] = 'success';
            $this->response['data']['message'] =  "Notification Pushed";

            return response()->json($this->response, 200);
        }catch(\Exception $th){
            $this->response['data']['message'] = 'Internal Error';
            return response()->json($this->response, 500);
        }

    }

    public function debugNotification(Request $request)
    {
        $firebaseToken = UserPushNotification::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAAKK9GKAE:APA91bELNXXSrX8VS-g7stPhlSLM_JP6JtzgFgkL0EyvPtk2qlCGWB0lAOteWN8SelfYoql5JuTI00bcD4ACcW2aHRr1WXudiwR9mtaEMwOehhtyCfMqIABa3PcijBDJbsyn-u9jPE1V';
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => "Sample Notification",
                "body" => "Lorem ipsum solor dit amet",
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
        return $response;
    }

    public function debug()
    {
        // $firebaseToken = UserPushNotification::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAAKK9GKAE:APA91bELNXXSrX8VS-g7stPhlSLM_JP6JtzgFgkL0EyvPtk2qlCGWB0lAOteWN8SelfYoql5JuTI00bcD4ACcW2aHRr1WXudiwR9mtaEMwOehhtyCfMqIABa3PcijBDJbsyn-u9jPE1V';
        $data = [
            "registration_ids" => ['dW2na4eBS7-OpFPxwqyE6Z:APA91bHGb8PAmEMePnozk-MAX8SGm3_2mKkdeJFKNPNu7hhy5qvRF4RIDV3QFQwA8upiEbdFc9wePayegKoE2G1BI1pIvfxFguS5h55R4mLuQmzseSF0FsFd_yNvQBY89ASZs52EhhMT'],
            "notification" => [
                "title" => "Sample Notification",
                "body" => "Lorem ipsum solor dit amet",
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
        return $response;
    }

}
