@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Data Paket</h4>
                <span>Seluruh Data Paket yang ada di naungan DBMPR Jabar</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getIDDataPaket') }}">Data Paket</a> </li>
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
                <h5>Edit Data Paket</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block pl-5 pr-5 pb-5">

                <form action="{{ route('updateIDDataPaket') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="kode_paket" value="{{$dataPaket->kode_paket}}">

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Kegiatan</label>
                        <div class="col-md-9">
                            <select class="form-control select2" name="kegiatan" style="min-width: 100%;">
                                @foreach ($pekerjaan as $pekerjaanData)
                                @if($pekerjaanData->name == $dataPaket->kegiatan)
                                <option value="<?php echo $pekerjaanData->name; ?>" selected><?php echo $pekerjaanData->name; ?></option>
                                @else
                                <option value="<?php echo $pekerjaanData->name; ?>"><?php echo $pekerjaanData->name; ?></option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Nama Paket</label>
                        <div class="col-md-9">
                            <input name="nama_paket" type="text" class="form-control" required value="{{$dataPaket->nama_paket}}">
                        </div>
                    </div>

                    <?php

                    use Illuminate\Support\Facades\Auth;

                    if (Auth::user()->internalRole->uptd) {
                        $uptd_id = str_replace('uptd', '', Auth::user()->internalRole->uptd); ?>
                        <input id="uptd" name="uptd_id" type="number" class="form-control" value="{{$uptd_id}}" hidden>
                    <?php } else { ?>
                        <div class=" form-group row">
                            <label class="col-md-3 col-form-label">UPTD</label>
                            <div class="col-md-9">
                                <select class="form-control select2" id="uptd" name="uptd_id" style="min-width: 100%;" onchange="ubahOption()">
                                    @foreach ($uptd as $uptdData)
                                    @if($dataPaket->uptd_id == $uptdData->id)
                                    <option value="<?php echo $uptdData->id; ?>" selected><?php echo $uptdData->nama; ?></option>
                                    @else
                                    <option value="<?php echo $uptdData->id; ?>"><?php echo $uptdData->nama; ?></option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <?php    } ?>


                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Satuan Pelayanan Pengelolaan</label>
                        <div class="col-md-9 my-auto">
                            <select class="form-control select2" id="sup" name="sup" style="min-width: 100%;">
                                <option value="<?php echo $dataPaket->sup; ?>"><?php echo $dataPaket->sup; ?></option>
                                <option disabled></option>
                                @foreach ($sup as $supData)
                                <option value="<?php echo $supData->name; ?>"><?php echo $supData->name; ?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Lokasi Pekerjaan</label>
                        <div class="col-md-9">
                            <select class="form-control select2" id="ruas_jalan" name="lokasi_pekerjaan" style="min-width: 100%;" required>
                                <option value="<?php echo $dataPaket->lokasi_pekerjaan; ?>"><?php echo $dataPaket->lokasi_pekerjaan; ?></option>
                                <option disabled></option>
                                @foreach ($ruasJalan as $ruasJalanData)
                                <option value="<?php echo $ruasJalanData->nama_ruas_jalan; ?>"><?php echo $ruasJalanData->nama_ruas_jalan; ?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Pagu Anggaran</label>
                        <div class="col-md-9">
                            <input name="pagu_anggaran" type="number" step="0.01" class="form-control" required value="{{$dataPaket->pagu_anggaran}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Target Panjang (meter)</label>
                        <div class="col-md-9">
                            <input name="target_panjang" type="text" class="form-control formatRibuan" required value="{{$dataPaket->target_panjang}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Waktu Pelaksanaan (HK)</label>
                        <div class="col-md-9">
                            <input name="waktu_pelaksanaan_hk" type="number" step="0.01" class="form-control" required value="{{$dataPaket->waktu_pelaksanaan_hk}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Waktu Pelaksanaan (Bulan)</label>
                        <div class="col-md-9">
                            <input name="waktu_pelaksanaan_bln" type="number" step="0.01" class="form-control" required value="{{$dataPaket->waktu_pelaksanaan_bln}}">
                        </div>
                    </div>
                    <hr class="mt-5">
                    <div class=" form-group row">
                        <label class="col-md-12 col-form-label" style="font-weight: bold;">KOORDINAT AWAL :</label>
                        <div class="col-md-3">
                            <hr>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Koordinat X</label>
                        <div class="col-md-9">
                            <input name="lat" type="text" id="lat" class="form-control formatLatLong" required value="{{$dataPaket->lat}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Koordinat Y</label>
                        <div class="col-md-9">
                            <input name="lng" type="text" id="lng" class="form-control formatLatLong" required value="{{$dataPaket->lng}}">
                        </div>
                    </div>
                    <hr>
                    <div class=" form-group row">
                        <label class="col-md-12 col-form-label" style="font-weight: bold;">KOORDINAT AKHIR :</label>
                        <div class="col-md-3">
                            <hr>
                        </div>
                    </div>
                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Koordinat X</label>
                        <div class="col-md-9">
                            <input name="lat1" type="text" id="lat1" class="form-control formatLatLong" required value="{{$dataPaket->lat1}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Koordinat Y</label>
                        <div class="col-md-9">
                            <input name="lng1" type="text" id="lng1" class="form-control formatLatLong" required value="{{$dataPaket->lng1}}">
                        </div>
                    </div>
                    <hr class="mb-5">
                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Titik / Segmen</label>
                        <div class="col-md-9">
                            <input name="titik_segmen1" type="number" class="form-control" required value="{{$dataPaket->titik_segmen1}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Lokasi KM Bdg</label>
                        <div class="col-md-9">
                            <input name="km_bdg1" type="text" class="form-control" required value="{{$dataPaket->km_bdg1}}">
                        </div>
                    </div>


                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Keterangan</label>
                        <div class="col-md-9">
                            <input name="keterangan" type="text" class="form-control" required value="{{$dataPaket->keterangan}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Jenis Penanganan</label>
                        <div class="col-md-9">
                            <input name="jenis_penanganan" type="text" class="form-control" required value="{{$dataPaket->jenis_penanganan}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Penyedia Jasa</label>
                        <div class="col-md-9">
                            <input name="penyedia_jasa" type="text" class="form-control" required value="{{$dataPaket->penyedia_jasa}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">No. Kontrak</label>
                        <div class="col-md-9">
                            <input name="nomor_kontrak" type="text" class="form-control" required value="{{$dataPaket->nomor_kontrak}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Tanggal Kontrak</label>
                        <div class="col-md-9">
                            <input name="tgl_kontrak" type="date" class="form-control" required value="{{$dataPaket->tgl_kontrak}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Nilai Kontrak</label>
                        <div class="col-md-9">
                            <input name="nilai_kontrak" type="number" step="0.01" class="form-control" required value="{{$dataPaket->nilai_kontrak}}">
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Nilai Tambahan 10%</label>
                        <div class="col-md-9">
                            <input name="nilai_tambahan" type="number" step="0.01" class="form-control" required value="{{$dataPaket->nilai_tambahan}}">
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

        var status = $('input[name="kondisi"]:checked').val();

        if (status == 'Mantap') {
            $("form-mantap").show();
            $("form-tidak-mantap").hide();

        } else {
            $("form-tidak-mantap").show();
            $("form-mantap").hide();
        }
    });

    function ubahOption() {

        //untuk select SUP
        id = document.getElementById("uptd").value
        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
        id_select = '#sup'
        text = 'Pilih SUP'
        option = 'name'

        setDataSelect(id, url, id_select, text, option, option)

        //untuk select Ruas
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'

        setDataSelect(id, url, id_select, text, option, option)

    }
</script>
@endsection
