@extends('admin.layout.index')

@section('title')Role Akses @endsection
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
                <h4>Role Akses </h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Role Akses</a> </li>
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
                <h4 class="card-title">Tambah Role Access</h4>
                {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <form action="{{route('storeRoleAccess')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">User Role</label>
                        <div class="col-md-9">

                            <select class="form-control"  name="user_role" tabindex="4" required>
                                @forelse ($user_role as $data)
                                <option value="{{$data->id}}">{{$data->role}}</option>
                                @empty
                                <option disabled value="" selected>Semua Role Terisi</option>
                                @endforelse
                            </select>
                        </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Menu</label>
                    <div class="col-md-9">
                        <select data-placeholder="User Role..." class="chosen-select " multiple  name="menu[]" tabindex="4" required>
                        @foreach($menu as $data)
                            <option value="{{$data->menu}}.Create" @if($data->menu == "Profil") selected @endif>{{$data->menu}}.Create</option>
                            <option value="{{$data->menu}}.View" @if($data->menu == "Profil") selected @endif>{{$data->menu}}.View</option>
                            <option value="{{$data->menu}}.Update" @if($data->menu == "Profil") selected @endif>{{$data->menu}}.Update</option>
                            <option value="{{$data->menu}}.Delete" @if($data->menu == "Profil") selected @endif>{{$data->menu}}.Delete</option>
                        @endforeach
                        </select>
                    </div>
                    
                </div>

                {{-- <div class="form-group row">
                    <label class="col-md-3 col-form-label">Role Access</label>
                    <div class="col-md-9">
                        <select data-placeholder="User Role..." class="chosen-select" multiple  name="role_access[]" tabindex="4" required>
                            <option value="Create" >Create</option>
                            <option value="View">View</option>
                            <option value="Update">Update</option>
                            <option value="Delete">Delete</option>
                        </select>
                    </div>
                </div> --}}

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">UPTD Access</label>
                    <div class="col-md-9">
                        {{-- <select data-placeholder="UPTD Access..." class="chosen-select" multiple name="uptd_access[]" tabindex="4" required>
                            <option value="1">UPTD 1</option>
                            <option value="2">UPTD 2</option>
                            <option value="3">UPTD 3</option>
                            <option value="4">UPTD 4</option>
                            <option value="5">UPTD 5</option>
                            <option value="6">UPTD 6</option>
                        </select> --}}

                        @foreach ($input_uptd_lists as $no => $uptd_list)
                            <input type="checkbox" class="custom-checkbox" name="uptd_access[]" value="{{ $uptd_list->id }}" id="uptd_{{ $uptd_list->id }}" >{{ $uptd_list->nama }}&nbsp;<br>
                        @endforeach
                        <br>
                            <i style="color :red; font-size: 10px;">Wajib isi</i>
                    </div>
                </div>
                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect " data-dismiss="modal">Kembali</button></a>
                <button type="submit" class="btn btn-primary waves-effect waves-light ">Simpan</button>
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
        $(".chosen-jenis-instruksi").chosen( { width: '100%' } );


    });
</script>
@endsection
