@extends('admin.layout.index')

@section('title') Edit User @endsection
@section('head')
<link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">

@endsection
@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Edit User</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('getMasterUser') }}">User</a> </li>
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
                <h5>Edit Data User</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">

                <form action="{{ route('updateUser') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$users->id}}">

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Email</label>
                        <div class="col-md-10">
                            <input name="email" type="email" class="form-control" value="{{$users->email}}" required>
                            <small class="form-text text-muted">Tidak bisa diedit</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Password</label>
                        <div class="col-md-10">
                            <div class="row" style="margin-left: 0px; margin-right: 0px;">
                                <input id="password-field" name="password" type="password" class="form-control">
                                <span style="cursor: pointer; margin-left: -30px;" class="ti-eye my-auto toggle-password" toggle="#password-field"></span>
                            </div>
                            <small class="form-text text-muted">Kosongkan jika tidak akan merubah password</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Nama Lengkap</label>
                        <div class="col-md-10">
                            <input name="name" type="text" class="form-control" value="{{$users->name}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">NIP/NIK</label>
                        <div class="col-md-10">
                            <input name="no_pegawai" type="text" class="form-control" value="{{@$users->pegawai->no_pegawai}}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">No. Telp/HP</label>
                        <div class="col-md-10">
                            <input name="no_tlp" type="text" class="form-control" value="{{@$users->pegawai->no_tlp}}" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">SUP</label>
                        <div class="col-md-10">
                            <select class="form-control searchableField" name="sup_id" id="sup_id" onchange="ubahOption1()">
                                <option value=",">Pilih SUP</option>
                                @foreach ($input_sup as $data)
                                <option value="{{ $data->kd_sup }}" @if($users->sup_id == $data->id) selected @endif>{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Ruas Jalan</label>
                        <div class="col-md-10">
                            <select data-placeholder="Ruas jalan" id="ruas_jalan_chosen" class="form-control chosen-select" multiple name="ruas_jalan[]" tabindex="4">
                                <option value=" ">Pilih Ruas</option>
                                    @foreach ($input_ruas_jalan as $data)
                                    <option value="{{$data->id}}" @if(@$users->ruas && in_array($data->id,array_column( @$users->ruas->toArray(), 'id'))) selected @endif>{{@$data->nama_ruas_jalan}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Pilih Jabatan</label>
                        <div class="col-md-10">
                            <select class="form-control searchableField" required name="internal_role_id">
                                <option>Pilih Jabatan</option>
                                @foreach ($role as $data)
                                <option value="{{$data->id}}" @if($user->internal_role_id == $data->id) selected @endif>{{$data->role}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if (Auth::user()->id == 1)
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Role</label>
                        <div class="col-md-10">
                            <select class="form-control searchableField" required name="role">
                                <option value="internal" @if($user->role == 'internal') selected @endif>Internal</option>
                                <option value="masyarakat" @if($user->role == 'masyarakat') selected @endif>Masyarakat</option>
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">Blokir</label>
                        <div class="col-md-10">
                            <label class="radio-inline">
                                <input type="radio" name="blokir" value="Y" @if($user->blokir=='Y') checked @endif> Y
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="blokir" value="N" @if($user->blokir=='N') checked @endif> N
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-mat btn-success">Simpan Perubahan</button>
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
<script type="text/javascript" src="{{ asset('assets/vendor/chosen_v1.8.7/chosen.jquery.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $(".chosen-select").chosen( { width: '100%' } );
    });

    function ubahOption1() {

    //untuk select SUP
    id = document.getElementById("sup").value

    //untuk select Ruas
    url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalanBySup') }}"
    id_select = '#ruas_jalan'
    text = 'Pilih Ruas Jalan'
    option = 'nama_ruas_jalan'
    id_ruass = 'id_ruas_jalan'

    setDataSelect(id, url, id_select, text, id_ruass, option)
    }
    function setDataSelectChosen(id, url, id_select, text, valueOption, textOption) {
            $.ajax({
                url: url,
                method: "get",
                dataType: "JSON",
                data: {
                    id: id,
                },
                complete: function(result) {

                    $(id_select).empty(); // remove old options
                    $(id_select).append($("<option disable></option>").text(text));
                    let i = 0;
                    result.responseJSON.forEach(function(item) {
                        $(id_select).append(
                            $("<option></option>")
                            .attr("value", item[valueOption])
                            .text(item[textOption])
                        )
                        i++

                    });

                    if(i === result.responseJSON.length){

                        $(id_select).chosen("destroy")
                        $(id_select).chosen()
                    }

                },
            });
        }
        function ubahOption1() {

            //untuk select SUP
            id = document.getElementById("sup_id").value

            //untuk select Ruas
            url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalanBySup') }}"
            id_select = '#ruas_jalan_chosen'
            text = 'Pilih Ruas Jalan'
            option = 'nama_ruas_jalan'
            id_ruass = 'id'


            setDataSelectChosen(id, url, id_select, text, id_ruass, option)


        }
</script>
<script>
    $(".toggle-password").click(function() {
        $(this).toggleClass("ti-eye ti-lock");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
@endsection
