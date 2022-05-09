<?php

namespace App\Http\Controllers\InputData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transactional\RumijaReport;
use App\Model\Transactional\RuasJalan;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RumijaReportController extends Controller
{
    //
    public function index(Request $request)
    {
    	$pelaporan = RumijaReport::all();
       	return view('admin.input_data.pelaporan.index',['pelaporan' => $pelaporan]);
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

            $nomor = intval(substr($row->id_laporan, strlen('LR-'))) + 1;
        }else{
            $nomor = 000001;
        }
        $temporari['id_laporan'] = 'LR-' . str_pad($nomor, 6, "0", STR_PAD_LEFT);

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

    	return view('admin.input_data.pelaporan.edit',compact('ruas','pelaporan'));

    }
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'image' => '',
            'video' => 'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi|max:102400',
            'ruas_jalan_id' => 'required',
            'lat' => 'required',
            'long' => 'required',
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
