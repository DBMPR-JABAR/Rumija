<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Jobs\SendEmail;
use App\User;
use App\Model\Transactional\Role;
use App\Model\Transactional\DisposisiPenanggungJawab;

use App\Model\Transactional\DisposisiApproved;
use Illuminate\Support\Str;
class DisposisiController extends Controller
{

// Lokasi: Landing Page

    // GET
    public function index()
    {
        $profil = DB::table('landing_profil')->where('id', 1)->first();
        $fitur = DB::table('landing_fitur')->get();
        $uptd = DB::table('landing_uptd')->get();
        $slideshow = DB::table('landing_slideshow')->get();
        $lokasi = DB::table('utils_lokasi')->get();
        $jenis_laporan = DB::table('utils_jenis_laporan')->get();

        // Compact mengubah variabel profil untuk dijadikan variabel yang dikirim
        return view('landing.index', compact('profil', 'fitur', 'uptd', 'slideshow', 'lokasi', 'jenis_laporan'));
    }

    public function getInboxDisposisi(){
        $disposisi = DB::table('disposisi as a')
        ->distinct()
        ->select('a.id','a.disposisi_code','a.dari','b.level','b.status as status_pj','a.perihal','c.name as pengirim','a.tgl_surat','a.no_surat','a.tanggal_penyelesaian','a.status','a.file','a.created_date','a.created_by')
                      ->join('disposisi_penanggung_jawab as b','b.disposisi_code','=','a.disposisi_code')
                      ->join('users as c','b.pemberi_disposisi','=','c.id')
                      ->where('b.user_role_id','=',Auth::user()->internal_role_id  )
                     // ->where('b.level','=','1')
                      ->orderBy('a.id','DESC')
                      ->get();

        $disposisi_kepada = "";
        $jenis_instruksi_select = "";
        $parent = DB::table('user_role')->select('parent_id')->where('id','=', Auth::user()->internal_role_id)->first();
        $user_role = DB::table('user_role')->select('id', 'keterangan')
         ->where('parent_id', Auth::user()->internal_role_id) ;
      //   ->orWhere('parent_id',$parent->parent_id);

        $listUserRole = $user_role->get();
        foreach ($listUserRole as $role) {
            $disposisi_kepada .= '<option value="' . $role->id . '">' . $role->keterangan . '</option>';
        }
        $jenisInstruksi = DB::table('master_disposisi_instruksi')->select('id', 'jenis_instruksi');
        $listJenisInstruksi = $jenisInstruksi->get();
        foreach ($listJenisInstruksi as $instruksi) {
            $jenis_instruksi_select .= '<option value="' . $instruksi->id . '">' . $instruksi->jenis_instruksi . '</option>';
        }

        return view('admin.disposisi.inbox',
            [
                'disposisi' => $disposisi,
                'disposisi_kepada' => $disposisi_kepada,
                'jenis_instruksi_select' => $jenis_instruksi_select,

            ]);
    }
    public function getDisposisiTindakLanjut(){
        $tindaklanjut = DB::table('disposisi_tindak_lanjut as b')
        ->distinct()
        ->select('b.id','a.id as disposisi_id','b.tindak_lanjut','b.status as status_tindak_lanjut','b.persentase','b.keterangan as keterangan_tl', 'a.disposisi_code','a.dari','a.perihal','c.name as pengirim','a.tgl_surat','a.no_surat','a.tanggal_penyelesaian','a.status','a.file','a.created_date','a.created_by')
                      ->join('disposisi as a','a.id','=','b.disposisi_id')
                      ->join('users as c','a.created_by','=','c.id')
                      ->where('b.created_by','=',Auth::user()->id  )
                      ->orderBy('b.id','DESC')
                      ->get();

        return view('admin.disposisi.tindaklanjut',
            [
                'tindaklanjut' => $tindaklanjut
            ]);
    }




