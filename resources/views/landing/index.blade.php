@extends('landing.template')
@section('body')

<!-- header -->
<header class="site-header" id="header">
    <nav class="navbar navbar-expand-lg transparent-bg static-nav">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <img src="{{ asset('assets/images/brand/text_putih.png') }}" alt=" logo" class="logo-default">
                <img src="{{ asset('assets/images/brand/text_hitam.png') }}" alt="logo" class="logo-scrolled">
            </a>
            <div class="collapse navbar-collapse">
                <ul class="mx-auto navbar-nav ml-xl-auto mr-xl-0">
                    <li class="nav-item">
                        <a class="nav-link active pagescroll" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll scrollupto" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item dropright">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" id="dropdownMenuButton" aria-expanded="true">Maps</a>
                        <div class="dropdown-menu" role="menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="https://tj.temanjabar.net/status-jalan/" target="_blank">Status
                                Jalan</a>
                            <a class="dropdown-item" href="{{ url('map/map-dashboard-masyarakat') }}">Kegiatan DBMPR</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#uptd">UPTD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#laporan">Laporan Kerusakan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#kontak">Kontak</a>
                    </li>
                    @if (Auth::check())
                    <li class="nav-item">
                        <a href="{{ url('admin') }}" class="nav-link">{{ Auth::user()->name }}</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a href="{{ url('login') }}" class="nav-link">Login</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <!--side menu open button-->
        <a href="javascript:void(0)" class="d-inline-block sidemenu_btn" id="sidemenu_toggle">
            <span></span> <span></span> <span></span>
        </a>
    </nav>
    <!-- side menu -->
    <div class="opacity-0 side-menu gradient-bg">
        <div class="overlay"></div>
        <div class="inner-wrapper">
            <span class="btn-close btn-close-no-padding" id="btn_sideNavClose"><i></i><i></i></span>
            <nav class="side-nav w-100">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active pagescroll" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll scrollupto" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#uptd">UPTD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#laporan">Laporan Kerusakan</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">Maps</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="https://tj.temanjabar.net/status-jalan/" target="_blank">Status
                                Jalan</a>
                            <a class="dropdown-item" href="{{ url('map/map-dashboard-masyarakat') }}">Kegiatan DBMPR</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pagescroll" href="#kontak">Kontak</a>
                    </li>
                    <a href="{{ url('login') }}">
                        <button type="button" class="btn btn-warning">Login</button>
                    </a>
                </ul>
            </nav>
            <div class="side-footer w-100">
                <ul class="social-icons-simple white top40">
                    <li><a href="{!! $profil->link_facebook !!}" target="_blank" class="facebook"><i
                                class="fab fa-facebook-f"></i> </a> </li>
                    <li><a href="{!! $profil->link_twitter !!}" target="_blank" class="twitter"><i
                                class="fab fa-twitter"></i> </a> </li>
                    <li><a href="{!! $profil->link_instagram !!}" target="_blank" class="insta"><i
                                class="fab fa-instagram"></i> </a> </li>
                    <li><a href="{!! $profil->link_youtube !!}" target="_blank" class="youtube"><i
                                class="fab fa-youtube"></i> </a> </li>
                </ul>
                <p class="whitecolor">&copy; <span id="year"></span> {{$profil->nama}}</p>
            </div>
        </div>
    </div>
    <div id="close_side_menu" class="tooltip"></div>
    <!-- End side menu -->
