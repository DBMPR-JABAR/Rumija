@extends('admin.layout.index')

@section('title') Pengumuman @endsection

@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Pengumuman</h4>
                    {{-- <span>Master Data Item Bahan Material</span> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('announcement.index') }}">Pengumuman</a>
                    </li>
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
                        <h5>Tambah Pengumuman</h5>
                    @else
                        <h5>Perbaharui Pengumuman</h5>
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
                        <form action="{{ route('announcement.store') }}" method="post" enctype="multipart/form-data">
                    @else
                        <form action="{{ route('announcement.update', @$announcement->id) }}" method="post" enctype="multipart/form-data">
                        @method('PUT')
                    @endif
                    @csrf

                    
                    <div class="form-group">
                        <label>Cover</label>
                        {{-- <input type="file" name="cover" class="form-control @error('cover') is-invalid @enderror"> --}}
                        <input name="cover" type="file" class="form-control @error('cover') is-invalid @enderror" accept="image/*">

                        @error('cover')
                        <div class="invalid-feedback" style="display: block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <fieldset class="form-group">
                        <div class="row">
                          <legend class="col-form-label col-sm-2">Dikirim Ke</legend>
                            <div class="col-sm-10">
                                <div class="form-check">
                                    <input class="form-check-input @error('sent_to') is-invalid @enderror" type="radio" name="sent_to" id="internal" value="internal" @if(@$announcement->sent_to == 'internal') checked  @endif>
                                    <label class="form-check-label" for="internal">
                                    Internal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('sent_to') is-invalid @enderror" type="radio" name="sent_to" id="masyarakat" value="masyarakat" @if(@$announcement->sent_to == 'masyarakat') checked  @endif>
                                    <label class="form-check-label" for="masyarakat">
                                        Masyarakat
                                    </label>
                                </div>
                              
                            </div>
                            @error('sent_to')
                            <div class="invalid-feedback" style="display: block; color:red">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </fieldset>
                  
                    <div class="form-group">
                        <label>Judul</label>
                        <input type="text" name="title" value="{{ old('title', @$announcement->title) }}" placeholder="Masukkan Title " class="form-control @error('title') is-invalid @enderror">
                        {{-- <p class="help-block">Example block-level help text here.</p> --}}
                        @error('title')
                        <div class="invalid-feedback" style="display: block; color:red">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Konten</label>
                        <textarea class="form-control content @error('content') is-invalid @enderror" name="content" placeholder="Masukkan Konten / Isi Berita" rows="10">{!! old('content',@$announcement->content) !!}</textarea>
                        @error('content')
                        <div class="invalid-feedback" style="display: block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    <div class=" form-group row">
                        <a href="{{ route('announcement.index') }}"><button type="button"
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.6.2/tinymce.min.js"></script>
    <script>
         var editor_config = {
         selector: "textarea.content",
         plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
          };
    
          tinymce.init(editor_config);
    </script>
@endsection
