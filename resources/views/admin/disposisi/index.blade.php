@extends('admin.layout.index')

@section('title') Kirim Disposisi  @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">
<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">

<style>
.chosen-container.chosen-container-single {
    width: 300px !important; /* or any value that fits your needs */
}

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
                <h4>Kirim Disposisi </h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Disposisi</a> </li>
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
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Kirim</a>
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Status</th> <th>Surat Dari</th>
                                <th>Perihal</th>
                                 <th>No Surat</th>
                                 <th>Tgl Surat</th>
                                <th>Disposisi</th>
                                <th>Tgl Deadline</th>

                                <th>Created Date</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            @foreach ($disposisi as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td><?php

if($data->status == "1")  {
    echo '<button class="  btn btn-inverse btn-mini btn-round">Submitted</button> ';
} else if($data->status == "2") {
    echo '<button class="btn btn-primary btn-mini btn-round">Accepted</button> ';
}  else if($data->status == "3") {
    echo '<button class="btn btn-success  btn-mini btn-round">On Progress</button> ';

} else if($data->status == "4") {

    echo '<button class="btn btn-info  btn-mini btn-round">Finish</button> ';

}

                                 ?></td>

                                <td>{{$data->dari}}</td>
                                <td>{{$data->perihal}}</td>
                                <td>{{$data->no_surat}}</td>
                                <td>
                                <?php $date_tgl_surat = date_create($data->tgl_surat);?>

                                {{ date_format($date_tgl_surat, 'd-m-Y')}}</td>
                                <td>
                                 @php   $inouts = App\Model\Transactional\DisposisiPenanggungJawab::where('disposisi_code',$data->disposisi_code)->get() @endphp
                                @foreach($inouts as $inout)
                                <span > {{!empty($inout->keterangan_role->keterangan) ?  $inout->keterangan_role->keterangan: "-"  }}</span><br/>
                                @endforeach
                                </td>
                                <td>
                                <?php
                                 $date_tanggal_penyelesaian = date_create($data->tanggal_penyelesaian);?>

                                {{ date_format($date_tanggal_penyelesaian, 'd-m-Y') }}
                                </td>
                                <td>
                                <?php $date_create_date = date_create($data->created_date);?>
                                {{ date_format($date_create_date, 'd-m-Y H:i:s')}}
                                    </td>
                                <td>
                                <a type="button" href="{{ route('getdetailDisposisi',$data->id) }}"  class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Rincian</a>
                                <a type="button" href="{{ route('editDisposisi',$data->id) }}" class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-pencil"></i>Edit</a>
                                <a type="button"href="#delModal"  data-toggle="modal" data-id="{{$data->id}}"     class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Hapus</a>
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
                    <h4 class="modal-title">Hapus Data Disposisi</h4>
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

    <div class="modal fade" id="acceptModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Disposisi Diterima?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin menerima disposisi ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="delHref" href="" class="btn btn-danger waves-effect waves-light ">Terima</a>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="disposisiModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Disposisi Diterima?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin menerima disposisi ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="delHref" href="" class="btn btn-danger waves-effect waves-light ">Terima</a>
                </div>

            </div>
        </div>
    </div>



</div>

<div class="modal-only">
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('saveInsertDisposisi')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Disposisi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-5">

                    <div class="form-group row">
                            <label class="col-md-3 col-form-label">Dari</label>
                            <div class="col-md-9">
                                <input name="dari" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Perihal</label>
                            <div class="col-md-9">
                                <textarea name="perihal" type="text" class="form-control" required></textarea>                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tgl Surat</label>
                            <div class="col-md-9">
                                <input name="tgl_surat" type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No Surat</label>
                            <div class="col-md-9">
                                <input name="no_surat" type="text" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Disposisikan Kepada</label>
                            <div class="col-md-9">
                            <select data-placeholder="Disposisikan Kepada..." class="chosen-select" multiple  name="target_disposisi[]" tabindex="4">
                                 <?php echo $disposisi_kepada;?>
                            </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Jenis Instruksi</label>
                            <div class="col-md-9">
                            <select data-placeholder="Jenis Instruksi..." class="chosen-jenis-instruksi" multiple name="jenis_instruksi[]" tabindex="4">
                                 <?php echo $jenis_instruksi_select;?>
                            </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tanggal Penyelesaian</label>
                            <div class="col-md-9">
                                <input name="tanggal_penyelesaian" type="date" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">File Lampiran</label>
                            <div class="col-md-9">
                                <input name="file" type="file"  class="form-control" required>
                            </div>
                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data Disposisi</h4>
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
    </li>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/vendor/chosen_v1.8.7/chosen.jquery.js') }}" type="text/javascript"></script>

<script>
    $(document).ready(function() {
        $(".chosen-select").chosen( { width: '100%' } );
        $(".chosen-jenis-instruksi").chosen( { width: '100%' } );
        $("#dttable").DataTable();
        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/master-data/ruas-jalan/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

        $('#acceptModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');

            console.log(id);
            const url = `{{ url('admin/disposisi/accepted') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

    });
</script>
@endsection