    public function getDaftarDisposisi()
    {
        $disposisi = DB::table('disposisi as a')
        ->distinct()
        ->select('a.id','a.disposisi_code','a.dari','a.perihal','a.tgl_surat','a.no_surat','a.tanggal_penyelesaian','a.status','a.file','a.created_date','a.created_by')
                      ->join('disposisi_penanggung_jawab as b','b.disposisi_code','=','a.disposisi_code')
                      ->where('a.created_by','=', Auth::user()->id)
                      //->orWhere('b.user_role_id','=',Auth::user()->role_id )
                      ->orderBy('a.id','DESC')
                      ->get();
        $disposisi_kepada = "";
        $jenis_instruksi_select = "";
        $user_role = DB::table('user_role')->select('id', 'keterangan') ->where('parent_id', Auth::user()->internal_role_id);
        $listUserRole = $user_role->get();
        foreach ($listUserRole as $role) {
            $disposisi_kepada .= '<option value="' . $role->id . '">' . $role->keterangan . '</option>';
        }
        $jenisInstruksi = DB::table('master_disposisi_instruksi')->select('id', 'jenis_instruksi');
        $listJenisInstruksi = $jenisInstruksi->get();
        foreach ($listJenisInstruksi as $instruksi) {
            $jenis_instruksi_select .= '<option value="' . $instruksi->id . '">' . $instruksi->jenis_instruksi . '</option>';
        }

        return view('admin.disposisi.index',
            [
                'disposisi' => $disposisi,
                'disposisi_kepada' => $disposisi_kepada,
                'jenis_instruksi_select' => $jenis_instruksi_select
            ]);
    }

    public function getDetailDisposisi($id){

        $detail_disposisi = DB::table('disposisi as a')
        ->distinct()
        ->select('a.id','a.disposisi_code','c.name as pengirim','a.dari','a.perihal','a.tgl_surat','a.no_surat','a.tanggal_penyelesaian','a.status','a.file','a.created_date','a.created_by')
                      ->join('disposisi_penanggung_jawab as b','b.disposisi_code','=','a.disposisi_code')
                      ->join('users as c','a.created_by','=','c.id')
                      ->where('a.id','=', $id)
                      ->first();

        $tindaklanjut = DB::table('disposisi_tindak_lanjut as b')
        ->distinct()
        ->select('b.id','a.id as disposisi_id','c.internal_role_id','c.name as penanggung_jawab','b.tindak_lanjut','b.status as status_tindak_lanjut','b.persentase','b.keterangan as keterangan_tl', 'a.disposisi_code','a.dari','a.perihal','c.name as pengirim','a.file','b.created_date','b.created_by')
                    ->join('disposisi as a','a.id','=','b.disposisi_id')
                    ->join('users as c','b.created_by','=','c.id')
                //      ->rightJoin('user_role as f','c.id','=','c.internal_role_id')
                    ->where('a.id','=',$id  )
                    ->where('b.status','<>','2'  )
                    ->orderBy('b.id','DESC')
                    ->get();

        $penanggung_jawab = DB::table('disposisi_approved as a')
        ->select( 'c.keterangan','b.name','a.created_date'  )
                        ->join('user_role as c','c.id','=','a.user_role_id')
                        ->join('users as b','b.id','=','a.user_id')
                        ->where('a.disposisi_id','=', $id)
                        ->get();
        $history =  DB::table('disposisi_history as a')
                    ->select( 'c.keterangan as role_name','a.keterangan','a.status','a.created_date','b.name','a.created_date'  )
                    ->join('users as b','b.id','=','a.created_by')
                    ->join('user_role as c','c.id','=','b.internal_role_id')
                    ->where('a.disposisi_id','=', $id)
                    ->get();

        $unit_responsible = DB::table('disposisi_penanggung_jawab  as a')
            ->select( 'a.parent','c.keterangan', 'd.id as disposisi_id','a.user_role_id','a.status'   )
                            ->join('user_role as c','c.id','=','a.user_role_id')

                            ->join('disposisi as d','d.disposisi_code','=','a.disposisi_code')
                            ->where('d.id','=', $id)
                            ->where('a.level','1')
                            ->get();

        $unit = "";

        foreach($unit_responsible as $pj){

             $unit.="<tr>". stateHelper($pj->status,$pj->keterangan) ."</tr>" ;
             $unit.="  ";
             $parents  = DB::table('disposisi_penanggung_jawab  as a')
            ->select( 'a.parent','c.keterangan', 'd.id as disposisi_id','a.user_role_id','a.status'   )
                            ->join('user_role as c','c.id','=','a.user_role_id')
                            ->join('disposisi as d','d.disposisi_code','=','a.disposisi_code')
                            ->where('d.id','=', $id)
                            ->where('a.level','2')
                            ->where('a.parent',$pj->user_role_id)
                            ->get();
            foreach($parents as $parent) {
                $unit.="   <tr>  ".   stateHelper($parent->status,$parent->keterangan,"1") ."</tr>";
            }
            }


        return view('admin.disposisi.detail',
                [
                    'tindaklanjut' => $tindaklanjut,
                    'detail_disposisi' => $detail_disposisi,
                    'penanggung_jawab' =>  $penanggung_jawab,
                    'unitData' => $unit,

                    'history' => $history
                ]);
    }

