@extends('admin.layout.index')

@section('title') DPA @endsection
@section('head')
  <link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/datatables.net/css/dataTables.bootstrap4.min.css') }}" />
  <link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/datatables.net/css/buttons.dataTables.min.css') }}" />
  <link rel="stylesheet" type="text/css"
    href="{{ asset('assets/vendor/data-table/extensions/responsive/css/responsive.dataTables.css') }}" />
  <style>
    table.table-bordered tbody td {
      word-break: break-word;
      vertical-align: top;
    }

    .no-border {
      border: 1px solid black !important
    }

  </style>
@endsection

@section('page-header')
  <div class="row align-items-end">
    <div class="col-lg-8">
      <div class="page-header-title">
        <div class="d-inline">
          <h4>DPA</h4>
          <span>DPA DBMPR Jabar</span>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="page-header-breadcrumb">
        <ul class=" breadcrumb breadcrumb-title">
          <li class="breadcrumb-item">
            <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
          </li>
          <li class="breadcrumb-item"><a href="{{ route('dpa.index') }}">DPA</a>
          </li>
          <li class="breadcrumb-item"><a href="#">{{ @$data->nama_paket }}</a> </li>
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
          <h5>Data DPA {{ @$data->nama_paket }}</h5>
          <div class="card-header-right">
            <ul class="list-unstyled card-option">
              <li><i class="feather icon-minus minimize-card"></i></li>
            </ul>
          </div>
        </div>

        <div class="p-5 card-block row">
          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Nama Paket</label>
            <div class="col-md-8">
              {{ @$data->nama_paket }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Kategori</label></label>
            <div class="col-md-8">
              {{ @$data->nama_kategori }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">UPTD</label>
            <div class="col-md-8">
              {{ @$data->nama_uptd }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Tahun Anggaran</label>
            <div class="col-md-8">
              {{ @$data->tahun_anggaran }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Pagu Anggaran</label>
            <div class="col-md-8">
              Rp. {{ @$data->pagu_anggaran }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Pagu Anggaran DPA Pergeseran</label>
            <div class="col-md-8">
              Rp. {{ @$data->pagu_anggaran_dpa_pergeseran }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Check</label>
            <div class="col-md-8">
              {{ @$data->nama_check }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Pendanaan</label>
            <div class="col-md-8">
              {{ @$data->nama_pendanaan }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Jenis Pengadaan</label>
            <div class="col-md-8">
              {{ @$data->nama_pengadaan }}
            </div>
          </div>

          <div class=" form-group row col-md-6">
            <label class="col-md-4 col-form-label font-weight-bold">Ruas Jalan</label>
            <div class="col-md-8">
              {{ @$data->nama_ruas_jalan ? $data->nama_ruas_jalan : '-' }}
            </div>
          </div>

          <div class="dt-responsive table-responsive">
            <table id="report" style="width: 100%" class="table table-borderless text-center no-border">
              <thead>
                <tr>
                  <th rowspan="3" class="align-middle no-border">Kode Rekening</th>
                  <th rowspan="3" class="align-middle no-border">Uraian</th>
                  <th colspan="5" class="align-middle no-border">Sebelum Perubahan</th>
                  <th colspan="5" class="align-middle no-border">Setelah Perubahan</th>
                  <th rowspan="3" class="align-middle no-border">Bertambah/(Berkurang)</th>
                </tr>
                <tr>
                  <th colspan="4" class="align-middle no-border">Rincian Perhitungan</th>
                  <th rowspan="2" class="align-middle no-border">Jumlah</th>
                  <th colspan="4" class="align-middle no-border">Rincian Perhitungan</th>
                  <th rowspan="2" class="align-middle no-border">Jumlah</th>
                </tr>
                <tr>
                  <th class="align-middle no-border">Koefisien</th>
                  <th class="align-middle no-border">Satuan</th>
                  <th class="align-middle no-border">Harga</th>
                  <th class="align-middle no-border">PPN</th>
                  <th class="align-middle no-border">Koefisien</th>
                  <th class="align-middle no-border">Satuan</th>
                  <th class="align-middle no-border">Harga</th>
                  <th class="align-middle no-border">PPN (%)</th>
                </tr>
              </thead>
              <tbody id="report-container">
              </tbody>
            </table>
          </div>

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
  <script type="text/javascript">
    var reports = @json($reports)

    const headReport = (data) => `<tr style="font-weight: bold"><td  class="align-middle no-border">${data.kode_rekening}</td>
        <td class="no-border text-left" colspan="5">${data.uraian}</td>
        <td class="align-middle no-border">Rp. ${data.sum.b_jumlah}</td>
        <td class="align-middle no-border" colspan="4"></td>
        <td class="align-middle no-border">Rp. ${data.sum.a_jumlah}</td>
        <td class="align-middle no-border">Rp. ${data.bertambah_berkurang || '-'}</td></tr>`

    const childReport = (data) => `<tr class="align-middle no-border"><td></td>
        <td class="no-border text-left">${data.uraian}</td>
        <td class="align-middle no-border">${data.b_koefisien}</td>
        <td class="align-middle no-border">${data.b_satuan}</td>
        <td class="align-middle no-border">${data.b_harga}</td>
        <td class="align-middle no-border">${data.b_ppn}</td>
        <td class="align-middle no-border">Rp. ${data.sum.b_jumlah}</td>
        <td class="align-middle no-border">${data.a_koefisien}</td>
        <td class="align-middle no-border">${data.a_satuan}</td>
        <td class="align-middle no-border">${data.a_harga}</td>
        <td class="align-middle no-border">${data.a_ppn}</td>
        <td class="align-middle no-border">Rp. ${data.sum.a_jumlah}</td>
        <td class="align-middle no-border">Rp. ${data.bertambah_berkurang || '-'}</td></tr>`

    function listToTree(list) {
      let map = {},
        node, roots = [],
        i;

      for (i = 0; i < list.length; i += 1) {
        map[list[i].id] = i;
        list[i].children = [];
      }

      for (i = 0; i < list.length; i += 1) {
        node = list[i];
        if (node.is_head == 0) {
          let sumBefore = Number(node.b_koefisien) * Number(node.b_harga);
          if (Number(node.b_ppn) > 0) sumBefore += (sumBefore * Number(node.b_ppn));
          node.b_jumlah = sumBefore.toFixed(2);;
          let sumAfter = Number(node.a_koefisien) * Number(node.a_harga);
          if (Number(node.a_ppn) > 0) sumAfter += (sumAfter * Number(node.a_ppn));
          node.a_jumlah = sumAfter.toFixed(2);

          if (sumAfter > sumBefore) {
            node.bertambah_berkurang = sumAfter - sumBefore
          } else {
            node.bertambah_berkurang = sumBefore - sumAfter
          }

        }
        if (node.parent_id !== null) {
          list[map[node.parent_id]].children.push(node);
        } else {
          roots.push(node);
        }
      }

      return roots;
    }


    $(document).ready(() => {

      const tree = listToTree(reports)

      let tempTree = tree
      let tempChild = {}

      let depth = 0;
      const calculate = array => {
        array.forEach(arr => {
          tempChild[`${depth}_${arr.id}_${arr.parent_id}`] = {
            a_jumlah: Number(arr.a_jumlah),
            b_jumlah: Number(arr.b_jumlah)
          }
          if (arr.children.length > 0) {
            depth++
            calculate(arr.children)
          }
        })
      }

      calculate(tree)

      let calcResults = {}
      const sorting = Object.keys(tempChild).sort((a, b) =>
        b.split('_')[0] - a.split('_')[0]
      )

      const sortReport = sorting.map(key => {
        const currentId = key.split('_')[1];
        return reports.find(report => report.id == currentId)
      })

      const results = sortReport.map(report => {
        const currentId = sorting.find(key => key.split('_')[1] == report.id)
        const childsKeys = sorting.filter(key => key.split('_')[2] == report.id)

        let a_jumlah = 0;
        let b_jumlah = 0;

        if (childsKeys.length > 0) {
          childsKeys.forEach(key => {
            a_jumlah += tempChild[key].a_jumlah
            b_jumlah += tempChild[key].b_jumlah
          })

          tempChild[currentId] = {
            a_jumlah,
            b_jumlah
          }
        } else {
          a_jumlah = Number(report.a_jumlah)
          b_jumlah = Number(report.b_jumlah)
        }

        return {
          ...report,
          sum: {
            a_jumlah,
            b_jumlah
          }
        }
      })

      const resultsTree = listToTree(results)

      console.log(resultsTree)

      var html = '';

      function generateHTML(data) {
        data.forEach(report => {
          if (report.is_head == 1) {
            html += headReport(report)
          } else {
            html += childReport(report)
          }
          if (report.children.length > 0) {
            generateHTML(report.children)
          }
        })
      }


      generateHTML(resultsTree)

      $('#report-container').html(html)

      //   $('#report').DataTable({
      //     language: {
      //       emptyTable: "Tidak ada data tersedia.",
      //     },
      //   })
    })
  </script>
@endsection
