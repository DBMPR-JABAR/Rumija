@extends('admin.layout.index')

@section('title')Permission @endsection
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
    <style>
        .switch {
          position: relative;
          display: inline-block;
          width: 60px;
          height: 34px;
        }
        
        .switch input { 
          opacity: 0;
          width: 0;
          height: 0;
        }
        
        .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          -webkit-transition: .4s;
          transition: .4s;
        }
        
        .slider:before {
          position: absolute;
          content: "";
          height: 26px;
          width: 26px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          -webkit-transition: .4s;
          transition: .4s;
        }
        
        input:checked + .slider {
          background-color: #2196F3;
        }
        
        input:focus + .slider {
          box-shadow: 0 0 1px #2196F3;
        }
        
        input:checked + .slider:before {
          -webkit-transform: translateX(26px);
          -ms-transform: translateX(26px);
          transform: translateX(26px);
        }
        
        /* Rounded sliders */
        .slider.round {
          border-radius: 34px;
        }
        
        .slider.round:before {
          border-radius: 50%;
        }
    </style>
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Permission </h4>

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Permission</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')

    <div class="row">
        <div class="col-xl-7 col-md-7">
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
                    <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>

                    {{-- <a href="{{ route('createRoleAccess') }}" class="btn btn-mat btn-primary mb-3">Tambah</a> --}}
                    <div class="dt-responsive table-responsive ">
                        <table id="dttable" class="table table-striped table-bordered able-responsive ">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="50%">Nama</th>
                                    {{-- <th>Menu</th> --}}
                                    <th>Menu</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody id="bodyJembatan">

                                @foreach ($permission as $no => $data)
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $data->nama }}</td>
                                        {{-- <td>{{$data['permissions']}}</td> --}}
                                        <td>{{ $data->nama_menu }}</td>
                                        <td>

                                            <a type='button' href='#editModal' data-toggle='modal'
                                                data-id='{{ $data->id }}' data-nama='{{ $data->nama }}'
                                                data-nama_menu='{{ $data->nama_menu }}' data-user_id='{{ $data->view_user_id }}'
                                                class='btn btn-warning btn-mini waves-effect waves-light'><i
                                                    class='icofont icofont-edit'></i></a>
                                            @if (Auth::user() && Auth::user()->id == $data->created_by)
                                                <br>
                                                <a type='button' href='#delModal' data-toggle='modal'
                                                    data-id='{{ $data->id }}'
                                                    class='btn btn-danger btn-mini waves-effect waves-light mt-1'><i
                                                        class="icofont icofont-trash"></i></a><br />
                                            @endif
                                            {{-- <a type='button' href='#editModal'  data-toggle='modal' data-id='{{$data->id}}' data-uptd_access='{{$uptd_access[$i]}}'  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Edit</a> --}}

                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Menu</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <a data-toggle="modal" href="#addModal1" class="btn btn-mat btn-primary mb-3">Tambah</a>

                    <div class="dt-responsive table-responsive">
                        <table id="dttable1" class="table table-striped table-bordered able-responsive">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    {{-- <th>Menu</th> --}}
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody id="bodyJembatan">

                                @foreach ($menu as $no => $data)

                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $data->nama }}</td>

                                        <td>
                                            <a type='button' href='#editModal1' data-toggle='modal'
                                                data-id='{{ $data->id }}' data-nama='{{ $data->nama }}'
                                                class='btn btn-warning btn-mini waves-effect waves-light'><i
                                                    class='icofont icofont-edit'></i></a>
                                            {{-- <a type='button' href='#editModal'  data-toggle='modal' data-id='{{$data->id}}' data-uptd_access='{{$uptd_access[$i]}}'  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Edit</a> --}}
                                            <a type='button' href='#delModal1' data-toggle='modal'
                                                data-id='{{ $data->id }}'
                                                class='btn btn-danger btn-mini waves-effect waves-light'><i
                                                    class="icofont icofont-trash"></i></a><br />

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
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <form action="{{ route('createPermis') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Permission</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-5">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama</label>
                                <div class="col-md-9">
                                    <input type="text" name="nama" id="nama" placeholder="Masukan Permission"
                                        class="form-control" required></input>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Menu</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="menu" name="menu" required>
                                        <option>Pilih Menu</option>
                                        @foreach ($menu as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}">
                                                {{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Hanya Dilihat Oleh Super Admin</label>
                                <div class="col-md-9">
                                    
                                    <label class="switch">
                                        <input type="checkbox" name="lihat_admin">
                                        <span class="slider round"></span>
                                    </label>

                                    
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
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ route('updatePermis') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Permission</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-5">
                            <input type="text" name="id" id="id_permission_edit" class="form-control" hidden></input>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama</label>
                                <div class="col-md-9">
                                    <input type="text" id="nama_permission" placeholder="Masukan Permission" class="form-control"
                                        readonly></input>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Menu</label>
                                <div class="col-md-9">
                                    <select class="form-control" id="menu_permission_edit" name="menu" required>
                                        <option>Pilih Menu</option>
                                        @foreach ($menu as $data)
                                            <option value="{{ $data->id }}" id="{{ $data->id }}">
                                                {{ $data->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Hanya Dilihat Oleh Super Admin</label>
                                <div class="col-md-9">
                                    
                                    <label class="switch">
                                        <input type="checkbox" id="super_admin" name = "lihat_admin">
                                        <span class="slider round"></span>
                                    </label>

                                    
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
        <div class="modal fade" id="addModal1" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <form action="{{ route('createMenu') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Menu</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-5">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Menu</label>
                                <div class="col-md-9">
                                    <input type="text" name="nama" placeholder="Masukan Nama Menu" class="form-control"
                                        required></input>
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
        <div class="modal fade" id="editModal1" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <form action="{{ route('updateMenu') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Menu</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-5">
                            <input type="text" name="id" id="id_menu" class="form-control" hidden></input>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Menu</label>
                                <div class="col-md-9">
                                    <input type="text" name="nama" id="nama_menu" placeholder="Masukan Nama Menu"
                                        class="form-control" required></input>
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
        <div class="modal fade" id="delModal1" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Menu</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus data ini?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                        <a id="delHref1" href="" class="btn btn-danger waves-effect waves-light ">Hapus</a>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Permission</h4>
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
            $("#dttable").DataTable();
            $("#dttable1").DataTable();

            $('#delModal').on('show.bs.modal', function(event) {
                const link = $(event.relatedTarget);
                const id = link.data('id');
                console.log(id);
                const url = `{{ url('admin/master-data/user/destroy-permission') }}/` + id;
                console.log(url);
                const modal = $(this);
                modal.find('.modal-footer #delHref').attr('href', url);
            });
            $('#editModal').on('show.bs.modal', function(event) {
                
                const link = $(event.relatedTarget);
                const id = link.data('id');
                // link.data('nama_menu')
                $('#id_permission_edit').val(link.data('id'));
                $('#nama_permission').val(link.data('nama'));
                const menu = @json($menu);
                const selectedMenu = menu.filter((item) => {
                    return item.nama == link.data('nama_menu')
                })
                if(selectedMenu.length > 0) {
                    // console.log(selectedMenu[0].id)
                    $('#menu_permission_edit').val(selectedMenu[0].id);
                }
                // if(link.data('user_id')){$('#super_admin').prop('checked',true) };
                link.data('user_id') ? $('#super_admin').prop('checked',true):$('#super_admin').prop('checked',false);
                // const baseUrl = `{{ url('admin/master-data/user/edit-permission') }}/` + id;
                // $.get(baseUrl, {
                //         id: id
                //     },
                //     function(response) {
                //         $('#id').val(response.permission[0].id);
                //         $('#nama').val(response.permission[0].nama);


                //     });

            });
            $('#editModal1').on('show.bs.modal', function(event) {

                const link = $(event.relatedTarget);
                // const id = link.data('id');
                // console.log(id);
                // const baseUrl = `{{ url('admin/master-data/user/edit-menu') }}/` + id;\
                $('#id_menu').val(link.data('id'));
                $('#nama_menu').val(link.data('nama'));
                // $.get(baseUrl, {
                //         id: id
                //     },
                //     function(response) {
                //         $('#nama').val(response.menu[0].nama);
                //     });

            });
            $('#delModal1').on('show.bs.modal', function(event) {
                const link = $(event.relatedTarget);
                const id = link.data('id');
                console.log(id);
                const url = `{{ url('admin/master-data/user/destroy-menu') }}/` + id;
                console.log(url);
                const modal = $(this);
                modal.find('.modal-footer #delHref1').attr('href', url);
            });

        });

    </script>
@endsection