</header>
<!-- header -->
<!--Main Slider-->
<section id="home" class="position-relative">
    <div id="revo_main_wrapper" class="p-0 m-0 rev_slider_wrapper fullwidthbanner-container bg-dark"
        data-alias="classic4export" data-source="gallery">
        <!-- START REVOLUTION SLIDER 5.4.1 fullwidth mode -->
        <div id="rev_main" class="rev_slider fullwidthabanner white" data-version="5.4.1">
            <ul>
                @php $n = 1; @endphp
                @foreach ($slideshow as $slide)
                <li data-index="rs-0{{$n}}" data-transition="fade" data-slotamount="default"
                    data-easein="Power100.easeIn" data-easeout="Power100.easeOut" data-masterspeed="2000"
                    data-fsmasterspeed="1500" data-param1="0{{$n}}">
                    <!-- MAIN IMAGE -->
                    <img src="{{ url('storage/'.$slide->gambar) }}" alt="" data-bgposition="center center"
                        data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" class="rev-slidebg"
                        data-no-retina>
                    <div class="overlay overlay-dark opacity-6"></div>

                    <!-- LAYER NR. 2 -->
                    <div class="tp-caption tp-resizeme" data-x="['center','center','center','center']"
                        data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']"
                        data-voffset="['-70','-70','-50','-20']" data-width="none" data-height="none" data-type="text"
                        data-textAlign="['center','center','center','center']" data-responsive_offset="on"
                        data-start="1000"
                        data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","speed":2000,"to":"o:1;","delay":1500,"ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'>
                        <h1 class="font-bold text-center text-capitalize whitecolor">{{$slide->judul}}</h1>
                    </div>

                    @php $n++; @endphp
                </li>

                @endforeach

            </ul>
        </div>
    </div>
    <ul class="social-icons-simple revicon white">
        <li class="d-table"><a href="{!! $profil->link_facebook !!}" target="_blank" class="facebook"><i
                    class="fab fa-facebook-f"></i></a>
        </li>
        <li class="d-table"><a href="{!! $profil->link_twitter !!}" target="_blank" class="twitter"><i
                    class="fab fa-twitter"></i> </a> </li>
        <li class="d-table"><a href="{!! $profil->link_instagram !!}" target="_blank" class="insta"><i
                    class="fab fa-instagram"></i> </a> </li>
        <li class="d-table"><a href="{!! $profil->link_youtube !!}" target="_blank" class="youtube"><i
                    class="fab fa-youtube"></i> </a> </li>
    </ul>
