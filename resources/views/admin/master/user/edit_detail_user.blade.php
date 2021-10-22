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
    <link rel="stylesheet" href="{{ asset('assets/vendor/chosen_v1.8.7/chosen.css') }}">

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
                    <h4>Edit Profil {!! Str::title(@@$profile_users->nama) !!} </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Edit Profil</a> </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')

    <form action="{{ url('admin/edit/profile/' . Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Informasi Pribadi</h4>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                                <li><i class="feather icon-minus minimize-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input name="nama" placeholder="Enter your First name" type="text"
                                value="{{ old('nama', @$profile->nama) }}"
                                class="form-control  @error('nama') is-invalid @enderror" required>
                            @error('nama')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>NIP/NIK</label>
                            <input name="no_pegawai" placeholder="Masukan NIP/NIK" type="number"
                                value="{{ old('no_pegawai', @$profile->no_pegawai) }}"
                                class="form-control  @error('no_pegawai') is-invalid @enderror" required>
                            @error('no_pegawai')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input name="tmp_lahir" placeholder="Masukan Tempat lahir" type="text"
                                        value="{{ old('tmp_lahir', @$profile->tmp_lahir) }}"
                                        class="form-control @error('tmp_lahir') is-invalid @enderror" required>
                                    @error('tmp_lahir')
                                        <div class="invalid-feedback" style="display: block; color:red">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input name="tgl_lahir" placeholder="Enter your date" type="date"
                                        value="{{ old('tgl_lahir', @$profile->tgl_lahir) }}"
                                        class="form-control @error('tgl_lahir') is-invalid @enderror" required>
                                    @error('tgl_lahir')
                                        <div class="invalid-feedback" style="display: block; color:red">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <select class="form-control" name="jenis_kelamin">

                                        <option>Select</option>
                                        {{-- <option selected>
                                        {!!  @$profile->jenis_kelamin !!}
                                    </option> --}}
                                        <option value="Laki-laki" @if (@$profile->jenis_kelamin != null && strpos('Laki-laki', @$profile->jenis_kelamin) !== false) selected @endif>Laki-Laki</option>
                                        <option value="Perempuan" @if (@$profile->jenis_kelamin != null && strpos('Perempuan', @$profile->jenis_kelamin) !== false) selected @endif>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Agama</label>
                                    <select class="form-control" name="agama" required>
                                        <option>Select</option>
                                        {{-- <option selected>
                                        {!!  @$profile->jenis_kelamin !!}
                                    </option> --}}
                                        <option value="Islam" @if (@$profile->agama != null && strpos('Islam', @$profile->agama) !== false) selected @endif>Islam</option>
                                        <option value="Kristen" @if (@$profile->agama != null && strpos('Kristen', @$profile->agama) !== false) selected @endif>Kristen</option>
                                        <option value="Hindu" @if (@$profile->agama != null && strpos('Hindu', @$profile->agama) !== false) selected @endif>Hindu</option>
                                        <option value="Budha" @if (@$profile->agama != null && strpos('Budha', @$profile->agama) !== false) selected @endif>Budha</option>
                                        <option value="Katolik" @if (@$profile->agama != null && strpos('Katolik', @$profile->agama) !== false) selected @endif>Katolik</option>
                                        <option value="Kong Hu Cu" @if (@$profile->agama != null && strpos('Kong Hu Cu', @$profile->agama) !== false) selected @endif>Kong Hu Cu</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Telepon</label>
                                    <input type="text" name="no_tlp" id="no_tlp"
                                        value="{{ old('no_tlp', @$profile->no_tlp) }}" placeholder="Masukan Nomer Telepon"
                                        class="form-control @error('no_tlp') is-invalid @enderror" required>
                                    @error('no_tlp')
                                        <div class="invalid-feedback" style="display: block; color:red">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Telepon Rumah</label>
                                    <input type="text" name="no_tlp_rumah" id="no_tlp_rumah"
                                        value="{{ old('no_tlp_rumah', @$profile->no_tlp_rumah) }}"
                                        placeholder="Masukan Telepon Rumah"
                                        class="form-control @error('no_tlp_rumah') is-invalid @enderror">
                                    @error('no_tlp_rumah')
                                        <div class="invalid-feedback" style="display: block; color:red">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Pekerjaan</h4>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                                <li><i class="feather icon-minus minimize-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="form-group">
                            <label>Pilih Jabatan</label>
                            <select class="form-control searchableField" disabled="true">
                                <option value="">Pilih Jabatan</option>
                                @foreach ($role as $data)
                                    <option value="{{ $data->id }}" @if ($user->internal_role_id == $data->id) selected @endif>{{ $data->role }}</option>
                                @endforeach
                            </select>
                            <i style="color :red; font-size: 10px;">Untuk perubahan hubungi admin</i>


                        </div>
                        <div class="form-group">
                            <label>SUP</label>
                            <select name="sup_id" id="sup_id" onchange="ubahOption1()" class="form-control searchableField  @error('sup_id') is-invalid @enderror">
                                <option value="">Pilih SUP</option>
                                @foreach ($input_sup as $data)
                                    <option value="{{ $data->kd_sup }}" @if (Auth::user()->sup_id != null && Auth::user()->sup_id == $data->id) selected @endif>{{ $data->name }}</option>
                                @endforeach
                            </select>
                           
                            @error('sup_id')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                            {{-- <i style="color :red; font-size: 10px;">Untuk perubahan hubungi admin</i> --}}
                        </div>
                        <div class="form-group">
                            <label>Ruas Jalan</label>
                            <select data-placeholder="Ruas jalan" id="ruas_jalan" name="ruas_jalan[]" class="form-control chosen-select @error('ruas_jalan') is-invalid @enderror" multiple >
                                <option value="">Pilih Ruas</option>
                                @foreach ($input_ruas_jalan as $data)
                                    <option value="{{ $data->id }}" @if(in_array($data->id,array_column( Auth::user()->ruas->toArray(), 'id'))) selected @endif>{{ $data->nama_ruas_jalan }}</option>    
                                @endforeach
                            </select>
                           
                            @error('ruas_jalan')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                            {{-- <i style="color :red; font-size: 10px;">Untuk perubahan hubungi admin</i> --}}
                        </div>
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input name="tgl_mulai_kerja" placeholder="Tanggal Mulai Kerja" type="date"
                                value="{{ old('tgl_mulai_kerja', @$profile->tgl_mulai_kerja) }}"
                                class="form-control  @error('tgl_mulai_kerja') is-invalid @enderror">
                            @error('tgl_mulai_kerja')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Riwayat Pendidikan</h4>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                                <li><i class="feather icon-minus minimize-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="form-group">
                            <label>Nama Institusi</label>
                            <input name="sekolah" placeholder="Masukan Institusi" type="text"
                                value="{{ old('sekolah', @$profile->sekolah) }}"
                                class="form-control  @error('sekolah') is-invalid @enderror">
                            @error('sekolah')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Jejang Pendidikan</label>
                            <select class="form-control" name="jejang">
                                <option value="">Select</option>
                                {{-- <option selected>
                                {!!  @$profile->jejang !!}
                            </option> --}}
                                <option value="SMA" @if (@$profile->jejang != null && strpos('SMA', @$profile->jejang) !== false) selected @endif>SMA</option>
                                <option value="SMK" @if (@$profile->jejang != null && strpos('SMK', @$profile->jejang) !== false) selected @endif>SMK</option>
                                <option value="S1" @if (@$profile->jejang != null && strpos('S1', @$profile->jejang) !== false) selected @endif>S1</option>
                                <option value="S2" @if (@$profile->jejang != null && strpos('S2', @$profile->jejang) !== false) selected @endif>S2</option>
                                <option value="S3" @if (@$profile->jejang != null && strpos('S3', @$profile->jejang) !== false) selected @endif>S3</option>
                                <option value="D1" @if (@$profile->jejang != null && strpos('D1', @$profile->jejang) !== false) selected @endif>D1</option>
                                <option value="D2" @if (@$profile->jejang != null && strpos('D2', @$profile->jejang) !== false) selected @endif>D2</option>
                                <option value="D3" @if (@$profile->jejang != null && strpos('D3', @$profile->jejang) !== false) selected @endif>D3</option>
                                <option value="SMP" @if (@$profile->jejang != null && strpos('SMP', @$profile->jejang) !== false) selected @endif>SMP</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Jurusan Pendidikan</label>
                            <input name="jurusan_pendidikan" placeholder="Masukan Jurusan" type="text"
                                value="{{ old('jurusan_pendidikan', @$profile->jurusan_pendidikan) }}"
                                class="form-control  @error('jurusan_pendidikan') is-invalid @enderror">
                            @error('jurusan_pendidikan')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="card">
                    <div class="card-header ">
                        <h4 class="card-title">Alamat Domisili</h4>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                                <li><i class="feather icon-minus minimize-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="form-group">
                            <label>Provinsi</label>

                            <select name="provinsi" id="province" class="form-control searchableField"
                                onchange="ubahOption()">
                                <option value="">== Select Provinsi ==</option>
                                @foreach ($provinces as $id => $name)
                                    <option value="{{ $id }}" @if (@$profile->province_id != null && @$profile->province_id == $id) selected @endif>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kota / Kabupaten</label>

                            <select name="kota" id="city" class="form-control searchableField">
                                <option value="">-</option>
                                @if (@$profile->city_id != null)
                                    @foreach ($cities as $id => $name)
                                        <option value="{{ $id }}" @if (@$profile->city_id != null && @$profile->city_id == $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                @else
                                    <option value="">-</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input name="kode_pos" placeholder="Masukan Jurusan" type="text"
                                value="{{ old('kode_pos', @$profile->kode_pos) }}"
                                class="form-control  @error('kode_pos') is-invalid @enderror">
                            @error('kode_pos')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <textarea name="alamat" placeholder="Masukan Alamat Lengkap"
                                class="form-control  @error('alamat') is-invalid @enderror">{!!  @$profile->alamat !!}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback" style="display: block; color:red">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger waves-effect "
                        data-dismiss="modal">Kembali</button></a>
                <button type="submit" class="btn btn-responsive btn-primary"><i class="fa fa-paper-plane"></i> Save</button>
            </div>
        </div>
        {{-- <button type="submit" class="btn btn-responsive btn-primary"><i class="fa fa-paper-plane"></i> Save</button> --}}
    </form>
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
        });
    </script>
    <script>
        // $(function () {
        //     $('#province').on('change', function () {
        //         axios.post('{{ url('getCITIES') }}', {id: $(this).val()})
        //             .then(function (response) {
        //                 $('#city').empty();

        //                 $.each(response.data, function (id, name) {
        //                     $('#city').append(new Option(name, id))
        //                 })
        //             });
        //     });
        // });

        function ubahOption() {

            //untuk select SUP
            id = document.getElementById("province").value
            url = "{{ url('getCity') }}"
            id_select = '#city'
            text = '== Select City =='
            option = 'name'
            value = 'id'


            setDataSelect(id, url, id_select, text, value, option)

        }
        function setDataSelectChosen(id, url, id_select, text, valueOption, textOption) {
            $.ajax({
                url: url,
                method: "get",
                dataType: "JSON",
                data: {
                    id: id,
                },
                complete: function(result) {
                    
                    $(id_select).empty(); // remove old options
                    $(id_select).append($("<option disable></option>").text(text));
                    let i = 0;
                    result.responseJSON.forEach(function(item) {
                        $(id_select).append(
                            $("<option></option>")
                            .attr("value", item[valueOption])
                            .text(item[textOption])
                        )
                        i++
                        
                    });
                    
                    if(i === result.responseJSON.length){ 
                        $(id_select).chosen("destroy")
                        $(id_select).chosen()
                    }

                },
            });
        }
        function ubahOption1() {

            //untuk select SUP
            id = document.getElementById("sup_id").value
            
            //untuk select Ruas
            url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalanBySup') }}"
            id_select = '#ruas_jalan'
            text = 'Pilih Ruas Jalan'
            option = 'nama_ruas_jalan'
            id_ruass = 'id'
            
          
            setDataSelectChosen(id, url, id_select, text, id_ruass, option)
         

        }

    </script>

@endsection
