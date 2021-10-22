@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Dashboard Pengujian Bahan LABKON</h4>
                    {{-- <span>lorem ipsum dolor sit amet, consectetur adipisicing elit</span> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Pengujian Bahan</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-xl-12">
            <div class="card proj-progress-card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <h6>Total Pemohon</h6>
                            <h5 class="m-b-30 f-w-700">200<span class="text-c-green m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:75%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>Waiting List</h6>
                            <h5 class="m-b-30 f-w-700">20<span class="text-c-yellow m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-yellow" style="width:60%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>On Progress</h6>
                            <h5 class="m-b-30 f-w-700">100<span class="text-c-yellow m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-yellow" style="width:70%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>Selesai</h6>
                            <h5 class="m-b-30 f-w-700">80<span class="text-c-green m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card proj-progress-card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <h6>Pemohon UPTD I</h6>
                            <h5 class="m-b-30 f-w-700">20<span class="text-c-green m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:100%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>Pemohon UPTD II</h6>
                            <h5 class="m-b-30 f-w-700">20<span class="text-c-red m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-red" style="width:20%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>Pemohon UPTD III</h6>
                            <h5 class="m-b-30 f-w-700">20<span class="text-c-green m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:90%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>Pemohon UPTD IV</h6>
                            <h5 class="m-b-30 f-w-700">80<span class="text-c-yellow m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-yellow" style="width:70%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card proj-progress-card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <h6>Pemohon UPTD V</h6>
                            <h5 class="m-b-30 f-w-700">20<span class="text-c-green m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:100%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>Pemohon UPTD VI</h6>
                            <h5 class="m-b-30 f-w-700">20<span class="text-c-red m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-red" style="width:20%"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <h6>Pemohon Eksternal</h6>
                            <h5 class="m-b-30 f-w-700">20<span class="text-c-green m-l-10"></span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-c-green" style="width:90%"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
