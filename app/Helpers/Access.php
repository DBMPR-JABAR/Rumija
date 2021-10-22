<?php


use Illuminate\Support\Facades\DB;
use App\Model\Push\UserPushNotification;

/**
 * hasAccess
 *
 * true if exists, false if not exists
 *
 * @param int $role_id --> User->internal_role_id
 * @param string $menu --> arbitrary
 * @param string $access --> [view, create, update, delete]
 * @return bool
 **/
function hasAccess($role_id, $menu, $access)
{
    $grantRole = DB::table('master_grant_role_aplikasi')
        ->where(['internal_role_id' => $role_id, 'menu' => $menu])->first();
    if ($grantRole) {
        return DB::table('utils_role_access')
            ->where(['master_grant_role_aplikasi_id' => $grantRole->id, 'role_access' => $access])
            ->exists();
    }

    return false;
}

function setAccessBuilder($role, $array_create, $array_view, $array_update, $array_delete)
{
    $roles = [
        'role:' . $role . ',Create' => $array_create,
        'role:' . $role . ',Update' => $array_update,
        'role:' . $role . ',View' => $array_view,
        'role:' . $role . ',Delete' => $array_delete,
    ];
    return $roles;
}


function stateHelper2($id)
{
    if ($id == "1") {
        $state  = 'Submitted';
    } else if ($id == "2") {
        $state  = 'Accepted';
    } else if ($id == "3") {
        $state  = 'On Progress';
    } else if ($id == "4") {
        $state  = 'Finish';
    } else {
        $state = "";
    }
    return $state;
}

function stateHelper($id, $unit = "", $is_child = "")
{
    //$state ="<table class='table' style='margin:0px;padding:0px'>";
    $state = "";
    if ($is_child == "1") {
        $child = "<i class='icofont icofont-arrow-right'></i>";
    } else {
        $child = "";
    }
    if ($id == "1") {

        $state .= ' <td>' . $child . "" . $unit . '</td><td> <button class="btn btn-inverse btn-mini btn-round">Submitted</button></td> ';
    } else if ($id == "2") {
        $state .= ' <td>' . $unit . '</td><td> <button class="btn btn-primary btn-mini btn-round"> Accepted</button></td> ';
    } else if ($id == "3") {
        $state .= ' <td>' . $unit . '</td><td> <button class="btn btn-success btn-mini btn-round">On Progress</button></td> ';
    } else if ($id == "4") {
        $state .= ' <td>' . $unit . '</td><td> <button class="btn btn-info btn-mini btn-round">Finish</button></td> ';
    } else {
    }
    //  $state.="</table>";
    return $state;
}

/**
 * uptdAccess
 *
 * return list uptd
 *
 * @param int $role_id --> User->internal_role_id
 * @param string $menu --> arbitrary
 * @return Array
 **/
function uptdAccess($role_id, $menu)
{
    $uptd = [];

    $grantRole = DB::table('master_grant_role_aplikasi')
        ->where(['internal_role_id' => $role_id, 'menu' => $menu])->first();
    if ($grantRole) {
        $uptd = DB::table('utils_role_access_uptd')
            ->where(['master_grant_role_aplikasi_id' => $grantRole->id])
            ->pluck('uptd_name')->toArray();
    }

    return $uptd;
}

function pushNotification($users, $title, $body)
{
    $firebaseToken = UserPushNotification::whereNotNull('device_token')->whereIn('user_id', $users)->pluck('device_token')->all();
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
    $response = curl_exec($ch);
    // if ($response === FALSE) {
    //     die('Curl failed: ' . curl_error($ch));
    // }
    curl_close($ch);
    return $response;
}

function pushNotificationDebug()
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
