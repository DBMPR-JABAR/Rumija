@extends('admin.layout.index')

@section('title') Admin Teman Jabar
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/progress_buble.css') }}" />
    <link rel="stylesheet" href="https://js.arcgis.com/4.18/esri/themes/light/main.css" />
@endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Pengujian Bahan LABKON</h4>
                    <span>Input Data Pengujian Bahan LABKON </span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ url('admin/pengujian_bahan/dashboard') }}">Pengujian Bahan
                            LABKON</a> </li>
                    <li class="breadcrumb-item"><a href="#">Tambah</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Pendaftaran Pengujian Bahan LABKON</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block pl-5 pr-5 pb-5">
                    <ul class="progress-indicator mb-3">
                        <li class="completed">
                            <span class="bubble"></span> Pendaftaran
                        </li>
                        <li class="completed">
                            <span class="bubble"></span> Bahan Uji
                        </li>
                        <li class="active" id="pengkajian_proggress">
                            <span class="bubble"></span> Pengkajian Permintaan Pengujian
                        </li>
                        <li id="pengujian_proggress">
                            <span class="bubble"></span> Pengujian
                        </li>
                        <li>
                            <span class="bubble"></span> Selesai
                        </li>
                    </ul>

                    <form id="form_pengkajian" action="javascript:void(0)" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No Permohonan Sementara</label>
                            <div class="col-md-9">
                                <input name="no_permohonan" type="text" class="form-control" required
                                    value="{{ $no_permohonan }}" readonly>
                            </div>
                        </div>
                        <div id="check_box_bahan_uji"></div>
                        <div id="waktu_hari" class="form-group m-0 row">
                            <label class="col-md-3 ml-0 pl-0 col-form-label">Hari</label>
                            <div class="col-md-1 pl-2 ml-0">
                                <input name="hari" type="number" class="form-control" required placeholder="0">
                            </div>
                            <div class="form-group col-md-4 row">
                                <label class="col-md-3 col-form-label">Dari</label>
                                <div class="col-md-9">
                                    <input name="dari" type="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group col-md-4 row">
                                <label class="col-md-3 col-form-label">Sampai</label>
                                <div class="col-md-9">
                                    <input name="sampai" type="date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-md-3 col-form-label">Kondisi Sampel</label>
                            <div class="col-md-9"><select class="searchableField form-control" style="width: 100%"
                                    id="kondisi_sampel" name="kondisi_sampel"></select></div>
                        </div>
                        <div class=" form-group row">
                            <a href="{{ route('listPengujianLabKon') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
                            <button id="ada_yang_kurang" type="button"
                                class="btn btn-warning waves-effect waves-light ml-2">Ada Yang
                                Kurang</button>
                            <button id="lanjut" type="submit"
                                class="btn btn-primary waves-effect waves-light ml-2">Lanjut</button>
                        </div>
                    </form>

                    <form id="form_pengujian" action="javascript:void(0)" method="post">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">No Permohonan</label>
                            <div class="col-md-9">
                                <input name="no_permohonan" type="text" class="form-control" required
                                    value="{{ $no_permohonan }}" readonly>
                            </div>
                        </div>
                        <div id="check_box_bahan_uji"></div>
                        <div class="form-group row"><label class="col-md-3 col-form-label">Status Pengujian
                                Labolatorium</label>
                            <div class="col-md-9">
                                <select class="form-control searchableField" style="width: 100%"
                                    id="status_pengujian_labolatorium" name="status_pengujian_labolatorium" required>
                                    <option value="1">Pilih Status Pengujian</option>
                                    <option value="2">On Progress</option>
                                    <option value="3">Selesai</option>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Kendala</label>
                            <div class="col-md-9">
                                <textarea name="kendala_labolatorium" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row"><label class="col-md-3 col-form-label">Status Pengujian Lapangan</label>
                            <div class="col-md-9">
                                <select class="form-control searchableField" style="width: 100%"
                                    id="status_pengujian_lapangan" name="status_pengujian_lapangan" required>
                                    <option value="1">Pilih Status Pengujian</option>
                                    <option value="2">On Progress</option>
                                    <option value="3">Selesai</option>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Kendala</label>
                            <div class="col-md-9">
                                <textarea name="kendala_lapangan" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class=" form-group row">
                            <a href="{{ route('listPengujianLabKon') }}"><button type="button"
                                    class="btn btn-default waves-effect">Batal</button></a>
                            <button id="simpan_pengujian" type="submit"
                                class="btn btn-primary waves-effect waves-light ml-2">Simpan</button>
                        </div>
                    </form>
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
    <script src="{{ asset('assets/vendor/jquery/js/jquery.mask.js') }}"></script>
    <script src="https://js.arcgis.com/4.18/"></script>
    <script>
        const bahanUji = [{
            menu: 'Apakah sudah memenuhi persyaratan ?',
            checksBox: [{
                name: 'Lingkup',
                id: 'linkup'
            }, {
                name: 'Jenis Sampel',
                id: 'jenis_sampel'
            }, {
                name: 'Metode',
                id: 'metode'
            }, {
                name: 'Personil',
                id: 'personil'
            }, {
                name: 'Waktu',
                id: 'waktu'
            }]
        }]

        let htmlCheckBoxBahanUji = ""
        bahanUji.forEach(bahan => {
            htmlCheckBoxBahanUji +=
                `<div class="form-group row">
                                                                                                                                                                                                                                                                                                                    <h4 class="col-md-3 col-form-label">${bahan.menu}</h4>
                                                                                                                                                                                                                                                                                                                    <div class="col-md-9 border-checkbox-section row">`
            bahan.checksBox.forEach(checkBox => {
                htmlCheckBoxBahanUji +=
                    `<div class="col-md-6 border-checkbox-group border-checkbox-group-primary">
                                                                                                                                                                                                                                                                                                                        <input class="border-checkbox" type="checkbox" name="${checkBox.id}" id="${checkBox.id}">
                                                                                                                                                                                                                                                                                                                        <label class="border-checkbox-label" for="${checkBox.id}">${checkBox.name}</label>
                                                                                                                                                                                                                                                                                                                        </div>`
            })
            htmlCheckBoxBahanUji +=
                `</div>
                                                                                                                                                                                                                                                                                                                </div>`
        })

        const kondisiSampel = [{
            name: 'Pilih Kondisi Sampel',
            value: 0
        }, {
            name: 'Baik',
            value: 'baik'
        }, {
            name: 'Sedang',
            value: 'sedang'
        }, {
            name: 'Buruk',
            value: 'buruk'
        }]
        let htmlDropdownkondisiSampel = '';
        kondisiSampel.forEach(metode => {
            htmlDropdownkondisiSampel += `<option value="${metode.value}">${metode.name}</option>`
        })


        $(document).ready(() => {
            $('#form_pengujian').hide();
            $('#waktu_hari').hide();
            $('#check_box_bahan_uji').html(htmlCheckBoxBahanUji);
            $('#kondisi_sampel').html(htmlDropdownkondisiSampel);
            $('#waktu').ready(() => {
                $('#waktu').bind('click', () => {
                    $('#waktu').prop("checked") ? $('#waktu_hari').show() : $('#waktu_hari').hide();
                })
            })
            $('#lanjut').ready(() => {
                $('#lanjut').bind('click', () => {
                    $('#form_pengkajian').hide();
                    $('#pengkajian_proggress').removeClass('active').addClass('completed')
                    $('#pengujian_proggress').addClass('active')
                    $('#form_pengujian').show();
                })
            })
            localStorage.getItem('permohonanLabkonStatus') ?JSON.parse(localStorage.getItem('permohonanLabkonStatus')).filter(
                data => data.no_permohonan == @json($no_permohonan)).length > 0 ? ($('#form_pengkajian')
                .hide() && $('#form_pengujian').show()) : true : true
            $('#simpan_pengujian').ready(() => {
                $('#simpan_pengujian').bind('click', () => {
                    let permohonanLabkonStatus = [];
                    const permohonanUpdate = {
                        no_permohonan: @json($no_permohonan),
                        status: 2,
                        date: Date.now()
                    }
                    if (localStorage.getItem('permohonanLabkonStatus'))
                        permohonanLabkonStatus = JSON.parse(
                            localStorage.getItem(
                                'permohonanLabkonStatus'))
                    permohonanLabkonStatus = permohonanLabkonStatus.filter(data => {
                        return data.no_permohonan != permohonanUpdate.no_permohonan
                    })
                    permohonanLabkonStatus.push(permohonanUpdate)
                    console.log('test', JSON.parse(
                        localStorage.getItem(
                            'permohonanLabkonStatus')))
                    console.log('afterpush', permohonanLabkonStatus)
                    localStorage.setItem('permohonanLabkonStatus', JSON.stringify(
                        permohonanLabkonStatus));
                    // $(':input[type="submit"]').prop('disabled', true);
                    window.open("{{ route('listPengujianLabKon') }}?msg=success")
                })
            })
        })

    </script>
@endsection
