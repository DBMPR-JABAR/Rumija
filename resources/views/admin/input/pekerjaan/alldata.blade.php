<tr>
    {{-- <td>{{++$no}}</td> --}}
    <td>{{$data->id_pek}}</td>
    <td>{{$data->nama_mandor}}</td>
    <td>{{$data->sup}}</td>
    <td>{{$data->ruas_jalan}}</td>
    <td>{{$data->jenis_pekerjaan}}</td>
    <td>{{$data->lokasi}}</td>
    <td>{{@$data->panjang}}</td>
    <td>{{@$data->perkiraan_kuantitas}}</td>
    {{-- <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/pekerjaan/'.$data->foto_awal) !!}" alt="" srcset=""></td>
    <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/pekerjaan/'.$data->foto_sedang) !!}" alt="" srcset=""></td>
    <td><img class="img-fluid" style="max-width: 100px" src="{!! url('storage/pekerjaan/'.$data->foto_akhir) !!}" alt="" srcset=""></td>
    <td><video width='150' height='100' controls> <source src="{!! url('storage/pekerjaan/'.$data->video) !!}" type='video/*' Sorry, your browser doesn't support the video element.></video></td> --}}
    <td>{{$data->tanggal}}</td>
    <td>@if($data->status)
            @if(str_contains($data->status->status,'Submitted') ||str_contains($data->status->status,'Approved') || str_contains($data->status->status,'Rejected')|| str_contains($data->status->status,'Edited') )
                @if(str_contains($data->status->status,'Approved') )
                    <button type="button" class="btn btn-mini btn-primary " disabled> {{$data->status->status}}</button>
                @elseif(str_contains($data->status->status,'Rejected') )
                    <button type="button" class="btn btn-mini btn-danger " disabled> {{$data->status->status}}</button>
                @elseif(str_contains($data->status->status,'Submitted') )
                    <button type="button" class="btn btn-mini btn-success waves-effect" disabled> {{$data->status->status}}</button>


                @else
                    <button type="button" class="btn btn-mini btn-warning " disabled> {{$data->status->status}}</button>
                @endif
                <br>{{$data->status->jabatan}}<br>
                <a href="{{ route('detailStatusPekerjaan',$data->id_pek) }}"><button type="button" class="btn btn-sm waves-effect waves-light " ><i class="icofont icofont-search"></i> Detail</button>
            @else
                @if($data->input_material)
                    <button type="button" class="btn btn-mini btn-success waves-effect " disabled>Submitted</button>
                @endif
            @endif
        @else
            <a href="@if(str_contains(Auth::user()->internalRole->role,'Mandor')  || str_contains(Auth::user()->internalRole->role,'Pengamat')|| str_contains(Auth::user()->internalRole->role,'Admin')) {{ route('materialDataPekerjaan',$data->id_pek) }} @else # @endif">

                <button type="button" class="btn btn-mini btn-warning waves-effect " @if(str_contains(Auth::user()->internalRole->role,'Mandor') || str_contains(Auth::user()->internalRole->role,'Pengamat') || str_contains(Auth::user()->internalRole->role,'Admin')) @else disabled @endif>Not Completed</button>
            </a>
            <br>
            <i style="color :red; font-size: 10px;">Lengkapi material</i>
        @endif
    </td>

    <td style="min-width: 170px;">

        <div class="btn-group" role="group" data-placement="top" title="" data-original-title=".btn-xlg">
            @if(Auth::user()->internalRole->role != null && str_contains(Auth::user()->internalRole->role,'Mandor')||str_contains(Auth::user()->internalRole->role,'Admin')||(str_contains(Auth::user()->internalRole->role,'Pengamat')&& $data->status != null && (str_contains($data->status->status,'Rejected')|| str_contains($data->status->status,'Edited'))) && !str_contains(Auth::user()->internalRole->role,'Kepala Satuan Unit Pemeliharaan'))
                @if(!$data->keterangan_status_lap ||str_contains($data->status->status,'Submitted')|| str_contains($data->status->status,'Rejected')|| (str_contains($data->status->status,'Edited')&&Auth::user()->id == $data->status->adjustment_user_id)||str_contains(Auth::user()->internalRole->role,'Admin'))
                    @if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Update"))
                    <a href="{{ route('editDataPekerjaan',$data->id_pek) }}"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Edit"><i class="icofont icofont-pencil"></i></button></a>
                    <a href="{{ route('materialDataPekerjaan',$data->id_pek) }}"><button class="btn btn-warning btn-sm waves-effect waves-light" data-toggle="tooltip" title="Lengkapi Data"><i class="icofont icofont-list"></i></button></a>
                    @endif
                    @if(!$data->keterangan_status_lap ||str_contains(Auth::user()->internalRole->role,'Admin'))
                        @if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Delete"))
                        <a href="#delModal" data-id="{{$data->id_pek}}" data-toggle="modal"><button class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                        @endif
                    @endif
                    {{-- @if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Update"))
                    <a href="#submitModal" data-id="{{$data->id_pek}}" data-toggle="modal"><button class="btn btn-success btn-sm waves-effect waves-light" data-toggle="tooltip" title="Submit"><i class="icofont icofont-check-circled"></i></button></a>
                    @endif --}}
                @elseif(str_contains(Auth::user()->internalRole->role,'Pengamat')&& $data->status != null && (str_contains($data->status->status,'Edited') && Auth::user()->id != $data->status->adjustment_user_id ))
                    @if(Auth::user()->internal_role_id!=null && Auth::user()->internal_role_id ==$data->status->parent )
                        @if(str_contains(Auth::user()->internalRole->role,'Pengamat') && Auth::user()->sup_id==$data->status->sup_id)
                            <a href="{{ route('jugmentDataPekerjaan',$data->id_pek) }}"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Judgement"><i class="icofont icofont-pencil"></i>Judgement</button></a>
                        @endif
                    @endif
                    @if(@$data->status->adjustment_user_id==Auth::user()->id )
                        <a href="{{ route('jugmentDataPekerjaan',$data->id_pek) }}"><button class="btn btn-warning btn-sm waves-effect waves-light" data-toggle="tooltip" title="Edit Judgement"><i class="icofont icofont-pencil"></i>Edit Judgement</button></a>
                    @endif
                @endif
            @else
                @if($data->status)
                    @if(Auth::user()->internal_role_id!=null && Auth::user()->internal_role_id ==$data->status->parent )
                        @if(str_contains(Auth::user()->internalRole->role,'Pengamat') || (str_contains(Auth::user()->internalRole->role,'Kepala Satuan Unit Pemeliharaan') && $data->status->status == "Approved" || $data->status->status =="Edited"|| $data->status->status =="Submitted") && Auth::user()->sup_id==$data->status->sup_id)
                            <a href="{{ route('jugmentDataPekerjaan',$data->id_pek) }}"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Judgement"><i class="icofont icofont-pencil"></i>Judgement</button></a>
                        @elseif(!str_contains(Auth::user()->internalRole->role,'Pengamat') && !str_contains(Auth::user()->internalRole->role,'Kepala Satuan Unit Pemeliharaan') && $data->status->status == "Approved")
                            <a href="{{ route('jugmentDataPekerjaan',$data->id_pek) }}"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Judgement"><i class="icofont icofont-pencil"></i>Judgement</button></a>
                        @endif
                    @endif
                    @if(@$data->status->adjustment_user_id==Auth::user()->id && !str_contains($data->status->status,'Submitted'))
                        <a href="{{ route('jugmentDataPekerjaan',$data->id_pek) }}"><button class="btn btn-warning btn-sm waves-effect waves-light" data-toggle="tooltip" title="Edit Judgement"><i class="icofont icofont-pencil"></i>Edit Judgement</button></a>
                    @elseif(str_contains(Auth::user()->internalRole->role,'Pengamat') && str_contains($data->status->status,'Submitted')&& str_contains($data->status->jabatan,'Pengamat'))
                        @if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Update"))
                        <a href="{{ route('editDataPekerjaan',$data->id_pek) }}"><button class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="tooltip" title="Edit"><i class="icofont icofont-pencil"></i></button></a>
                        <a href="{{ route('materialDataPekerjaan',$data->id_pek) }}"><button class="btn btn-warning btn-sm waves-effect waves-light" data-toggle="tooltip" title="Lengkapi Data"><i class="icofont icofont-list"></i></button></a>
                        @endif
                            @if (hasAccess(Auth::user()->internal_role_id, "Pekerjaan", "Delete"))
                                <a href="#delModal" data-id="{{$data->id_pek}}" data-toggle="modal"><button class="btn btn-danger btn-sm waves-effect waves-light" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></button></a>
                            @endif
                    @endif
                @endif
            @endif
            &nbsp;<a href="{{ route('detailPemeliharaan',$data->id_pek) }}"><button class="btn btn-success btn-sm waves-effect waves-light" data-toggle="tooltip" title="lihat"><i class="icofont icofont-search"></i></button></a>

        </div>
    </td>
</tr>