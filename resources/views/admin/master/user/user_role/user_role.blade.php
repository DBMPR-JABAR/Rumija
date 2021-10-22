@extends('admin.layout.index')

@section('title') User Role @endsection
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
                <h4>User Role </h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">User Role</a> </li>
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
                @if (hasAccess(Auth::user()->internal_role_id, "User Role", "Create"))
                <a data-toggle="modal" href="#addModal" class="btn btn-mat btn-primary mb-3">Tambah</a>
                @endif
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Role</th>
                                <th>Parent</th>
                                <th>Is Superadmin</th>
                                <th>Keterangan</th>
                                <th>Is Active</th>
                                {{-- <th>Is Deleted</th> --}}
                                {{-- <th>UPTD</th> --}}
                                {{-- <th>Created at</th>
                                <th>Updated at</th> --}}
                                <th>Created by</th>
                                <th>Updated by</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                             @foreach ($user_role_list as $data)
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{$data->role}}</td>
                                    <td>{{$data->parent}}</td>
                                    <td>@php
                                        if($data->is_superadmin == 0){
                                            echo "No";
                                        }
                                        else{
                                            echo "Yes";
                                        }
                                    @endphp
                                    </td>

                                    <td>{{$data->keterangan}}</td>
                                    <td>
                                        @php
                                        if($data->is_active == 0){
                                            echo "No";
                                        }
                                        else{
                                            echo "Yes";
                                        }
                                    @endphp
                                    </td>
                                    {{-- <td>@php
                                        if($data->is_deleted == 0){
                                            echo "No";
                                        }
                                        else{
                                            echo "Yes";
                                        }
                                    @endphp</td> --}}
                                    {{-- <td>{{$data->uptd}}</td> --}}

                                    <td>{{$data->created_by}}</td>
                                    <td>{{$data->updated_by}}</td>
                                    <td>
                                        <a type="button" href="{{ route('detailUserRole',$data->id) }}"  class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Rincian</a>
                                        @if (hasAccess(Auth::user()->internal_role_id, "User Role", "Update"))
                                        <a type="button"href="#editModal"  data-toggle="modal" data-id="{{$data->id}}"  class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Edit</a>
                                        @endif
                                        @if (hasAccess(Auth::user()->internal_role_id, "User Role", "Delete"))
                                        <a type="button"href="#delModal"  data-toggle="modal" data-id="{{$data->id}}"     class="btn btn-primary btn-mini waves-effect waves-light"><i class="icofont icofont-check-circled"></i>Hapus</a>
                                        @endif
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
                    <h4 class="modal-title">Hapus Data User Role</h4>
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