     public function getAcceptedRequest($id){

        $data['status'] = '3';
        DB::table('disposisi')->where('id',$id)->update($data);

        $data2['disposisi_id'] = $id;
        $data2['user_id'] = Auth::user()->id;
        $data2['user_role_id'] = Auth::user()->internal_role_id;
        $data2['created_date'] = date('Y-m-d H:i:s');
        DB::table('disposisi_approved')->insert($data2);
        $this->saveHistory($id,"2","Menerima Disposisi");

        $disposisi = DB::table('disposisi')->where('id',$id)->first();

        $this->updateDisposisiPenanggungJawabStatus($id,"2");

        $sumberDisposisi = User::where('id',$disposisi->created_by)->first();

        $mail['disposisi_code'] = $disposisi->disposisi_code;
        $mail['nama'] = Auth::user()->name;
        $mail['role'] = Auth::user()->internalRole->keterangan;
        $mail['type_mail'] ="Accepted";
        $mail['mail_to'] = [$sumberDisposisi->email];
        $mail['date_now'] = date('d-m-Y H:i:s');

        SendEmail::dispatch($mail);
        pushNotification([$sumberDisposisi->id], "Disposisi", "Disposisi Telah Diterima Oleh ".$mail['role']);

        $color = "success";
        $msg = "Disposisi telah anda terima";
        return redirect(route('disposisi-masuk'))->with(compact('color', 'msg'));
    }

    public function saveTargetDisposisiLevel($target,$code,$status,$level){

        for($i = 0; $i< count($target); $i++) {
            $data['disposisi_code'] = $code;
            $data['user_role_id'] = $target[$i];
            $data['status'] =  $status;
            $data['level'] =  $level;
            $data['pemberi_disposisi'] =  Auth::user()->id;
            $data['parent'] = $this->getParentByRoleId($target[$i]);
            DB::table('disposisi_penanggung_jawab')->insert($data);
         }

    }

    public function saveTargetDisposisi($target,$code){
        $users = [];
        for($i = 0; $i< count($target); $i++) {
            $data['disposisi_code'] = $code;
            $data['user_role_id'] = $target[$i];
            $data['pemberi_disposisi'] =  Auth::user()->id;
            $data['level'] = "1";
            $data['status'] = "1";
            $data['parent'] = $this->getParentByRoleId($target[$i]);
            DB::table('disposisi_penanggung_jawab')->insert($data);

            $users = array_merge($users, User::where('internal_role_id',$target[$i])->pluck('email')->toArray());
        }
        return $users;
    }
    public function getRecipientId($target){
        $users = [];
        for($i = 0; $i< count($target); $i++) {
            $users = array_merge($users, User::where('internal_role_id',$target[$i])->pluck('id')->toArray());
        }
        return $users;
    }

    public function saveJenisInstruksi($jenis,$code){
        for($i = 0; $i< count($jenis); $i++) {
            $data['disposisi_code'] = $code;
            $data['disposisi_instruksi_id'] = $jenis[$i];
            DB::table('disposisi_jenis_instruksi')->insert($data);
        }
    }

    public function generateCode(){
        //$count = DB::table('visit_reservation')->count();
        $max = DB::table('disposisi')->max('id');
        $code = date('ymd').''.($max+1);
        return $code;
    }

    public function saveHistory($disposisi_id,$state,$desc){

        $data['disposisi_id'] =$disposisi_id;
        $data['status'] =$state;
        $data['keterangan'] =$desc;
        $data['created_by'] = Auth::user()->id;
        $data['created_date'] = date("YmdHis");
        DB::table('disposisi_history')->insert($data);

    }
    public function getDisposisiId($code){
        $data = DB::table('disposisi')->select('id')->where('disposisi_code','=', $code)->first();
      return $data->id;

    }
    public function getDisposisiCodeById($id){
        $data = DB::table('disposisi')->select('disposisi_code')->where('id','=', $id)->first();
      return $data->disposisi_code;

    }
    public function getTargetDisposisi($target){
        $msg="";
        for($i = 0; $i< count($target); $i++) {
        $role = Role::where('id',$target[$i])->first();
        $msg.=$role->keterangan.($i != count($target) ? "," :".");
        }
        return $msg;
    }

    public function updateDisposisi(Request $request){
         
        $data['dari'] = $request->dari;
        $data['perihal'] = $request->perihal;
        $data['tgl_surat'] = $request->tgl_surat;
        $data['no_surat'] = $request->no_surat;
        $data['tanggal_penyelesaian'] = $request->tanggal_penyelesaian;
        $old = DB::table('disposisi')->where('id', $request->id)->first();


        if ($request->file != null) {
            $old->file ?? Storage::delete('public/' . $old->file);

            $path = 'disposisi/'.Str::snake(date("YmdHis").'_'.$request->file->getClientOriginalName());
            
            $request->file->storeAs('public/', $path);
            $data['file'] = $path;
        }

        $data['updated_by'] = Auth::user()->id;
        $data['updated_date'] = date("Y-m-d H:i:s");
        
        DB::table('disposisi')->where('id',$request->id) ->update($data);
        
        $color = "success";
        $msg = "Data Berhasil Diperbaharui";
        return back()->with(compact('color', 'msg')); 
    } 

    public function create(Request $request)
    {
        //
        if($request->file != null){
            $path = 'disposisi/'.Str::snake(date("YmdHis").'_'.$request->file->getClientOriginalName());
            $request->file->storeAs('public/',$path);
            $disposisi ['file'] = $path;
        }
        $code = $this->generateCode();

        $disposisi['disposisi_code'] = $code;
        $disposisi['dari'] = $request->dari;
        $disposisi['perihal'] = $request->perihal;
        $disposisi['tgl_surat'] = $request->tgl_surat;
        $disposisi['no_surat'] = $request->no_surat;
        $disposisi['tanggal_penyelesaian'] = $request->tanggal_penyelesaian;
        $disposisi['status'] ='1';
        $disposisi['created_by'] = Auth::user()->id;
        $disposisi['created_date'] = date("YmdHis");
        DB::table('disposisi')->insert($disposisi);

        $recipient = $this->saveTargetDisposisi($request->target_disposisi,$code);
        $this->saveJenisInstruksi($request->jenis_instruksi,$code);
        $this->saveHistory($this->getDisposisiId($code),"1","Mengirim disposisi kepada ".$this->getTargetDisposisi($request->target_disposisi));


        $disposisi['pengirim'] = $this->getPengirim(Auth::user()->id);
        $disposisi['type_mail'] ="Disposisi";
        $disposisi['mail_to'] = $recipient;
     //    $disposisi['mail_to'] = ["izqfly@gmail.com","zanmit.consultant@gmail.com"];
        $disposisi['date_now'] = date('d-m-Y H:i:s');
        $disposisi['instruksi'] = "ditindaklanjuti";

        SendEmail::dispatch($disposisi);  //send notification
        pushNotification($recipient, "Disposisi", "Anda Mendapatkan Disposisi Baru");

        $color = "success";
        $msg = "Berhasil Menambah Data Disposisi";
        return back()->with(compact('color', 'msg'));
    }

