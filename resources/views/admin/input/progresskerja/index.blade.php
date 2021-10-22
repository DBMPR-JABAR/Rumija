@extends('admin.layout.index') @section('title') Progress Pekerjaan @endsection
@section('head')
<link
    rel="stylesheet"
    type="text/css"
    href="{{
        asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css')
    }}"
/>
<link
    rel="stylesheet"
    type="text/css"
    href="{{
        asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css')
    }}"
/>
<link
    rel="stylesheet"
    type="text/css"
    href="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css'
        )
    }}"
/>

<link
    rel="stylesheet"
    type="text/css"
    href="{{ asset('assets/vendor/datepicker/bootstrap-datepicker.min.css') }}"
/>

<link
    rel="stylesheet"
    href="https://js.arcgis.com/4.17/esri/themes/light/main.css"
/>

<style>
    table.table-bordered tbody td {
        word-break: break-word;
        vertical-align: top;
    }
</style>
@endsection @section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Progress Pekerjaan</h4>
                <span>Data Progress Pekerjaan</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class="breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}">
                        <i class="feather icon-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="#!">Progress Pekerjaan</a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection @section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Tabel Progress Pekerjaan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{--
                        <li><i class="feather icon-maximize full-card"></i></li>
                        --}}
                        <li>
                            <i class="feather icon-minus minimize-card"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if (hasAccess(Auth::user()->internal_role_id, "Progress Kerja",
                "Create")) {{--
                <a
                    data-toggle="modal"
                    href="#addModal"
                    class="btn btn-mat btn-primary mb-3"
                    >Tambah</a
                >
                --}} @endif
                <div class="dt-responsive table-responsive">
                    <table
                        id="progress-table"
                        class="table table-striped table-bordered"
                    >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Penyedia Jasa</th>
                                <th>Nama Paket</th>
                                <th>Rencana / Realisasi / Deviasi</th>
                                <th>
                                    Waktu Kontrak /Terpakai / Sisa / Prosentase
                                </th>
                                <th>Nilai Kontrak</th>
                                <th>Keuangan Prosentase</th>
                                <th>Foto</th>
                                <th>Video</th>
                                <th>Status</th>
                                <th style="min-width: 75px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            <!--   @foreach ($pekerjaan as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$data->tanggal}}</td>
                                <td>{{$data->penyedia_jasa}}</td>
                                <td>{{$data->nama_paket}}</td>
                                <td>{{$data->rencana}}<br>{{$data->realisasi}}<br>{{$data->deviasi}}</td>
                                <td>{{$data->waktu_kontrak}}<br>{{$data->terpakai}}<br>{{$data->sisa}}<br>{{$data->prosentase}}</td>

                                <td>{{number_format($data->nilai_kontrak,2)}}</td>
                                <td>{{$data->bayar}}<br>{{number_format($data->bayar,2)}} %</td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/progresskerja/'.$data->foto) !!}" alt="" srcset=""></td>
                                <td><video width='150' height='100' controls>
                                        <source src="{!! url('storage/progresskerja/'.$data->video) !!}" type='video/*' Sorry, your browser doesn't support the video element.></video></td>
                                <td>{{$data->status}}</td>
                                <td style="min-width: 75px;">
                                    <div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">
                                        @if (hasAccess(Auth::user()->internal_role_id, "Progress Kerja", "Update"))
                                        <a href="{{ route('editDataProgress',$data->id) }}"><button data-toggle="tooltip" title="Edit" class="btn btn-primary btn-sm waves-effect waves-light"><i class="icofont icofont-pencil"></i></button></a>
                                        @endif
                                        @if (hasAccess(Auth::user()->internal_role_id, "Progress Kerja", "Delete"))
                                        <a href="#delModal" data-id="{{$data->id}}" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light"><i class="icofont icofont-trash"></i></button></a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-only">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form
                    action="{{ route('createDataProgress') }}"
                    method="post"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Tambah Data Progress Pekerjaan
                        </h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Kegiatan</label
                            >
                            <div class="col-md-10">
                                <select
                                    class="form-control"
                                    name="kegiatan"
                                    required
                                >
                                    @foreach ($jenis as $data)
                                    <option value="{{$data->name}}">
                                        {{$data->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Tanggal</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="tanggal"
                                    type="date"
                                    class="form-control"
                                    required
                                />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Nama Paket</label
                            >
                            <div class="col-md-10">
                                <select
                                    class="form-control"
                                    name="nama_paket"
                                    required
                                >
                                    @foreach ($paket as $data)
                                    <option value="{{ $data }}">
                                        {{ $data }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Penyedia Jasa</label
                            >
                            <div class="col-md-10">
                                <select
                                    class="form-control"
                                    name="penyedia_jasa"
                                    required
                                >
                                    @foreach ($penyedia as $data)
                                    <option value="{{ $data }}">
                                        {{ $data }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(Auth::user()->internalRole->uptd)
                        <input
                            type="hidden"
                            id="uptd"
                            name="uptd_id"
                            value="{{str_replace('uptd','',Auth::user()->internalRole->uptd)}}"
                        />
                        @else
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">UPTD</label>
                            <div class="col-md-10">
                                <select
                                    class="form-control"
                                    id="uptd"
                                    name="uptd_id"
                                    onchange="ubahOption()"
                                    required
                                >
                                    <option value="">Pilih UPTD</option>
                                    @foreach ($uptd as $data)
                                    <option value="{{$data->id}}">
                                        {{$data->nama}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Satuan Pelayanan Pengelolaan</label
                            >
                            <div class="col-md-10 my-auto">
                                <select
                                    class="form-control"
                                    id="sup"
                                    name="sup"
                                    required
                                >
                                    @if (Auth::user()->internalRole->uptd)
                                    @foreach ($sup as $data)
                                    <option value="{{$data->name}}">
                                        {{$data->name}}
                                    </option>
                                    @endforeach @else
                                    <option>-</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Ruas Jalan</label
                            >
                            <div class="col-md-10">
                                <select
                                    class="form-control"
                                    id="ruas_jalan"
                                    name="ruas_jalan"
                                    required
                                >
                                    @if (Auth::user()->internalRole->uptd)
                                    @foreach ($ruas_jalan as $data)
                                    <option value="{{$data->nama_ruas_jalan}}">
                                        {{$data->nama_ruas_jalan}}
                                    </option>
                                    @endforeach @else
                                    <option>-</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Jenis Pekerjaan</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="jenis_pekerjaan"
                                    type="text"
                                    class="form-control"
                                    required
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Lokasi</label
                            >
                            <div class="col-md-8">
                                <input
                                    name="lokasi"
                                    type="text"
                                    class="form-control"
                                    required
                                />
                            </div>
                            <div class="col-md-2">KM BDG</div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Koordinat X</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="lat"
                                    type="text"
                                    class="form-control formatLatLong"
                                    required
                                    size="15"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label"
                                >Koordinat Y</label
                            >
                            <div class="col-md-10">
                                <input
                                    name="lng"
                                    type="text"
                                    class="form-control formatLatLong"
                                    required
                                    size="15"
                                />
                            </div>
                        </div>

                        <hr />
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Rencana</label
                            >
                            <div class="col-md-6">
                                <input
                                    type="text"
                                    name="rencana"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Realisasi</label
                            >
                            <div class="col-md-6">
                                <input
                                    type="text"
                                    name="realisasi"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Waktu Kontrak</label
                            >
                            <div class="col-md-6">
                                <input
                                    type="text"
                                    name="waktu_kontrak"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Terpakai</label
                            >
                            <div class="col-md-6">
                                <input
                                    type="text"
                                    name="terpakai"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Nilai Kontrak</label
                            >
                            <div class="col-md-6">
                                <input
                                    type="number"
                                    name="nilai_kontrak"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Keuangan</label
                            >
                            <div class="col-md-6">
                                <input
                                    type="text"
                                    name="bayar"
                                    class="form-control"
                                />
                            </div>
                        </div>
                        <hr />
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Foto Dokumentasi</label
                            >
                            <div class="col-md-6">
                                <input
                                    name="foto"
                                    type="file"
                                    class="form-control"
                                    accept="image/*"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label"
                                >Video Dokumentasi</label
                            >
                            <div class="col-md-6">
                                <input
                                    name="video"
                                    type="file"
                                    class="form-control"
                                    accept="video/mp4"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-default waves-effect"
                            data-dismiss="modal"
                        >
                            Tutup
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary waves-effect waves-light"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal-only">
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data Pekerjaan</h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus data ini?</p>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-default waves-effect"
                        data-dismiss="modal"
                    >
                        Tutup
                    </button>
                    <a
                        id="delHref"
                        href=""
                        class="btn btn-danger waves-effect waves-light"
                        >Hapus</a
                    >
                </div>
            </div>
        </div>
    </div>
</div>
@endsection @section('script')
<!-- <script src="{{ asset('assets/vendor/jquery/js/jquery-3.4.1.min.js') }}" ></script> -->
<script src="{{
        asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')
    }}"></script>
<script src="{{
        asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js')
    }}"></script>
<script src="{{
        asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js')
    }}"></script>

