@extends('admin.layout.index')

@section('title') Permohonan Rumija @endsection
@section('head')
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
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
                <h4>Permohonan Rumija</h4>
                <span>Data Permohonan Rumija</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Permohonan Rumija</a> </li>
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
                <h5>Tabel Permohonan Rumija</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                @if (hasAccess(Auth::user()->internal_role_id, 'Permohonan Rumija', 'Create'))
                <a href="{{ route('permohonan_rumija.create') }}" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="permohonan_rumija-table" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Surat</th>
                                <th>Nama Pemohon</th>
                                <th>Perihal</th>
                                <th>Tanggal</th>
                                <th style="min-width: 75px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            @foreach ($permohonan_rumija as $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $data->nomor }}</td>
                                <td>{{ $data->nama_pemohon }}</td>
                                <td>{{ $data->perihal }}</td>
                                <td>{{ $data->tanggal }}</td>
                                <td style="min-width: 75px;">
                                    <div class="btn-group " role="group" data-placement="top" title=""
                                        data-original-title=".btn-xlg">
                                        @if (hasAccess(Auth::user()->internal_role_id, 'Permohonan Rumija', 'Update'))
                                        <a href="{{ route('permohonan_rumija.edit', $data->id) }}"><button
                                                class="btn btn-primary btn-sm waves-effect waves-light"
                                                data-toggle="tooltip" title="Edit"><i
                                                    class="icofont icofont-pencil"></i></button></a>
                                        @endif
                                        @if (hasAccess(Auth::user()->internal_role_id, 'Permohonan Rumija', 'Delete'))
                                        <a href="#delModal" data-id="{{ $data->id }}" data-toggle="modal"><button
                                                class="btn btn-danger btn-sm waves-effect waves-light"
                                                data-toggle="tooltip" title="Hapus"><i
                                                    class="icofont icofont-trash"></i></button></a>
                                        @endif
                                        <a href="{{route('surat_permohonan_rumija', $data->id)}}" >
                                            <button class="btn btn-success btn-sm waves-effect waves-light"
                                                data-toggle="tooltip" title="Download surat permohonan"><i
                                                    class="icofont icofont-download"></i></button></a>
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
                    <h4 class="modal-title">Hapus Data Permohonan Rumija</h4>
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
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>

<script>
    $(document).ready(function() {
            $('#permohonan_rumija-table').DataTable();
        });

        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/input-data/rumija/permohonan_rumija/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

</script>
@endsection
