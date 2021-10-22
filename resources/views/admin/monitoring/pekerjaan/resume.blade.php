@extends('admin.layout.index')

@section('title') Pekerjaan @endsection
@section('head')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}">

<style>
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
                <h4>Pekerjaan
                    @if(Auth::user()->internalRole->role != null &&
                    str_contains(Auth::user()->internalRole->role,'Mandor'))
                    {{ Str::title(Auth::user()->name) }}
                    @endif

                </h4>
                <span>Data Pekerjaan</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Pekerjaan</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-block accordion-block">
                <div id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="accordion-panel">
                        <div class="accordion-heading" role="tab" id="headingOne">
                            <h3 class="card-title accordion-title">
                                <a class="accordion-msg" data-toggle="collapse" data-parent="#accordion"
                                    href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Filter
                                </a>
                            </h3>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel"
                            aria-labelledby="headingOne">
                            <div class="accordion-content accordion-desc">
                                <div class="card-block w-100">
                                    <form id="formFilter" action="{{ route('resume_pekerjaan') }}" method="get"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row col-12">
                                            <div class="col-sm-12 col-xl-2 ">
                                                <h4 class="sub-title">Tanggal Awal</h4>
                                                <input required name="tanggal_awal" id="filterTanggalAwal" type="date"
                                                    class="form-control form-control-primary">
                                            </div>
                                            <div class="col-sm-12 col-xl-2">
                                                <h4 class="sub-title">Tanggal Akhir</h4>
                                                <input name="tanggal_akhir" id="filterTanggalAkhir" type="date"
                                                    class="form-control form-control-primary">
                                            </div>
                                            <div class="col-sm-12 col-xl-2">
                                                <h4 class="sub-title">UPTD</h4>
                                                <select required name="uptd" id="filterUPTD" name="select"
                                                    class="form-control form-control-primary">
                                                    @foreach ($uptd as $row)
                                                    <option value="{{$row->id}}"
                                                        {{$row->id == @$filter->uptd ? 'selected':''}}>{{$row->nama}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-12 col-xl-2">
                                                <h4 class="sub-title">SUP</h4>
                                                <select required name="sup" id="filterSUP" name="select"
                                                    class="form-control form-control-primary">
                                                    <option value="ALL" {{@$filter->sup == "ALL" ? 'selected' : ''}}>
                                                        Semua SUP</option>
                                                    @foreach ($sup as $row)
                                                    @if ($row->uptd_id == (@$filter->uptd ?$filter->uptd: 1))
                                                    <option value="{{$row->id}}"
                                                        {{$row->id == @$filter->sup ? 'selected':''}}>{{$row->name}}
                                                    </option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <input name="filter" value="true" style="display: none" />

                                            <div class="mt-3 col-sm-12 col-xl-2">
                                                <button type="submit"
                                                    class="mt-4 btn btn-primary waves-effect waves-light">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 id="tbltitle">Tabel Pekerjaan</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                        <li><i class="feather icon-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table id="dttable" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Laporan</th>
                                <th>Nama Mandor</th>
                                <th>SUP</th>
                                <th>Ruas Jalan</th>
                                <th>Jenis Pekerjaan</th>
                                <th>Lokasi</th>
                                <th>Panjang (meter)</th>
                                <th>Perkiraan Kuantitas</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="bodyJembatan">
                            @foreach ($pekerjaan as $data)
                            <tr>
                                <td>{{$loop->index + 1}}</td>
                                <td>{{$data->id_pek}}</td>
                                <td>{{$data->nama_mandor}}</td>
                                <td>{{$data->sup}}</td>
                                <td>{{$data->ruas_jalan}}</td>
                                <td>{{$data->paket}}</td>
                                <td>{{$data->lokasi}}</td>
                                <td>{{@$data->panjang}}</td>
                                <td>{{@$data->perkiraan_kuantitas}}</td>
                                <td>{{$data->tanggal}}</td>
                                <td>@if($data->status)
                                    @if(str_contains($data->status->status,'Submitted')
                                    ||str_contains($data->status->status,'Approved') ||
                                    str_contains($data->status->status,'Rejected')||
                                    str_contains($data->status->status,'Edited') )
                                    @if(str_contains($data->status->status,'Approved') )
                                    <button type="button" class="btn btn-mini btn-primary " disabled>
                                        {{$data->status->status}}</button>
                                    @elseif(str_contains($data->status->status,'Rejected') )
                                    <button type="button" class="btn btn-mini btn-danger " disabled>
                                        {{$data->status->status}}</button>
                                    @elseif(str_contains($data->status->status,'Submitted') )
                                    <button type="button" class="btn btn-mini btn-success waves-effect" disabled>
                                        {{$data->status->status}}</button>
                                    @else
                                    <button type="button" class="btn btn-mini btn-warning " disabled>
                                        {{$data->status->status}}</button>
                                    @endif

                                    @else
                                    @if($data->input_material)
                                    <button type="button" class="btn btn-mini btn-success waves-effect "
                                        disabled>Submitted</button>
                                    @endif
                                    @endif
                                    @else
                                    <button type="button" class="btn btn-mini btn-warning waves-effect "
                                        @if(str_contains(Auth::user()->internalRole->role,'Mandor') ||
                                        str_contains(Auth::user()->internalRole->role,'Pengamat') ||
                                        str_contains(Auth::user()->internalRole->role,'Admin')) @else disabled
                                        @endif disabled>Not Completed</button>
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

@endsection
@section('script')
<!-- <script src="{{ asset('assets/vendor/jquery/js/jquery-3.4.1.min.js') }}" ></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<script src="{{
    asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js')
}}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script>
    const sup = @json($sup)


   const filter = @json($filter)


        $(document).ready(function() {

            const supSelect = document.getElementById('filterSUP')
        const uptdSelect = document.getElementById('filterUPTD')
        uptdSelect.onchange = event=>{
            console.log(event)
            const filterSup = sup.filter(data=>data.uptd_id == event.target.value)
            console.log(filterSup)
            let html = `<option value="ALL">Semua SUP</option>`
            filterSup.forEach(data=>{
                html += `<option value="${data.id}">${data.name}</option>`
            })
            supSelect.innerHTML = html
        }

        const now = new Date().toISOString().split('T')[0]
        const tanggalNow = now.toLocaleString('id','YYYY-MM-DD')
        document.getElementById('filterTanggalAwal').value = filter ? filter.tanggal_awal : now
        document.getElementById('filterTanggalAkhir').value = filter ? filter.tanggal_akhir : now


        const table = $("#dttable").DataTable({
            dom: 'Bfrtip',
            buttons: ['excel'],
            drawCallback: function( oSettings ) {
                $( "#dttable tbody tr" ).hover(
                function() {
                    $(this).find('td').hide()
                    $( this ).prepend( $( `<td id="detailRow" colspan="11"><p class="text-center p-1 m-0">Klik untuk melihat detail</p></td>` ) );
                }, function() {
                    $( this ).find( "#detailRow" ).remove();
                    $(this).find('td').show()
                }
                );
            }
        });

        $('#dttable tbody').on('click', 'tr', function () {
            const data = table.row( this ).data();
            window.open('{{url("/pemeliharaan/pekerjaan")}}'+'/'+data[1], '_self')
        });
    });

</script>
@endsection