</section>
<!--Main Slider ends -->
<!--Some Services-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="services-slider" class="owl-carousel">
                @foreach ($fitur as $fit)
                <div class="item">
                    <div class="service-box">
                        <span class="bottom25"><i class="{{ $fit->icon }}"></i></span>
                        <h4 class="bottom10 text-nowrap"><a href="{!! $fit->link !!}">{{ $fit->judul }}</a></h4>
                        <p>{{ $fit->deskripsi }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!--Some Services ends-->
<!--Some Feature -->
<section id="about" class="single-feature padding mt-n5">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="text-center col-lg-6 col-md-7 col-sm-7 text-sm-left wow fadeInLeft" data-wow-delay="300ms">
                <div class="mb-4 heading-title">
                    <h2 class="font-normal darkcolor bottom30">Kami {{ $profil->nama }}</h2>
                </div>
                <p class="bottom35">
                    {{ $profil->deskripsi }}
                </p>
                <a href="{!! $profil->link_website !!}" class="mb-4 button gradient-btn mb-sm-0">Lihat
                    Selengkapnya</a>
            </div>
            <div class="col-lg-5 offset-lg-1 col-md-5 col-sm-5 wow fadeInRight" data-wow-delay="300ms">
                <div class="image"><img alt="SEO" src="{!! url('storage/'.$profil->gambar) !!}"></div>
            </div>
        </div>
    </div>
</section>
<section id="video" class="padding">
    <div class="container">
        <h3 class="font-normal darkcolor bottom30">Berita kami</h3>
        <div class="row">
            @foreach ($pengumuman_masyarakat as $item)
            <div class="col-md-6">
                <a href="{{ route('announcementShow', $item->slug) }}" target="_blank">
                    <div class="mb-2 card w-100 ">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-top">
                                    <img class="media-object" src="{{ url('storage/pengumuman/'.$item->image) }}"
                                        height="100px" width="100px" alt="image">
                                </div>
                                <div class="media-body">
                                    <p class="media-heading">&nbsp; {{ $item->title }}
                                        <div class="pull-right">&nbsp; <span style="color :grey; font-size: 10px;"><i
                                                    class='icofont icofont-user'></i> {{ $item->nama_user }}|| <i
                                                    class='icofont icofont-time'></i>
                                                {{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</span>
                                        </div>


                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
            {{-- {{ $pengumuman_masyarakat->links() }} --}}

        </div>
        <div class="container-grid fadeInUp" data-wow-delay="300ms">
            @foreach ($video as $index => $data)
            <div class="vid{{++$index}}">
                <iframe width="100%" height="100%" src="{{$data->url}}" frameborder="0"></iframe>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--Some Feature ends-->
<!-- Counters -->
<section id="bg-counters" class="padding bg-counters parallax">
    <div class="container">
        <div class="text-center row align-items-center">
            <div class="col-lg-4 col-md-4 col-sm-4 bottom10">
                <div class="counters whitecolor top10 bottom10">
                    <span class="font-light count_nums" data-to="{{ $profil->pencapaian_selesai }}" data-speed="2500">
                    </span>
                </div>
                <h3 class="font-light whitecolor top20">Infrastruktur Yang terselesaikan diseluruh wilayah Jawa
                    Barat</h3>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <p class="font-light whitecolor top20 bottom20 title">Kami terus meningkatkan konektivitas jalan dan
                    infrastruktur ke seluruh wilayah Jawa Barat</p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 bottom10">
                <div class="counters whitecolor top10 bottom10">
                    <span class="font-light count_nums" data-to="{{ $profil->pencapaian_target }}" data-speed="2500">
                    </span>
                </div>
                <h3 class="font-light whitecolor top20">Target Infrastruktur diseluruh wilayah Jawa Barat</h3>
            </div>
        </div>
    </div>
</section>
<!-- Counters ends-->
<!-- Gallery -->
<section id="uptd" class="position-relative padding">
    <div class="container">
        <div class="row">
            <div class="text-center col-md-12 wow fadeIn" data-wow-delay="300ms">
                <div class="heading-title darkcolor wow fadeInUp" data-wow-delay="300ms">
                    <span class="defaultcolor"> Ayo pantau proses pembangunan di daerah anda </span>
                    <h2 class="font-normal darkcolor heading_space_half"> Unit Pelaksana Teknis Dinas Daerah (UPTD)
                    </h2>
                </div>
                <div class="col-md-12 offset-md-3 heading_space_half">
                    <p>Kabupaten/Kota di seluruh Jawa Barat</p>
                </div>
            </div>
            <div class="col-lg-12">
                <div id="mosaic-filter" class="text-center cbp-l-filters bottom30 wow fadeIn" data-wow-delay="350ms">
                    <div data-filter="*" class="cbp-filter-item">
                        <span>All</span>
                    </div>
                    @foreach ($uptd as $wil)
                    <div data-filter=".{{$wil->slug}}" class="cbp-filter-item">
                        <span>{{$wil->nama}}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-12">
                <div id="grid-mosaic" class="cbp cbp-l-grid-mosaic-flat">
                    @foreach ($uptd as $wil)
                    <div class="cbp-item {{$wil->slug}}">
                        <img src="{!! url('storage/'.$wil->gambar) !!}" alt="">
                        <div class="gallery-hvr whitecolor">
                            <div class="center-box">
                                <!-- <a href="{!! url('storage/'.$wil->gambar) !!}" class="opens" data-fancybox="gallery"
                                    title="Zoom In"> <i class="fa fa-search-plus"></i>
                                </a> -->
                                @if($wil->slug != 'uptdlabkon')
                                <a href="{{ url('uptd/'.$wil->slug) }}" class="opens" title="View Details">
                                    <i class="fas fa-link"></i>
                                </a>
                                @else
                                <a href="{{ url('uptd/labkon/home') }}" class="opens" title="View Details">
                                    <i class="fas fa-link"></i>
                                </a>
                                @endif
                                <h4 class="w-100">{{$wil->deskripsi}}</h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- <div class="col-lg-12">
                    Load more itema from another html file using ajax
                    <div id="js-loadMore-mosaic" class="cbp-l-loadMore-button ">
                        <a href="load-more.html"
                            class="border-0 cbp-l-loadMore-link font-13 button gradient-btn whitecolor transition-3"
                            rel="nofollow">
                            <span class="cbp-l-loadMore-defaultText">Load More (<span
                                    class="cbp-l-loadMore-loadItems">6</span>)</span>
                            <span class="cbp-l-loadMore-loadingText">Loading <i
                                    class="fas fa-spinner fa-spin"></i></span>
                            <span class="cbp-l-loadMore-noMoreLoading d-none">NO MORE WORKS</span>
                        </a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</section>
<!-- Gallery ends -->
<!-- Main sign-up section starts -->
<section id="laporan" class="bglight position-relative padding">
    <div class="container">
        <div class="row">
            <div class="text-center col-md-12 wow fadeIn" data-wow-delay="300ms">
                <h2 class="heading bottom40 darkcolor font-light2"><span class="font-normal">Laporkan</span> Kerusakan
                    <span class="divider-center"></span>
                </h2>
                <div class="col-md-12 offset-md-2 heading_space">
                    <p>Ayo bangun Infrastruktur bersama-sama...Laporkan kerusak di sekitar anda,kami akan
                        SEGERA memperbaikinya.</p>
                </div>
            </div>
            @if (Session::has('laporan-msg'))
            <div class="col-md-12">
                <div class="alert alert-{{ Session::get('color') }} alert-dismissible fade show" role="alert">
                    {{ Session::get('laporan-msg') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif
            <div class="col-lg-6 col-md-12 col-sm-12 pr-lg-0 whitebox wow fadeInLeft">
                <div class="widget logincontainer">
                    <h3 class="text-center darkcolor bottom35 text-md-left">Identitas Pelapor </h3>
                    <form action="{{ route('tambah-laporan') }}" method="POST" class="getin_form border-form"
                        id="register" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group bottom35">
                                    <label for="registerName" class="d-none"></label>
                                    <input name="nama" class="form-control" type="text" placeholder="Nama Lengkap:"
                                        required id="registerName">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group bottom35">
                                    <label for="nik" class="d-none"></label>
                                    <input name="nik" class="form-control" type="number" placeholder="No.KTP:" required
                                        id="nik">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group bottom35">
                                    <label for="telp" class="d-none"></label>
                                    <input name="telp" class="form-control" type="number" placeholder="Telp:" required
                                        id="telp">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group bottom35">
                                    <label for="registerEmail" class="d-none"></label>
                                    <input name="email" class="form-control" type="email" placeholder="Email:" required
                                        id="registerEmail">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group bottom35">
                                    <label for="alamat" class="d-none"></label>
                                    <textarea name="alamat" class="form-control" type="text"
                                        placeholder="Alamat lengkap: " required id="alamat"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group bottom35">
                                    <label class="my-1 mr-2" for="pilihanUptd">UPTD</label>
                                    <select name="uptd_id" class="my-1 custom-select mr-sm-2" id="pilihanUptd"
                                        onchange="ubahOption()" required>
                                        <option selected>Pilih...</option>
                                        @foreach ($uptd_lists as $no => $uptd_list)
                                        <option value="{{ $uptd_list->id }}">{{ $uptd_list->nama }}
                                            ({{ $uptd_list->deskripsi }})</option>
                                        @endforeach
                                        {{-- <option value="1">UPTD-I (kab.cianjur, kota/kab.bogor, kota depok, kota/kab.bekasi)</option>
                                        <option value="2">UPTD-II (kota & kab. sukabumi)</option>
                                        <option value="3">UPTD-III (kota/kab.bandung, kota cimahi, kab.bandung barat, kab.subang, kab.karawang, kab.purwakarta)</option>
                                        <option value="4">UPTD-IV (kab.sumedang, kab. garut)</option>
                                        <option value="5">UPTD-V (kab/kota tasikmalaya, kota banjar, kab.ciamis, kab.pangandaran, kab.kuningan)</option>
                                        <option value="6">UPTD-VI (kota/kab cirebon, kab. majalengka, kab. indramayu)</option> --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group bottom35">
                                    <label class="my-1 mr-2" for="pilihanKeluhan">Lokasi</label>
                                    <select name="lokasi" class="my-1 custom-select mr-sm-2 w-100" id="ruas_jalan"
                                        required>
                                        <option selected>Pilih...</option>
                                        @foreach ($lokasi as $kabkota)
                                        <option value="{{$kabkota->name}}">{{$kabkota->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12 col-sm-12">
                                <div class="form-group bottom35">
                                    <label class="my-1 mr-2" for="pilihanKeluhan">Keluhan</label>
                                    <select name="jenis" class="my-1 custom-select mr-sm-2 w-100" id="pilihanKeluhan"
                                        required>
                                        <option selected>Pilih...</option>
                                        @foreach ($jenis_laporan as $laporan)
                                        <option value="{{$laporan->id}}">{{$laporan->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group bottom35">
                                    <label for="saran" class="d-none"></label>
                                    <textarea name="deskripsi" class="form-control" type="text"
                                        placeholder="Saran/Keluhan:" required id=saran></textarea>
                                </div>
                            </div>
                            <input name="lat" type="hidden" id="lat">
                            <input name="long" type="hidden" id="long">
                            <!-- <div class="col-md-6 col-sm-6">
                                <div class="form-group bottom35">
                                    <label for="lat" class="d-none"></label>

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group bottom35">
                                    <label for="lng" class="d-none"></label>
                                    <input name="long" class="form-control" type="text" placeholder="Longitude (107.10987)"
                                        required id="lng">
                                </div>
                            </div> -->
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <button type="button" class="button gradient-btn" data-toggle="modal"
                                        data-target="#latLong">
                                        <i class="fas fa-map-marked-alt"></i> Lat Long
                                    </button>
                                </div>
                                <div class="modal fade" id="latLong" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Pilih lokasi kerusakan untuk mendapatkan data
                                                    Lat Long</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body w-100">
                                                <div id="mapLatLong" class="full-map"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group bottom35">
                                    <input name="gambar" type="file" class="form-control-file" id="pilihFile">
                                    <label for="pilihFile">Foto Kerusakan saat ini</label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="button gradient-btn w-100">Kirim Pengaduan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block pl-lg-0 wow fadeInRight">
                <div class=" image login-image h-100">
                    <img src="https://picsum.photos/id/1067/750/680" alt="" class="h-100">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main sign-up section ends -->
<!-- Contact US -->
<section id="kontak" class="position-relative padding noshadow">
    <div class="container whitebox">
        <div class="py-5 widget">
            <div class="row">
                <div class="text-center col-md-12 wow fadeIn mt-n3" data-wow-delay="300ms">
                    <h2 class="pt-1 heading bottom30 darkcolor font-light2"><span class="font-normal">Kontak</span>
                        Kami
                        <span class="divider-center"></span>
                    </h2>
                    <div class="col-md-12 offset-md-2 bottom35">
                        <p>Apakah ada yang ingin anda tanyakan kepada kami?</p>
                    </div>
                </div>
                @if (Session::has('pesan-msg'))
                <div class="col-md-12">
                    <div class="alert alert-{{ Session::get('color') }} alert-dismissible fade show" role="alert">
                        {{ Session::get('pesan-msg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                @endif
                <div class="col-md-6 col-sm-6 order-sm-2">
                    <div class="px-2 text-center contact-meta text-md-left">
                        <div class="heading-title">
                            <span class="mb-3 defaultcolor">Agen {{ $profil->nama }}</span>
                            <h2 class="mb-4 font-normal darkcolor">
                                Kantor Pusat Kami <span class="d-none d-md-inline-block">Di Kota Bandung</span></h2>
                        </div>
                        <p class="bottom10">Alamat: {!! $profil->alamat !!}</p>
                        <p class="bottom10">{{ $profil->kontak }}</p>
                        <p class="bottom10"><a href="mailto:{{ $profil->email }}">{{ $profil->email }}</a></p>
                        <p class="bottom10">Senin - Jumat: {{ $profil->jam_layanan }}</p>
                        <ul class="mt-4 mb-4 social-icons mb-sm-0 wow fadeInUp" data-wow-delay="300ms">
                            <li><a href="{!! $profil->link_facebook !!}" target="_blank"><i
                                        class="fab fa-facebook-f"></i> </a> </li>
                            <li><a href="{!! $profil->link_twitter !!}" target="_blank"><i class="fab fa-twitter"></i>
                                </a> </li>
                            <li><a href="{!! $profil->link_instagram !!}" target="_blank"><i
                                        class="fab fa-instagram"></i> </a> </li>
                            <li><a href="{!! $profil->link_youtube !!}" target="_blank"><i class="fab fa-youtube"></i>
                                </a> </li>
                            <li><a href="mailto:{!! $profil->email !!}" target="_blank"><i class="far fa-envelope"></i>
                                </a> </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6">
                    <div class="heading-title wow fadeInUp" data-wow-delay="300ms">
                        <form action="{{ url('tambah-pesan') }}" method="POST" class="getin_form wow fadeInUp"
                            data-wow-delay="400ms">
                            @csrf
                            <div class="px-2 row">
                                <div class="col-md-12 col-sm-12" id="result1"></div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="name1" class="d-none"></label>
                                        <input name="nama" class="form-control" id="name1" type="text"
                                            placeholder="Nama:" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="email1" class="d-none"></label>
                                        <input name="email" class="form-control" type="email" id="email1"
                                            placeholder="Email:">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="message1" class="d-none"></label>
                                        <textarea class="form-control" id="message1" placeholder="Pesan:" required
                                            name="pesan"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <button type="submit" id="submit_btn1"
                                        class="button gradient-btn w-100">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="text-center widget top60 w-100">
                    <div class="contact-box">
                        <span class="icon-contact defaultcolor"><i class="fas fa-mobile-alt"></i></span>
                        <p class="bottom0"><a href="tel:{!! $profil->kontak !!}">{!! $profil->kontak !!}</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="text-center widget top60 w-100">
                    <div class="contact-box">
                        <span class="icon-contact defaultcolor"><i class="fas fa-map-marker-alt"></i></span>
                        <p class="bottom0">{!! $profil->alamat !!}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="text-center widget top60 w-100">
                    <div class="contact-box">
                        <span class="icon-contact defaultcolor"><i class="far fa-envelope"></i></span>
                        <p class="bottom0"><a href="mailto:{!! $profil->email !!}">{!! $profil->email !!}</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="text-center widget top60 w-100">
                    <div class="contact-box">
                        <span class="icon-contact defaultcolor"><i class="far fa-clock"></i></span>
                        <p class="bottom15">Senin - Jumat: {!! $profil->jam_layanan !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact US ends -->
<!-- map -->
<div class="w-100">
    <div id="mapOffice" class="full-map"></div>
</div>
<!-- map end -->
<!-- Contact US ends -->
<div>
    <a id="container_wa" href="#" target="_blank"><i id="wa" class="fab fa-whatsapp"></i>
    </a>
</div>
<style>
    #container_wa {
        position: fixed;
        width: 65px;
        height: 65px;
        bottom: 80px;
        right: 20px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 42px;
        box-shadow: 2px 2px 3px #999;
        z-index: 100;
    }

    #wa {
        margin-top: -2px;
    }
</style>
@endsection
@section('script')
<script src="https://js.arcgis.com/4.18/"></script>
<script>
    $(document).ready(() => {
    const baseUrl = "{{url('/')}}";

    $('#mapLatLong').ready(() => {
        require([
        "esri/Map",
        "esri/views/MapView",
        "esri/Graphic"
        ], function(Map, MapView, Graphic) {

            const map = new Map({
                basemap: "hybrid"
            });

            const view = new MapView({
                container: "mapLatLong",
                map: map,
                center: [107.6191, -6.9175],
                zoom: 8,
            });

            let tempGraphic;
            view.on("click", function(event){
                if($("#lat").val() != '' && $("#long").val() != ''){
                    view.graphics.remove(tempGraphic);
                }
                var graphic = new Graphic({
                  geometry: event.mapPoint,
                  symbol: {
                    type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                    url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                    width: "14px",
                    height: "24px"
                  }
                });
                tempGraphic = graphic;
                $("#lat").val(event.mapPoint.latitude);
                $("#long").val(event.mapPoint.longitude);

                view.graphics.add(graphic);
              });
        });
    });

    $('#mapOffice').ready(() => {
        require([
        "esri/Map",
        "esri/views/MapView",
        "esri/Graphic",
        ], function(Map, MapView, Graphic) {
            const map = new Map({
                basemap: "streets-relief-vector"
            });

            const view = new MapView({
                container: "mapOffice",
                map: map,
                center: [107.6088113, -6.9213147],
                zoom: 16,
            });

            const point = {
                type: "point",
                longitude: 107.6108861,
                latitude: -6.9213706
            };

            const marker = {
                type: "picture-marker",
                url: "https://static.arcgis.com/images/Symbols/Shapes/BlackStarLargeB.png",
                width: "32px",
                height: "32px"
            };

            const pointGraphic = new Graphic({ geometry: point, symbol: marker });

            view.graphics.add(pointGraphic);
        });
    });
});


    function ubahOption() {


        //untuk select Ruas
        url = "{{ url('/') }}"
        id_select = '#ruas_jalan'
        text = 'Pilih Ruas Jalan'
        option = 'nama_ruas_jalan'

        setDataSelect(id, url, id_select, text, option, option)
    }
</script>
@endsection
