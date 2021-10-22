@extends('admin.layout.index')

@section('title') Detail Laporan @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Detail Laporan</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getDataPekerjaan') }}">Data Pekerjaan</a> </li>
                <li class="breadcrumb-item"><a href="#">Detail</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-md-12">
        @if ($material!='')
            <form action="{{ route('updateDataMaterialPekerjaan',$pekerjaan->id_pek) }}" method="post" enctype="multipart/form-data">
        @else
            <form action="{{ route('createDataMaterialPekerjaan',$pekerjaan->id_pek) }}" method="post" enctype="multipart/form-data">
        @endif
        @csrf
        <div class="card">
            <div class="card-header">
                <h5>Detail</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                
                    <input type="hidden" name="id_pek" value="{{$pekerjaan->id_pek}}">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">No Pekerjaan</label>
                        <div class="col-md-10">
                            <input name="id_pek" type="text" class="form-control" value="{{$pekerjaan->id_pek}}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Tanggal</label>
                        <div class="col-md-10">
                            <input name="tanggal" type="date" class="form-control" required value="{{$pekerjaan->tanggal}}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Mandor</label>
                        @if(Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role,'Mandor'))
                        <div class="col-md-10">
                            <input  type="text" name="nama_mandor" class="form-control" value="{{ Auth::user()->name}},{{ Auth::user()->id}}" readonly>
                        </div>
                        @else
                        <div class="col-md-10">
                            <select class="form-control searchableModalField" name="nama_mandor" required readonly>
                                @foreach ($mandor as $data)
                                <option value="{{$data->name}},{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jenis Pekerjaan</label>
                        <div class="col-md-10">

                            <input name="jenis_pekerjaan" type="text" class="form-control" required value="{{$pekerjaan->jenis_pekerjaan}}" readonly>
                        </div>
                    </div>
                    @if (Auth::user()->internalRole->uptd)
                        <input type="hidden" name="uptd_id" value="{{Auth::user()->internalRole->uptd}}">
                    @else
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Uptd</label>
                       <div class="col-md-10">
                            <select class="form-control" name="uptd_id">
                                @foreach ($uptd as $data)
                                <option value="{{$data->id}}">{{$data->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">Alat yang digunakan</label>
                        <label class="col-md-4 col-form-label"> Jenis Peralatan</label>
                        <label class="col-md-3 col-form-label">Jumlah</label>                    
                        <label class="col-md-4 col-form-label">Satuan</label>
                        {{-- <div class="col-md-10">
                            <input name="peralatan" type="text" class="form-control" required value="{{@$pekerjaan->peralatan}}">
                        </div> --}}
                    </div>
                    <div class="form-group row fieldGroupPeralatan  ">
                       
                        <div class="col-md-4">
                            <select class="form-control" name="nama_peralatan[]" required>
                                @foreach ($item_peralatan as $no =>$data)
                                <option value="{{ $data->id }},{{ $data->nama_peralatan }}" @if(@$detail_peralatan[0]->nama_peralatan == $data->nama_peralatan) selected @endif>{{ $data->nama_peralatan }}</option>
                                    
                                @endforeach  
                            </select>
                            <i style="color :red; font-size: 10px;">Segera hubungi admin jika pilihan tidak</i>

                        </div>
                        <div class="col-md-3">
                            <input name="jum_peralatan[]" type="number" class="form-control" value="{{@$detail_peralatan[0]->kuantitas}}" required>
                            {{-- {{ ( $detail_peralatan!=null) ? $detail_peralatan[0]->kuantitas : ''}} --}}
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="satuan_peralatan[]" required >
                                <option value="Unit" @if(@$detail_peralatan[0]->satuan == "Unit") selected @endif>Unit</option>
                                <option value="Set" @if(@$detail_peralatan[0]->satuan == "Set") selected @endif>Set</option>
                            </select>
                        </div>
                        <div class="col-md-1"> 
                            {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                            <a href="javascript:void(0)" data-toggle="modal"><button id="addMorePeralatan" class="btn btn-primary addMorePeralatan btn-mini waves-effect waves-light" data-toggle="tooltip" title="Tambah Bahan Material"><i class="icofont icofont-plus"></i></button></a>
                        </div>
                        
                    </div>
                    <div id="sisaPeralatan"></div>
                    <!-- copy of input fields group -->
                    <div  class="form-group row fieldGroupCopyPeralatan" style="display: none;">
                        <div class="col-md-4">
                            <select class="form-control" name="nama_peralatan[]" required>
                               
                                @foreach ($item_peralatan as $no =>$data)
                                <option value="{{ $data->id }},{{ $data->nama_peralatan }}">{{ $data->nama_peralatan }}</option>
                                    
                                @endforeach  
                                  
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input name="jum_peralatan[]" type="number" class="form-control" >
                            {{-- {{ ( $detail_peralatan!=null) ? $detail_peralatan[0]->kuantitas : ''}} --}}
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="satuan_peralatan[]" required >
                                <option value="Unit">Unit</option>
                                <option value="Set">Set</option>
                            </select>
                        </div>
                        <div class="col-md-1"> 
                            {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                            <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removePeralatan btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>

                        </div>
                    </div>
                    
                    
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h6>Bahan Operasional Peralatan</h6>
                <i style="color :red; font-size: 10px;">Biarkan jika tidak ada</i>

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">   
                 

                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Material</label>
                    
                    <label class="col-md-3 col-form-label">Jumlah</label>
                    
                    <label class="col-md-4 col-form-label">Satuan</label>
                </div>
                <div class="form-group row fieldGroupOperasional">
                 
                    <div class="col-md-4">
                        <select class="form-control" name="nama_bahan_operasional[]" required>
                            @foreach ($bahan as $data)
                                @if ($data->keterangan)
                                    <option value="{{$data->no}}" @if(@$detail_bahan_operasional[0]->id_material == $data->no) selected @endif>{{$data->nama_item}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input name="jum_bahan_operasional[]" type="number" class="form-control" value="{{ @$detail_bahan_operasional[0]->kuantitas}}">
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" name="satuan_operasional[]" required >
                            <option value="Ltr" @if (@$detail_bahan_operasional[0]->satuan == "Ltr") selected @endif>Ltr</option>

                        </select>
                    </div>
                    <div class="col-md-1"> 
                        {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                        <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-primary addMoreOperasional btn-mini waves-effect waves-light" data-toggle="tooltip" title="Tambah Bahan Material"><i class="icofont icofont-plus"></i></button></a>
                    </div>
                </div>

                @if(@$detail_bahan_operasional && count($detail_bahan_operasional)>1)
                    @for ($i = 1; $i< count($detail_bahan_operasional);$i++ )
                    
                        <div class="form-group row fieldGroupOperasional">
                            <div class="col-md-4">
                                <select class="form-control" name="nama_bahan_operasional[]" required>
                                    @foreach ($bahan as $data)
                                        @if ($data->keterangan)
                                            <option value="{{$data->no}}" @if(@$detail_bahan_operasional[$i]->id_material == $data->no) selected @endif>{{$data->nama_item}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input name="jum_bahan_operasional[]" type="number" class="form-control" value="{{ @$detail_bahan_operasional[$i]->kuantitas}}">
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="satuan_operasional[]" required >
                                    <option value="Ltr" @if (@$detail_bahan_operasional[$i]->satuan == "Ltr") selected @endif>Ltr</option>

                                </select>
                            </div>
                            <div class="col-md-1"> 
                                {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                                <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removeOperasional btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                                
                            </div>
                        </div>
                    @endfor
                @endif
                <!-- copy of input fields group -->
                <div class="form-group row fieldGroupCopyOperasional" style="display: none;">
                    
                        <div class="col-md-4">
                            <select class="form-control" name="nama_bahan_operasional[]" required>
                                
                                @foreach ($bahan as $data)
                                    @if ($data->keterangan)
                                        <option value="{{$data->no}}">{{$data->nama_item}}</option>
                                    @endif
                                @endforeach
                              
                            </select>
                        </div>
                        <div class="col-md-3">

                            <input name="jum_bahan_operasional[]" type="number" class="form-control" >
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="satuan_operasional[]" required>
                                   
                                    <option value="Ltr" >Ltr</option>

                            </select>
                        </div>
                        <div class="col-md-1"> 
                            {{-- <a href="javascript:void(0)" class="btn btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Remove</a> --}}
                            <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removeOperasional btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                        </div>
                </div>


            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h6>Material Tiba Di Lokasi</h6>
                <i style="color :red; font-size: 10px;">Biarkan jika tidak ada</i>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">    
                @if ($material!='')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 1</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan1" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan1) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan1" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan1 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan1" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan1) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
    
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 2</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan2" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan2) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan2" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan2 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan2" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan2) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 3</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan3" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan3) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan3" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan3 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan3" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan3) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 4</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan4" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan4) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan4" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan4 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan4" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan4) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 5</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan5" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan5) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan5" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan5 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan5" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan5) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 6</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan6" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan6) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan6" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan6 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan6" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan6) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 7</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan7" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan7) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan7" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan7 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan7" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan7) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 8</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan8" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan8) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan8" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan8 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan8" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan8) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 9</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan9" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan9) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan9" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan9 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan9" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan9) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 10</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan10" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan10) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan10" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan10 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan10" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan10) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 11</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan11" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan11) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan11" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan11 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan11" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan11) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 12</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan12" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan12) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan12" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan12 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan12" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan12) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 13</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan13" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan13) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan13" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan13 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan13" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan13) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 14</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan14" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan14) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan14" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan14 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan14" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan14) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Bahan Material 15</label>
                        <div class="col-md-3">
                            <select class="form-control" name="nama_bahan15" required>
                                @if ($material!='')
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}" {{ ( $data->nama_item == $material->nama_bahan15) ? 'selected' : ''}}>{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @else
                                    @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <label class="col-md-1 col-form-label">Jumlah</label>
                        <div class="col-md-2">
    
                            <input name="jum_bahan15" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan15 : ''}}">
                        </div>
                        <label class="col-md-1 col-form-label">Satuan</label>
                        <div class="col-md-3">
                        <select class="form-control" name="satuan15" required>
                            @if ($material!='')
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan15) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                @endforeach
                            @else
                                @foreach ($satuan as $data)
                                <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                @endforeach
                            @endif
                            </select>
                        </div>
                    </div>
                    @else
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Bahan Material</label>
                        
                        <label class="col-md-3 col-form-label">Jumlah</label>
                        
                        <label class="col-md-4 col-form-label">Satuan</label>
                    </div>
                    <div class="form-group row fieldGroup">
                     
                        <div class="col-md-4">
                            <select class="form-control" name="nama_bahan[]" required>
                                @foreach ($bahan as $data)
                                    @if (!$data->keterangan)
                                        <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input name="jum_bahan[]" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan1 : ''}}">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="satuan[]" required>
                                @if ($material!='')
                                    @foreach ($satuan as $data)
                                    <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan1) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                    @endforeach
                                @else
                                    @foreach ($satuan as $data)
                                    <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-1"> 
                            {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                            <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-primary addMore btn-mini waves-effect waves-light" data-toggle="tooltip" title="Tambah Bahan Material"><i class="icofont icofont-plus"></i></button></a>
                            
                        </div>

                    </div>
                    <!-- copy of input fields group -->
                    <div class="form-group row fieldGroupCopy" style="display: none;">
                        
                            <div class="col-md-4">
                                <select class="form-control" name="nama_bahan[]" required>
                                    
                                    @foreach ($bahan as $data)
                                        @if (!$data->keterangan)
                                            <option value="{{$data->nama_item}}">{{$data->nama_item}}</option>
                                        @endif
                                    @endforeach
                                  
                                </select>
                            </div>
                            <div class="col-md-3">

                                <input name="jum_bahan[]" type="number" class="form-control" value="{{ ( $material!='') ? $material->jum_bahan1 : ''}}">
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="satuan[]" required>
                                    @if ($material!='')
                                        @foreach ($satuan as $data)
                                        <option value="{{$data->satuan}}" {{ ( $data->satuan == $material->satuan1) ? 'selected' : ''}}>{{$data->satuan}}</option>
                                        @endforeach
                                    @else
                                        @foreach ($satuan as $data)
                                        <option value="{{$data->satuan}}">{{$data->satuan}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-1"> 
                                {{-- <a href="javascript:void(0)" class="btn btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Remove</a> --}}
                                <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger remove btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                            </div>
                    </div>
                @endif
           </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h6>Tenaga Kerja</h6>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">   
                 
                <div class="form-group row">
                    <label class="col-md-7 col-form-label">Jabatan</label>
                    
                    <label class="col-md-4 col-form-label">Jumlah</label>
                </div>
                <div class="form-group row fieldGroupPekerja">
                 
                    <div class="col-md-7">
                        <select class="form-control" name="jabatan_pekerja[]" required>
                            <option value="Pekerja" @if(@$detail_pekerja[0]->jabatan == "Pekerja") selected @endif>Pekerja</option>
                            <option value="Tukang" @if(@$detail_pekerja[0]->jabatan == "Tukang") selected @endif>Tukang</option>
                            <option value="Operator" @if(@$detail_pekerja[0]->jabatan == "Operator") selected @endif>Operator</option>
                            <option value="Sopir" @if(@$detail_pekerja[0]->jabatan == "Sopir") selected @endif>Sopir</option>    
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input name="jum_pekerja[]" type="number" class="form-control" value="{{ @$detail_pekerja[0]->jumlah }}">
                    </div>
                    <div class="col-md-1"> 
                        {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                        <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-primary addMorePekerja btn-mini waves-effect waves-light" data-toggle="tooltip" title="Tambah Pekerja"><i class="icofont icofont-plus"></i></button></a>
                    </div>
                </div>
                @if(@$detail_pekerja && count($detail_pekerja)>1)
                    @for ($i = 1; $i< count($detail_pekerja);$i++ )
                    <div class="form-group row fieldGroupPekerja">
                        <div class="col-md-7">
                            <select class="form-control" name="jabatan_pekerja[]" required>
                                <option value="Pekerja" @if(@$detail_pekerja[$i]->jabatan == "Pekerja") selected @endif>Pekerja</option>
                                <option value="Tukang" @if(@$detail_pekerja[$i]->jabatan == "Tukang") selected @endif>Tukang</option>
                                <option value="Operator" @if(@$detail_pekerja[$i]->jabatan == "Operator") selected @endif>Operator</option>
                                <option value="Sopir" @if(@$detail_pekerja[$i]->jabatan == "Sopir") selected @endif>Sopir</option>    
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input name="jum_pekerja[]" type="number" class="form-control" value="{{ @$detail_pekerja[$i]->jumlah }}">
                        </div>
                        <div class="col-md-1"> 
                            {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                           
                            <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removePekerja btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                        
                        </div>
                    </div>
                    @endfor
                @endif
                <!-- copy of input fields group -->
                <div class="form-group row fieldGroupCopyPekerja" style="display: none;">
                    
                    <div class="col-md-7">
                        <select class="form-control" name="jabatan_pekerja[]" required>
                            <option value="Pekerja">Pekerja</option>
                            <option value="Tukang">Tukang</option>
                            <option value="Operator">Operator</option>
                            <option value="Sopir">Sopir</option>    
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input name="jum_pekerja[]" type="number" class="form-control" value="">
                    </div>
                        <div class="col-md-1"> 
                            {{-- <a href="javascript:void(0)" class="btn btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Remove</a> --}}
                            <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removePekerja btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                        </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h6>Penghambat Pelaksanaan</h6>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">   
                 
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Jenis</label>
                    <label class="col-md-5 col-form-label">Waktu</label>
                    <label class="col-md-4 col-form-label">Akibat</label>
                </div>
                <div class="form-group row fieldGroupPenghambat">
                    <div class="col-md-3">
                        <select class="form-control" name="jenis_gangguan[]" required>
                            <option value="Cerah" @if(@$detail_penghambat[0]->jenis_gangguan == "Cerah") selected @endif>Cerah</option>
                            <option value="Berawan" @if(@$detail_penghambat[0]->jenis_gangguan == "Berawan") selected @endif>Berawan</option>
                            <option value="Hujan Gerimis" @if(@$detail_penghambat[0]->jenis_gangguan == "Hujan Gerimis") selected @endif>Hujan Gerimis</option>
                            <option value="Hujan Lebat" @if(@$detail_penghambat[0]->jenis_gangguan == "Hujan Lebat") selected @endif>Hujan Lebat</option>    
                        </select>
                    </div>
                    <div class="col-md-5 "id="start_time">
                        <div class="row">
                            <div class="col-md-6">
                                <input name="start_time[]" type="time" class="form-control " value="{{ @$detail_penghambat[0]->start_time}}">
                            </div>
                            s/d
                            <div class="col-md-5">
                                <input name="end_time[]" type="time" id="end_time_input" class="form-control" value="{{ @$detail_penghambat[0]->end_time}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <textarea name="akibat[]" class="form-control">{{ @$detail_penghambat[0]->akibat }}</textarea>
                    </div>
                    <div class="col-md-1"> 
                        {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                        <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-primary addMorePenghambat btn-mini waves-effect waves-light" data-toggle="tooltip" title="Tambah Penghambat"><i class="icofont icofont-plus"></i></button></a>
                    </div>
                </div>
                @if(@$detail_penghambat && count($detail_penghambat)>1)
                    @for ($i = 1; $i< count($detail_penghambat);$i++ )
                        <div class="form-group row fieldGroupPenghambat">
                            <div class="col-md-3">
                                <select class="form-control" name="jenis_gangguan[]" required>
                                    <option value="Cerah" @if(@$detail_penghambat[$i]->jenis_gangguan == "Cerah") selected @endif>Cerah</option>
                                    <option value="Berawan" @if(@$detail_penghambat[$i]->jenis_gangguan == "Berawan") selected @endif>Berawan</option>
                                    <option value="Hujan Gerimis" @if(@$detail_penghambat[$i]->jenis_gangguan == "Hujan Gerimis") selected @endif>Hujan Gerimis</option>
                                    <option value="Hujan Lebat" @if(@$detail_penghambat[$i]->jenis_gangguan == "Hujan Lebat") selected @endif>Hujan Lebat</option>    
                                </select>
                            </div>
                            <div class="col-md-5 "id="start_time">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="start_time[]" type="time" class="form-control " value="{{ @$detail_penghambat[$i]->start_time}}">
                                    </div>
                                    s/d
                                    <div class="col-md-5">
                                        <input name="end_time[]" type="time" id="end_time_input" class="form-control" value="{{ @$detail_penghambat[$i]->end_time}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <textarea name="akibat[]" class="form-control">{{ @$detail_penghambat[$i]->akibat }}</textarea>
                            </div>
                            <div class="col-md-1"> 
                                {{-- <a href="javascript:void(0)" class="btn btn-success addMore"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> Add</a> --}}
                                <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removePenghambat btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>                        
                            </div>
                        </div>
                    @endfor
                @endif
                <!-- copy of input fields group -->
                <div class="form-group row fieldGroupCopyPenghambat" style="display: none;">
                    
                    <div class="col-md-3">
                        <select class="form-control" name="jenis_gangguan[]" required>
                            <option value="Cerah">Cerah</option>
                            <option value="Berawan">Berawan</option>
                            <option value="Hujan Gerimis">Hujan Gerimis</option>
                            <option value="Hujan Lebat">Hujan Lebat</option>    
                        </select>
                    </div>
                    <div class="col-md-5 "id="start_time">
                        <div class="row">
                            <div class="col-md-6">
                                <input name="start_time[]" type="time" class="form-control " value="{{ @$detail_penghambat[0]->waktu}}">
                            </div>
                            s/d
                            <div class="col-md-5">
                                <input name="end_time[]" type="time" id="end_time_input" class="form-control" value="{{ @$detail_penghambat[0]->waktu}}">
                            </div>
                        </div>
                    </div>
                  
                    <div class="col-md-3">
                        <textarea name="akibat[]" class="form-control"></textarea>
                    </div>
                    <div class="col-md-1"> 
                        {{-- <a href="javascript:void(0)" class="btn btn-danger remove"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> Remove</a> --}}
                        <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removePenghambat btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                    </div>
                </div>
            </div>
        </div>
        @if (str_contains(Auth::user()->internalRole->role,'Pengamat'))
            <div class="card">
                <div class="card-header">
                    <h6>Instruksi / Saran / Usul</h6>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">   
                    
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label">Apakah ada Instruksi / Saran / Usul ?</label>
                        <div class="col-md-12">
                            <input name="keterangan_instruksi" type="text" class="form-control" placeholder="Type here" required value="{{ @$detail_instruksi }}">
                        </div>
                    </div>
                    
                </div>
            </div>
            
        @endif
    </div>
   
    <div class="col-md-12">
        <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>
        </form>
    </div>
</div>

@endsection
@section('script')
<script>
    
    $(document).ready(function(){
    //group add limit
    var maxGroup = 15;
    var maxGroupOperasional = 4;
    var maxGroupPeralatan = 9;
    var maxGroupPekerja = 4;
    var maxGroupPenghambat = 4;

    
    //add more fields group
    $(".addMore").click(function(){
        if($('body').find('.fieldGroup').length < maxGroup){
            var fieldHTML = '<div class="form-group row fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
            $('body').find('.fieldGroup:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroup+' groups are allowed.');
        }
    });
    
    //remove fields group
    $("body").on("click",".remove",function(){ 
        $(this).parents(".fieldGroup").remove();
    });

    $(".addMoreOperasional").click(function(){
        if($('body').find('.fieldGroupOperasional').length < maxGroupOperasional){
            var fieldHTML = '<div class="form-group row fieldGroupOperasional">'+$(".fieldGroupCopyOperasional").html()+'</div>';
            $('body').find('.fieldGroupOperasional:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroupOperasional+' groups are allowed.');
        }
    });
    
    //remove fields group
    $("body").on("click",".removeOperasional",function(){ 
        $(this).parents(".fieldGroupOperasional").remove();
    });

    $(".addMorePekerja").click(function(){
        if($('body').find('.fieldGroupPekerja').length < maxGroupPekerja){
            var fieldHTML = '<div class="form-group row fieldGroupPekerja">'+$(".fieldGroupCopyPekerja").html()+'</div>';
            $('body').find('.fieldGroupPekerja:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroupPekerja+' groups are allowed.');
        }
    });
    
    //remove fields group
    $("body").on("click",".removePekerja",function(){ 
        $(this).parents(".fieldGroupPekerja").remove();
    });
    $(".addMorePenghambat").click(function(){
        if($('body').find('.fieldGroupPenghambat').length < maxGroupPenghambat){
            var fieldHTML = '<div class="form-group row fieldGroupPenghambat">'+$(".fieldGroupCopyPenghambat").html()+'</div>';
            $('body').find('.fieldGroupPenghambat:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroupPenghambat+' groups are allowed.');
        }
    });
    
    //remove fields group
    $("body").on("click",".removePenghambat",function(){ 
        $(this).parents(".fieldGroupPenghambat").remove();
    });

    $(".addMorePeralatan").click(function(){
        if($('body').find('.fieldGroupPeralatan').length < maxGroupPeralatan){
            var fieldHTML = '<div class="form-group row fieldGroupPeralatan">'+$(".fieldGroupCopyPeralatan").html()+'</div>';
            $('body').find('.fieldGroupPeralatan:last').after(fieldHTML);
        }else{
            alert('Maximum '+maxGroupPeralatan+' groups are allowed.');
        }
    });

    const detail_peralatan = @json($detail_peralatan);
    console.log(detail_peralatan)
    
    // const buttonAdd = $('#addMorePeralatan').trigger('click');
    // if(detail_peralatan.length>1) {
    //     const buttonAdd = $('#addMorePeralatan');
    // let klik = 1;
    // while(klik <select detail_peralatan.length) {
    //     console.log(klik)
    // buttonAdd.trigger('click');
    // klik++;
    // }}

    let html = '';
    // let peralatan = ['Dump Truck', 'Grass Cutter', 'Alat Pemadat', 'Alat Bantu'];
    let peralatan =  @json($item_peralatan);

    detail_peralatan.forEach((value,index)=> {
        if(index>0) {
            html += ` <div class="form-group row fieldGroupPeralatan"><div class="col-md-4">`;
            html += `<select class="form-control" name="nama_peralatan[]" required>`
            peralatan.forEach((alat,indx)=> {
                html += `<option value="${alat.id},${alat.nama_peralatan}" ${value.nama_peralatan == alat.nama_peralatan ? 'selected' : ''}>${alat.nama_peralatan}</option>`
            })

            html += '</select>';
            html += `</div>
                                <div class="col-md-3">
                                    <input name="jum_peralatan[]" type="number" class="form-control" value="${value.kuantitas}" required>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" name="satuan_peralatan[]" required >
                                        <option value="Unit" ${value.satuan == "Unit" ? 'selected' :''}>Unit</option>
                                        <option value="Set" ${value.satuan == "Set" ? 'selected' :''}>Set</option>
                                    </select>
                                </div>
                                <div class="col-md-1"> 
                                    <a href="javascript:void(0)" data-toggle="modal"><button class="btn btn-danger removePeralatan btn-mini waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                                </div></div>`
        }
    })
    console.log(html)
    document.getElementById('sisaPeralatan').innerHTML += html;
    //remove fields group
    $("body").on("click",".removePeralatan",function(){ 
        $(this).parents(".fieldGroupPeralatan").remove();
    });

    

});
</script>
@endsection