<div class="modal-only">
    <div class="modal fade searchableModalContainer" id="addModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <form action="{{route('createUserRole')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title ">Tambah User Role</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-5">

                    <div class="form-group row">
                            <label class="col-md-3 col-form-label">Role</label>
                            <div class="col-md-9">
                                <input type="text" name="user_role" class="form-control" placeholder="Contoh : Kepala Dinas"></input>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Parent</label>
                            <div class="col-md-9">
                                <select  class="searchableModalField form-control" style="width: 100%;"  name="parent" tabindex="4">
                                    @foreach($user_role_list as $data)
                                    @if(Auth::user()->internalRole->uptd == $data->uptd)
                                        <option value="{{$data->id}}">{{$data->role}}</option>
                                        @elseif(Auth::user()->internalRole->uptd == null)
                                        <option value="{{$data->id}}">{{$data->role}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if(Auth::user()->internalRole->uptd == null)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Is Superadmin? </label>
                            <div class="col-md-9">
                                <select  name="super_admin" class="form-control" tabindex="4" required>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        @endif


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Keterangan</label>
                            <div class="col-md-9">
                                <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Is Active?</label>
                            <div class="col-md-9">
                            <select  name="is_active" tabindex="4" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <!--
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Is Deleted?</label>
                            <div class="col-md-9">
                            <select  name="is_deleted" tabindex="4" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">UPTD</label>
                            <div class="col-md-9">
                            @if(Auth::user() && !Auth::user()->internalRole->uptd )
                            <select  name="uptd" class="form-control" tabindex="4" >
                                <option value="">All</option>
                                @foreach ($uptd_lists as $no => $item)
                                    <option value="{{ Str::limit($item->slug,4,$end=++$no) }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                            @else
                                <input type="text" name="uptd" class="form-control" value="{{ Auth::user()->internalRole->uptd }}" readonly></input>

                            @endif
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

                <form action="{{route('updateUserRole')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Role Access</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body p-5">

                    <div class="form-group row">
                            <label class="col-md-3 col-form-label">Role</label>
                            <div class="col-md-9">
                                <input type="text" name="user_role" id="user_role" class="form-control"></input>
                                <input type="text" name="id" class="form-control" value="{{$data->id}}" hidden></input>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Parent</label>
                            <div class="col-md-9">
                                <input type="text" name="parent" id="parent" class="form-control"></input>
                            </div>
                        </div>
                        {{-- <div class="form-group row">
                            <label class="col-md-3 col-form-label">Parent</label>
                            <div class="col-md-9">
                                <select id="parent_edit"  class="searchableModalField form-control" style="width: 100%;"  name="parent" tabindex="4">
                                    @foreach($user_role_list as $data)
                                    @if(Auth::user()->internalRole->uptd == $data->uptd)
                                        <option value="{{$data->id}}" id="{{ $data->id }}">{{$data->role}}</option>
                                        @elseif(Auth::user()->internalRole->uptd == null)
                                        <option value="{{$data->id}}" id="{{ $data->id }}">{{$data->role}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        @if(Auth::user()->internalRole->uptd == null)

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Is Superadmin?</label>
                            <div class="col-md-9">
                                <select  name="super_admin" id="super_admin" tabindex="4" required>
                                    <option value="1" id="is_superadmin_1">Yes</option>
                                    <option value="0" id="is_superadmin_2">No</option>
                                </select>
                            </div>
                        </div>
                        @endif


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Keterangan</label>
                            <div class="col-md-9">
                                <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Is Active?</label>
                            <div class="col-md-9">
                            <select  name="is_active" id="is_active" tabindex="4" required>
                                    <option value="1" id="is_active_1">Yes</option>
                                    <option value="0" id="is_active_2">No</option>
                                </select>
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label class="col-md-3 col-form-label">Is Deleted?</label>
                            <div class="col-md-9">
                            <select  name="is_deleted" id="is_deleted" tabindex="4" required>
                                    <option value="1" id="is_deleted_1">Yes</option>
                                    <option value="0" id="is_deleted_2">No</option>
                                </select>
                            </div>
                        </div> --}}
                        @if(Auth::user()->internalRole->uptd == null)

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">UPTD</label>
                            <div class="col-md-9">
                            <select  name="uptd" id="uptd" tabindex="4" required>
                                    <option value="-" id="uptd1">All</option>
                                    <option value="uptd1" id="uptd1">UPTD 1</option>
                                    <option value="uptd2" id="uptd2">UPTD 2</option>
                                    <option value="uptd3" id="uptd3">UPTD 3</option>
                                    <option value="uptd4" id="uptd4">UPTD 4</option>
                                    <option value="uptd5" id="uptd5">UPTD 5</option>
                                    <option value="uptd6" id="uptd6">UPTD 6</option>
                            </select>
                            </div>
                        </div>

                        @endif

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ">Simpan</button>
                    </div>

                </form>


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
            const url = `{{ url('admin/master-data/user/user-role/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

        $('#editModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            console.log(id);
            const baseUrl = `{{ url('admin/master-data/user/user-role/getData') }}/` + id;
            $.get(baseUrl, { id: id },
                function(response){
                    console.log(response);
                    $('#user_role').val(response.user_role[0].role);
                    $('#parent').val(response.user_role[0].parent);

                    const user_role_list = @json($user_role_list);
                    const selectedMenu = user_role_list.filter((data) => {
                        return data.id == link.data('parent')
                    });
                    if(selectedMenu.length > 0) {
                        console.log(selectedMenu[0].id)
                        $('#parent_edit').val(selectedMenu[0].id);
                    };
                    $('#keterangan').html(response.user_role[0].keterangan);
                    const keterangan = response.user_role[0].keterangan;

                    if($('#is_active_1').val() == response.user_role[0].is_active){ $('#is_active_1').attr("selected","selected")};
                    if($('#is_active_2').val() == response.user_role[0].is_active){ $('#is_active_2').attr("selected","selected")};
                    if($('#is_deleted_1').val()== response.user_role[0].is_deleted){ $('#is_deleted_1').attr("selected","selected")};
                    if($('#is_deleted_2').val()== response.user_role[0].is_deleted){ $('#is_deleted_2').attr("selected","selected")};
                    if($('#is_superadmin_1').val()== response.user_role[0].is_superadmin){ $('#is_superadmin_1').attr("selected","selected")};
                    if($('#is_superadmin_2').val()== response.user_role[0].is_superadmin){ $('#is_superadmin_2').attr("selected","selected")};
                    for(let i=1;i<=6;i++){
                        if($('#uptd'+i).val() == response.user_role[0].uptd){
                            $('#uptd'+i).attr("selected","selected");
                        }
                    }
                });
        });


    });
</script>
@endsection
