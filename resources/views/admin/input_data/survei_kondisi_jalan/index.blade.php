@extends('admin.layout.index') @section('title') Survei Kondisi Jalan
@endsection @section('head')
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
                <h4>Survei Kondisi Jalan</h4>
                <span>Data Seluruh Survei Kondisi Jalan</span>
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
                    <a href="#!">Survei Kondisi Jalan</a>
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
                <h5>Tabel Survei Kondisi Jalan</h5>
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
                @if (hasAccess(Auth::user()->internal_role_id, 'Survey Kondisi Jalan',
                'Create'))
                <a
                    href="{{ route('survei_kondisi_jalan.create') }}"
                    class="btn btn-mat btn-primary mb-3"
                    >Tambah</a
                >
                <button
                    type="button"
                    class="btn btn-mat btn-primary mb-3"
                    data-toggle="modal"
                    data-target="#importExcel"
                >
                    Import
                </button>
                @endif
                <div class="dt-responsive table-responsive">
                    <table
                        id="surveikondisijalan-table"
                        class="table table-striped table-bordered able-responsive"
                    >
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Id Segmen</th>
                                <th>Nama Ruas Jalan</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Jarak (m)</th>
                                <th>Kecepatan (km/j)</th>
                                <th>Altitude</th>
                                <th>Grade (%)</th>
                                <th>eIri</th>
                                <th>cIri</th>
                                <th>RoadId</th>
                                <th>Dibuat</th>
                                <th>Diupdate</th>
                                <th style="min-width: 75px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            @foreach ($surveiKondisiJalan as $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $data->id_segmen }}</td>
                                <td>
                                    {{ @$ruas_jalan_lists->where('id_ruas_jalan', $data->id_ruas_jalan)->first()->nama_ruas_jalan }}
                                </td>
                                <td>{{ $data->latitude }}</td>
                                <td>{{ $data->longitude }}</td>
                                <td>{{ $data->distance }}</td>
                                <td>{{ $data->speed }}</td>
                                <td>{{ $data->altitude }}</td>
                                <td>{{ $data->grade }}</td>
                                <td>{{ $data->e_iri }}</td>
                                <td>{{ $data->c_iri }}</td>
                                <td>{{ $data->road_id }}</td>
                                @if (Count($users->where('id',
                                $data->created_user)) > 0)
                                <td>
                                    {{ $users->where('id', $data->created_user)->first()->name }}
                                </td>
                                @else
                                <td>-</td>
                                @endif @if (Count($users->where('id',
                                $data->updated_user)) > 0)
                                <td>
                                    {{ $users->where('id', $data->updated_user)->first()->name }}
                                </td>
                                @else
                                <td>-</td>
                                @endif
                                <td style="min-width: 75px">
                                    <div
                                        class="btn-group"
                                        role="group"
                                        data-placement="top"
                                        title=""
                                        data-original-title=".btn-xlg"
                                    >
                                        @if(hasAccess(Auth::user()->internal_role_id,'Survey Kondisi Jalan', 'Update'))
                                        <a
                                            href="{{ route('survei_kondisi_jalan.edit', $data->id) }}"
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
                                        'Survey Kondisi Jalan', 'Delete'))
                                        <a
                                            href="#delModal"
                                            data-id="{{ $data->id }}"
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
    <!-- Import Excel -->
    <div
        class="modal fade searchableModalContainer"
        id="importExcel"
        tabindex="-1"
        role="dialog"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <form
                method="post"
                action="{{ route('importSurveiRuasJalan') }}"
                enctype="multipart/form-data"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Import Excel
                        </h5>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"
                                >Nama Ruas Jalan</label
                            >
                            <div class="col-md-9">
                                <select
                                    id="id_ruas_jalan"
                                    name="id_ruas_jalan"
                                    class="form-control searchableModalField"
                                    required
                                >
                                    @foreach ($ruas_jalan_lists as $data)
                                    <option
                                        value="{{ $data->id_ruas_jalan }}"
                                        w
                                    >
                                        {{ $data->nama_ruas_jalan }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-3 col-form-label"
                                >File Excel</label
                            >
                            <div class="col-md-9">
                                <input
                                    type="file"
                                    name="survei_excel"
                                    class="form-control formatLatLong"
                                    required
                                />
                            </div>
                            <label class="col-md-3 col-form-label"
                                >Hapus masal?</label
                            >
                            <div class="col-md-9 mt-2">
                                <label class="radio-inline">
                                    <input
                                        type="radio"
                                        name="is_deleted"
                                        value="Y"
                                    />
                                    Ya
                                </label>
                                <label class="radio-inline">
                                    <input
                                        type="radio"
                                        name="is_deleted"
                                        value="N"
                                        checked
                                    />
                                    Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                        >
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Import
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data Survei Kondisi Jalan</h4>
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
        $("#surveikondisijalan-table").DataTable();
    });

    $("#delModal").on("show.bs.modal", function (event) {
        const link = $(event.relatedTarget);
        const id = link.data("id");
        console.log(id);
        const url =
            `{{ url('admin/input-data/survei_kondisi_jalan/delete') }}/` + id;
        console.log(url);
        const modal = $(this);
        modal.find(".modal-footer #delHref").attr("href", url);
    });
</script>
@endsection
