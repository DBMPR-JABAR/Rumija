@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Kondisi Jalan</h4>
                <span>Seluruh Kondisi Jalan yang ada di naungan DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getIDKondisiJalan') }}">Kondisi Jalan</a> </li>
                <li class="breadcrumb-item"><a href="#">Tambah</a> </li>
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
                <h5>Tambah Data Kondisi Jalan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block pl-5 pr-5 pb-5">

                <form action="{{ route('createIDKondisiJalan') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <?php

                    use Illuminate\Support\Facades\Auth;

                    if (Auth::user()->internalRole->uptd) {
                        $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd); ?>
                        <input id="uptd" name="uptd" type="number" class="form-control" value="{{$uptd_id}}" hidden>
                    <?php } else { ?>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">UPTD</label>
                            <div class="col-md-9">
                                <select class="form-control select2" id="uptd" name="uptd" style="min-width: 100%;" onchange="ubahDataRuasJalan()">
                                    <option>Pilih UPTD</option>
                                    @foreach ($uptd as $uptdData)
                                    <option value="<?php echo $uptdData->id; ?>"><?php echo $uptdData->nama; ?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <?php    } ?>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nama Ruas Jalan</label>
                        <div class="col-md-9">
                            <select id="ruas_jalan" name="ruas_jalan" class="form-control" required>
                                @if (Auth::user()->internalRole->uptd)
                                @foreach ($ruasJalan as $data)
                                <option value="{{$data->nama_ruas_jalan}}">{{$data->nama_ruas_jalan}}</option>
                                @endforeach
                                @else
                                <option>-</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Nama Kota</label>
                        <div class="col-md-9">
                            <input id="nama_kota" name="nama_kota" type="text" class="form-control" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">KM Asal</label>
                        <div class="col-md-9">
                            <input name="km_asal" type="number" step="0.01" class="form-control" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Dari KM</label>
                        <div class="col-md-9">
                            <input name="dari_km" type="number" step="0.01" class="form-control" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Sampai dengan KM</label>
                        <div class="col-md-9">
                            <input name="sampai_km" type="number" step="0.01" class="form-control" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Panjang KM</label>
                        <div class="col-md-9">
                            <input name="panjang_km" type="number" step="0.01" class="form-control" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Lebar Rata2 (meter)</label>
                        <div class="col-md-9">
                            <input name="lebar_rata_rata" type="text" class="form-control formatRibuan" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Jenis Permukaan</label>
                        <div class="col-md-9">
                            <input name="jenis_permukaan" type="text" class="form-control" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="latitude" type="text" class="form-control formatLatLong" required>
                                </div>
                                <div class="col-md-6">
                                    <input name="longitude" type="text" class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="akhir_latitude" type="text" class="form-control formatLatLong" required>
                                </div>
                                <div class="col-md-6">
                                    <input name="akhir_longitude" type="text" class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Kondisi Jalan</label>
                        <div class="col-md-9">
                            <input type="radio" name="kondisi" id="mantap" value="Mantap" onchange="my_function('Mantap')" checked> Mantap
                            <input class="ml-4" type="radio" name="kondisi" id="tidakMantap" value="Tidak Mantap" onchange="my_function('tidakMantap')"> Tidak Mantap
                        </div>
                    </div>
                    <div id="form-mantap">
                        <hr class="mt-4">
                        <div class=" form-group row mt-4">
                            <div class="col-md-12" style="text-align: center;">
                                <p style="color:green; font-size: 25px; font-weight: 1000;">Mantap</p>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-sm-3 col-form-label my-auto">Sangat baik</label>
                            <div class="col-md-9 col-sm-9">
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Lokasi (KM - KM)</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="sb_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="sb_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Panjang (KM)</label>
                                    <div class="col-md-9">
                                        <input name="sb_panjang" type="number" step="0.01" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="sba_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="sba_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="sbak_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="sbak_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row mt-5">
                            <label class="col-md-3 col-sm-3 col-form-label my-auto">Baik</label>
                            <div class="col-md-9 col-sm-9">
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Lokasi (KM - KM)</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="b_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="b_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Panjang (KM)</label>
                                    <div class="col-md-9">
                                        <input name="b_panjang" type="number" step="0.01" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="ba_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="ba_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="bak_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="bak_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row mt-5">
                            <label class="col-md-3 col-sm-3 col-form-label my-auto">Sedang</label>
                            <div class="col-md-9 col-sm-9">
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Lokasi (KM - KM)</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="sd_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="sd_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Panjang (KM)</label>
                                    <div class="col-md-9">
                                        <input name="sd_panjang" type="number" step="0.01" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="sda_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="sda_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="sdak_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="sdak_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="form-tidak-mantap">
                        <hr class="mt-4">
                        <div class=" form-group row mt-4">
                            <div class="col-md-12" style="text-align: center;">
                                <p style="color:red; font-size: 25px; font-weight: 1000;">Tidak Mantap</p>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-sm-3 col-form-label my-auto">Jelek</label>
                            <div class="col-md-9 col-sm-9">
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Lokasi (KM - KM)</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="jlk_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="jlk_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Panjang (KM)</label>
                                    <div class="col-md-9">
                                        <input name="jlk_panjang" type="number" step="0.01" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="ja_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="ja_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="jak_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="jak_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row mt-5">
                            <label class="col-md-3 col-sm-3 col-form-label my-auto">Parah</label>
                            <div class="col-md-9 col-sm-9">
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Lokasi (KM - KM)</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="parah_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="parah_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Panjang (KM)</label>
                                    <div class="col-md-9">
                                        <input name="parah_panjang" type="number" step="0.01" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="pa_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="pa_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="pak_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="pak_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" form-group row mt-5">
                            <label class="col-md-3 col-sm-3 col-form-label my-auto">Sangat Parah</label>
                            <div class="col-md-9 col-sm-9">
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Lokasi (KM - KM)</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="sp_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="sp_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Panjang (KM)</label>
                                    <div class="col-md-9">
                                        <input name="sp_panjang" type="number" step="0.01" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="spa_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="spa_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="spak_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="spak_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row mt-5">
                            <label class="col-md-3 col-sm-3 col-form-label my-auto">Hancur</label>
                            <div class="col-md-9 col-sm-9">
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Lokasi (KM - KM)</label>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="hancur_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="hancur_lokasi" type="number" step="0.01" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class=" form-group row">
                                    <label class="col-md-3 col-form-label">Panjang (KM)</label>
                                    <div class="col-md-9">
                                        <input name="hancur_panjang" type="number" step="0.01" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Awal Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="ha_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="ha_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">Akhir Latitude dan Longitude</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input name="hak_lat" type="text" class="form-control formatLatLong">
                                    </div>
                                    <div class="col-md-6">
                                        <input name="hak_long" type="text" class="form-control formatLatLong">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Tanggal</label>
                        <div class="col-md-9">
                            <input name="tanggal" type="date" step="0.01" class="form-control">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Upload Foto Dokumentasi</label>
                        <div class="col-md-9">
                            <input name="foto_dokumentasi" type="file" accept=".jpg,.jpeg,.png" step="0.01" class="form-control">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Keterangan</label>
                        <div class="col-md-9">
                            <textarea name="keterangan" rows="5" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                        </div>
                    </div>
                    <div class=" form-group row">
                        <a href="{{ route('getIDKondisiJalan') }}"><button type="button" class="btn btn-default waves-effect">Batal</button></a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-2">Simpan</button>
                    </div>
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
        $("#form-mantap").show();
        $("#form-tidak-mantap").hide();

        // Format mata uang.
        $('.formatRibuan').mask('000.000.000.000.000', {
            reverse: true
        });

        // Format untuk lat long.
        $('.formatLatLong').keypress(function(evt) {
            return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
        });
    });

    function my_function(val) {
        // alert(val);
        if (val == 'Mantap') {
            $("#form-mantap").show();
            $("#form-tidak-mantap").hide();

        } else {
            $("#form-tidak-mantap").show();
            $("#form-mantap").hide();
            // }
        }
    }

    function ubahDataRuasJalan() {

        val = document.getElementById("uptd").value
        // alert(val);
        console.log(val)

        $.ajax({
            url: "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}",
            method: 'get',
            dataType: 'JSON',
            data: {
                id: val
            },
            complete: function(result) {
                $('#ruas_jalan').empty(); // remove old options
                $('#ruas_jalan').append($("<option></option>").text('Pilih Ruas Jalan'));

                result.responseJSON.forEach(function(item) {
                    $('#ruas_jalan').append($("<option></option>").attr("value", item["nama_ruas_jalan"]).text(item["nama_ruas_jalan"]));
                });
            }
        });
    }
</script>
@endsection
