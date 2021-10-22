<?php
if (! function_exists('dateID')) {         
    /**
     * dateID
     *
     * @param  mixed $tanggal
     * @return void
     */
    function dateID($tanggal) {
        $value = Carbon\Carbon::parse($tanggal);
        $parse = $value->locale('id');
        return $parse->translatedFormat('l, d F Y');
    }
}
if (! function_exists('declarLog')) {         
    /**
     * declarLog
     *
     * @param  mixed $tanggal
     * @return void
     */
    function declarLog($cek, $target, $unix= null, $status= 0) {
        // storeLogActivity(declarLog(3, 'Manajemen User', $user->first()->email,1 ));
        
        if($cek == 1){
            $activity = 'Add Item';
            $desc2 = ' Menambahkan Data '.$target.' ' ;
        }else if($cek == 2){
            $activity = 'Edit Item';
            $desc2 = ' Merubah Data '.$target.' ' ;
        }else if($cek == 3){
            $activity = 'Remove Item';
            $desc2 = ' Menghapus Data '.$target.' ' ;
        }else if($cek == 4){
            $activity = 'Restore Item';
            $desc2 = ' Mengembalikan Data '.$target.' ' ;
        }else if($cek == 5){
            $activity = 'Response Item';
            $desc2 = ' Response Data '.$target.' ' ;
        }else if($cek == 6){
            $activity = 'Print Data';
            $desc2 = ' Print Data '.$target.' ' ;
        }
        if($status == 0){
            $stat = 'error';
            $desc1 = 'Gagal';
        }else if($status == 1){
            $stat = 'success';
            $desc1 = 'Berhasil';
        }
        $tempAct = [
            'activity' => $activity,
            'target' => $target,
            'description' => $desc1.$desc2.$unix,
            'status' => $stat
        ];
        return $tempAct;
    }
}
if (! function_exists('storeLogActivity')) {         
    /**
     * storeLogActivity
     *
     * @param  mixed $tanggal
     * @return void
     */
    function storeLogActivity($temporari) {
      
        $data = [
            'activity' => $temporari['activity'],
            'target' => $temporari['target'],
            'user_id' => Auth::user()->id,
            'description' => $temporari['description'],
            'status' => $temporari['status'],
            'ip_address' => request()->ip(),
            'sup_id' => Auth::user()->sup_id
        ];
    
        if(Auth::user() && Auth::user()->internalRole->uptd != null){
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $data['uptd_id'] = $uptd_id;
        }
        // dd($data);
        $store = App\Model\Transactional\Log::create($data);
       
        return $store;
    }
}