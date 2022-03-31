@extends('admin.layout.index') @section('title') Rumija @endsection
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
@endsection @section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Pelaporan Rumija</h4>
                {{-- <span>Data Seluruh Rumija</span> --}}
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
                <li class="breadcrumb-item"><a href="/admin">Rumija</a></li>
                <li class="breadcrumb-item"><a href="#!">Pelaporan Rumija</a></li>

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
                <h5>Pelaporan Rumija</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        {{--
                        <li><i class="feather icon-maximize full-card"></i></li>
                        --}}
                        <li>
                            <i class="feather icon-minus minimize-card"></i>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                
                <button type="button" class="mb-3 btn btn-mat btn-primary" data-toggle="modal" data-target="#exampleModal">Tambah</button>

                <!-- Modal -->
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">			     
                          <h3 class="modal-title" id="exampleModalLabel">Tambah Data</h3>
                        </div>
                        <div class="modal-body">
                          <form action="/pelaporan/create" method="POST">
                              {{csrf_field()}}
                            <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">Nama Pelapor</label>
                              <input name="pelapor_names" type="text" class="form-control @error('pelapor_names') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan Nama" required>
                              @error('pelapor_names')
                                  <div class="invalid-feedback">{{$message}}</div>
                              @enderror						    
                            </div>
  
                            <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">KTP Pelapor</label>
                              <input name="pelapor_ktps" type="text" class="form-control @error('pelapor_ktps') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan No KTP" required>
                              @error('pelapor_ktps')
                                  <div class="invalid-feedback">{{$message}}</div>
                              @enderror
                            </div>
  
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Pilih UPTD</label>
                                <select name="pelapor_uptd" class="form-control">
                                    <option selected>Pilih UPTD</option>
                                    <option value="UPTD 1">UPTD 1</option>
                                    <option value="UPTD 2">UPTD 2</option>
                                    <option value="UPTD 3">UPTD 3</option>
                                  <option value="UPTD 4">UPTD 4</option>
                              </select>
                            </div>						  
  
                            <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">Nomor Telpon Pelapor</label>
                              <input name="pelapor_numbers" type="text" class="form-control @error('pelapor_numbers') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan Nomor Telpon" required>
                                @error('pelapor_numbers')
                                  <div class="invalid-feedback">{{$message}}</div>
                              @enderror
                            </div>
  
                            <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">E-mail Pelapor</label>
                              <input name="pelapor_emails" type="text" class="form-control @error('pelapor_emails') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan E-mail" required>
                                @error('pelapor_emails')
                                  <div class="invalid-feedback">{{$message}}</div>
                              @enderror
                            </div>
  
                            <div class="mb-3">
                              <label for="exampleInputEmail1" class="form-label">Alamat Pelapor</label>
                              <input name="pelapor_address" type="text" class="form-control @error('pelapor_address') is-invalid @enderror" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan Alamat" required>
                                @error('pelapor_address')
                                  <div class="invalid-feedback">{{$message}}</div>
                              @enderror
                            </div>			  						  						  						  
                            
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>
              
                <div class="dt-responsive table-responsive">
                    <table id="rumija_table" class="table table-striped table-bordered able-responsive">
                        <thead>
                            <tr>
                                <th style="width: 1mm">No</th>
                                <th>NAMA</th>
                                <th>KTP</th>
                                <th>UPTD</th>
                                <th>Telepon</th>
                                <th>E-MAIL</th>
                                <th>ALAMAT</th>
                                <th style="width: 1mm">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelaporan as $pelaporan)
                            <tr>
                                <th>{{$loop->iteration}}</th>
                                <td>{{$pelaporan->pelapor_names}}</td>
                                <td>{{$pelaporan->pelapor_ktps}}</td>
                                <td>{{$pelaporan->pelapor_uptd}}</td>
                                <td>{{$pelaporan->pelapor_numbers}}</td>
                                <td>{{$pelaporan->pelapor_emails}}</td>
                                <td>{{$pelaporan->pelapor_address}}</td>
                                <td>
                                    <a href="/admin/pelaporan/{{$pelaporan->id}}/edit" class="btn btn-warning btn-sm">Ubah</a>
                                    <a href="/admin/pelaporan/{{$pelaporan->id}}/delete" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')">Hapus</a>
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
<div class="modal-only">
    <div class="modal fade" id="delModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Inventaris</h4>
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
    $(document).ready(function () {
        $("#rumija_table").DataTable({
            language: {
                emptyTable: "Tidak ada data tersedia.",
            },
            dom: 'Bfrtip',
            buttons: ['excel']
        });
    });

    $("#delModal").on("show.bs.modal", function (event) {
        const link = $(event.relatedTarget);
        const id = link.data("id");
        console.log(id);
        const url = `{{ url('admin/inventarisasi/delete') }}/` + id;
        console.log(url);
        const modal = $(this);
        modal.find(".modal-footer #delHref").attr("href", url);
    });
</script>
@endsection
