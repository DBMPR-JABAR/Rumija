@extends('admin.layout.index')

@section('title') Rincian User Role @endsection
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
                <h4>Rincian User Role</h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Rincian User Role</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
<div class="col-xl-8 col-md-12">
    <div class="card">

        <div class="card-block-big">
            <ul class="nav nav-tabs  tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#Detail" role="tab">Detail</a>
                 </li>


            </ul>
                                                                <!-- Tab panes -->
            <div class="tab-content tabs card-block">
                <div class="tab-pane active" id="Detail" role="tabpanel">
                     <table style="padding:0;margin:0" class="table table-striped table-bordered nowrap dataTable">
                     <tr><td>	Role</td><td>{{$user_role[0]->role}}</td></tr>
                      <tr><td>	Parent</td><td>{{$user_role[0]->parent}}</td></tr>
                      <tr><td>  Is Superadmin</td>
                      <td><?php
                                if($user_role[0]->is_superadmin == 0){
                                    echo "No";
                                }
                                else {
                                    echo "Yes";
                                }
                            ?></td>
                      </tr>
                      <tr><td>  Keterangan</td><td>{{$user_role[0]->keterangan}}</td>
                      </tr>
                      </tr>
                      <tr><td>  Is Active</td>
                      <td><?php
                                if($user_role[0]->is_superadmin == 0){
                                    echo "No";
                                }
                                else {
                                    echo "Yes";
                                }
                        ?></td>
                      </tr>
                      <tr><td>  Is Deleted</td>
                      <td><?php
                                if($user_role[0]->is_superadmin == 0){
                                    echo "No";
                                }
                                else {
                                    echo "Yes";
                                }
                        ?></td>
                        </tr>
                        <tr><td> UPTD</td><td>{{$user_role[0]->uptd}}</td></tr>
                        <tr><td> Created At</td><td>{{$user_role[0]->created_at}}</td></tr>
                        <tr><td> Updated At</td><td>{{$user_role[0]->updated_at}}</td></tr>
                        <tr><td> Created By</td><td>{{$user_role[0]->created_by}}</td></tr>
                        <tr><td> Updated By</td><td>{{$user_role[0]->updated_by}}</td></tr>
                     </table>
                 </div>

            </div>
        </div>

    </div>
</div>
</div>


@endsection
@section('script')

@endsection
