<!DOCTYPE html>
<!--
	Daraz by TEMPLATE STOCK
	templatestock.co @templatestock
	Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->

<html lang="en">
	<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UPTD LABORATORIUM BAHAN KONSTRUKSI</title>

    <link rel="stylesheet" href="{{ asset('assets/labkon/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/labkon/css/font-awesome.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/labkon/css/animate.css')}}"/>

		<link rel="stylesheet" href="{{ asset('assets/labkon/css/style.css')}}" />

    <script type="text/javascript" src="{{ asset('assets/labkon/js/jquery-1.11.2.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/labkon/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCZXJBVDf7R4JqmSpopVPoduIGWx1IwpBM"></script>
    <script type="text/javascript" src="{{ asset('assets/labkon/js/plugins.js')}}"></script>


	</head>
	<body>
	<div class="svg-wrap">
      <svg width="64" height="64" viewBox="0 0 64 64">
        <path id="arrow-left" d="M26.667 10.667q1.104 0 1.885 0.781t0.781 1.885q0 1.125-0.792 1.896l-14.104 14.104h41.563q1.104 0 1.885 0.781t0.781 1.885-0.781 1.885-1.885 0.781h-41.563l14.104 14.104q0.792 0.771 0.792 1.896 0 1.104-0.781 1.885t-1.885 0.781q-1.125 0-1.896-0.771l-18.667-18.667q-0.771-0.813-0.771-1.896t0.771-1.896l18.667-18.667q0.792-0.771 1.896-0.771z"></path>
      </svg>

      <svg width="64" height="64" viewBox="0 0 64 64">
        <path id="arrow-right" d="M37.333 10.667q1.125 0 1.896 0.771l18.667 18.667q0.771 0.771 0.771 1.896t-0.771 1.896l-18.667 18.667q-0.771 0.771-1.896 0.771-1.146 0-1.906-0.76t-0.76-1.906q0-1.125 0.771-1.896l14.125-14.104h-41.563q-1.104 0-1.885-0.781t-0.781-1.885 0.781-1.885 1.885-0.781h41.563l-14.125-14.104q-0.771-0.771-0.771-1.896 0-1.146 0.76-1.906t1.906-0.76z"></path>
      </svg>
    </div>


    <!-- MAIN CONTENT -->

   <div class="container-fluid">

    <!-- HEADER -->

    <section id="header">

      <!-- NAVIGATION -->
      <nav class="navbar navbar-fixed-top navbar-default bottom">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#header">UPTD Laboratorium Bahan Konstruksi</a>
          </div><!-- /.navbar-header -->

          <div class="collapse navbar-collapse" id="menu">
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#header">Beranda</a></li>
              <li><a href="#about">Tentang Kami</a></li>
              <li><a href="#portfolio">Jenis Pengujian</a></li>
			  <li><a href="#team">Informasi</a></li>
              <li><a href="#contact">Kontak Kami</a></li>
              <li><a href="http://124.81.122.131/laboratorium_konstruksi/dashboard">Login</a></li>
            </ul>
          </div> <!-- /.navbar-collapse -->
        </div> <!-- /.container -->
      </nav>

      <!-- SLIDER -->
      <div class="header-slide">
        <section>
          <div id="loader" class="pageload-overlay" data-opening="M 0,0 0,60 80,60 80,0 z M 80,0 40,30 0,60 40,30 z">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
              <path d="M 0,0 0,60 80,60 80,0 Z M 80,0 80,60 0,60 0,0 Z"/>
            </svg>
          </div> <!-- /.pageload-overlay -->

          <div class="image-slide bg-fixed">
            <div class="overlay">
              <div class="container">
                <div class="row">
                  <div class="col-md-12">

                    <div class="slider-content">
                      <h1></h1>
                    <!--  <p>We are a creative agency from the earth of Bandung, Indonesia</p>-->
                    </div>

                  </div> <!-- /.col-md-12 -->
                </div> <!-- /.row -->
              </div> <!-- /.container -->
            </div> <!-- /.overlay -->
          </div> <!-- /.image-slide -->

          <nav class="nav-slide">
            <a class="prev" href="#prev">
              <span class="icon-wrap">
                <svg class="icon" width="32" height="32" viewBox="0 0 64 64">
                  <use xlink:href="#arrow-left">
                </svg>
              </span>
              <div>
                <span>Prev Photo</span>
                <h3>...</h3>
                <p>...</p>
                <img alt="Previous thumb">
              </div>
            </a>
            <a class="next" href="#next">
              <span class="icon-wrap">
                <svg class="icon" width="32" height="32" viewBox="0 0 64 64">
                  <use xlink:href="#arrow-right">
                </svg>
              </span>
              <div>
                <span>Next Photo</span>
                <h3>...</h3>
                <p>...</p>
                <img alt="Next thumb">
              </div>
            </a>
          </nav>
        </section>

        <script type="text/javascript">
        var dataHeader = [
                            {
                              bigImage :"{{ asset('assets/labkon/images/UPTD.JPEG')}}",
                              title : "UPTD Laboratorium Bahan Konstruksi",
							  author : "Templatestock"
                            },
                            {
                              bigImage :"{{ asset('assets/labkon/images/Lab_Beton.png')}}",
                              title : "Lab Tanah  & Agregat",
                              author : "Templatestock"
                            },
                            {
                              bigImage :"{{ asset('assets/labkon/images/3m.png')}}",
                              title : "Achieve Success",
                              author : "Templatestock"
                            }
                        ],
            loaderSVG = new SVGLoader(document.getElementById('loader'), {speedIn : 600, speedOut : 600, easingIn : mina.easeinout});
            loaderSVG.show()
        </script>

      </div><!-- /.header-slide -->
    </section>

    <!-- HEADER END -->


    <!-- ABOUT -->

    <section id="about" class="light">
      <header class="title">
        <h2>Visi</h2>
		<p> Sebagai laboratorium pengujian mutu tanah dan bangunan serta jalan dan jembatan  yang terpercaya untuk terwujudnya  Jawa Barat Juara melalui jaringan jalan yang mantap dengan didukung oleh jasa konstruksi yang profesional, dalam tata ruang.</p>
		<h2>Misi</h2>
		<ol type ="1">
        <li>Menunjang visi dan misi Dinas Bina Marga dan Penataan Ruang Provinsi Jawa Barat  dalam hal meningkatkan kualitas penyelenggaraan jasa kontruksi.</li>
        <li>Menjadikan laboratorium bahan konstruksi sebagai laboratorium rujukan dalam rangka pemenuhan persyaratan teknis pengujian aspal, beton dan tanah di tingkat regional maupun tingkat nasional.</li>
        <li>Menghasilkan data pengujian yang cepat, tepat, akurat dan terpercaya dengan mengacu kepada sistem manajemen mutu yang sesuai ISO 17025 : 2017</li>
		</ol>
		<h2>FUNGSI LABORATORIUM</h2>
		<ol type ="1">
		<li>Penyelenggaraan pengkajian bahan kebijakan teknis pengelolaan Laboratorium Bahan Konstruksi</li>
		<li>Penyelenggaraan pengelolaan Laboratorium Bahan Konstruksi</li>
		<li>Penyelenggaraan evaluasi dan pelaporan UPTD Laboratorium Bahan Konstruksi</li>
		<li>Penyelenggaraan fungsi lain sesuai dengan tugas dan fungsi lain sesuai dengan tugas dan fungsinya Laboratorium Bahan Konstruksi</li>
		</ol>
		<h2>KEBIJAKAN MUTU</h2>
		<ol type ="1">
		<li>Laboratorium Bahan Konstruksi berkomitmen bahwa laboratorium ini berkomitmen bahwa laboratorium ini akan bekerja secara profesional dan menjamin mutu hasil kalibrasi dalam melayani pelanggan.</li>
        <li>Laboratorium Bahan Konstruksi akan terus – menurus berusaha melaksanakan standar pelayanan laboratorium.</li>
        <li>Semua personil yang terlibat dalam kegiatan pengujian di laboratorium memahami dokumentasi sistem manajemen mutu dan menerapkan kebijakan serta prosedur dalam pekerjaan mereka.</li>
        <li>Laboratorium Bahan Konstruksi berkomitmen untuk memenuhi persyaratan ISO/IEC 17025:2017 dan secara berkelanjutan meningkatkan effektifitasnya dalam penerapan sistem manajemen mutu.</li>
      </header>



              </div>
            </div>
          </div>
        </div> <!-- /.row table-row -->
      </div> <!-- /.container -->
    </section>


	    <!-- PORTFOLIO -->

    <section id="portfolio" class="light">
      <header class="title">
        <h2>Jenis Pengujian </h2>
        <p>Jenis Pengujian Beserta SNI dan Tata Cara Pengujiannya .</p>
      </header>

      <div class="container-fluid">
        <div class="row">
          <ul id="filters" class="list-inline">
            <li data-filter="all" class="filter">All</li>
            <li data-filter=".branding" class="filter">Aspal</li>
            <li data-filter=".graphic" class="filter">Agregat</li>
            <li data-filter=".printing" class="filter">Beton</li>
            <li data-filter=".video" class="filter">Campuran Aspal</li>
            <li data-filter=".tanah" class="filter">Tanah</li>
            <li data-filter=".lapangan" class="filter">Lapangan</li>
            <li data-filter=".campuran" class="filter">Perencanaan Campuran</li>
          </ul>
        </div>

        <div class="row">
          {{-- <div class="container-portfolio">
            <!-- PORTFOLIO OBJECT -->
                        <script type="text/javascript">
                          var portfolio = [
                                {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/penetrasi.jpg')}}",
                                  title : "Penetrasi Pada 25 C(0,1 mm)",
                                  link : "Penetrasi_Aspal.html",
                                  text : "SNI 2456:2011."
                                },
                                          {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/viskonsitas1.jpg')}}",
                                  title : "Viskonsitas Kinematis 135 C",
                                  link : "viskonsitas.html",
                                  text : "ASTM D2170 - 10."
                                },
                                          {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/titiklembek1.jpg')}}",
                                  title : "Titik Lembek",
                                  link : "Titik_Lembek.html",
                                  text : "SNI 2434:2011."
                                },
                        {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/daktilitassetelah.jpg')}}",
                                  title : "Daktilitas pada 25 C (cm)",
                                  link : "daktilitas_aspal.html",
                                  text : "SNI 2432:2011."
                                },
                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/titiknyala1.jpg')}}",
                                  title : "Titik Nyala",
                                  link : "titiknyala_aspal.html",
                                  text : "SNI 2433:2011."
                                },
                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/kelarutan1.jpg')}}",
                                  title : "Kelarutan dalam Trichloroethylene(%)",
                                  link : "kelarutan_aspal.html",
                                  text : "AASHTO T44-14."
                                },
                        {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/bj_aspal1.jpg')}}",
                                  title : "Berat Jenis",
                                  link : "beratjenis.html",
                                  text : "SNI 2441:2011."
                                },
                              {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/stabilitasaspal.jpg')}}",
                                  title : "Stabilitas Penyimpanan ",
                                  link : "stabilitas_penyimpanan.html",
                                  text : "SNI 2434:2011 dan ASTM D 5976-00."
                                },

                        {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/kadarparafin1.jpg')}}",
                                  title : "Kadar Parafin Lilin(%)",
                                  link : "parafin.html",
                                  text : "SNI 03-3639-2002."
                                },


                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/penetrasisetelah.jpg')}}",
                                  title : "Penetrasi Setelah TFOT 25 C ",
                                  link : "Penetrasi_Aspal25.html",
                                  text : "SNI 2465 :2011."
                                },

                          {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/daktilitas2.jpg')}}",
                                  title : "Daktilitas Setelah TFOT 25 C ",
                                  link : "daktilitas_aspal25.html",
                                  text : "SNI 2432:2011."
                                },

                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/berathilang.jpg')}}",
                                  title : "Kehilangan TFOT",
                                  link : "kehilangan_tfot.html",
                                  text : "SNI 2432:2011."
                                },


                                {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/p-2.png')}}",
                                  title : "Kekekalan (Soundness)",
                                  link : "#none",
                                  text : "SNI 3407:2008."
                                },
                                {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/abrasiagg.jpg')}}",
                                  title : "Abrasi Los Angeles",
                                  link : "abrasi.html",
                                  text : "SNI 2147 : 2008."
                                },
                      {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/kelekatan1.jpg')}}",
                                  title : "Kelekatan Agregat Terhadap Aspal",
                                  link : "#none",
                                  text : "SNI 2439 : 2011."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/butiranagg.jpg')}}",
                                  title : "Butiran Pecah Agregat Kasar",
                                  link : "#none",
                                  text : "SNI 7619:2012."
                                },
                      {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/200ag.jpg')}}",
                                  title : "Material Lolos Ayakan No.200",
                                  link : "#none",
                                  text : "SNI ASTM C 117:2012."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/Sand-equivalent.jpg')}}",
                                  title : "Sand Equivalent",
                                  link : "#none",
                                  text : "SNI 03-4428-1997."
                                },

                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/urah.jpg')}}",
                                  title : "Uji Rongga Kadar Agregat Halus Tanpa Pemadatan",
                                  link : "ronggaudaraagg.html",
                                  text : "SNI 03-6877-2002."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/lempung.jpg')}}",
                                  title : "Butiran Gumpalan Lempung",
                                  link : "#none",
                                  text : "SNI 4141:2015."
                                },
                      {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/agregathalus.jpg')}}",
                                  title : "Berat Jenis Agregat Halus",
                                  link : "bj_agregathalus.html",
                                  text : "SNI 1970:2016."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/analisca.jpg')}}",
                                  title : "Analisis Saringan Campuran Aspal, Analisis Saringan Laporan Pondasi,Analisis Saringan Hasil Ekstraksi",
                                  link : "analisa_saringanag.html",
                                  text : "SNI ASTM C136:2012."
                                },
                            {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/bi_agregat.jpg')}}",
                                  title : "Berat Isi",
                                  link : "#none",
                                  text : "SNI 03-4804-1998."
                                },
                                {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Marshall<span>Test</span>",
                                  link : "marshal.html",
                                  text : "SNI 1996:2008."
                                },
                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Density Core Drill<span>/Briket</span>",
                                  link : "density.html",
                                  text : "SNI 3423:2008."
                                },

                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Kadar Aspal<span>/ Ekstraksi</span>",
                                  link : "ekstraksi.html",
                                  text : "SNI 3423:2008."
                                },

                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Gradasi<span>/ Campuran</span>",
                                  link : "gradasi.html",
                                  text : "SNI 3423:2008."
                                },

                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Berat Jenis Maksimum<span>/ GMM</span>",
                                  link : "Bj_maksimum.html",
                                  text : "SNI 3423:2008."
                                },


                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/saringan.jpg')}}",
                                  title : "AnalisisSarinagan<span>dan Hidrometer</span>",
                                  link : "hidro_ansa.html",
                                  text : "SNI 3423:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/bi_tanah1.jpg')}}",
                                  title : "Berat<span>Isi</span>",
                                  link : "beratisitanah.html",
                                  text : "SNI 03-3637-1994."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/bj_tanah.jpg')}}",
                                  title : "Berat<span>Jenis</span>",
                                  link : "beratjenis_tanah.html",
                                  text : "SNI 1964:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/kadarair_tanah.jpg')}}",
                                  title : "Kadar<span>Air</span>",
                                  link : "kadarairtanah.html",
                                  text : "SNI 1965:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/ucs1.jpg')}}",
                                  title : "UCS",
                                  link : "ucs.html",
                                  text : "SNI 3638:2012."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/directshear.jpg')}}",
                                  title : "Direct<span>Shear</span>",
                                  link : "directsheartanah.html",
                                  text : "SNI 2813:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/konsolidasi1.jpg')}}",
                                  title : "Konsolidasi",
                                  link : "konsolidasitanah.html",
                                  text : "SNI 2812:2012."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/atteberg.png')}}",
                                  title : "Atteberg<span>Limit</span>",
                                  link : "atteberglimit.html",
                                  text : "SNI 1966:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/pemadatantanah.jpg')}}",
                                  title : "Pemadatan<span>Standar</span>",
                                  link : "pemadatantanah",
                                  text : "SNI 6886:2012."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/rendam.jpg')}}",
                                  title : "Pemadatan Modified; CBR Rendaman Modified<span>CBR Rendaman Standar</span>",
                                  link : "#none",
                                  text : "SNI 1743-2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/cbrstandar.jpg')}}",
                                  title : "CBR Langsung Standar<span>CBR Langsung Modified</span>",
                                  link : "#none",
                                  text : "SNI 1744:2012."
                                },
                        {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/sandcone.jpg')}}",
                                  title : "Sand<span>Cone</span>",
                                  link : "sandcone.html",
                                  text : "SNI 03-2828-2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/dcp.gif')}}",
                                  title : "DCP",
                                  link : "#none",
                                  text : "."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{asset('assets/labkon/images/humertest.jpg')}}",
                                  title : "Hammer<span>Test</span>",
                                  link : "#none",
                                  text : ""
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/sondir.png')}}",
                                  title : "Sondir",
                                  link : "sondir.html",
                                  text : "SNI 2827 : 2008."
                                },
                        {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/handboring.jpg')}}",
                                  title : "Hand<span>Boring</span>",
                                  link : "handboring.html",
                                  text : "SNI 03-2436-2008."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/cbr.jpg')}}",
                                  title : "CBR<span>Lapangan</span>",
                                  link : "CBR_Laboratorium.html",
                                  text : "SNI 1738 : 2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/testpit.jpg')}}",
                                  title : "Testpit",
                                  link : "#none",
                                  text : "SNI 6872 : 2015."
                                },
                                {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/coredrilaspal.jpg')}}",
                                  title : "Coredrill<span>Beton Aspal</span>",
                                  link : "#none",
                                  text : "SNI 03-6890-2002."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/coredrilsemen.jpg')}}",
                                  title : "Coredrill<span>Beton Semen</span>",
                                  link : "#none",
                                  text : "SNI 03-2492-2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/benkelman-beam.jpg')}}",
                                  title : "Benkelman<span>Beam</span>",
                                  link : "benkelmanbeam.html",
                                  text : "SNI 2416:2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/geolistrik1.jpg')}}",
                                  title : "Pengujian<span>Geolistrik</span>",
                                  link : "#none",
                                  text : "-."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Pengambilan Contoh Uji <span>Campuran Beraspal</span>",
                                  link : "#none",
                                  text : "SNI 6890 : 204."
                                },

                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/dmxaspal.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(AC-WC)</span>",
                                  link : "dm_acwc",
                                  text : "SNI 6890 : 204."
                                },

                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/aspalacwc.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(AC-BC)</span>",
                                  link : "dm_acbc",
                                  text : "SNI 6890 : 204."
                                },
                          {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/acb.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(AC-Base)</span>",
                                  link : "dm_acbase",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/betondmx.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(Beton)</span>",
                                  link : "dm_beton",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/mortar.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(Mortar)</span>",
                                  link : "dm_mortar",
                                  text : "SNI 6890 : 204."
                                },
                        {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/penetrasiaspal.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(LPA)</span>",
                                  link : "dm_lpa",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/penetrasiaspal.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(LPB)</span>",
                                  link : "dm_lpb",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Design Mix Camp<span>Aspal(LPS)</span>",
                                  link : "dm_lps",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/analisabeton.jpg')}}",
                                  title : "Analisa<span>Saringan</span> ",
                                  link : "as_beton",
                                  text : "SNI ASTM C136:2012."
                                },
                            {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/beratisibeton.jpg')}}",
                                  title : "Berat<span>Isi</span> ",
                                  link : "bi_beton.html",
                                  text : "SNI 03-4804-1998."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/keausanbeton.jpg')}}",
                                  title : "Keausan Agregat<span>dengan Mesin Los Angeles</span> ",
                                  link : "angeles.html",
                                  text : "SNI 2417 : 2008."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan.jpg')}}",
                                  title : "Kekekalan Bentuk Agregat terhadap<span>larutan Natrium sulfat</span> ",
                                  link : "sulfat.html",
                                  text : "SNI 3407 : 2008."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan.jpg')}}",
                                  title : "Gumpulan Lempung<span> dan Butir Mudah Pecah</span> ",
                                  link : "lempung.html",
                                  text : "SNI 03-4141-1996."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan.jpg')}}",
                                  title : "Berat yang lolos saringan<span> No.200</span> ",
                                  link : "no200.html",
                                  text : "SNI ASTM C117: 2012."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/bj_betonkasar.jpg')}}",
                                  title : "Berat Jenis<span> Agregat Kasar</span> ",
                                  link : "bj_agregatkasar",
                                  text : "SNI 1969:2016."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/bj_betonhalus.jpg')}}",
                                  title : "Berat Jenis<span> Agregat Halus</span> ",
                                  link : "bj_agregathalus.html",
                                  text : "SNI 1970:2016."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan1.jpg')}}",
                                  title : "Kuat<span>Tekan</span> ",
                                  link : "kuattekan.html",
                                  text : "SNI 03-3403-1994."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuatlentur1.jpg')}}",
                                  title : "Kuat<span> Lentur</span> ",
                                  link : "kuatlentur.html",
                                  text : "SNI 4431:2011."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/slumptest1.jpg')}}",
                                  title : "Slump <span>Test</span>",
                                  link : "slump.html",
                                  text : "SNI 1972 : 2008."

                                }
                              ];
                          </script>
          </div> --}}
          <div class="container-portfolio">
            <!-- PORTFOLIO OBJECT -->
                        <script type="text/javascript">
                          var portfolio = [
                                {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/penetrasi.jpg')}}",
                                  title : "Penetrasi Pada 25 C(0,1 mm)",
                                  link : "#",
                                  text : "SNI 2456:2011."
                                },
                                          {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/viskonsitas1.jpg')}}",
                                  title : "Viskonsitas Kinematis 135 C",
                                  link : "#",
                                  text : "ASTM D2170 - 10."
                                },
                                          {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/titiklembek1.jpg')}}",
                                  title : "Titik Lembek",
                                  link : "#",
                                  text : "SNI 2434:2011."
                                },
                        {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/daktilitassetelah.jpg')}}",
                                  title : "Daktilitas pada 25 C (cm)",
                                  link : "#",
                                  text : "SNI 2432:2011."
                                },
                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/titiknyala1.jpg')}}",
                                  title : "Titik Nyala",
                                  link : "#",
                                  text : "SNI 2433:2011."
                                },
                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/kelarutan1.jpg')}}",
                                  title : "Kelarutan dalam Trichloroethylene(%)",
                                  link : "#",
                                  text : "AASHTO T44-14."
                                },
                        {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/bj_aspal1.jpg')}}",
                                  title : "Berat Jenis",
                                  link : "#",
                                  text : "SNI 2441:2011."
                                },
                              {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/stabilitasaspal.jpg')}}",
                                  title : "Stabilitas Penyimpanan ",
                                  link : "#",
                                  text : "SNI 2434:2011 dan ASTM D 5976-00."
                                },

                        {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/kadarparafin1.jpg')}}",
                                  title : "Kadar Parafin Lilin(%)",
                                  link : "#",
                                  text : "SNI 03-3639-2002."
                                },


                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/penetrasisetelah.jpg')}}",
                                  title : "Penetrasi Setelah TFOT 25 C ",
                                  link : "#",
                                  text : "SNI 2465 :2011."
                                },

                          {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/daktilitas2.jpg')}}",
                                  title : "Daktilitas Setelah TFOT 25 C ",
                                  link : "#",
                                  text : "SNI 2432:2011."
                                },

                      {
                                  category : "branding",
                                  image : "{{ asset('assets/labkon/images/berathilang.jpg')}}",
                                  title : "Kehilangan TFOT",
                                  link : "#",
                                  text : "SNI 2432:2011."
                                },


                                {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/p-2.png')}}",
                                  title : "Kekekalan (Soundness)",
                                  link : "#",
                                  text : "SNI 3407:2008."
                                },
                                {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/abrasiagg.jpg')}}",
                                  title : "Abrasi Los Angeles",
                                  link : "#",
                                  text : "SNI 2147 : 2008."
                                },
                      {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/kelekatan1.jpg')}}",
                                  title : "Kelekatan Agregat Terhadap Aspal",
                                  link : "#",
                                  text : "SNI 2439 : 2011."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/butiranagg.jpg')}}",
                                  title : "Butiran Pecah Agregat Kasar",
                                  link : "#",
                                  text : "SNI 7619:2012."
                                },
                      {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/200ag.jpg')}}",
                                  title : "Material Lolos Ayakan No.200",
                                  link : "#",
                                  text : "SNI ASTM C 117:2012."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/Sand-equivalent.jpg')}}",
                                  title : "Sand Equivalent",
                                  link : "#",
                                  text : "SNI 03-4428-1997."
                                },

                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/urah.jpg')}}",
                                  title : "Uji Rongga Kadar Agregat Halus Tanpa Pemadatan",
                                  link : "#",
                                  text : "SNI 03-6877-2002."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/lempung.jpg')}}",
                                  title : "Butiran Gumpalan Lempung",
                                  link : "#",
                                  text : "SNI 4141:2015."
                                },
                      {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/agregathalus.jpg')}}",
                                  title : "Berat Jenis Agregat Halus",
                                  link : "#",
                                  text : "SNI 1970:2016."
                                },
                        {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/analisca.jpg')}}",
                                  title : "Analisis Saringan Campuran Aspal, Analisis Saringan Laporan Pondasi,Analisis Saringan Hasil Ekstraksi",
                                  link : "#",
                                  text : "SNI ASTM C136:2012."
                                },
                            {
                                  category : "graphic",
                                  image : "{{ asset('assets/labkon/images/bi_agregat.jpg')}}",
                                  title : "Berat Isi",
                                  link : "#",
                                  text : "SNI 03-4804-1998."
                                },
                                {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Marshall<span>Test</span>",
                                  link : "#",
                                  text : "SNI 1996:2008."
                                },
                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Density Core Drill<span>/Briket</span>",
                                  link : "#",
                                  text : "SNI 3423:2008."
                                },

                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Kadar Aspal<span>/ Ekstraksi</span>",
                                  link : "#",
                                  text : "SNI 3423:2008."
                                },

                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Gradasi<span>/ Campuran</span>",
                                  link : "#",
                                  text : "SNI 3423:2008."
                                },

                      {
                                  category : "video",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Berat Jenis Maksimum<span>/ GMM</span>",
                                  link : "#",
                                  text : "SNI 3423:2008."
                                },


                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/saringan.jpg')}}",
                                  title : "AnalisisSarinagan<span>dan Hidrometer</span>",
                                  link : "#",
                                  text : "SNI 3423:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/bi_tanah1.jpg')}}",
                                  title : "Berat<span>Isi</span>",
                                  link : "#",
                                  text : "SNI 03-3637-1994."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/bj_tanah.jpg')}}",
                                  title : "Berat<span>Jenis</span>",
                                  link : "#",
                                  text : "SNI 1964:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/kadarair_tanah.jpg')}}",
                                  title : "Kadar<span>Air</span>",
                                  link : "#",
                                  text : "SNI 1965:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/ucs1.jpg')}}",
                                  title : "UCS",
                                  link : "#",
                                  text : "SNI 3638:2012."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/directshear.jpg')}}",
                                  title : "Direct<span>Shear</span>",
                                  link : "#",
                                  text : "SNI 2813:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/konsolidasi1.jpg')}}",
                                  title : "Konsolidasi",
                                  link : "#",
                                  text : "SNI 2812:2012."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/atteberg.png')}}",
                                  title : "Atteberg<span>Limit</span>",
                                  link : "#",
                                  text : "SNI 1966:2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/pemadatantanah.jpg')}}",
                                  title : "Pemadatan<span>Standar</span>",
                                  link : "#",
                                  text : "SNI 6886:2012."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/rendam.jpg')}}",
                                  title : "Pemadatan Modified; CBR Rendaman Modified<span>CBR Rendaman Standar</span>",
                                  link : "#",
                                  text : "SNI 1743-2008."
                                },
                      {
                                  category : "tanah",
                                  image : "{{ asset('assets/labkon/images/cbrstandar.jpg')}}",
                                  title : "CBR Langsung Standar<span>CBR Langsung Modified</span>",
                                  link : "#",
                                  text : "SNI 1744:2012."
                                },
                        {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/sandcone.jpg')}}",
                                  title : "Sand<span>Cone</span>",
                                  link : "#",
                                  text : "SNI 03-2828-2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/dcp.gif')}}",
                                  title : "DCP",
                                  link : "#",
                                  text : "."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{asset('assets/labkon/images/humertest.jpg')}}",
                                  title : "Hammer<span>Test</span>",
                                  link : "#",
                                  text : ""
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/sondir.png')}}",
                                  title : "Sondir",
                                  link : "#",
                                  text : "SNI 2827 : 2008."
                                },
                        {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/handboring.jpg')}}",
                                  title : "Hand<span>Boring</span>",
                                  link : "#",
                                  text : "SNI 03-2436-2008."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/cbr.jpg')}}",
                                  title : "CBR<span>Lapangan</span>",
                                  link : "#",
                                  text : "SNI 1738 : 2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/testpit.jpg')}}",
                                  title : "Testpit",
                                  link : "#",
                                  text : "SNI 6872 : 2015."
                                },
                                {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/coredrilaspal.jpg')}}",
                                  title : "Coredrill<span>Beton Aspal</span>",
                                  link : "#",
                                  text : "SNI 03-6890-2002."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/coredrilsemen.jpg')}}",
                                  title : "Coredrill<span>Beton Semen</span>",
                                  link : "#",
                                  text : "SNI 03-2492-2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/benkelman-beam.jpg')}}",
                                  title : "Benkelman<span>Beam</span>",
                                  link : "#",
                                  text : "SNI 2416:2011."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/geolistrik1.jpg')}}",
                                  title : "Pengujian<span>Geolistrik</span>",
                                  link : "#",
                                  text : "-."
                                },
                      {
                                  category : "lapangan",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Pengambilan Contoh Uji <span>Campuran Beraspal</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },

                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/dmxaspal.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(AC-WC)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },

                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/aspalacwc.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(AC-BC)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },
                          {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/acb.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(AC-Base)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/betondmx.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(Beton)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/mortar.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(Mortar)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },
                        {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/penetrasiaspal.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(LPA)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/penetrasiaspal.jpg')}}",
                                  title : "Design Mix Camp<span>Aspal(LPB)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "campuran",
                                  image : "{{ asset('assets/labkon/images/p-4.png')}}",
                                  title : "Design Mix Camp<span>Aspal(LPS)</span>",
                                  link : "#",
                                  text : "SNI 6890 : 204."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/analisabeton.jpg')}}",
                                  title : "Analisa<span>Saringan</span> ",
                                  link : "#",
                                  text : "SNI ASTM C136:2012."
                                },
                            {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/beratisibeton.jpg')}}",
                                  title : "Berat<span>Isi</span> ",
                                  link : "#",
                                  text : "SNI 03-4804-1998."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/keausanbeton.jpg')}}",
                                  title : "Keausan Agregat<span>dengan Mesin Los Angeles</span> ",
                                  link : "#",
                                  text : "SNI 2417 : 2008."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan.jpg')}}",
                                  title : "Kekekalan Bentuk Agregat terhadap<span>larutan Natrium sulfat</span> ",
                                  link : "#",
                                  text : "SNI 3407 : 2008."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan.jpg')}}",
                                  title : "Gumpulan Lempung<span> dan Butir Mudah Pecah</span> ",
                                  link : "#",
                                  text : "SNI 03-4141-1996."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan.jpg')}}",
                                  title : "Berat yang lolos saringan<span> No.200</span> ",
                                  link : "#",
                                  text : "SNI ASTM C117: 2012."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/bj_betonkasar.jpg')}}",
                                  title : "Berat Jenis<span> Agregat Kasar</span> ",
                                  link : "#",
                                  text : "SNI 1969:2016."
                                },
                      {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/bj_betonhalus.jpg')}}",
                                  title : "Berat Jenis<span> Agregat Halus</span> ",
                                  link : "#",
                                  text : "SNI 1970:2016."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuattekan1.jpg')}}",
                                  title : "Kuat<span>Tekan</span> ",
                                  link : "#",
                                  text : "SNI 03-3403-1994."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/kuatlentur1.jpg')}}",
                                  title : "Kuat<span> Lentur</span> ",
                                  link : "#",
                                  text : "SNI 4431:2011."
                                },
                                {
                                  category : "printing",
                                  image : "{{ asset('assets/labkon/images/slumptest1.jpg')}}",
                                  title : "Slump <span>Test</span>",
                                  link : "#",
                                  text : "SNI 1972 : 2008."

                                }
                              ];
                          </script>
          </div>
          {{-- <div class="container-portfolio">
            @foreach ($data as $no => $item)
              <img src="{{ asset($item->image) }}" style="width: 10%; height: auto" alt="">
            @endforeach
          </div> --}}
        </div>
      </div>
    </section>


    <!-- TEAM -->

    <section id="team" class="light">
      <header class="title">
        <h2>Informasi <span>UPTD Laboratorium Bahan Konstruksi</span></h2>
        <p>Informasi Mengenai Pengujian UPTD Laboratorium Bahan Konstruksi.</p>
      </header>

      <div class="container">
        <div class="row">
          <div class="col-md-3 col-sm-6 text-center">
            <div class="wrap animated" data-animate="fadeInDown">
              <div class="img-team">
                <img src="{{ asset('assets/labkon/images/daftar.png')}}" alt="" class="img-circle">
              </div>

              <h3><a href= "https://docs.google.com/forms/d/12e6XgW4VsGHilVrvd7N0TaZcnfCGkepJWhCnda4UPYg/viewform?edit_requested=true">Pendaftaran</a></h3>



              <p>Silakan Mulai Lakukan Pendaftaran Pada Saat Ingin Melakukan Pengujian.</p>
              <div class="team-social">
                <ul class="list-inline social-list">
                  <li><a href="#" class="fa fa-twitter"></a></li>
                  <li><a href="#" class="fa fa-linkedin"></a></li>
                  <li><a href="#" class="fa fa-facebook"></a></li>
                  <li><a href="#" class="fa fa-google-plus"></a></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6 text-center">
            <div class="wrap animated" data-animate="fadeInDown">
              <div class="img-team">
                <img src="{{ asset('assets/labkon/images/kuisioner.png')}}" alt="" class="img-circle">
              </div>

              <h3><a href="https://docs.google.com/forms/d/e/1FAIpQLSeS7_qujh_vtpAsl-DiV3JZECfcNW3e4W7wzDJLTWPxh1BZNg/viewform">Kuisioner</a></h3>


              <p>Silakan isi kuisioner setelah pengambilan laporan hasil sampel uji.</p>

              <div class="team-social">
                <ul class="list-inline social-list">
                  <li><a href="#" class="fa fa-twitter"></a></li>
                  <li><a href="#" class="fa fa-linkedin"></a></li>
                  <li><a href="#" class="fa fa-facebook"></a></li>
                  <li><a href="#" class="fa fa-google-plus"></a></li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6 text-center">
            <div class="wrap animated" data-animate="fadeInDown">
              <div class="img-team">
                <img src="{{ asset('assets/labkon/images/kuisioner.png')}}" alt="" class="img-circle">
              </div>

            <h3><a href="syarat_uji.html">Syarat Uji</a></h3>


              <p>Syarat Uji Sampel Berisi Tentang Persyratan Pengujian dan Rincian Kebutuhan Sampel.</p>

              <div class="team-social">
                <ul class="list-inline social-list">
                  <li><a href="#" class="fa fa-twitter"></a></li>
                  <li><a href="#" class="fa fa-linkedin"></a></li>
                  <li><a href="#" class="fa fa-facebook"></a></li>
                  <li><a href="#" class="fa fa-google-plus"></a></li>
                </ul>
              </div>
            </div>
          </div>


          <div class="col-md-3 col-sm-6 text-center">
            <div class="wrap animated" data-animate="fadeInDown">
              <div class="img-team">
                <img src="{{ asset('assets/labkon/images/Persyaratan-Pengujian.jpg')}}" alt="" class="img-circle">
              </div>


              <h3><a href="alur.html">Alur Pengujian</a></h3>
              <p>Silahkan Agar Membaca Alur pengujian Terlebih Dahulu Sebelum Mendaftar.</p>

              <div class="team-social">
                <ul class="list-inline social-list">
                  <li><a href="#" class="fa fa-twitter"></a></li>
                  <li><a href="#" class="fa fa-linkedin"></a></li>
                  <li><a href="#" class="fa fa-facebook"></a></li>
                  <li><a href="#" class="fa fa-google-plus"></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- /.container -->
    </section>

	    <!-- INFO -->

    <!-- FOOTER CONTACT -->

     <section id="contact" class="dark">
      <header class="title">
        <h2>Kontak <span>Kami</span></h2>
        <p>Silakan Hubungi Kami Jika dan Saran dan Masukan .</p>
      </header>

      <div class="container">
        <div class="row">
          <div class="col-md-8 animated" data-animate="fadeInLeft">
            <form action="#">
              <div class="row">
                <div class="col-md-6">
                  <input type="text" class="form-control" placeholder="Your Name">
                </div>
                <div class="col-md-6">
                  <input type="email" class="form-control" placeholder="Your Email">
                </div>
                <div class="col-md-12">
                  <textarea class="form-control" rows="3" placeholder="Tell Us Everything"></textarea>
                </div>
                <div class="col-md-12">
                  <button class="btn btn-default submit">Send Message</button>
                </div>
              </div>
            </form>
          </div>

          <div class="col-md-4 animated" data-animate="fadeInRight">
            <address>
                <span><i class="fa fa-map-marker fa-lg"></i> Jalan AH. Nasution No.117 Ujungberung Bandung - 40619</span>
                <span><i class="fa fa-phone fa-lg"></i> (022) 63734300</span>
                <span><i class="fa fa-envelope-o fa-lg"></i> <a href="mailto:contact@example.com">Laboratorium.Konstruksi@gmail.com</a></span>
                <span><i class="fa fa-globe fa-lg"></i> <a href="http://support.example.com">dbmpr.balaiuji@yahoo.com</a></span>
            </address>
          </div>

        </div>
      </div>
    </section>

    <section id="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <p>Made BY <i class="fa fa-heart"></i> <a href="http://124.81.122.131/temanjabar/public/" target="_blank">Teman Jabar</a></p>
            <!-- <p><small>Images : unsplash.com</small></p> -->
          </div>
        </div>
      </div>
    </section>

   </div><!-- /.container-fluid -->

    <!-- SCRIPT -->
    <script type="text/javascript" src="{{ asset('assets/labkon/js/main.js')}}"></script>
	</body>
</html>
