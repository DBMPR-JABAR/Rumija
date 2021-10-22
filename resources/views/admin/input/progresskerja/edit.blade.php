@extends('admin.layout.index')

@section('title') Edit Progress Pekerjaan @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Edit Progress Pekerjaan</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getDataProgress') }}">Progress Pekerjaan</a> </li>
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
                <h5>Edit Data Progress Pekerjaan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <form action="{{ route('updateDataProgress',$progressKerja->id) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{$progressKerja->id}}">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Kegiatan</label>
                        <div class="col-md-10">
                            <select class="form-control" name="kegiatan" required value="{{$progressKerja->kegiatan}}">
                                @foreach ($jenis as $data)
                                <option value="{{$data->name}}" {{ ( $data->name == $progressKerja->kegiatan) ? 'selected' : ''}}>{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Tanggal</label>
                        <div class="col-md-10">
                            <input name="tanggal" type="date" class="form-control" required value="{{$progressKerja->tanggal}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama Paket</label>
                        <div class="col-md-10">
                            <select class="form-control" name="nama_paket" required value="{{$progressKerja->nama_paket}}">
                                @foreach ($paket as $data)
                                <option value="{{$data}}" {{ ( $data == $progressKerja->nama_paket) ? 'selected' : ''}}>{{$data}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Penyedia Jasa</label>
                        <div class="col-md-10">
                            <select class="form-control" name="penyedia_jasa" required value="{{$progressKerja->penyedia_jasa}}">
                                @foreach ($penyedia as $data)
                                <option value="{{$data}}" {{ ( $data == $progressKerja->penyedia_jasa) ? 'selected' : ''}}>{{$data}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(Auth::user()->internalRole->uptd)
                    <input type="hidden" id="uptd" name="uptd_id" value="{{str_replace('uptd','',Auth::user()->internalRole->uptd)}}">
                    @else
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">UPTD</label>
                        <div class="col-md-10">
                            <select class="form-control" id="uptd" name="uptd_id" onchange="ubahOption()" required>
                                <!-- <option value="">Pilih UPTD</option> -->
                                @foreach ($uptd as $data)
                                @if($data->id == $progressKerja->uptd_id)
                                <option value="{{$data->id}}" selected>{{$data->nama}}</option>
                                @else
                                <option value="{{$data->id}}">{{$data->nama}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Satuan Pelayanan Pengelolaan</label>
                        <div class="col-md-10">
                            <select class="form-control" name="sup" required value="{{$progressKerja->sup}}">
                                <option value="<?php echo $progressKerja->sup; ?>"><?php echo $progressKerja->sup; ?></option>
                                <option disabled></option>
                                @foreach ($sup as $data)
                                <option value="{{$data->name}}" {{ ( $data->name == $progressKerja->sup) ? 'selected' : ''}}>{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Ruas Jalan</label>
                        <div class="col-md-10">
                            <select class="form-control" name="ruas_jalan" required value="{{$progressKerja->ruas_jalan}}">
                                @foreach ($ruas_jalan as $data)
                                <option value="{{$data->nama_ruas_jalan}}" {{ ( $data->nama_ruas_jalan == $progressKerja->ruas_jalan) ? 'selected' : ''}}>{{$data->nama_ruas_jalan}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Jenis Pekerjaan</label>
                        <div class="col-md-10">
                            <input name="jenis_pekerjaan" type="text" class="form-control" required value="{{$progressKerja->jenis_pekerjaan}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Lokasi</label>
                        <div class="col-md-8">
                            <input name="lokasi" type="text" class="form-control" required value="{{$progressKerja->lokasi}}">
                        </div>
                        <div class="col-md-2"> KM BDG</div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Koordinat X</label>
                        <div class="col-md-10">
                            <input name="lat" type="text" class="form-control formatLatLong" required value="{{$progressKerja->lat}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Koordinat Y</label>
                        <div class="col-md-10">
                            <input name="lng" type="text" class="form-control formatLatLong" required value="{{$progressKerja->lng}}">
                        </div>
                    </div>

                    <hr>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Rencana</label>
                        <div class="col-md-10">
                            <input type="text" name="rencana" class="form-control" value="{{$progressKerja->rencana}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Realisasi</label>
                        <div class="col-md-10">
                            <input type="text" name="realisasi" class="form-control" value="{{$progressKerja->realisasi}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Waktu Kontrak</label>
                        <div class="col-md-10">
                            <input type="text" name="waktu_kontrak" class="form-control" value="{{$progressKerja->waktu_kontrak}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Terpakai</label>
                        <div class="col-md-10">
                            <input type="text" name="terpakai" class="form-control" value="{{$progressKerja->terpakai}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nilai Kontrak</label>
                        <div class="col-md-10">
                            <input type="text" name="nilai_kontrak" class="form-control" value="{{$progressKerja->nilai_kontrak}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Keuangan</label>
                        <div class="col-md-10">
                            <input type="text" name="bayar" class="form-control" value="{{$progressKerja->bayar}}">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Foto Dokumentasi</label>
                        <div class="col-md-5">
                            <img style="max-height: 400px;" class="img-thumbnail rounded mx-auto d-block" src="{{ url('storage/progresskerja/'.$progressKerja->foto) }}" alt="">
                        </div>
                        <div class="col-md-5">
                            <input name="foto" type="file" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Video Dokumentasi</label>
                        <div class="col-md-5">
                            <video style="max-height: 400px;" controls class="img-thumbnail rounded mx-auto d-block">
                                <source src="{{ url('storage/progresskerja/'.$progressKerja->video) }}" type="video/mp4" />
                            </video>
                        </div>
                        <div class="col-md-5">
                            <input name="video" type="file" class="form-control" accept="video/mp4">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>
                </form>

            </div>
        </div>
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
</script>
@endsection
