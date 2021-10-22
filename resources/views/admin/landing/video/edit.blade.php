<form action="{{ route('video-news.update', $data->id) }}" method="post">
    @method('PUT')
    @csrf

    <div class="modal-body">

        <div class="form-group row">
            <label class="col-md-2 col-form-label">Judul Video</label>
            <div class="col-md-10">
                <input name="title" type="text" class="form-control" value="{{$data->title}}" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-2 col-form-label">Video URL</label>
            <div class="col-md-10">
                <input name="url" type="text" class="form-control" value="{{$data->url}}" required>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary waves-effect waves-light ">Simpan</button>
        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
    </div>
</form>

