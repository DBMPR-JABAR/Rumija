<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtilsController extends Controller
{
    public function uptd_list()
    {
        try {
            $uptd_list = DB::table('landing_uptd')->get();
            $this->response['status'] = 'success';
            $this->response['uptd_list'] = $uptd_list;
            return response()->json($this->response, 200);
        } catch (\Exception $e) {
            $this->response['message'] = 'Internal Error';
            $this->response['error']['message'] = $e->getMessage();
            return response()->json($this->response, 500);
        }
    }

    public function has_access($permission)
    {
        $permission = (object)[
            'create' => hasAccess(auth('api')->user()->internal_role_id, $permission, "Create"),
            'view' => hasAccess(auth('api')->user()->internal_role_id, $permission, "View"),
            'update' => hasAccess(auth('api')->user()->internal_role_id, $permission, "Update"),
            'delete' => hasAccess(auth('api')->user()->internal_role_id, $permission, "Delete"),
        ];
        $this->response['status'] = 'success';
        $this->response['data']['permission'] = $permission;
        return response()->json($this->response, 200);
    }
}
