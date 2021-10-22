@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection

@section('page-header')
<div class="row align-items-end">
    <div class="col-lg-8">
        <div class="page-header-title">
            <div class="d-inline">
                <h4>Monitoring Survey Kondisi Jalan - UPTD 3</h4>
             </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="page-header-breadcrumb">
            <ul class=" breadcrumb breadcrumb-title">
                <li class="breadcrumb-item">
                    <a href="{{url('admin')}}"> <i class="feather icon-home"></i> </a>
                </li>
                <li class="breadcrumb-item"><a href="{{url('admin/monitoring/survey-kondisi-jalan')}}">Survey Kondisi Jalan</a> </li>
                <li class="breadcrumb-item"><a href="#!">UPTD 3</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('page-body')
<style>

#controls {
  overflow: hidden;
  padding-bottom: 3px;
}
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <section id="our-services" class="pt-5 bglight">
                <div class="container">
                    <div class="row whitebox top15">

                        <div class="col-lg-12 col-md-12">
                            <div class="widget heading_space text-center text-md-left">

                                <div class="col-12 px-0">
                                    <div class="w-100">
                                    <div id="googleMap" style="width:100%;height:400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
@section('script')
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBSpJ4v4aOY7DEg4QAIwcSFCXljmPJFUg&callback=initMap">
</script>
<script>
    var points = [
        ['Tamblong', -6.9147444,107.6098111, 12, `{{ route('kondisiJalanUPTDDetail',['uptd3','tamblong']) }}`],
        ['Pasopati', -6.9, 107.6, 11, `{{ route('kondisiJalanUPTDDetail',['uptd3','pasopati']) }}`],
        ['Arcamanik', -6.913244, 107.666, 10, `{{ route('kondisiJalanUPTDDetail',['uptd3','arcamanik']) }}`],
        ['Cimahi', -6.9147444, 107.5098111, 9, `{{ route('kondisiJalanUPTDDetail',['uptd3','cimahi']) }}`],
        ['Lembang', -6.8147444, 107.6098111, 8, `{{ route('kondisiJalanUPTDDetail',['uptd3','lembang']) }}`],
        ['Banjaran', -7.0147444, 107.6098111, 7, `{{ route('kondisiJalanUPTDDetail',['uptd3','banjaran']) }}`]
    ];

    function setMarkers(map, locations) {
        var shape = {
            coord: [1, 1, 1, 20, 18, 20, 18 , 1],
            type: 'poly'
        };

        for (var i = 0; i < locations.length; i++) {
            // var flag = new google.maps.MarkerImage('markers/' + (i + 1) + '.png',
            // new google.maps.Size(17, 19),
            // new   google.maps.Point(0,0),
            // new google.maps.Point(0, 19));

            var place = locations[i];
            var myLatLng = new google.maps.LatLng(place[1], place[2]);
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: place[0],
                zIndex: place[3],
                url: place[4]
            });
            google.maps.event.addListener(marker, 'click', function() {
                window.location.href = this.url;
            });
        }
    }

    function initMap() {
        var options = {
            center:new google.maps.LatLng(-6.9032739,107.5731165),
            zoom:10,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("googleMap"),options);
        setMarkers(map, points);
    }
        // event jendela di-load
        //google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection
