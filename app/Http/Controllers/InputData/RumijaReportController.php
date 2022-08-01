<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transactional\RumijaReport;
use App\Transactional\RumijaTipe;
use App\Model\Transactional\RuasJalan;
use App\Model\Transactional\SUP;
use App\Model\Transactional\UPTD;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RumijaReportController extends Controller
{
    //
    public function index(Request $request)
    {
        $filter['uptd_filter']=null;
        $filter['sup_filter']=null;
        $sup = SUP::orderBy('uptd_id');

        $pelaporan = RumijaReport::latest();
        $rumija_tipe = RumijaTipe::all();
        if($request->uptd_filter || $request->sup_filter ){
            if($request->uptd_filter){
                $filter['uptd_filter'] = $request->uptd_filter;
                $sup = $sup->where('uptd_id',$filter['uptd_filter']);
                $pelaporan = $pelaporan->where('uptd_id',$filter['uptd_filter']);
            }
            if($request->sup_filter && $request->sup_filter != 'Pilih Semua'){
                $filter['sup_filter'] = $request->sup_filter;
                $pelaporan = $pelaporan->where('sup_id',$filter['sup_filter']);

            }
        }
        if (Auth::user() && Auth::user()->internalRole->uptd) {
            $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd);
            $pelaporan = $pelaporan->where('uptd_id',$uptd_id);
            $sup = $sup->where('uptd_id',$uptd_id);

            if (Auth::user()->sup_id) {
                $pelaporan = $pelaporan->where('sup_id',Auth::user()->sup_id);
                $sup = $sup->where('id',Auth::user()->sup_id);

                // if (count(Auth::user()->ruas) > 0) {
                //     $data = $data->whereIn('ruas_jalan_id', Auth::user()->ruas->pluck('id_ruas_jalan')->toArray());
                // }
            }
        }
        
    	$pelaporan = $pelaporan->get();
        $sup = $sup->get();

       	return view('admin.input_data.pelaporan.index',compact('rumija_tipe','pelaporan','sup','filter'));
    }

    public function create(Request $request)
    {
    	RumijaReport::create($request->all());
    	return redirect('/admin/pelaporan')->with('sukses','Data Berhasil Ditambahkan!');
    }

    public function store(Request $request)
    {
        // dd(Auth::user()->id);
        $validator = Validator::make($request->all(), [
            'image' => '',
            'video' => 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:102400',
            'ruas_jalan_id' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'tipe_laporan' => 'required',
            'keterangan' =>''
        ]);
        if ($validator->fails()) {
            storeLogActivity(declarLog(1, 'Laporan Rumija', $validator->errors()));
            $color = "danger";
            $msg = $validator->errors();
            return redirect(route('admin.rumija.report.index'))->with(compact('color', 'msg'));
        }
        $ruas = RuasJalan::where('id_ruas_jalan',$request->ruas_jalan_id)->first();
        $temporari =[
            'lat' => $request->lat,
            'long' => $request->long,
            'created_by' =>Auth::user()->id,
            'ruas_jalan_id'=>$request->ruas_jalan_id,
            'sup_id'=>$ruas->data_sup->id,
            'sup'=>$ruas->data_sup->name,
            'kota_id'=>$ruas->kota_id,
            'uptd_id'=>$ruas->uptd_id,
            'rumija_tipe_id' =>$request->tipe_laporan,
            'keterangan'=>$request->keterangan
        ];
        if($request->file('video')){

            $video = $request->file('video');
            $filename = $video->hashName();
            $video->storeAs('public/laporan_rumija',$filename);
            $temporari['video'] = $video->hashName();

        }

        if($request->file('image')){
            $image = $request->file('image');
            $image->storeAs('public/laporan_rumija',$image->hashName());
            $temporari['image'] = $image->hashName();
        }
        
        $row = RumijaReport::select('id_laporan')->orderByDesc('id_laporan')->limit(1)->first();
        if($row){

            $nomor = intval(substr($row->id_laporan, strlen('LR-')));
            $nomor = substr($nomor, -4)+1;
        }else{
            $nomor = 0001;
        }
        $now = Carbon::now();
        $date = $now->format('y').$now->format('m');
        $temporari['id_laporan'] = 'LR-' .$date. str_pad($nomor, 4, "0", STR_PAD_LEFT);
        // dd($temporari);

        RumijaReport::create($temporari);
        storeLogActivity(declarLog(1, 'Laporan Rumija', $temporari['id_laporan'],1));
    	return redirect(route('admin.rumija.report.index'))->with('sukses','Data Berhasil Ditambahkan!');
        
    }
    public function show($id)
    {
    	$pelaporan = RumijaReport::find($id);

    	return view('admin.input_data.pelaporan.show',compact('pelaporan'));

    }    
    public function edit($id)
    {
    	$pelaporan = RumijaReport::find($id);
        $ruas = RuasJalan::where('uptd_id',$pelaporan->uptd_id)->get();
        $rumija_tipe = RumijaTipe::all();

    	return view('admin.input_data.pelaporan.edit',compact('ruas','pelaporan','rumija_tipe'));

    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'image' => '',
            'video' => 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:102400',
            'ruas_jalan_id' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'tipe_laporan' => 'required',
            'keterangan' =>''
        ]);
        if ($validator->fails()) {
            storeLogActivity(declarLog(2, 'Laporan Rumija', $validator->errors()));
            $color = "danger";
            $msg = $validator->errors();
            return redirect(route('admin.rumija.report.index'))->with(compact('color', 'msg'));
        }
    
        $ruas = RuasJalan::where('id_ruas_jalan',$request->ruas_jalan_id)->first();
        $data = RumijaReport::findOrFail($id);
        $temporari =[
            'lat' => $request->lat,
            'long' => $request->long,
            'created_by' =>Auth::user()->id,
            'ruas_jalan_id'=>$request->ruas_jalan_id,
            'sup_id'=>$ruas->data_sup->id,
            'sup'=>$ruas->data_sup->name,
            'kota_id'=>$ruas->kota_id,
            'uptd_id'=>$ruas->uptd_id,
            'rumija_tipe_id' =>$request->tipe_laporan,
            'keterangan'=>$request->keterangan
        ];
        if($request->file('image')){
            $delete = Storage::delete('public/laporan_rumija/'.$data->image);
            $image = $request->file('image');
            $image->storeAs('public/laporan_rumija',$image->hashName());
            $temporari['image'] = $image->hashName();
        }
        if($request->file('video')){
            $delete = Storage::delete('public/laporan_rumija/'.$data->video);
            $video = $request->file('video');
            $filename = $video->hashName();
            $video->storeAs('public/laporan_rumija',$filename);
            $temporari['video'] = $video->hashName();

        }
        $data->update($temporari);

        storeLogActivity(declarLog(2, 'Laporan Rumija', $data->id_laporan,1));
        $color = "success";
        $msg = "Data Berhasil Dirubah!";
    	return redirect(route('admin.rumija.report.index'))->with(compact('color', 'msg'));

    }
    public function delete($id)
    {
    	$pelaporan = RumijaReport::find($id);
        if($pelaporan->image){
            $delete = Storage::delete('public/laporan_rumija/'.$pelaporan->image);
        }
        if($pelaporan->video){
            $delete = Storage::delete('public/laporan_rumija/'.$pelaporan->video);
        }
        storeLogActivity(declarLog(3, 'Laporan Rumija', $pelaporan->id_laporan,1));
    	$pelaporan->delete($pelaporan);
    	return redirect(route('admin.rumija.report.index'))->with('sukses','Data Berhasil Dihapus!');

    }
}
