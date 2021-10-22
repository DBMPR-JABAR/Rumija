@extends('admin.layout.index')

@section('title') Ruas Jalan @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">

<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">

<style>
    table.table-bordered tbody td {
        word-break: break-word;
        vertical-align: top;
    }
</style>
@endsection

@section('page-header')
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
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Kondisi Jalan</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Tabel Kondisi Jalan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if (hasAccess(Auth::user()->internal_role_id, "Kondisi Jalan", "Create"))
                <a href="{{ route('addIDKondisiJalan') }}" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="kondisijalan-table" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Ruas Jalan</th>
                                <th>Nama Kota</th>
                                <th>KM Asal</th>
                                <th>Panjang KM</th>
                                <th>dari KM</th>
                                <th>Sampai dengan KM</th>
                                <th>Lebar rata2 (meter)</th>
                                <th>Dokumentasi</th>
                                <th style="min-width: 75px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                          <!--   @foreach ($kondisiJalan as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$data->ruas_jalan}}</td>
                                <td>{{$data->nama_kota}}</td>
                                <td>{{$data->km_asal}}</td>
                                <td>{{$data->panjang_km}}</td>
                                <td>{{$data->dari_km}}</td>
                                <td>{{$data->sampai_km}}</td>
                                <td>{{$data->lebar_rata_rata}}</td>
                                <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/'.$data->foto_dokumentasi) !!}" alt="" srcset=""></td>
                                <td style="min-width: 75px;">
                                    <div class="btn-group " role="group" data-placement="top" title="" data-original-title=".btn-xlg">
                                        @if (hasAccess(Auth::user()->internal_role_id, "Kondisi Jalan", "Update"))
                                        <a href="{{ route('editIDKondisiJalan',$data->id) }}"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Edit"><i class="icofont icofont-pencil"></i></button></a>
                                        @endif
                                        @if (hasAccess(Auth::user()->internal_role_id, "Kondisi Jalan", "Delete"))
                                        <a href="#delModal" data-id="{{$data->id}}" data-toggle="modal"><button class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
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
                    <h4 class="modal-title">Hapus Data Kondisi Jalan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="delHref" href="" class="btn btn-danger waves-effect waves-light ">Hapus</a>
                </div>

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
<script>
    $(document).ready(function() {
        // $("#dttable").DataTable();
        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/input-data/kondisi-jalan/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

        let table = $('#kondisijalan-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 'kondisi-jalan/json',
            columns: [
                {'mRender': function (data, type, full,meta) {
                    return +meta.row +1;
                    }
                },
                { data: 'ruas_jalan', name: 'ruas_jalan' },
                { data: 'nama_kota', name: 'nama_kota' },
                { data: 'km_asal', name: 'km_asal' },
                { data: 'panjang_km', name: 'panjang_km' },
                { data: 'dari_km', name: 'dari_km' },
                { data: 'sampai_km', name: 'sampai_km' },
                { data: 'lebar_rata_rata', name: 'lebar_rata_rata' },
                // { data: 'dokumentasi', name: 'dokumentasi' },
                {'mRender': function (data, type, full) {
                    return '<img class="img-fluid" style="max-width: 100px" src="'+`{{ url('storage/') }}` +'/'+full['foto_dokumentasi']+'" alt="" srcset="">';
                    }
                },
                { data: 'action', name: 'action' },
            ]
        });

        table.on( 'order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>
@endsection
