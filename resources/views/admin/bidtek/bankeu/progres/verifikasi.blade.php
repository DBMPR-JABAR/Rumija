@extends('admin.layout.index') @section('title') Bantuan Keuangan @endsection
@section('head')
<link rel="stylesheet" type="text/css" href="{{
        asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css')
    }}" />
<link rel="stylesheet" type="text/css" href="{{
        asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css')
    }}" />
<link rel="stylesheet" type="text/css" href="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css'
        )
    }}" />

<link rel="stylesheet" href="https://js.arcgis.com/4.17/esri/themes/light/main.css" />

<style>
    table.table-bordered tbody td {
        word-break: break-word;
        vertical-align: top;
    }
</style>
@endsection @section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Laporan Progres Bantuan Keuangan {{@$bankeu->no_kontrak}}</h4>
                <span>Verifikasi Data Laporan Progres Bantuan Keuangan {{@$bankeu->no_kontrak}}</span>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class="breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}">
                        <i class="feather icon-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Laporan Progres Bantuan Keuangan {{@$bankeu->no_kontrak}}</a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection @section('page-body')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">{{'('.@$bankeu->target.'/'.@$bankeu->pembagian_progres. ') '.@$bankeu->no_kontrak}}</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <h5 class="m-b-30 f-w-700">Nama Kegiatan</h5>
                        <h6>{{$bankeu->nama_kegiatan}}</h6>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h5 class="m-b-30 f-w-700">Kategori</span></h5>
                        <h6>{{$bankeu->kategori}}</h6>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h5 class="m-b-30 f-w-700">Waktu Pelaporan (Rencana/Realisasi)</h5>
                        <h6>{{$bankeu->tanggal.'/'.$bankeu->updated_at}}</h6>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h5 class="m-b-30 f-w-700">Progres</span></h5>
                        <h6>{{$bankeu->progress}}%
                            ({{ $bankeu->is_verified == 1 ? 'Terverifikasi': ($bankeu->is_verified == 0 ? 'Belum Diverifikasi' : 'Ditolak') }})
                        </h6>
                    </div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-12 mb-3">
                        <h5 class="m-b-30 f-w-700 text-center">Foto 1</span></h5>
                        @if ($bankeu->foto_1)
                        <img src="{{url('storage/'.$bankeu->foto_1)}}"
                            class="img-fluid img-thumbnail rounded mx-auto d-block" style="max-height: 400px">
                        @else <p class=" text-center">Tidak Disertakan</p>
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <h5 class="m-b-30 f-w-700 text-center">Foto 2</span></h5>
                        @if ($bankeu->foto_2)
                        <img src="{{url('storage/'.$bankeu->foto_2)}}"
                            class="img-fluid img-thumbnail rounded mx-auto d-block" style="max-height: 400px">
                        @else <p class=" text-center">Tidak Disertakan</p>
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <h5 class="m-b-30 f-w-700 text-center">Foto 3</span></h5>
                        @if ($bankeu->foto_3)
                        <img src="{{url('storage/'.$bankeu->foto_3)}}"
                            class="img-fluid img-thumbnail rounded mx-auto d-block" style="max-height: 400px">
                        @else <p class=" text-center">Tidak Disertakan</p>
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <h5 class="m-b-30 f-w-700 text-center">Video</span></h5>
                        @if ($bankeu->video)
                        <video class="mx-auto rounded img-thumbnail d-block"
                            src="{{ url('storage/' . @$bankeu->video) }}" alt="" controls>
                            @else <p class=" text-center">Tidak Disertakan</p>
                            @endif
                    </div>
                    <div class="col-12 mb-3">
                        <h5 class="m-b-30 f-w-700 text-center">Dokumen</span></h5>
                        @if ($bankeu->dokumen)
                        <a role="button" class="btn btn-success mx-auto d-block" style="max-width: 80px"
                            href="{{ url('storage/' . @$bankeu->dokumen) }}" download>
                            Unduh
                        </a>
                        @else <p class=" text-center">Tidak Disertakan</p>
                        @endif
                    </div>
                </div>
                @if ($bankeu->is_verified == 0 )

                <div class="ml-3">
                    <form
                        action="{{ route('bankeu.verifikasi.update', ['id'=>$bankeu->id_bankeu,'target'=>$bankeu->target]) }}"
                        method="POST">
                        @csrf
                        <fieldset class="form-group row">
                            <legend class="col-form-label col-sm-2 float-sm-left pt-0">Tindakan ?</legend>
                            <div class="col-sm-8">
                                <div class="form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_verified"
                                        id="verifikasi_checked" value="1" checked>
                                    <label class="form-check-label" for="verifikasi_checked">
                                        Verifikasi
                                    </label>
                                </div>
                                <div class="form-check-inline">
                                    <input class="form-check-input" value="2" type="radio" name="is_verified"
                                        id="verifikasi_unchecked">
                                    <label class="form-check-label" for="verifikasi_unchecked">
                                        Tolak
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div id="catatan" class="form-group row">
                            <label class="col-md-2 col-form-label float-sm-left">Catatan</label>
                            <div class="col-md-5">
                                <textarea id="catatan_txt" name="catatan" rows="3"
                                    class="form-control">{{ @$bankeu->catatan ? $bankeu->catatan : ''}}</textarea>
                            </div>
                        </div>
                        <div class="col-form-label mt-0 pt-0"> <small style="color: red">*Pastikan tindakan anda benar, tindakan
                                tidak dapat dibatalkan
                            </small></div>
                        <div class=" form-group row">
                            <a href="{{ route('bankeu.progres') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
                            <button type="submit" class="ml-2 btn btn-success waves-effect waves-light">Simpan</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal-only">
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data Bantuan Keuangan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus data ini?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
                        Tutup
                    </button>
                    <a id="delHref" href="" class="btn btn-danger waves-effect waves-light">Hapus</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection @section('script')
<script src="{{
        asset('assets/vendor/datatables.net/js/jquery.dataTables.min.js')
    }}"></script>
<script src="{{
        asset('assets/vendor/datatables.net/js/dataTables.buttons.min.js')
    }}"></script>
<script src="{{
        asset('assets/vendor/datatables.net/js/dataTables.bootstrap4.min.js')
    }}"></script>
<script src="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/js/dataTables.responsive.min.js'
        )
    }}"></script>
<script src="{{
        asset(
            'assets/vendor/data-table/extensions/responsive/js/responsive.bootstrap4.min.js'
        )
    }}"></script>
<script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    $(document).ready(function () {
        $("#bankeu_table").DataTable({
            language: {
                emptyTable: "Tidak ada data tersedia.",
            },
        });


        const isVerified = document.getElementById("verifikasi_checked");
        const isNoVerified = document.getElementById("verifikasi_unchecked");
        $("#catatan_txt").prop("required", false);
        const catatanContainer = $('#catatan')
        catatanContainer.hide()

        isVerified.onchange = (event) => {
                    if (event.target.checked) {
                        catatanContainer.hide()
                        $("#catatan_txt").prop("required", false);
                    }
                };

        isNoVerified.onchange = (event) => {
                    if (event.target.checked) {
                        catatanContainer.show()
                        $("#catatan_txt").prop("required", true);
                    }
                };

    });

    $("#delModal").on("show.bs.modal", function (event) {
        const link = $(event.relatedTarget);
        const id = link.data("id");
        console.log(id);
        const url = `{{ url('admin/input-data/bankeu/delete') }}/` + id;
        console.log(url);
        const modal = $(this);
        modal.find(".modal-footer #delHref").attr("href", url);
    });
</script>
@endsection
