@extends('admin.layout.index') @section('title') Kondisi Jalan @endsection
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
                <h4>Kondisi Jalan</h4>
                <span>Data Seluruh Kondisi Jalan</span>
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
                <li class="breadcrumb-item"><a href="#!">Kondisi Jalan</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection @section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Tabel Kondisi Jalan</h5>
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
                @if (hasAccess(Auth::user()->internal_role_id, 'Kondisi Jalan',
                'Create'))
                <a
                    href="{{ route('kondisi_jalan.create') }}"
                    class="btn btn-mat btn-primary mb-3"
                    >Tambah</a
                >
                @endif
                <div class="dt-responsive table-responsive">
                    <table
                        id="kondisi_jalan-table"
                        class="table table-striped table-bordered able-responsive"
                    >
                        <thead>
                            <tr>
                                <th>No</th>
                                {{--
                                <th>No Ruas</th>
                                <th>SUB Ruas</th>
                                <th>Suffix</th>
                                --}}
                                <th>Bulan/Tahun</th>
                                <th>Kota Kabupaten</th>
                                <th>Ruas Jalan</th>
                                <th>KM Asal</th>
                                <th>Lokasi</th>
                                <th>Panjang (m)</th>
                                <th>Latitude Awal</th>
                                <th>Longitude Awal</th>
                                <th>Latitude Akhir</th>
                                <th>Longitude Akhir</th>
                                <th>Luas (m2)</th>
                                <th>Sangat Baik (m2)</th>
                                <th>Baik (m2)</th>
                                <th>Sedang (m2)</th>
                                <th>Jelek (m2)</th>
                                <th>Parah (m2)</th>
                                <th>Sangat Parah (m2)</th>
                                <th>Hancur (m2)</th>
                                <th>Keterangan</th>
                                <th>SUP</th>
                                <td>UPTD</td>
                                <th style="min-width: 75px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            @foreach ($kondisi_jalan as $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                {{--
                                <td>{{ $data->NO_RUAS }}</td>
                                <td>{{ $data->SUB_RUAS }}</td>
                                <td>{{ $data->SUFFIX }}</td>
                                --}}
                                <td>{{ $data->BULAN . '/' . $data->TAHUN }}</td>
                                <td>{{ $data->KOTA_KAB }}</td>
                                <td>{{ $data->RUAS_JALAN }}</td>
                                <td>{{ $data->KM_ASAL }}</td>
                                <td>{{ $data->LOKASI }}</td>
                                <td>{{ $data->PANJANG }}</td>
                                <td>{{ $data->LAT_AWAL }}</td>
                                <td>{{ $data->LONG_AWAL }}</td>
                                <td>{{ $data->LAT_AKHIR }}</td>
                                <td>{{ $data->LONG_AKHIR }}</td>
                                <td>{{ $data->LUAS }}</td>
                                <td>{{ $data->SANGAT_BAIK }}</td>
                                <td>{{ $data->BAIK }}</td>
                                <td>{{ $data->SEDANG }}</td>
                                <td>{{ $data->JELEK }}</td>
                                <td>{{ $data->PARAH }}</td>
                                <td>{{ $data->SANGAT_PARAH }}</td>
                                <td>{{ $data->HANCUR }}</td>
                                <td>{{ $data->KETERANGAN }}</td>
                                <td>{{ $data->SUP }}</td>
                                <td>{{ $data->UPTD }}</td>
                                <td style="min-width: 75px">
                                    <div
                                        class="btn-group"
                                        role="group"
                                        data-placement="top"
                                        title=""
                                        data-original-title=".btn-xlg"
                                    >
                                        @if(hasAccess(Auth::user()->internal_role_id,
                                        'Kondisi Jalan', 'Update'))
                                        <a
                                            href="{{ route('kondisi_jalan.edit', $data->ID_KEMANTAPAN) }}"
                                            ><button
                                                class="btn btn-primary btn-sm waves-effect waves-light"
                                                data-toggle="tooltip"
                                                title="Edit"
                                            >
                                                <i
                                                    class="icofont icofont-pencil"
                                                ></i></button
                                        ></a>
                                        @endif
                                        @if(hasAccess(Auth::user()->internal_role_id,
                                        'Kondisi Jalan', 'Delete'))
                                        <a
                                            href="#delModal"
                                            data-id="{{ $data->ID_KEMANTAPAN }}"
                                            data-toggle="modal"
                                            ><button
                                                class="btn btn-danger btn-sm waves-effect waves-light"
                                                data-toggle="tooltip"
                                                title="Hapus"
                                            >
                                                <i
                                                    class="icofont icofont-trash"
                                                ></i></button
                                        ></a>
                                        @endif
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
                    <h4 class="modal-title">Hapus Data Kondisi Jalan</h4>
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
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    $(document).ready(function () {
        $("#kondisi_jalan-table").DataTable({
            language: {
                emptyTable: "Tidak ada data tersedia.",
            },
        });
    });

    $("#delModal").on("show.bs.modal", function (event) {
        const link = $(event.relatedTarget);
        const id = link.data("id");
        console.log(id);
        const url = `{{ url('admin/input-data/kondisi_jalan/delete') }}/` + id;
        console.log(url);
        const modal = $(this);
        modal.find(".modal-footer #delHref").attr("href", url);
    });
</script>
@endsection
