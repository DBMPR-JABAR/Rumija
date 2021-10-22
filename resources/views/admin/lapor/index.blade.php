@extends('admin.layout.index')

@section('title') Daftar Laporan @endsection
@section('head')
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">

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
                <h4>Daftar Laporan</h4>
                <span>Data Seluruh Laporan</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Daftar Laporan</a> </li>
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
                <h5>Tabel Daftar Laporan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <!-- <a href="{{ route('addLapor') }}" class="btn btn-mat btn-primary mb-3">Tambah</a> -->
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Pengaduan</th>
                                <th>No Pengaduan</th>
                                <th>Status</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Alamat</th>
                                <th>Telp</th>
                                <th>Email</th>
                                <th>Jenis</th>
                                <th>Deskripsi</th>
                                <th>UPTD</th>
                                <th>Foto</th>
                                <th style="min-width: 135px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aduan as $no => $item)

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
                    <h4 class="modal-title">Hapus Data Laporan</h4>
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


    <div class="modal fade" id="jqrModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Perbaharui Status Laporan JQR</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Status </label>
                        <div class="col-md-10">
                            <select id="status_jqr" class="form-control" name="status">
                                <option value="1">Submitted</option>
                                <option value="2">(Approved) Diverifikasi</option>
                                <option value="4">(Progress) Dalam Proses Survei</option>
                                <option value="5">(Progress) Dalam Rencana Tindakan</option>
                                <option value="6">(Progress) Dalam Proses Tindakan</option>
                                <option value="7">(Done) Selesai</option>
                                <option value="3">(Done) Aduan Ditolak</option>
                            </select>
                            <!-- <input name="status" type="text" class="form-control" disabled required> -->
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="jqrUpdateHref" href="" class="btn btn-success waves-effect waves-light ">Perbaharui</a>
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
            const url = `{{ url('admin/lapor/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });


        $('#jqrModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('no_pengaduan');
            const oldstatus = link.data('status_jqr')
            const status = document.getElementById('status_jqr')
            console.log(id,oldstatus);
            status.value = oldstatus
            let url = `{{ url('admin/lapor/update') }}/` + id+'/'+status.value;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #jqrUpdateHref').attr('href', url);
            status.onchange = (e) => {
                url = `{{ url('admin/lapor/update') }}/` + id+'/'+e.target.value;
                console.log(url)
                modal.find('.modal-footer #jqrUpdateHref').attr('href', url);
            }
        });

        var table = $('#dttable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('admin/lapor/json') }}",
            order: [[ 1, "desc" ]],
            columns: [{
                    'mRender': function(data, type, full, meta) {
                        return +meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'nomorPengaduan',
                    name: 'nomorPengaduan'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'nik',
                    name: 'nik'
                },
                {
                    data: 'alamat',
                    name: 'alamat'
                },
                {
                    data: 'telp',
                    name: 'telp'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'jenis',
                    name: 'jenis'
                },
                {
                    data: 'deskripsi',
                    name: 'deskripsi'
                },
                {
                    data: 'uptd_id',
                    name: 'uptd_id'
                },
                {
                    data: 'imglaporan',
                    name: 'imglaporan'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@endsection