    public function sendEmailNotification($data){
        SendEmail::dispatch($data);  //send notification
    }
    public function getPengirim($id){
        $pengirim = DB::table('users as a')
        ->distinct()
        ->select('a.name','b.keterangan')
                      ->join('user_role as b','b.id','=','a.internal_role_id')
                      ->where('a.id','=', $id)
                      ->first();
      return $pengirim->name.' ('.$pengirim->keterangan.')';
    }

    public function sendMail($data){

    }


    // TODO: Rombak ini sesuai flow yang seharusnya
    public function saveDisposisiLevel2(Request $request)
    {

        $target  = $request->target_disposisi;
        $code = $request->disposisi_code_level2;
        $recipient = [];
        $historyRecipient = "";

        for($i = 0; $i < count($target); $i++) {
        //    $validate = $this->validatePenanggungJawab($code,$target[$i]);
            //if($validate == true) {
                $data['disposisi_code'] = $code;
                $data['user_role_id'] = $target[$i];
                $data['level'] =  "2";
                $data['pemberi_disposisi'] = Auth::user()->id;
                $data['parent'] = $this->getParentByRoleId($target[$i]);
                // DB::table('disposisi_penanggung_jawab')->insert($data);
                $a = User::where('internal_role_id',$target[$i])->pluck('email')->toArray();
                $recipient = array_merge($recipient, $a);

                $historyRecipient.= User::where('internal_role_id',$target[$i])->pluck('name').'('. Role::where('id',$target[$i])->pluck('keterangan') .')';


        }
        $this->saveTargetDisposisiLevel($target,$code,"1","2");

        if($request->file != null){
            $path = 'disposisi/tindak_lanjut/'.Str::snake(date("YmdHis").'_'.$request->file->getClientOriginalName());
            $request->file->storeAs('public/',$path);
            $disposisi ['file'] = $path;
        }
        $disposisi['disposisi_id'] = $request->disposisi_id;
        $disposisi['tindak_lanjut'] = $request->tindak_lanjut;
        $disposisi['status'] = "2"; //disposisi submitted
        //  $disposisi['role_id'] = $target[$i];
        $disposisi['keterangan'] = $request->keterangan;
         $disposisi['persentase'] =  "0";
        $disposisi['level'] = '2';
        $disposisi['created_by'] = Auth::user()->id;
        $disposisi['created_date'] = date("YmdHis");
        // DB::table('disposisi_tindak_lanjut')->insert($disposisi);

        $disposisi['disposisi_code'] = $code;
        $disposisi['pengirim'] = $this->getPengirim(Auth::user()->id);
        $disposisi['type_mail'] ="TindakLanjut";
        $disposisi['mail_to'] = $recipient;
        //    $disposisi['mail_to'] = ["izqfly@gmail.com","zanmit.consultant@gmail.com"];
        $disposisi['date_now'] = date('d-m-Y H:i:s');
        $disposisi['instruksi'] = "ditindaklanjuti";
        $this->saveHistory($request->disposisi_id,"4","Melanjutkan Disposisi Pekerjaan kepada ".$historyRecipient);

        SendEmail::dispatch($disposisi);  //send notification
        pushNotification($this->getRecipientId($target), "Disposisi", "Anda Mendapatkan Disposisi Baru");



    //    }
       $color = "success";
        $msg = "Berhasil Mendisposisikan Tindak Lanjut";
       return back()->with(compact('color', 'msg'));


    }

    public function validatePenanggungJawab($code, $unit){
        $dpj= DB::table('disposisi_penanggung_jawab')
                      ->select('id')
                      ->where('disposisi_code','=', $code)
                      ->where('user_role_id','=', $unit)
                      ->first();
         if(!empty($dpj->id)){
             return true;
         }  else {
             return false;
         }
    }
    public function downloadFile($id){
        $entry = DB::table('disposisi_tindak_lanjut')->where('id',$id)->first();
        $path = storage_path('app/public/' . $entry->file);
        return response()->download($path);
    }

