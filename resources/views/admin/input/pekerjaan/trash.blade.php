@extends('admin.layout.index')

@section('title') Pekerjaan @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">

<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
<link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css">

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
                    <h4>Pekerjaan

                    </h4>
                    <span>Data Pekerjaan</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('getDataPekerjaan')}}"> Pekerjaan </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Trash</a> </li>
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
                <h5 id="tbltitle">Tabel Pekerjaan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <a href="{{ route('getDataPekerjaan') }}" class="btn btn-mat btn-primary mb-3">Kembali</a>   
                
                <div class="dt-responsive table-responsive">
                    <table id="dttable"  class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Laporan</th>
                                <th>Tanggal</th>
                                <th>Nama Mandor</th>
                                <th>SUP</th>
                                <th>Ruas Jalan</th>
                                <th>Lokasi</th>
                                
                               
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                           
                            @foreach ($pekerjaan as $no => $data)
                                
                                <tr>
                                    <td>{{++$no}}</td>
                                    <td>{{$data->id_pek}}</td>
                                    <td>{{$data->tanggal}}</td>
                                    <td>{{$data->nama_mandor}}</td>
                                    <td>{{$data->sup}}</td>
                                    <td>{{$data->ruas_jalan}}</td>
                                   
                                    <td>{{$data->lokasi}}</td>
                                   
                                    <td>
                                        <a href="#restore" data-id="{{$data->id_pek}}" data-toggle="modal"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Hapus">Restore</button></a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $pekerjaan->withQueryString()->onEachSide(2)->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-only">
    <div class="modal fade" id="restore" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Kembalikan Data Pekerjaan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin mengembalikan data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="reHref" href="" class="btn btn-danger waves-effect waves-light ">Restore</a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <!-- <script src="{{ asset('assets/vendor/jquery/js/jquery-3.4.1.min.js') }}" ></script> -->
    <script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
    <script src="https://js.arcgis.com/4.18/"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

   <script>
        $(document).ready(function() {
           
            $("#dttable").DataTable(
                {
                "bInfo" : false
                }
            );
            $('#restore').on('show.bs.modal', function(event) {
                const link = $(event.relatedTarget);
                const id = link.data('id');
                console.log(id);
                const url = `{{ url('admin/input-data/pekerjaan/restore') }}/` + id;
                console.log(url);
                const modal = $(this);
                modal.find('.modal-footer #reHref').attr('href', url);
            });
            
            // $('select').attr('value').trigger('change');
        });

    
    </script>
@endsection