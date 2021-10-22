@extends('admin.layout.index')

@section('title') Rincian Disposisi @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">
<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/list-scroll/list.css') }}">



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
                <h4>Detail Disposisi </h4>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Disposisi</a> </li>
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

                                                                    <li class="nav-item">
                                                                        <a class="nav-link" data-toggle="tab" href="#lampiran" role="tab">Lampiran</a>
                                                                    </li>

                                                                </ul>
                                                                <!-- Tab panes -->
                                                                <div class="tab-content tabs card-block">
                                                                    <div class="tab-pane active" id="Detail" role="tabpanel">
                                                                    <table style="padding:0;margin:0" class="table table-striped table-bordered nowrap dataTable">
                                                             <tr><td>	Disposisi Code</td><td>{{$detail_disposisi->disposisi_code}}</td></tr>
                                                             <tr><td>	Pemberi Disposisi</td><td>{{$detail_disposisi->pengirim}}</td></tr>
                                                             <tr><td>	Surat dari</td><td>{{$detail_disposisi->dari}}</td></tr>
                                                             <tr><td>	Perihal</td><td>{{$detail_disposisi->perihal}}</td></tr>
                                                             <tr><td>	Tanggal Surat</td><td>{{$detail_disposisi->tgl_surat}}</td></tr>

                                                             <tr><td>	No Surat</td><td>{{$detail_disposisi->no_surat}}</td></tr>
                                                             <tr><td>	Tanggal Penyelesaian</td><td>{{$detail_disposisi->tanggal_penyelesaian}}</td></tr>
                                                             <tr><td>	Status</td><td>
                                                             <?php

                                    if($detail_disposisi->status == "1")  {
                                        echo '<button class="  btn btn-inverse btn-mini btn-round">Submitted</button> ';
                                    } else if($detail_disposisi->status == "2") {
                                        echo '<button class="btn btn-info btn-mini btn-round">Accepted</button> ';
                                    }  else if($detail_disposisi->status == "3") {
                                        echo '<button class="btn btn-success  btn-mini btn-round">On Progress</button> ';

                                    } else if($detail_disposisi->status == "4") {

                                        echo '<button class="btn btn-info  btn-mini btn-round">Finish</button> ';

                                    }
                                    ?>
 </td></tr>
                                                             <tr><td>	Disposisi Kepada</td><td>
                                                              <?php echo $unitData ?>
                                                             </td></tr>

                                                             <tr><td>	Created Date</td><td>{{$detail_disposisi->created_date}}</td></tr>
</table>
                                                                </div>
                                                                <div class="tab-pane" id="tindaklanjut" role="tabpanel">

                                                                </div>
                                                                    <div class="tab-pane" id="lampiran" role="tabpanel">
                                                                    <?php $ex = explode(".",$detail_disposisi->file);  ?>
                                                                      <iframe src="{{  asset('storage/'.$detail_disposisi->file)  }}" id="pdf_display_frame" width="100%" height="600px"></iframe>
                                                                     </div>

                                                                </div>



                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-12">
                                                <div class="card feed-card">
                                                    <div class="card-header">

                                                    </div>
                                                    <div class="row card-block">
                                                    <div class="col-md-12 col-lg-12">
                                                        <h6 class="sub-title">History</h6>
                                                    <ul class="scroll-list wave" style="overflow: auto; width: auto; height: 500px;">
                                                    @foreach($history as $h)

                                                    <?php $date = date_create($h->created_date);?>

                                                    <li style="border-bottom: 1px solid rgba(204,204,204,0.35);">
                                                    <p style="margin:0px"><i><?php  echo date_format($date, 'd-m-Y H:i:s') ?></i></p>
                                                    <p> <?php echo $h->name . ' (' . $h->role_name . ') '.$h->keterangan; ?></p> </li>
                                                          @endforeach

                                                                </ul>

</div></div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- statustic-card start -->




</div>

<div class="row">
<div class="col-xl-12 col-md-12" >
<div class="card feed-card">
                                                    <div class="card-header">
                                                      <h5> Tindak Lanjut </h5>
                                                    </div>
                                                    <div class="card-block">
                <div class="dt-responsive table-responsive">
<table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                            <th>No</th>
                            <th>Created Date</th>
                                 <th>Unit</th>
                                 <th>Tindak Lanjut</th>

                                <th>Keterangan</th>
                                <th>Status</th>
                                <th>Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($tindaklanjut as $data)
                        <tr>
                        <td>{{$loop->index + 1}}</td>
                        <td>
                                <?php $date_create_date = date_create($data->created_date);?>
                                {{ date_format($date_create_date, 'd-m-Y H:i:s')}}
                                    </td>
                                <td>
                                {{ $data->penanggung_jawab}}<br/>
                                @php   $roleData = \App\Model\Transactional\Role::where('id',$data->internal_role_id)->first() @endphp
                                <span > {{!empty($roleData->keterangan) ?  $roleData->keterangan: "-"  }}</span>

                                </td>
                                <td>{{$data->tindak_lanjut}}</td>
                                <td>{{$data->keterangan_tl}}</td>
                                 <td><?php

if($data->status_tindak_lanjut == "2")  {
    echo "Submitted";
}
if($data->status_tindak_lanjut == "3")  {
                                        echo "On Progress";
                                    }  else if($data->status_tindak_lanjut == "4") {
                                        echo "Finish";
                                    }

                                 ?></td>


                                <td>
                                <a href="{{route('download',$data->id)}}">
                                <button class="btn btn-success  btn-mini btn-round"><i class="icofont icofont-download"></i> Download</button>
                                </a>


                                </td>
                                </tr>
                            @endforeach
                         </tbody>
                        </table>
                        </div></div>
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

<script src="{{ asset('assets/vendor/list-scroll\list-custom.js') }}"></script>


<script>
    $(document).ready(function() {
        $("#dttable").DataTable();
    });
        </script>

@endsection
