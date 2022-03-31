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
                <h4>Edit Pelaporan</h4>
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
                <li class="breadcrumb-item"><a href="#!">Pelaporan</a></li>

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
                <h5>Pelaporan</h5>
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
                        <thead>

                        </thead>
                        <tbody>
							<form action="/admin/pelaporan/{{$pelaporan->id}}/update" method="POST">
								{{csrf_field()}}
										  <div class="mb-3">
											<label for="exampleInputEmail1" class="form-label">Nama Pelapor</label>
											<input name="pelapor_names" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan Nama" value="{{$pelaporan->pelapor_names}}">
										  </div>
				
										  <div class="mb-3">
											<label for="exampleInputEmail1" class="form-label">KTP</label>
											<input name="pelapor_ktps" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan KTP" value="{{$pelaporan->pelapor_ktps}}">
										  </div>
				
										  <div class="mb-3">
											  <label for="exampleInputEmail1" class="form-label">UPTD</label>
											  <select class="form-control">
												  <option selected>Pilih UPTD</option>
												  <option value="UPTD 1" @if($pelaporan->pelapor_uptd == 'UPTD 1') selected @endif>UPTD 1</option>
												  <option value="UPTD 2" @if($pelaporan->pelapor_uptd == 'UPTD 2') selected @endif>UPTD 2</option>
												  <option value="UPTD 3" @if($pelaporan->pelapor_uptd == 'UPTD 3') selected @endif>UPTD 3</option>
												<option value="UPTD 4" @if($pelaporan->pelapor_uptd == 'UPTD 4') selected @endif>UPTD 4</option>
											</select>
										  </div>
				
										  <div class="mb-3">
											<label for="exampleInputEmail1" class="form-label">Telepon</label>
											<input name="pelapor_numbers" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan Telpon" value="{{$pelaporan->pelapor_numbers}}">
										  </div>
				
										  <div class="mb-3">
											<label for="exampleInputEmail1" class="form-label">E-Mail</label>
											<input name="pelapor_emails" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan E-Mail" value="{{$pelaporan->pelapor_emails}}">
										  </div>
				
										  <div class="mb-3">
											<label for="exampleInputEmail1" class="form-label">Alamat</label>
											<input name="pelapor_address" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Masukkan Alamat" value="{{$pelaporan->pelapor_address}}">
										  </div>
										  <button type="submit" class="btn btn-warning">Update</button>
										  <a href="/admin/pelaporan" class="btn btn-secondary">Back</a>
							</form>
                        </tbody>
                    </table>
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
@endsection
