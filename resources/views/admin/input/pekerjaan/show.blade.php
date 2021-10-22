@extends('admin.layout.index')

@section('title') Edit Pekerjaan @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Judgement Pekerjaan {{Str::title($pekerjaan->nama_mandor)}}</h4>
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
                <li class="breadcrumb-item"><a href="#">Edit</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>{{ Str::upper($pekerjaan->sup) }}</h5>

                <h2>{{ Str::upper($pekerjaan->status->uptd) }}</h2>
                <div class="card-header-right">
                    {{ $pekerjaan->tanggal }}
                </div>
            </div>
            <div class="card-block">


                    <input type="hidden" name="id_pek" value="{{$pekerjaan->id_pek}}">

                    <div class="row">
                        <div class="col-md-4">
                            <label class="col-md-6 col-form-label"><b>Jenis Pekerjaan</b></label>
                            <hr>
                            <label class="col-md-12 col-form-label">{{$pekerjaan->jenis_pekerjaan}}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-6 col-form-label"><b>Nama Paket</b></label>
                            <hr>
                            <label class="col-md-12 col-form-label">{{$pekerjaan->paket}}</label>
                        </div>
                        <div class="col-md-4">
                            <label class="col-md-12 col-form-label"><b>Perkiraan Kuantitas</b></label>
                            <hr>
                            <label class="col-md-12 col-form-label">{{$pekerjaan->perkiraan_kuantitas}}</label>
                        </div>
                    </div>
                    @if(count($detail_pekerja)>=1)
                    <div class="table-responsive">
                        <br>
                        <label style="font-weight: bold;">Tenaga Kerja</label>
                        <table class="table table-striped">
                            <tr>
                                <th>#</th>
                                <th>Jabatan</th>
                                <th>Satuan</th>
                                <th>Jumlah</th>

                            </tr>
                            @foreach ($detail_pekerja as $no => $item)
                            <tr>
                                <td width="5%">{{ ++$no }}</td>
                                <td width="25%">{{ $item->jabatan }}</td>

                                <td width="15%">Hok</td>
                                <td width="15%">{{ $item->jumlah }}</td>

                            </tr>
                                @endforeach

                        </table>
                    </div>
                    @endif


            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>Lokasi {{$pekerjaan->lokasi}}</h5>
                <br>
                <h5>{{$pekerjaan->ruas_jalan}}</h5>
                <div class="card-header-right">
                    <i class="feather icon-maximize full-card"></i>
                    <i class="feather icon-minus minimize-card"></i>
                </div>
            </div>
            <div class="card-block">


                    <div class="row">

                        <div class="col-md-6">
                            <label class="col-md-6 col-form-label"><b>Koordinat X</b></label>
                            <hr>
                            <label class="col-md-12 col-form-label">{{$pekerjaan->lat}}</label>
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-6 col-form-label"><b>Koordinat Y</b></label>
                            <hr>
                            <label class="col-md-12 col-form-label">{{$pekerjaan->lng}}</label>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Panjang (meter)</label>
                        <label class="col-md-10 col-form-label">{{$pekerjaan->panjang}} meter</label>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <label class="col-md-12 col-form-label text-center">Foto Dokumentasi (0%)</label>
                            <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block" src="{{ url('storage/pekerjaan/'.$pekerjaan->foto_awal) }}" alt="">

                        </div>
                        <div class="col-md-6">
                            <label class="col-md-12 col-form-label text-center">Foto Dokumentasi (50%)</label>
                            <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block" src="{{ url('storage/pekerjaan/'.$pekerjaan->foto_sedang) }}" alt="">

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-12 col-form-label text-center">Foto Dokumentasi (100%)</label>
                            <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block" src="{{ url('storage/pekerjaan/'.$pekerjaan->foto_akhir) }}" alt="">


                    </div>
                    <div class="form-group row">
                        <label class="col-md-12 col-form-label text-center">Video Dokumentasi</label>
                            <video  style="max-height: 400px;" controls class="img-thumbnail rounded mx-auto d-block">
                                <source src="{{ url('storage/pekerjaan/'.$pekerjaan->video) }}" type="video/mp4" />
                            </video>
                    </div>
                    @if(count($detail_penghambat)>=1)
                    <div class="table-responsive">
                        <br>
                        <label style="font-weight: bold;">Kejadian Penghambat Pelaksanaan</label>
                        <table class="table table-striped">
                            <tr>
                                <th>#</th>
                                <th>Jenis Gangguan</th>
                                <th>Waktu</th>
                                <th>Akibat</th>

                            </tr>
                            @foreach ($detail_penghambat as $no => $item)
                            <tr>
                                <td width="5%">{{ ++$no }}</td>
                                <td width="25%">{{ $item->jenis_gangguan }}</td>

                                <td width="15%">{{ $item->start_time }} - {{ $item->end_time }}</td>
                                <td width="15%">{{ $item->akibat }}</td>

                            </tr>
                                @endforeach

                        </table>
                    </div>
                    @endif

            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>Material</h3>
                <div class="card-header-right">
                    <i class="feather icon-maximize full-card"></i>
                    <i class="feather icon-minus minimize-card"></i>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    
                    <div class="col-md-6 col-sm-12">
                        @if(count($peralatan)>=1)
                        <div class="table-responsive">
                            <label style="font-weight: bold;">Alat yang Digunakan</label>
                            <table class="table table-striped">
                                <tr>
                                    <th>#</th>
                                    <th>Peralatan</th>
                                    <th>Kuantitas</th>
                                    <th>Satuan</th>
        
                                </tr>
                                @php
                                    $counter = 0;
                                @endphp
                                @foreach ($peralatan as $no => $item)
                                <tr>
                                    <td width="5%">{{ ++$no }}</td>
                                    <td width="25%">{{ $item->nama_peralatan }}</td>
        
                                    <td width="15%">{{ $item->kuantitas }}</td>
                                    <td width="15%">{{ $item->satuan }}</td>
        
                                </tr>
                                    @endforeach
        
                            </table>
                        </div>
                        @endif

                    </div>
                    <div class="col-md-6 col-sm-12">
                        @if(count($detail_bahan_operasional)>=1)
                            <div class="table-responsive">
                                <label style="font-weight: bold;">Bahan Operasional Peralatan</label>
                                <table class="table table-striped">
                                    <tr>
                                        <th>#</th>
                                        <th>Material</th>
                                        <th>Kuantitas</th>
                                        <th>Satuan</th>
            
                                    </tr>
                                    @php
                                        $counter = 0;
                                    @endphp
                                    @foreach ($detail_bahan_operasional as $no => $item)
                                    <tr>
                                        <td width="5%">{{ ++$no }}</td>
                                        <td width="25%">{{ $item->nama_item }}</td>
            
                                        <td width="15%">{{ $item->kuantitas }}</td>
                                        <td width="15%">{{ $item->satuan }}</td>
            
                                    </tr>
                                        @endforeach
            
                                </table>
                            </div>
                        @endif
                        
                    </div>
                
                </div>
                <div class="table-responsive">
                    <label style="font-weight: bold;">Detail Material</label>
                    <table class="table table-striped">
                        <tr>
                            <th>#</th>
                            <th>Bahan Material</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>

                        </tr>
                        @php
                            $counter = 0;
                        @endphp
                        @foreach ($pekerjaan->nama_bahan as $item)
                        <tr>
                            <td width="5%">{{ ++$counter }}</td>
                            <td width="35%">{{ $item }}</td>

                            <td width="15%">{{ $pekerjaan->jum_bahan[$counter-1] }}</td>
                            <td width="15%">{{ $pekerjaan->satuan[$counter-1] }}</td>

                        </tr>
                            @endforeach

                    </table>
                </div>
                
            </div>
        </div>
        <form action="{{ route('jugmentLaporanMandor',$pekerjaan->id_pek) }}" method="post" enctype="multipart/form-data">
            @csrf
        
            @if (str_contains(Auth::user()->internalRole->role,'Pengamat')|| str_contains(Auth::user()->internalRole->role,'Kepala Satuan Unit Pemeliharaan'))
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
            <div class="card">

                <div class="card-block">

                        <div class="form-group">
                            <label>Judgement</label>
                            <select class="form-control" name="status" required>
                                <option value="">Select</option>

                                <option value="Approved" @if (@$detail->status != null && strpos('Approved', @$detail->status) !== false) selected @endif>Approved</option>
                                <option value="Rejected" @if (@$detail->status != null && strpos('Rejected', @$detail->status) !== false) selected @endif>Rejected</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan"
                                value="{{ old('keterangan', @$detail->description) }}" placeholder="Masukan Keterangan"
                                class="form-control @error('keterangan') is-invalid @enderror" >
                            @error('keterangan')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect " data-dismiss="modal">Kembali</button></a>
                    <button type="submit" class="btn btn-responsive btn-primary">Submit</button>

                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script>
    $(document).ready(function() {
        // Format mata uang.
        $('.formatRibuan').mask('000.000.000.000.000', {
            reverse: true
        });

        // Format untuk lat long.
        $('.formatLatLong').keypress(function(evt) {
            return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
        });
    });

    function ubahOption() {

        //untuk select Ruas
        id = document.getElementById("uptd").value
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'

        setDataSelect(id, url, id_select, text, option, option)

        //untuk select SUP
        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
        id_select = '#sup'
        text = 'Pilih SUP'
        option = 'name'

        setDataSelect(id, url, id_select, text, option, option)
    }
</script>
@endsection
