@extends('pdf.layouts.app')
@section('title')
Rekap Data Entry
@parent
@stop
@section('content')
<page size="A4">
    <div class="row g-3">
        {{-- <div class="col-3 p-2">
            <img id="logo" class="float-right" src="{{ asset('logo.png') }}" alt="logo" />
        </div> --}}
        <div class="col-12 judul p-2 text-center">
            {{-- <p>PEMERINTAH PROVINSI JAWA BARAT</p>
            <p>DINAS BINA MARGA DAN PENATAAN RUANG</p>
            <p>UNIT PELAKSANA TEKNIS DAERAH WILAYAH PELAYANAN UPTD {{ @$data->uptd_romawi }}</p>
            <p>SUB KEGIATAN PEMELIHARAAN RUTIN JALAN</p>
            <p>PROVINSI JAWA BARAT</p> --}}
            <h5 class=" font-weight-bold" style="font-size: 12px">REKAP DATA ENTRY PEKERJAAN PEMELIHARAAN RUTIN</h5>
            <button id="cetak" style="float: right; z-index:-1" type="button" >CETAK</button>
        
        </div>            
        
    </div>
    
    <div class="row">
        <div class="col-7 mt-2">
            <p class=" font-weight-bold">Periode : {{ $filter['tanggal_awal'] }} s/d {{ $filter['tanggal_akhir'] }} </p>

            <table class="table table-sm table-bordered" >
                <thead>
                    <tr class="text-center">
                        <th>UPTD</th>
                        <th>TOTAL</th>
                        <th>TERAKHIR ENTRY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $no => $item)
                    <tr>
                        <td>{{ Str::limit($item->nama, 6, $end='') }}</td>  
                        <td class="text-center">{{ count($item->library_pemeliharaan->whereBetween('tanggal', [$filter['tanggal_awal'] , $filter['tanggal_akhir'] ])) }}</td> 
                        <td>
                            @if (count($item->library_pemeliharaan)>=1)
                            {{ $item->library_pemeliharaan()->orderBy('tglreal','desc')->first()->tglreal }}
                            @else
                            
                            @endif
                        </td>  

                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
        <div class="col-12 mt-3">
            <p class=" font-weight-bold">Detail</p>
            <table class="table table-sm table-bordered" >
                <thead class="text-center">
                    <tr class="text-center">
                        <th rowspan="2" class="align-middle">UPTD</th>
                        <th colspan="2">SPPJJ</th>
                        <th rowspan="2" class="align-middle">JUMLAH ENTRY</th>
                        <th rowspan="2" class="align-middle">TOTAL</th>
                        <th rowspan="2" class="align-middle">TERAKHIR ENTRY</th>
                    </tr>
                    <tr>
                        <th>NAMA</th>
                        <th>JUMLAH MANDOR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $no => $item)
                    @php
                    if($item->id == 1){
                        $role = 'Mandor - UPTD 1';
                        $role_id = 52;
                    }else if($item->id == 2){
                        $role = 'Mandor - UPTD 2';
                        $role_id = 91;
                    }else if($item->id == 3){
                        $role = 'Mandor - UPTD 3';
                        $role_id = 61;
                    }else if($item->id == 4){
                        $role = 'Mandor - UPTD 4';
                        $role_id = 70;
                    }else if($item->id == 5){
                        $role = 'Mandor - UPTD 5';
                        $role_id = 77;
                    }else if($item->id == 6){
                        $role = 'Mandor - UPTD 6';
                        $role_id = 84;
                    }
                   
                    @endphp
                    <tr>
                        <td class="align-middle" rowspan="{{ count($item->library_sup) }}">{{ Str::limit($item->nama, 6, $end='') }}</td>  
                        <td class="align-middle">{{ $item->library_sup[0]->name }}</td>
                        <td class="text-center">{{ count($item->library_sup[0]->library_user->where('internal_role_id',$role_id)) }}</td>
                        <td class="text-center">{{ count($item->library_sup[0]->library_pemeliharaan->whereBetween('tanggal', [$filter['tanggal_awal'] , $filter['tanggal_akhir'] ])) }}</td>
                        
                        <td  class="text-center align-middle" rowspan="{{ count($item->library_sup) }}">{{ count($item->library_pemeliharaan->whereBetween('tanggal', [$filter['tanggal_awal'] , $filter['tanggal_akhir'] ])) }}</td> 
                        <td class="align-middle" rowspan="{{ count($item->library_sup) }}">
                            @if (count($item->library_pemeliharaan)>=1)
                            {{ $item->library_pemeliharaan()->orderBy('tglreal','desc')->first()->tglreal }}
                            @else
                            
                            @endif
                        </td>  

                    </tr>
                        @for ($x=1 ; $x < count($item->library_sup) ;$x++)
                        <tr>
                            <td>{{ $item->library_sup[$x]->name }}</td>
                            <td class="text-center">{{ count($item->library_sup[$x]->library_user->where('internal_role_id',$role_id)) }}</td>
                            <td class="text-center">{{ count($item->library_sup[$x]->library_pemeliharaan->whereBetween('tanggal', [$filter['tanggal_awal'] , $filter['tanggal_akhir'] ])) }}</td>


                        </tr>
                            
                        @endfor
                        
                    @endforeach
                </tbody>
            </table>
            
        </div>
        
    </div>
    
</page>
@endsection
@push('scripts')
<style>
     #logo {
            height: 2.5cm;
            object-fit:scale-down;
        }
</style>
@endpush