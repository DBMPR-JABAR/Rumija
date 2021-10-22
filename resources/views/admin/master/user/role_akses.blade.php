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
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    @if (hasAccess(Auth::user()->internal_role_id, "Role Akses", "Create"))
                    <a href="{{ route('createRoleAccess') }}" class="btn btn-mat btn-primary mb-3">Tambah</a>
                    @endif
                    <div class="dt-responsive table-responsive">
                        <table id="dttable" class="table table-striped table-bordered able-responsive">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="50%">User Role</th>
                                    {{-- <th>Menu</th> --}}
                                    <th>UPTD Access</th>
                                    <th>Aksi</th>

                                </tr>
                            </thead>
                            <tbody id="bodyJembatan">
                                @php
                                    $i=0;
                                @endphp
                                @foreach ($menu_access as $data)

                                    <tr>
                                        <td>{{$loop->index + 1}}</td>
                                        <td>{{$data['role']}}</td>
                                        {{-- <td>{{$data['permissions']}}</td> --}}


                                        <td>@if (Auth::user() && Auth::user()->internalRole->uptd)
                                            {{$data['uptd_aks']}}
                                            @else {{$uptd_access[$i]}}
                                            @endif
                                        </td>
                                        <td>
                                                <a type='button' href="{{ route('detailRoleAkses', $data['role_id']) }}"  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Rincian</a>
                                                @if (hasAccess(Auth::user()->internal_role_id, "Role Akses", "Update"))
                                                <a type='button' href='{{ route('editRoleAccess', $data['role_id']) }}'  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Edit</a>
                                                @endif
                                                {{-- <a type='button' href='#editModal'  data-toggle='modal' data-id='{{$data['role_id']}}' data-uptd_access='{{$uptd_access[$i]}}'  class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Edit</a> --}}
                                                @if (hasAccess(Auth::user()->internal_role_id, "Role Akses", "Create"))
                                                <a type='button' href='#delModal'  data-toggle='modal' data-id='{{$data['role_id']}}'     class='btn btn-primary btn-mini waves-effect waves-light'><i class='icofont icofont-check-circled'></i>Hapus</a><br/>
                                                @endif
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
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
                        <h4 class="modal-title">Hapus Data Role Akses</h4>
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
            const url = `{{ url('admin/master-data/user/role-akses/delete') }}/` + id;
            console.log(url);
            const modal = $(this);
            modal.find('.modal-footer #delHref').attr('href', url);
        });

        $('#editModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const id = link.data('id');
            var myUptdAccess = $(this).data('uptd_access');


            console.log(id);
            const baseUrl = `{{ url('admin/master-data/user/role-akses/getData') }}/` + id;
            $.get(baseUrl, { id: id },
                function(response){

                        console.log(response);
                        $("#select_user_role").html(`<option value="${response.user_role_list[0].role_id}">${response.user_role_list[0].role}</option>`);
                        $("#uptdAccess").val( myUptdAccess );

                        for(var i=1; i<=$('#edit_select_menu').children('option').length;i++){
                            for(var j=0; j<response.user_role.length;j++){
                                if($('#menu_'+i).val() == response.user_role[j].menu){
                                    $('#menu_'+i).attr("selected","selected");
                                }
                            }
                        }
                        for(var i=1; i<=$('#edit_role_access > option').length;i++){
                            for(var j=0; j < response.role_access.length ;j++){
                                if($('#user_role_'+i).val() == response.role_access[j].role_access){
                                    $('#user_role_'+i).attr("selected","selected");
                                }
                            }
                        }
                        for(var i=1; i<=$('#edit_uptd_access').children('option').length;i++){
                            for(var j=0; j<response.uptd_access.length;j++){
                                if($('#uptd_'+i).val() == response.uptd_access[j].uptd_name){
                                    $('#uptd_'+i).attr("selected","selected");
                                }
                            }
                        }
                        $("#edit_select_menu").chosen( { width: '100%' } );
                        $("#edit_role_access").chosen( { width: '100%' } );
                        $("#edit_uptd_access").chosen( { width: '100%' } );

            });
        });

    });
</script>
@endsection
