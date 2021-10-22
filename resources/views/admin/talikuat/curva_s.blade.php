@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Kendali Kontrak</h4>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="#!">Kendali Kontrak</a> </li>
            </ul>
        </div>
    </div>
</div>
<style>
    table.table-bordered tbody td {
        word-break: break-word;
        vertical-align: top;
    }
</style>
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
                                <div class="card-block">
                                    <div class="row">
                                        <div class="col-sm-12 col-xl-3 m-b-30">
                                            <h4 class="sub-title">Tahun</h4>
                                            <select id="filterTahun" name="tahun"
                                                class="form-control form-control-primary">
                                                <option value="0" selected>Semua</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-xl-3 m-b-30">
                                            <h4 class="sub-title">UPTD</h4>
                                            <select id="filterUPTD" name="select"
                                                class="form-control form-control-primary">
                                                @if (Auth::user()->internalRole->uptd)
                                                <option value="{{ Auth::user()->internalRole->uptd }}" selected>UPTD
                                                    {{ str_replace('uptd', '', Auth::user()->internalRole->uptd) }}
                                                </option>
                                                @else
                                                <option value="" selected>Semua</option>
                                                <option value="">Dinas</option>
                                                <option value="1">UPTD 1</option>
                                                <option value="2">UPTD 2</option>
                                                <option value="3">UPTD 3</option>
                                                <option value="4">UPTD 4</option>
                                                <option value="5">UPTD 5</option>
                                                <option value="6">UPTD 6</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-xl-3 m-b-30">
                                            <h4 class="sub-title">Kegiatan</h4>
                                            <select id="filterKegiatan" name="select"
                                                class="form-control form-control-primary">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-block accordion-block">
                <div id="iframe_curva"></div>
            </div>
        </div>
    </div>


</div>
@endsection

@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    const data_umum = @json($data_umum);

        const monthName = ['A', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
            'September', 'Oktober', 'November', 'Desember'
        ];

        const tahunField = "tgl_kontrak"

        const iframeCurvaTemplate = ({id}) => `<iframe id="iframe_curva" src="https://tk.temanjabar.net/curva/index.php?data=${id}" height="700"
                    style="border:none;" width="100%" title="curva"></iframe>`

        $(document).ready(function() {
            const filterUPTD = document.getElementById('filterUPTD')
            const filterKegiatan = document.getElementById('filterKegiatan')
            const iframeCurva = document.getElementById('iframe_curva')
            const filterTahun = document.getElementById('filterTahun')

            const tahunKontrak = data_umum.map(data=>{
                return new Date(data.tgl_kontrak).getFullYear()
            })

            const tahunUnique = [...new Set(tahunKontrak)];
            let htmlFilterTahun = ""
            tahunUnique.forEach(tahun=> htmlFilterTahun += `<option value="${tahun}">${tahun}</option>`)
            filterTahun.innerHTML += htmlFilterTahun

            const htmlFilterKegiatanTemplate = ({data_umum_filter}) => {
                let htmlFilterKegiatan = ""
                data_umum_filter.forEach(data => {
                htmlFilterKegiatan += `<option value="${data.id}">${data.nm_paket}</option>`
                })
                if(data_umum_filter.length == 0) {htmlFilterKegiatan = "<option>Tidak ada kegiatan</option>"
                iframeCurva.innerHTML = `<p class="text-center">Tidak ada data</p>`
            } else
                iframeCurva.innerHTML = iframeCurvaTemplate({id:data_umum_filter[0].id})
                return htmlFilterKegiatan
            }

            filterKegiatan.innerHTML = htmlFilterKegiatanTemplate({data_umum_filter:data_umum});

            iframeCurva.innerHTML = iframeCurvaTemplate({id:data_umum[0].id})

            filterTahun.onchange = (event) => {
                const value = event.target.value
                if(value != 0) {
                   const data_umum_filter = data_umum.filter(data=> new Date(data.tgl_kontrak).getFullYear() == value)
                   filterKegiatan.innerHTML= htmlFilterKegiatanTemplate({data_umum_filter})
                if(data_umum_filter.length > 0) iframeCurva.innerHTML = iframeCurvaTemplate({id:data_umum_filter[0].id})
                }
            }

            filterKegiatan.onchange = (event) => {
                iframeCurva.innerHTML = iframeCurvaTemplate({id:event.target.value})
                }

            filterUPTD.onchange = (event) => {
                const value = event.target.value
                let data_umum_filter
                if(value > 0) {
                data_umum_filter = data_umum.filter(data => data.id_uptd == value)
            } else {
                data_umum_filter = data_umum
            }
            filterKegiatan.innerHTML= htmlFilterKegiatanTemplate({data_umum_filter})
            }
        });

</script>
@endsection
