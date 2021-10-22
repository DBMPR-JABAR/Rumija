@extends('admin.layout.index')

@section('title') Data Foto Jembatan @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Hapus Foto Jembatan</h4>
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
                <li class="breadcrumb-item"><a href="#">Hapus Foto</a> </li>
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
                <h5>Hapus Foto Jembatan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Foto Jembatan</label>
                        </div>
                        @foreach($foto as $i => $data)

                            <div class ="row" style="border: 1px solid;border-radius: 25px">
                               <div class="col-md-4">
                                    <input type="hidden" name="id_j[]" class="form-control m-input" value="{{$data->id}}" required>
                                   <h5>{{$data->nama}}</h5>
                                </div>
                                <div class="col-md-6">
                                    <img src="/storage/{{$data->foto}}" width="100%" height="200px">

                                </div>
                                <div class="col-md-2">
                                   <!--  <button type="submit" class="btn btn-mat btn-danger" style="vertical-align: middle;">Hapus</button> -->
                                   <a href="#delPhotoModal" data-id="{{$data->id}}" data-toggle="modal"><button data-toggle="tooltip" title="Hapus" class="btn btn-danger btn-sm waves-effect waves-light">Hapus</button></a>

                                </div>
                            </div><br>
                         @endforeach
                          <div class=" form-group row">
                        <a href="{{ route('getMasterJembatan') }}"><button type="button" class="btn btn-info waves-effect">Close</button></a>
                    </div>
                        <!--  <div class="modal-footer">
                            <a href="/" class="btn btn-mat btn-info">Kembali</button>
                        </div> -->

            </div>
        </div>
    </div>
</div>

 @if (hasAccess(Auth::user()->internal_role_id, "Jembatan", "Delete"))
    <div class="modal fade" id="delPhotoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Hapus Photo Jembatan</h4>
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

    $("#addRow").click(function () {
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
    $(document).on('click', '#removeRow', function () {
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
</script>
@endsection
