@extends('admin.layout.index')

@section('title')Manajemen User @endsection
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
                <h4>Manajemen User </h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Manajemen User</a> </li>
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
                @if(Request::segment(5) == 'trash')
                <a href="{{ route('getMasterUser') }}" class="btn btn-mat btn-primary mb-3">Kembali</a>
                @else
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                    @if (Auth::user()->id == 1)
                    <a href="{{ route('getMasterUserTrash') }}" class="btn btn-mat btn-danger mb-3">Trash</a>   
                    @endif
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Email Verified At</th>
                                <th>Kode OTP</th>
                                <th>Role</th>
                                <th>Internal Role Id</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            @php
                                if(Auth::user() && Auth::user()->internalRole->uptd != null){
                                    $temporari = $user_lists_uptd;
                                }else{
                                    $temporari = $users;
                                }
                            @endphp
                             @foreach ($temporari as $data)
                             @php
                                $role = App\Model\Transactional\Role::find($data->internal_role_id);
                             @endphp
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{$data->name}}</td>
                                    <td>{{$data->email}}</td>
                                    <td>{{$data->email_verified_at}}</td>
                                    <td>{{$data->kode_otp}}</td>
                                    <td>{{$data->role}}</td>
                                    <td>{{$role->role ?? ''}}</td>
                                    <td>{{$data->created_at}}</td>
                                    <td>{{$data->updated_at}}</td>
                                    <td>
                                            
                                            @if(Request::segment(5) == 'trash')
                                            <a type='button' href='#restore'  data-toggle='modal' data-id='{{$data->id}}'     class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Restore</a><br/>
                                            <a type='button' href='#delPermanent'  data-toggle='modal' data-id='{{$data->id}}'     class='btn btn-danger btn-mini waves-effect waves-light'><i class='icofont icofont-trash'></i>Hapus</a><br/>
                                            @else
                                            <a type='button' href="{{ route('detailMasterUser',$data->id ) }}"  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Rincian</a>
                                            <a type='button' href='{{route('editUser',$data->id)}}'  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Edit</a>
                                            <a type='button' href='#delModal'  data-toggle='modal' data-id='{{$data->id}}'     class='btn btn-danger btn-mini waves-effect waves-light'><i class='icofont icofont-trash'></i>Hapus</a><br/>
                                            @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <button onclick="tableHtmlToExcel('dttable', 'members-data')">Export Table Data To Excel File</button> --}}
                
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
                    <h4 class="modal-title">Hapus Data User</h4>
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
    <div class="modal fade" id="restore" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Kembalikan Data User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin mengembalikan data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="reHref" href="" class="btn btn-danger waves-effect waves-light ">Restore</a>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="delPermanent" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Hapus Permanent Data User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus Permanent data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                    <a id="delHrefPermanent" href="" class="btn btn-danger waves-effect waves-light ">Hapus</a>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal-only">
    <div class="modal fade searchableModalContainer" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('createUser')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-5">

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">NIP/NIK</label>
                            <div class="col-md-9">
                                <input type="text" name="no_pegawai" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Contoh : 19680405XXXXXXXXXX"></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nama</label>
                            <div class="col-md-9">
                                <input type="text" name="name" class="form-control" placeholder="Nama"></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email</label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control" placeholder="Example@mail.com"></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <input type="password" name="password" class="form-control"></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No Telp</label>
                            <div class="col-md-9">
                                <input type="text" name="no_tlp" oninput="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="6282218XXXXXX" class="form-control"></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Internal Role</label>
                            <div class="col-md-9">
                                <select  class="searchableModalField form-control" style="width: 100%;"  name="internal_role_id" tabindex="4">
                                    @foreach($roles as $data)
                                        <option value="{{$data->id}}">{{$data->role}}</option>
                                    @endforeach
                                </select>
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

                <form action="{{route('updateUser')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Edit User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-5">
                        <input type="text" name="id" id="id" class="form-control" hidden></input>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nama</label>
                            <div class="col-md-9">
                                <input type="text" name="name" id="nama" class="form-control"></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email</label>
                            <div class="col-md-9">
                                <input type="email" name="email" id="email" class="form-control"></input>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <input type="password" name="password" id="password" class="form-control"></input>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Internal Role</label>
                            <div class="col-md-9">
                                <select  class="chosen-select"  name="internal_role_id" tabindex="4">
                                    @foreach($roles as $data)
                                        <option value="{{$data->id}}" id="id-{{$loop->index + 1}}">{{$data->role}}</option>
                                    @endforeach
                                </select>
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
                    <h4 class="modal-title">Hapus Data user</h4>
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
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".chosen-select").chosen( { width: '100%' } );
        $(".chosen-jenis-instruksi").chosen( { width: '100%' } );
        $("#dttable").DataTable();
        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/master-data/user/manajemen/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });
        $('#delPermanent').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/master-data/user/manajemen/deletepermanent') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHrefPermanent').attr('href', url);
        });
        $('#restore').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const url = `{{ url('admin/master-data/user/manajemen/restore') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #reHref').attr('href', url);
        });

        

    });

    function tableHtmlToExcel(dttable, filename = ''){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(dttable);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
        filename = filename?filename+'.xls':'excel_data.xls';
    
        downloadLink = document.createElement("a");
        
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], {
                type: dataType
            });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
            downloadLink.download = filename;
        
            downloadLink.click();
        }
    }
</script>
@endsection