<script src="{{
        asset('assets/vendor/datepicker/bootstrap-datepicker.min.js')
    }}"></script>

<script src="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js'
        )
    }}"></script>
<script src="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js'
        )
    }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script>
    $(document).ready(function () {
        // Format mata uang.
        $(".formatRibuan").mask("000.000.000.000.000", {
            reverse: true,
        });

        // Format untuk lat long.
        $(".formatLatLong").keypress(function (evt) {
            return /^\-?[0-9]*\.?[0-9]*$/.test($(this).val() + evt.key);
        });

        $("#dttable").DataTable();
        $("#delModal").on("show.bs.modal", function (event) {
            const link = $(event.relatedTarget);
            const id = link.data("id");
            console.log(id);
            const url =
                `{{ url('admin/input-data/progresskerja/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find(".modal-footer #delHref").attr("href", url);
        });

        $("#date-start").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
        });

        let table = $("#progress-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "progresskerja/json",
            columns: [
                {
                    mRender: function (data, type, full, meta) {
                        return +meta.row + 1;
                    },
                },
                { data: "tanggal", name: "tanggal" },
                { data: "penyedia_jasa", name: "penyedia_jasa" },
                { data: "nama_paket", name: "nama_paket" },
                // { data: 'rencana_temp', name: 'rencana_temp' },
                // { data: 'waktu_temp', name: 'waktu_temp' },
                {
                    mRender: function (data, type, full) {
                        return (
                            full["rencana"] +
                            "<br />" +
                            full["realisasi"] +
                            "<br>" +
                            full["deviasi"]
                        );
                    },
                },
                {
                    mRender: function (data, type, full) {
                        return (
                            full["waktu_kontrak"] +
                            "<br />" +
                            full["terpakai"] +
                            "<br>" +
                            full["sisa"] +
                            "<br>" +
                            full["prosentase"]
                        );
                    },
                },
                { data: "nilai_kontrak", name: "nilai_kontrak" },
                {
                    mRender: function (data, type, full) {
                        var databayar = parseFloat(full["bayar"]);
                        return full["bayar"] + "<br />" + databayar.toFixed(2);
                    },
                },
                {
                    mRender: function (data, type, full) {
                        return (
                            '<img class="img-fluid" style="max-width: 100px" src="' +
                            `{!! url('storage/progresskerja/') !!}` +
                            "/" +
                            full["foto"] +
                            '" alt="" srcset="">'
                        );
                    },
                },
                {
                    mRender: function (data, type, full) {
                        return (
                            '<video width="150" height="100" controls><source src="' +
                            `{!! url('storage/progresskerja/') !!}` +
                            "/" +
                            full["video"] +
                            '" type="video/mp4" Sorry, your browser doesnt support the video element.></video>'
                        );
                    },
                },
                { data: "status", name: "status" },
                { data: "action", name: "action" },
            ],
        });

        table
            .on("order.dt search.dt", function () {
                table
                    .column(0, { search: "applied", order: "applied" })
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            })
            .draw();
    });

    function ubahOption() {
        //untuk select SUP
        id = document.getElementById("uptd").value;
        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}";
        id_select = "#sup";
        text = "Pilih SUP";
        option = "name";

        setDataSelect(id, url, id_select, text, option, option);

        //untuk select Ruas
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}";
        id_select = "#ruas_jalan";
        text = "Pilih Ruas Jalan";
        option = "nama_ruas_jalan";

        setDataSelect(id, url, id_select, text, option, option);
    }
</script>
@endsection