    public function createTindakLanjut(Request $request)
    {
        //
        if($request->file != null){
            $path = 'disposisi/tindak_lanjut/'.Str::snake(date("YmdHis").'/'.$request->file->getClientOriginalName());
            $request->file->storeAs('public/',$path);
            $disposisi ['file'] = $path;
        }
        $disposisi['disposisi_id'] = $request->disposisi_id;
        $disposisi['tindak_lanjut'] = $request->tindak_lanjut;
        $disposisi['status'] = $request->status;
        $disposisi['keterangan'] = $request->keterangan;
        //$disposisi['persentase'] = $request->persentase;
        $disposisi['persentase'] = "0";
        $disposisi['created_by'] = Auth::user()->id;
        $disposisi['created_date'] = date("YmdHis");
        $disposisi['level'] = '1';
        DB::table('disposisi_tindak_lanjut')->insert($disposisi);

        $this->saveHistory($request->disposisi_id,"3","Melaporkan Tindak Lanjut");




        $this->updateDisposisiPenanggungJawabStatus($request->disposisi_id,$request->status );
        if($request->status == "4") {
            $this->automaticparentUpdate($request->disposisi_id,Auth::user()->internal_role_id);
        }
        $validate = $this->validateStateParent($request->disposisi_id);
        if($validate == true){
            $datad['status'] = '4';
        } else {
            $datad['status'] = '3';
        }
        DB::table('disposisi')->where('id',$request->disposisi_id)->update($datad);

        $email['disposisi_code'] = DB::table('disposisi')->where('id',$request->disposisi_id)->first()->disposisi_code;
        $email['pengirim'] = $this->getPengirim(Auth::user()->id);
        $email['type_mail'] = "TindakLanjut";
        $email['mail_to'] =  $this->getParentEmail(Auth::user()->internal_role_id);
      // $email['mail_to'] = ["izqfly@gmail.com","zanmit.consultant@gmail.com"];
        $email['tindak_lanjut'] =  $request->tindak_lanjut;
    //    $email['persentase'] =  $request->persentase;
        $email['keterangan'] =  $request->keterangan;
        $email['date_now'] = date('d-m-Y H:i:s');
        SendEmail::dispatch($email);

        pushNotification($this->getParentId(Auth::user()->internal_role_id), "Disposisi", "Anda Mendapatkan Disposisi Tindak Lanjut");

        $color = "success";
        $msg = "Berhasil Menambah Data Tindak Lanjut";
        return back()->with(compact('color', 'msg'));
    }

    public function updateDisposisiPenanggungJawabStatus($id,$status) {
    $datapj['status'] = $status;
    DB::table('disposisi_penanggung_jawab')->where('disposisi_code',$this->getDisposisiCodeById($id))
                                           ->where('user_role_id',Auth::user()->internal_role_id)
                                           ->update($datapj);
    }

    public function edit($id){
        $disposisi_kepada = "";
        $jenis_instruksi_select = "";
        $disposisi = DB::table('disposisi')->where('id','=',$id)->first(); 
        $parent = DB::table('user_role')->select('parent_id')->where('id','=', Auth::user()->internal_role_id)->first();
        $user_role = DB::table('user_role')->select('id', 'keterangan')
         ->where('parent_id', Auth::user()->internal_role_id) ;
      //   ->orWhere('parent_id',$parent->parent_id);

        $listUserRole = $user_role->get();
        foreach ($listUserRole as $role) {
            $disposisi_kepada .= '<option value="' . $role->id . '">' . $role->keterangan . '</option>';
        }
        $jenisInstruksi = DB::table('master_disposisi_instruksi')->select('id', 'jenis_instruksi');
        $listJenisInstruksi = $jenisInstruksi->get();
        foreach ($listJenisInstruksi as $instruksi) {
            $jenis_instruksi_select .= '<option value="' . $instruksi->id . '">' . $instruksi->jenis_instruksi . '</option>';
        }


        return view('admin.disposisi.edit',
            [
                'disposisi' => $disposisi,
                'disposisi_kepada' => $disposisi_kepada,
                'jenis_instruksi_select' => $jenis_instruksi_select
            ]);

    }
    public function automaticparentUpdate($id, $role_id){

        $parent_id = $this->getParentByRoleId($role_id);
        if(!empty($parent_id)) {
        $data["status"] = "4"; //selesai
        DB::table('disposisi_penanggung_jawab')
        ->where('disposisi_code', $this->getDisposisiCodeById($id) )
        ->where('user_role_id',$parent_id)
        ->update($data);
        }
     }

