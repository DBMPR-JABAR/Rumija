@extends('admin.layout.index') @section('title') Bantuan Keuangan @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{
        asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css')
    }}" />
<link rel="stylesheet" type="text/css" href="{{
        asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css')
    }}" />
<link rel="stylesheet" type="text/css" href="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css'
        )
    }}" />

<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css" />

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
                <h4>Laporan Progres Bantuan Keuangan</h4>
                <span>Data Seluruh Laporan Progres Bantuan Keuangan</span>
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
                <li class="breadcrumb-item"><a href="#!">Laporan Progres Bantuan Keuangan</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection @section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Tabel Laporan Progres Bantuan Keuangan</h5>
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
                <div class="dt-responsive table-responsive">
                    <table id="bankeu_table" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Target</th>
                                <th>Kategori</th>
                                <th>Nama Kegiatan</th>
                                <th>No. Kontrak</th>
                                <th>Progress (%)</th>
                                <th>Status</th>
                                <th style="min-width: 75px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bankeu as $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $data->target.'/'.$data->pembagian_progres }}</td>
                                <td>{{ $data->kategori }}</td>
                                <td>{{ $data->nama_kegiatan }}</td>
                                <td>{{ $data->no_kontrak }}</td>
                                <td>{{ $data->progress ? $data->progress :"-"}}</td>
                                <td>{{ $data->is_verified == 1 ? 'Terverifikasi': ($data->is_verified == 0 ? 'Belum Diverifikasi' : 'Ditolak') }}
                                </td>
                                <td style="min-width: 75px">
                                    <div class="btn-group" role="group" data-placement="top" title=""
                                        data-original-title=".btn-xlg">
                                        {{-- <a class="d-inline-block" href="{{ route('bankeu.show', $data->id) }}"><button
                                            class="btn btn-success btn-sm waves-effect mr-1 waves-light"
                                            data-toggle="tooltip" title="Histori Progres">
                                            <i class="icofont icofont-eye-alt"></i></button></a> --}}
                                        @if(hasAccess(Auth::user()->internal_role_id,
                                        'Bantuan Keuangan', 'Update'))
                                        <a class="d-inline-block"
                                            href="{{ route('bankeu.verifikasi', ['id'=>$data->id,'target'=>$data->target]) }}"><button
                                                class="btn btn-primary mr-1 btn-sm waves-effect waves-light"
                                                data-toggle="tooltip"
                                                title="{{ $data->is_verified == 0 ? 'Konfirmasi': 'Detail' }}">
                                                <i class="icofont {{ $data->is_verified == 0 ? 'icofont-pencil': 'icofont-eye' }}"></i></button></a>
                                        @endif
                                        {{-- @if(hasAccess(Auth::user()->internal_role_id,
                                        'Bantuan Keuangan', 'Delete'))
                                        <a class="d-inline-block" href="#delModal" data-id="{{ $data->id }}"
                                        data-toggle="modal"><button
                                            class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="tooltip"
                                            title="Hapus">
                                            <i class="icofont icofont-trash"></i></button></a>
                                        @endif --}}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
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
                    <h4 class="modal-title">Hapus Data Bantuan Keuangan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                        Tutup
                    </button>
                    <a id="delHref" href="" class="btn btn-danger waves-effect waves-light">Hapus</a>
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
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    $(document).ready(function () {
        $("#bankeu_table").DataTable({
            language: {
                emptyTable: "Tidak ada data tersedia.",
            },
        });
    });

    $("#delModal").on("show.bs.modal", function (event) {
        const link = $(event.relatedTarget);
        const id = link.data("id");
        console.log(id);
        const url = `{{ url('admin/input-data/bankeu/delete') }}/` + id;
        console.log(url);
        const modal = $(this);
        modal.find(".modal-footer #delHref").attr("href", url);
    });
</script>
@endsection
