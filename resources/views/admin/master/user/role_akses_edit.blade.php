@extends('admin.layout.index')

@section('title')Role Akses @endsection
@section('head')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">
    <link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">



    <style>
        .chosen-container.chosen-container-single {
            width: 300px !important;
            /* or any value that fits your needs */
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
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
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
                    <h4 class="card-title">Edit Role Access</h4>
                    {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <form action="{{ route('updateRoleAccess', $alldata['role_id']) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        {{-- @method('PUT') --}}
                        <div class="modal-body p-5">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">User Role</label>
                                <div class="col-md-10">
                                    @foreach ($user_role as $data)

                                        <select class="form-control" name="user_role" tabindex="4" required>
                                            <option value="{{ $data->id }}" checked>{{ $data->role }}</option>
                                        </select>
                                    @endforeach

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">UPTD Access</label>
                                <div class="col-md-10">

                                    @foreach ($input_uptd_lists as $no => $uptd_list)
                                        @foreach ($alldata['uptd_akses'] as $item)
                                            @php
                                                $act = ' ';
                                                if ($item == $uptd_list->id) {
                                                    $act = 'Checked';
                                                    break;
                                                }
                                            @endphp
                                        @endforeach
                                        <input type="checkbox" class="custom-checkbox" name="uptd_access[]"
                                            value="{{ $uptd_list->id }}" id="uptd_{{ $uptd_list->id }}"
                                            {{ $act }}>{{ $uptd_list->nama }}<br>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-12 col-form-label font-weight-bold">Menu </label>
                                {{-- <div class="col-md-10">
                                <select data-placeholder="User Role..." class="chosen-select" multiple  name="menu[]" tabindex="4" required>
                                @foreach ($menu as $data)
                                    <option value="{{$data->menu}}.Create" >{{$data->menu}}.Create</option>
                                    <option value="{{$data->menu}}.View">{{$data->menu}}.View</option>
                                    <option value="{{$data->menu}}.Update">{{$data->menu}}.Update</option>
                                    <option value="{{$data->menu}}.Delete">{{$data->menu}}.Delete</option>
                                @endforeach
                                </select>
                            </div> --}}
                                @php
                                    $pointer = 0;
                                    $pointer1 = $pointer;
                                    $pointer2 = count($alldata['menu_test']);
                                    // echo $pointer2;

                                @endphp

                                <div class="row">
                                    {{-- @foreach ($alldata['menu'] as $data)

                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <label class="form-check-label">
                                                @foreach ($alldata['permissions'] as $item)

                                                    @php
                                                        if (strpos($item, $data) !== false) {
                                                            $i = 'checked';
                                                            break;
                                                        } else {
                                                            $i = '';
                                                        }
                                                    @endphp
                                                @endforeach
                                                <input type="checkbox" class="form-check-input" name="menu[]" value="{{ $data }}"{{ $i }}>&nbsp;{{ $data }}

                                            </label>
                                        </div>

                                    @endforeach --}}
                                    @foreach ($cekopoint as $nos => $items)
                                    <label class="col-md-12 col-form-label text-center font-weight-bold">
                                        <input id="{{'_checkall_'.$nos}}" type="checkbox" class="form-check-input" name="select_all">&nbsp;Pilih semua {{ $items->nama_menu }} </label>
                                        @foreach ($alldata['menu_test'] as $data)

                                            @if($data['nama_menu'] == $items->nama_menu )
                                                <div class="col-sm-12 col-md-6 col-lg-4">
                                                    <label class="form-check-label">
                                                        @foreach ($alldata['permissions'] as $item)

                                                            @php
                                                                if (strpos($item, $data['nama']) !== false) {
                                                                    $i = 'checked';
                                                                    break;
                                                                } else {
                                                                    $i = '';
                                                                }
                                                            @endphp
                                                        @endforeach

                                                        <input id="{{implode('_',explode(' ',$data['nama'])).'_'.$nos}}" type="checkbox" class="form-check-input" name="menu[]" value="{{ $data['nama'] }}"{{ $i }}>&nbsp;{{ $data['nama'] }}


                                                    </label>
                                                </div>

                                            @endif
                                        @endforeach
                                    @endforeach

                                </div>
                            </div>
                            {{-- <div class="form-group row">
                            <label class="col-md-2 col-form-label">Role Access</label>
                            <div class="col-md-10">
                                <select data-placeholder="User Role..." class="chosen-select" multiple  name="role_access[]" tabindex="4" required>
                                    <option value="Create" >Create</option>
                                    <option value="View">View</option>
                                    <option value="Update">Update</option>
                                    <option value="Delete">Delete</option>
                                </select>
                            </div>
                        </div> --}}
                        </div>
                        {{-- <div class="clearfix"></div> --}}
                        <div class="modal-footer p-5">
                            <a href="{{ route('getRoleAkses') }}"><button type="button" class="btn btn-danger waves-effect "
                                    data-dismiss="modal">Kembali</button></a>
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
    <script type="text/javascript" src="{{ asset('assets/vendor/chosen_v1.8.7/chosen.jquery.js') }}"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen({
                width: '100%'
            });
            $(".chosen-jenis-instruksi").chosen({
                width: '100%'
            });


        });

        $(document).ready(()=>{
            const cekopoint = @json($cekopoint);
            const allData = @json($alldata['menu_test']);
            const permission = @json($alldata['permissions']);
            cekopoint.forEach((data,key)=> {
                const selectAllButton = document.getElementById(`_checkall_${key}`)
                const menus = allData.filter(permission=> permission.nama_menu === data.nama_menu)
                const ids = menus.map(menu => {
                       return {
                           id: String(menu.nama).split(' ').join('_')+'_'+key
                       }
                   })
                   let checkedAll = true;
                   ids.forEach(id => {
                       if(document.getElementById(id.id).checked === false) checkedAll = false
                   })

                   selectAllButton.checked = checkedAll
               selectAllButton.onchange = (event) => {
                   console.log(event.target.checked)
                   ids.forEach(id=>{
                       document.getElementById(id.id).checked = event.target.checked
                   })
               }
            })
        })

    </script>
@endsection
