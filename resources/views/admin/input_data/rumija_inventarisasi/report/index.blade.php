@extends('admin.layout.index') @section('title') Laporan Inventarisasi Rumija @endsection

@section('head')
@endsection

@section('page-header')
  <div class="row align-items-end">
    <div class="col-lg-8">
      <div class="page-header-title">
        <div class="d-inline">
          <h4>Laporan Inventarisasi</h4>
          <span></span>
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
          <li class="breadcrumb-item"><a href="#!">Laporan Inventarisasi</a></li>
        </ul>
      </div>
    </div>
  </div>
@endsection

@section('page-body')
  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h5>Laporan Inventarisasi</h5>
          <div class="card-header-right">
            <ul class="list-unstyled card-option">
              <li>
                <i class="feather icon-minus minimize-card"></i>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-block">
          <form method="GET" action="{{ route('rumija.inventarisasi.report.download') }}">
            @if (Auth::user()->internalRole->uptd)
              <input type="hidden" id="uptd" name="uptd_id" value="{{ Auth::user()->internalRole->uptd }}">
            @else
              <div class="form-group row">
                <label class="col-md-2 col-form-label">UPTD</label>
                <div class="col-md-4">
                  <select class="form-control searchableField" id="uptd" name="uptd_id" onchange="onChangeUPTD()"
                    required>
                    <option>Pilih UPTD</option>
                    @foreach ($input_uptd_lists as $data)
                      <option value="{{ $data->id }}">{{ $data->nama }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            @endif
            <div class="form-group row">
              <label class="col-md-2 col-form-label">SUP</label>
              <div class="col-md-4">
                <select class="form-control searchableField" id="sup" name="sup_id" onchange="onChangeSUP()" required>
                  @if (Auth::user()->internalRole->uptd)
                    @foreach ($sup as $data)
                      <option value="{{ $data->kd_sup }}" @if (Auth::user()->sup_id != null && Auth::user()->sup_id == $data->id) selected @endif>{{ $data->name }}</option>
                    @endforeach
                  @else
                    <option>-</option>
                  @endif
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-2 col-form-label">Ruas Jalan</label>
              <div class="col-md-4">
                <select class="form-control searchableField" multiple id="ruas_jalan" name="id_ruas_jalan[]" required>
                  @if (Auth::user()->internalRole->uptd)
                    @foreach ($input_ruas_jalan as $data)
                      <option value="{{ $data->id_ruas_jalan . '___' . strtoupper($data->nama_ruas_jalan) }}">
                        {{ strtoupper($data->nama_ruas_jalan) }}</option>
                    @endforeach
                  @else
                    <option>-</option>
                  @endif
                </select>
              </div>
            </div>

            <div class=" form-group row">
              <div class="col-6">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Download</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    function onChangeUPTD() {
      id = document.getElementById("uptd").value
      url = "{{ url('admin/master-data/ruas-jalan/getSUP') }}"
      id_select = '#sup'
      text = 'Pilih SUP'
      option = 'name'
      id_supp = 'kd_sup'
      setDataSelect(id, url, id_select, text, id_supp, option)

      //   url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalan') }}"
      //   id_select = '#ruas_jalan'
      //   text = 'Pilih Ruas Jalan'
      //   option = 'nama_ruas_jalan'
      //   id_ruass = 'id_ruas_jalan'
      //   setDataSelect(id, url, id_select, text, id_ruass, option)
    }

    function onChangeSUP() {
      id = document.getElementById("sup").value
      url = "{{ url('admin/input-data/kondisi-jalan/getRuasJalanBySup') }}"
      id_select = '#ruas_jalan'
      text = 'Pilih Ruas Jalan'
      option = 'nama_ruas_jalan'
      id_ruass = 'id_ruas_jalan'

      $.ajax({
        url: url,
        method: "get",
        dataType: "JSON",
        data: {
          id: id,
        },
        complete: function(result) {
          $(id_select).empty();
          $(id_select).append($("<option disable></option>").text(text));

          result.responseJSON.forEach(function(item) {
            $(id_select).append(
              $("<option></option>")
              .attr("value", item['id_ruas_jalan'] + "___" + item['nama_ruas_jalan'].toUpperCase() + "___" + (
                Number(item['panjang']) / 1000))
              .text(item[option].toUpperCase())
            );
          });
        },
      });
    }
  </script>
@endsection