    public function validateStateParent($id){
        $itemRolePJ = DB::table('disposisi_penanggung_jawab')->where('disposisi_code', $this->getDisposisiCodeById($id) )->get()->count();
        $itemRolePJSelesai = DB::table('disposisi_penanggung_jawab')->where('disposisi_code', $this->getDisposisiCodeById($id) )->where('status', "4")->get()->count();
        if($itemRolePJSelesai == $itemRolePJ) {
            return true;
        }else {
            return false;
        }

    }
    public function getParentEmail($role_id){
        $role = Role::where('id',$role_id)->first();
        $parent_id = $role->parent_id;
        $users = [];
        $users = array_merge($users, User::where('internal_role_id',$parent_id)->pluck('email')->toArray());
        return $users;
    }
    public function getParentId($role_id)
    {
        $role = Role::where('id',$role_id)->first();
        $parent_id = $role->parent_id;
        $users = [];
        $users = array_merge($users, User::where('internal_role_id',$parent_id)->pluck('id')->toArray());
        return $users;
    }

    public function getParentByRoleId($id){
        $role = Role::where('id',$id)->first();
        return $role->parent_id;
    }
    public function getDaftarDisposisiInstruksi(){
        $instruksi = DB::table('master_disposisi_instruksi as a')
        ->distinct()
        ->select('a.id','a.jenis_instruksi','a.keterangan')
        ->get();
        return view('admin.disposisi.instruksi',
                [
                    'instruksi' => $instruksi
                ]);
    }
    public function createInstruksi(Request $request)
    {

        $disposisi['jenis_instruksi'] = $request->jenis_instruksi;
        $disposisi['keterangan'] = $request->ket;
        DB::table('master_disposisi_instruksi')->insert($disposisi);

        $color = "success";
        $msg = "Berhasil Menambah Data Disposisi Instruksi";
        return back()->with(compact('color', 'msg'));
    }
    public function getDetailDisposisiInstruksi($id){

        $detail_disposisi_instruksi = DB::table('master_disposisi_instruksi as a')
        ->distinct()
        ->select('a.id','a.jenis_instruksi','a.keterangan')
                      ->where('a.id','=', $id)
                      ->first();

        return view('admin.disposisi.detail-instruksi',
                [
                    'detail_disposisi_instruksi' => $detail_disposisi_instruksi
                ]);
    }
    public function getDataDisposisiInstruksi(Request $request){

        $detail_disposisi_instruksi = DB::table('master_disposisi_instruksi as a')
        ->distinct()
        ->select('a.id','a.jenis_instruksi','a.keterangan')
                      ->where('a.id','=', $request->id)
                      ->first();

        return response()->json(["data" => $detail_disposisi_instruksi], 200)     ;
    }
    public function updateDisposisiInstruksi(Request $request)
    {
        $disposisi['jenis_instruksi'] = $request->jenis_instruksi;
        $disposisi['keterangan'] = $request->ket;
        DB::table('master_disposisi_instruksi')->where('id',$request->id)->update($disposisi);

        $color = "success";
        $msg = "Berhasil Update Data Disposisi Instruksi";
        return back()->with(compact('color', 'msg'));
    }
    public function deleteDisposisiInstruksi($id){

        $disposisi_instruksi = DB::table('master_disposisi_instruksi');
        $data = $disposisi_instruksi->where('id',$id);
        $data->delete();

        $color = "success";
        $msg = "Berhasil Menghapus Data Disposisi Instruksi";
        return back()->with(compact('color', 'msg'));
    }
}
