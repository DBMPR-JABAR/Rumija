@extends('admin.layout.index') @section('title') Data Paket @endsection
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
                <h4>Data Paket</h4>
                <span>Data Seluruh Paket</span>
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
                <li class="breadcrumb-item"><a href="#!">Data Paket</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection @section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Tabel Data Paket</h5>
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
                @if (hasAccess(Auth::user()->internal_role_id, "Data Paket",
                "Create")) {{--
                <a
                    href="{{ route('addIDDataPaket') }}"
                    class="btn btn-mat btn-primary mb-3"
                    >Tambah</a
                >
                --}} @endif
                <div class="dt-responsive table-responsive">
                    <table
                        id="paket-table"
                        class="table table-striped table-bordered"
                    >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Paket</th>
                                <th>Lokasi Pekerjaan</th>
                                <th>Pagu Anggaran</th>
                                <th>Target Panjang (meter)</th>
                                <th>Waktu Pelaksanaan (HK)</th>
                                <th>Jenis Penanganan</th>
                                <th>Penyedia Jasa</th>
                                <th>No. Kontrak</th>
                                <th>Tanggal Kontrak</th>
                                <th>Nilai Kontrak</th>
                                <th>Nilai Tambahan</th>
                                <th>Nilai Kontrak Perubahan</th>
                                <th>Total Tambahan</th>
                                <th>Total Sisa Lelang</th>
                                <th>Tanggal Pembaruan</th>
                                <th>Diperbaharui Oleh</th>
                                <th style="min-width: 75px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            <!-- @foreach ($dataPaket as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td width='50px'>{{$data->nama_paket}}</td>
                                <td>{{$data->lokasi_pekerjaan}}</td>
                                <td>{{$data->pagu_anggaran}}</td>
                                <td>{{$data->target_panjang}}</td>
                                <td>{{$data->waktu_pelaksanaan_hk}}</td>
                                <td>{{$data->jenis_penanganan}}</td>
                                <td>{{$data->penyedia_jasa}}</td>
                                <td>{{$data->nomor_kontrak}}</td>
                                <td>{{$data->tgl_kontrak}}</td>
                                <td>{{$data->nilai_kontrak}}</td>
                                <td>{{$data->nilai_tambahan}}</td>
                                <td>{{$data->nilai_kontrak_perubahan}}</td>
                                <td>{{$data->total_tambahan}}</td>
                                <td>{{$data->total_sisa_lelang}}</td>
                                <td style="min-width: 75px;">
                                    <div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">
                                        @if (hasAccess(Auth::user()->internal_role_id, "Data Paket", "Update"))
                                        <a href="{{ route('editIDDataPaket',$data->kode_paket) }}"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Edit"><i class="icofont icofont-pencil"></i></button></a>
                                        @endif
                                        @if (hasAccess(Auth::user()->internal_role_id, "Data Paket", "Delete"))
                                        <a href="#delModal" data-id="{{$data->kode_paket}}" data-toggle="modal"><button class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
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
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data Paket</h4>
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
        asset(
            'assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js'
        )
    }}"></script>
<script src="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js'
        )
    }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script>
    $(document).ready(function () {
        // $("#dttable").DataTable();
        $("#delModal").on("show.bs.modal", function (event) {
            const link = $(event.relatedTarget);
            const id = link.data("id");
            console.log(id);
            const url = `{{ url('admin/input-data/data-paket/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find(".modal-footer #delHref").attr("href", url);
        });

        let table = $("#paket-table").DataTable({
            processing: true,
            serverSide: true,
            ajax: "data-paket/json",
            columns: [
                {
                    mRender: function (data, type, full, meta) {
                        return +meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    data: "nama_paket",
                    name: "nama_paket",
                },
                {
                    data: "lokasi_pekerjaan",
                    name: "lokasi_pekerjaan",
                },
                {
                    data: "pagu_anggaran",
                    name: "nama_paket",
                },
                {
                    data: "target_panjang",
                    name: "target_panjang",
                },
                {
                    data: "waktu_pelaksanaan_hk",
                    name: "waktu_pelaksanaan_hk",
                },
                {
                    data: "jenis_penanganan",
                    name: "jenis_penanganan",
                },
                {
                    data: "penyedia_jasa",
                    name: "penyedia_jasa",
                },
                {
                    data: "nomor_kontrak",
                    name: "nomor_kontrak",
                },
                {
                    data: "tgl_kontrak",
                    name: "tgl_kontrak",
                },
                {
                    data: "nilai_kontrak",
                    name: "nilai_kontrak",
                },
                {
                    data: "nilai_tambahan",
                    name: "nilai_tambahan",
                },
                {
                    data: "nilai_kontrak_perubahan",
                    name: "nilai_kontrak_perubahan",
                },
                {
                    data: "total_tambahan",
                    name: "total_tambahan",
                },
                {
                    data: "total_sisa_lelang",
                    name: "total_sisa_lelang",
                },
                {
                    data: "updated_at_format",
                    name: "updated_at_format",
                },
                {
                    data: "updated_by",
                    name: "updated_by",
                },
                {
                    data: "action",
                    name: "action",
                },
            ],
        });

        table
            .on("order.dt search.dt", function () {
                table
                    .column(0, {
                        search: "applied",
                        order: "applied",
                    })
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            })
            .draw();
    });
</script>
@endsection
