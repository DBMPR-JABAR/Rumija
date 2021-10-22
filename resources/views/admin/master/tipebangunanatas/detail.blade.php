<form action="{{ route('tipebangunanatas.update', $data->id) }}" method="post">
    @method('PUT')
    @csrf

    <div class="modal-body">

        <div class="form-group row">
            <label class="col-md-2 col-form-label">Tipe Bangunan Atas</label>
            <div class="col-md-10">
                <input name="nama" type="text" class="form-control" value="{{$data->nama}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2 col-form-label">Keterangan</label>
            <div class="col-md-10">
                <input name="deskripsi" type="text" class="form-control" value="{{$data->deskripsi}}" required>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary waves-effect waves-light ">Simpan</button>
        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
    </div>
</form>

