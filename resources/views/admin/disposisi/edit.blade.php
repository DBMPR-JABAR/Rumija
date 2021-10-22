@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Edit Informasi Disposisi</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('daftar-disposisi') }}">Daftar disposisi</a> </li>
                <li class="breadcrumb-item"><a href="#">Edit</a> </li>
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

                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block pl-5 pr-5 pb-5">

            <form action="{{route('updateDisposisi')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Disposisi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-5">

                    <div class="form-group row">
                            <label class="col-md-3 col-form-label">Dari</label>
                            <div class="col-md-9">
                             <input type="hidden" name="id" value="{{ $disposisi->id }}" />
                                <input name="dari" type="text" class="form-control" value="{{ $disposisi->dari }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Perihal</label>
                            <div class="col-md-9">

                                <textarea name="perihal" type="text" class="form-control"  required>{{ $disposisi->perihal }}</textarea>                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tgl Surat</label>
                            <div class="col-md-9">
                                <input name="tgl_surat" type="date" class="form-control" value="{{ $disposisi->tgl_surat }}"  required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No Surat</label>
                            <div class="col-md-9">
                                <input name="no_surat" type="text" class="form-control" value="{{ $disposisi->no_surat }}" required>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tanggal Penyelesaian</label>
                            <div class="col-md-9">
                                <input name="tanggal_penyelesaian" type="date" class="form-control" value="{{ $disposisi->tanggal_penyelesaian }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">File Lampiran</label>
                            <div class="col-md-9">
                                <input name="file" type="file"  class="form-control" >
                                <small class="form-text text-muted">Kosongkan jika tidak akan merubah foto jembatan</small>
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
    // Format mata uang.
    $('.formatRibuan').mask('000.000.000.000.000', {
        reverse: true
    });

    function ubahDataSUP() {

        val = document.getElementById("uptd_id").value

        $.ajax({
            url: "{{ url('admin/master-data/ruas-jalan/getSUP') }}",
            method: 'get',
            dataType: 'JSON',
            data: {
                id: val
            },
            complete: function(result) {
                $('#sup').empty(); // remove old options
                $('#sup').append($("<option></option>").text('Pilih SUP'));

                result.responseJSON.forEach(function(item) {
                    $('#sup').append($("<option></option>").attr("value", item["name"]).text(item["name"]));
                });
            }
        });
    }
</script>
@endsection
