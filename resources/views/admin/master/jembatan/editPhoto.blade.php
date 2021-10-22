@extends('admin.layout.index')

@section('title') Edit Jembatan @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Edit Foto Jembatan</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getMasterJembatan') }}">Jembatan</a> </li>
                <li class="breadcrumb-item"><a href="#">Edit Foto</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Edit Foto Jembatan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <form action="{{ route('updatePhotoJembatan') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{$jembatan->id}}">


                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Foto Jembatan</label><br>
                    </div>
                    @foreach($foto as $i => $data)
                    <div class="row">
                        <div class="col-md-2 col-sm-12">
                            <img class="img-fluid" style="width: 100%;height: auto;object-fit: cover;" src="/storage/{{$data->foto}}" alt="" srcset="">
                        </div>
                        <div class="row col-md-10 col-sm-12  d-flex align-items-center">
                            <img class="img-thumbnail rounded mx-auto d-block text-center mb-2" src="{{ url('storage/'.$data->foto) }}" style="max-height: 400px">
                            <div id="inputFormRow" class="col-md-12 col-sm-12">
                                <div class="input-group">
                                    <input type="hidden" name="id_j[]" class="form-control m-input" value="{{$data->id}}" required>
                                    <input type="text" name="nama[]" class="form-control m-input" value="{{$data->nama}}" placeholder="Judul Foto" autocomplete="off" required>
                                    <input type="file" name="foto[]" class="form-control m-input" value="{{$data->foto}}" accept="image/*">
                                    <!-- <span> -->
                                    <?php if ($data->foto != '') {
                                        echo '<a href="#delPhotoModal" data-id="' . $data->id . '" data-toggle="modal"><button data-toggle="tooltip" title="Hapus Foto" class="btn btn-danger btn-sm waves-effect waves-light">Hapus</button></a>';
                                    } else {
                                        echo '<label class="label label-danger">Foto belum diupload</label>';
                                    } ?>
                                    <!-- </span> -->
                                </div>
                                <small class="form-text text-muted">Kosong form foto jika tidak akan merubah foto</small>
                            </div>
                        </div>
                    </div>

                    @endforeach
                    <hr>
                    <div id="newRow"></div>
                    <button id="addRow" type="button" class="btn btn-info">Tambah Foto Jembatan</button>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@if (hasAccess(Auth::user()->internal_role_id, "Jembatan", "Update") )
<div class="modal fade" id="delPhotoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Hapus Foto Jembatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Apakah anda yakin ingin menghapus foto ini?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                <a id="delHref" href="" class="btn btn-danger waves-effect waves-light ">Hapus</a>
            </div>

        </div>
    </div>
</div>
@endif

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
        // Format mata uang.
        $('.formatRibuan').mask('000.000.000.000.000', {
            reverse: true
        });

        // Format untuk lat long.
        $('.formatLatLong').keypress(function(evt) {
            return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
        });
    });

    $("#addRow").click(function() {
        var html = '';
        html += '<div id="inputFormRow">';
        html += '<div class="input-group">';
        html += '<input type="hidden" name="id_j[]" class="form-control m-input">';
        html += '<input type="text" name="nama[]" class="form-control m-input" placeholder="Enter title" autocomplete="off" required>';
        html += '<input type="file" name="foto[]" class="form-control m-input" accept="image/*" required>';
        html += '<div class="input-group-append">';
        html += '<button id="removeRow" type="button" class="btn btn-danger">Hapus</button>';
        html += '</div>';
        html += '</div>';


        $('#newRow').append(html);
    });

    // remove row
    $(document).on('click', '#removeRow', function() {
        $(this).closest('#inputFormRow').remove();
    });


    function ubahOption() {

        //untuk select SUP
        val = document.getElementById("uptd").value
        id = parseInt(val.slice(val.length - 1))

        url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
        id_select = '#sup'
        text = 'Pilih SUP'
        option = 'name'

        setDataSelect(id, url, id_select, text, option, option)

        //untuk select Ruas
        url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'

        setDataSelect(id, url, id_select, text, option, option)
    }

    $(document).ready(function() {
        // $("#dttable").DataTable();
        $('#delPhotoModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/master-data/jembatan/delPhoto') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });
        // Format mata uang.
        $('.formatRibuan').mask('000.000.000.000.000', {
            reverse: true
        });

        // Format untuk lat long.
        $('.formatLatLong').keypress(function(evt) {
            return (/^\-?[0-9]*\.?[0-9]*$/).test($(this).val() + evt.key);
        });
    });
</script>
@endsection
