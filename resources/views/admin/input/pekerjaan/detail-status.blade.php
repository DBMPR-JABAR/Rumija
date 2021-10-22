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
                    <h4>Pekerjaan </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Pekerjaan</a> </li>
                    <li class="breadcrumb-item"><a href="#!">Status</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-header mb-0 pb-0">
                    <h4 class="card-title">Status Laporan {{ Str::title($adjustment->nama_mandor) }} ~
                        {{ $adjustment->id_pek }}</h4>
                    <div class="card-header-right">
                        {{-- {{ $adjustment->tglreal}} --}}
                    </div>
                </div>
                <div class="card-block pt-0">
                    {{-- <a href="{{ route('createRoleAccess') }}" class="btn btn-mat btn-primary mb-3">Tambah</a> --}}
                    {{-- <form action="{{url('admin/user/account/'.Auth::user()->id)}}" method="post" enctype="multipart/form-data"> --}}

                    <div class="panel-body">
                        <label style="font-weight: bold;">Detail Status </label>
                        <ul class="timeline">
                            {{-- <li class="Submitted" data-toggle="tooltip"
                                data-original-title="Submitted">
                                <div class="row ml-3">
                                    <div class="col-8">
                                        <p style="display: inline-block">{{ Str::title($adjustment->nama_mandor) }} - Mandor</p>
                                    </div>
                                    <div class="col-4">
                                        <p class="float-right">{{ date_create($adjustment->tglreal)->format('d M Y H:i') }}
                                    </div>
                                </div>
                            </li> --}}
                            @foreach ($detail_adjustment as $no => $item)
                                <li class="{{ $item->status }}" data-toggle="tooltip"
                                    data-original-title="{{ $item->status }}">
                                    <div class="row ml-3">
                                        <div class="col-8">
                                            @if(str_contains($item->status,'Submitted'))
                                            <p style="display: inline-block">{!! @$item->nama_user_create !!} - {!! @$item->jabatan_user_create !!}</p>
                                            @else
                                            <p style="display: inline-block">{!! @$item->name !!} - {!! @$item->jabatan !!}</p>

                                            @endif
                                            @if ($item->description)
                                            <p><i style="color :red; font-size: 12px;">Catatan : {!! @$item->description !!}</i><p>
                                            @endif
                                        </div>
                                        <div class="col-4">
                                            <p class="float-right">{{ date_create($det[$no++])->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ url()->previous() }}"><button type="button" class="btn btn-success waves-effect "
                            data-dismiss="modal">Kembali</button></a>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-only">
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form action="{{ url('admin/user/account/' . Auth::user()->id) }}" method="post"
                        enctype="multipart/form-data">
                        {{-- <form action="{{url('admin/edit/profile/'.Auth::user()->id)}}" method="POST" enctype="multipart/form-data"> --}}

                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-5">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">email</label>
                                <div class="col-md-9">
                                    <input type="text" name="email" id="email" value="{{ Auth::user()->email }}"
                                        class="form-control"></input>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Password Lama</label>
                                <div class="col-md-9">
                                    <input type="password" name="password_lama" id="password_lama"
                                        placeholder="Masukkan Password Lama" class="form-control"></input>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" id="password"
                                            value="{{ old('password') }}" placeholder="Masukkan Password Baru"
                                            class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                            <div class="invalid-feedback" style="display: block; color:red">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ulangi Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            value="{{ old('password_confirmation') }}"
                                            placeholder="Masukkan Konfirmasi Password Baru" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <i style="color :red; font-size: 10px;">Biarkan jika tidak ada perubahan</i>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary waves-effect " data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-danger waves-effect waves-light ">Simpan</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <style>
        ul.timeline {
            list-style-type: none;
            position: relative;
        }

        ul.timeline:before {
            content: ' ';
            background: #d4d9df;
            display: inline-block;
            position: absolute;
            left: 29px;
            width: 2px;
            height: 100%;
            z-index: 400;
        }

        ul.timeline>li {
            margin: 20px 0;
            padding-left: 20px;
        }

        ul.timeline>li::before {
            content: ' ';
            background: white;
            display: inline-block;
            position: absolute;
            border: 3px solid #e5ff00;
            border-radius: 50%;
            left: 20px;
            width: 20px;
            height: 20px;
            z-index: 400;
        }

        ul.timeline>.Submitted::before {
            border: 3px solid #15ff00;
        }

        ul.timeline>.Submitted:hover {}

        ul.timeline>.Approved::before {
            border: 3px solid #0077ff;
        }

        ul.timeline>.Rejected::before {
            border: 3px solid #ff0000;
        }

        ul.timeline>.Edited::before {
            border: 3px solid #e5ff00;
        }

        ul li .left {
            margin-left: 40px
        }

    </style>
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

            $('#editModal').on('show.bs.modal', function(event) {
                const link = $(event.relatedTarget);
                const id = link.data('id');

                console.log(id);
                const baseUrl = `{{ url('admin/master-data/user/role-akses/getData') }}/` + id;
                $.get(baseUrl, {
                        id: id
                    },
                    function(response) {


                    });
            });

        });

    </script>
@endsection
