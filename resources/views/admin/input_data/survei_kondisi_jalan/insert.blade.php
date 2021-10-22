@extends('admin.layout.index')

@section('title') Survei Kondisi Jalan @endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Survei Kondisi Jalan</h4>
                    <span>Seluruh Survei Kondisi Jalan yang ada di naungan DBMPR Jabar</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('survei_kondisi_jalan.index') }}">Survei Kondisi
                            Jalan</a> </li>
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
                    @if ($action == 'store')
                        <h5>Tambah Data Survei Kondisi Jalan</h5>
                    @else
                        <h5>Perbaharui Data Survei Kondisi Jalan</h5>
                    @endif
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block pl-5 pr-5 pb-5">


                    @if ($action == 'store')
                        <form action="{{ route('survei_kondisi_jalan.store') }}" method="post">
                        @else
                            <form action="{{ route('survei_kondisi_jalan.update', $surveiKondisiJalan->id) }}"
                                method="post">
                                @method('PUT')
                    @endif
                    @csrf

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nama Ruas Jalan dan RoadId</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <select id="id_ruas_jalan" name="id_ruas_jalan" class="form-control searchableField"
                                        required>
                                        @foreach ($ruas_jalan_lists as $data)
                                            <option value="{{ $data->id_ruas_jalan }}" @isset($surveiKondisiJalan)
                                                    {{ $data->id_ruas_jalan == $surveiKondisiJalan->id_ruas_jalan ? 'selected' : '' }}
                                                @endisset>
                                                {{ $data->nama_ruas_jalan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input name="road_id" value="{{ @$surveiKondisiJalan->road_id }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Latitude dan Longitude</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="latitude" value="{{ @$surveiKondisiJalan->latitude }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                                <div class="col-md-6">
                                    <input name="longitude" value="{{ @$surveiKondisiJalan->longitude }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Jarak (m) dan Id Segmen</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="distance" value="{{ @$surveiKondisiJalan->distance }}" type="text"
                                        class="form-control formatLatLong">
                                </div>
                                <div class="col-md-6">
                                    <input name="id_segmen" value="{{ @$surveiKondisiJalan->id_segmen }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Kecepatan dan Kecepatan Rata-rata (Km/j)</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="speed" value="{{ @$surveiKondisiJalan->speed }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                                <div class="col-md-6">
                                    <input name="avg_speed" value="{{ @$surveiKondisiJalan->avg_speed }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">Altitude dan Grade (%)</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="altitude" value="{{ @$surveiKondisiJalan->altitude }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                                <div class="col-md-6">
                                    <input name="grade" value="{{ @$surveiKondisiJalan->grade }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=" form-group row">
                        <label class="col-md-3 col-form-label">eIRI dan cIRI</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="e_iri" value="{{ @$surveiKondisiJalan->e_iri }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                                <div class="col-md-6">
                                    <input name="c_iri" value="{{ @$surveiKondisiJalan->c_iri }}" type="text"
                                        class="form-control formatLatLong" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <a href="{{ route('survei_kondisi_jalan.index') }}"><button type="button"
                                class="btn btn-default waves-effect">Batal</button></a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light ml-2">Simpan</button>
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

    <script>
    </script>
@endsection
