@extends('admin.layout.index')

@section('title') Admin Dashboard @endsection
@section('page-header')
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4 id="headerHead">Daftar Permohon Labolatorium Kontruksi</h4>
                    {{-- <span>Data Seluruh Jembatan</span> --}}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class=" breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ url('admin') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li id="breadcrumbText" class="breadcrumb-item"><a href="#!">Daftar Permohon</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('page-body')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 id="headerBody">Tabel Daftar Permohon</h5>
                    <div class="card-header-right">
                        <ul class="list-unstyled card-option">
                            {{-- <li><i class="feather icon-maximize full-card"></i></li> --}}
                            <li><i class="feather icon-minus minimize-card"></i></li>
                        </ul>
                    </div>
                </div>
                <div class="card-block">
                    <div id="index_component">
                        @if (hasAccess(Auth::user()->internal_role_id, 'Bahan Uji Labkon', 'Create'))
                            <button id="create_add" class="btn btn-mat btn-primary mb-3">Tambah</button>
                        @endif
                        <div class="dt-responsive table-responsive">
                            <table id="tabel_init" class="table table-hover m-b-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Permohonan</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Email Perusahaan</th>
                                        <th>No. Telp Perusahaan</th>
                                        <th>Nama Penanggung Jawab</th>
                                        <th>Email Penanggung Jawab</th>
                                        <th>No. Telp Penanggung Jawab</th>
                                        <th>NIP</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                    <div id="create_component" class="d-none">
                        <form action="javascript:void(0)">
                            <h5>Perusahaan</h5>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Perusahaan</label>
                                <div class="col-md-9">
                                    <input name="nama_perusahaan" type="text" class="form-control" required
                                        id="nama_perusahaan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Email Perusahaan</label>
                                <div class="col-md-9">
                                    <input name="email_perusahaan" type="email" class="form-control" required
                                        id="email_perusahaan">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">No Telp Perusahaan</label>
                                <div class="col-md-9">
                                    <input name="no_telp_perusahaan" type="number" class="form-control" required
                                        id="no_telp_perusahaan">
                                </div>
                            </div>
                            <h5>Penanggung Jawab</h5>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Nama Penanggung Jawab</label>
                                <div class="col-md-9">
                                    <input name="nama_penanggung_jawab" id="nama_penanggung_jawab" type="text"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Email Penanggung Jawab</label>
                                <div class="col-md-9">
                                    <input name="email_penanggung_jawab" type="email" class="form-control"
                                        id="email_penanggung_jawab" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">No Telp Penanggung Jawab</label>
                                <div class="col-md-9">
                                    <input name="no_telp_penanggung_jawab" type="number" class="form-control" required
                                        id="no_telp_penanggung_jawab">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">NIP Penanggung Jawab</label>
                                <div class="col-md-9">
                                    <input name="nip" type="number" class="form-control" required id="nip">
                                </div>
                            </div>
                            <div class=" form-group row">
                                <div class="col-12">
                                    <button id="create_cancel" type="button"
                                        class="btn btn-default waves-effect">Batal</button>
                                    <button id="create_submit" type="submit"
                                        class="btn btn-primary waves-effect waves-light ml-2">Simpan</button>
                                </div>
                            </div>
                        </form>
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
                        <h4 class="modal-title">Hapus Data Pemohon</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus data ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Tutup</button>
                        <button type="button" id="deleleConfirm" href=""
                            class="btn btn-danger waves-effect waves-light ">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const token = `bearer {{ Auth::user()->remember_token }}`;
        const headers = {
            Authorization: token,
        }

        function initializeDataTable({
            data,
            columns
        }) {
            $('#tabel_init').DataTable().clear().destroy();
            $(`#tabel_init`).DataTable({
                data,
                columns,
                language: {
                    emptyTable: "Tidak ada data tersedia.",
                },
            });
            $('.button_edit').bind('click', function(event) {
                const id = $(event.currentTarget).data('id')
                mode({
                    type: 'edit',
                    id
                });
            })
        }

        async function getDaftarPermohonan() {
            const url = `{{ route('api_labkon_pemohon_index') }}`;
            const results = await api.get('/labkon/daftar_pemohon')
            const columns = [{
                render: function(data, type, full, meta) {
                    return +meta.row + 1;
                }
            }, {
                data: 'id_pemohon'
            }, {
                data: 'nama_perusahaan'
            }, {
                data: 'email_perusahaan'
            }, {
                data: 'no_telp_perusahaan'
            }, {
                data: 'nama_penanggung_jawab'
            }, {
                data: 'email_penanggung_jawab'
            }, {
                data: 'no_telp_penanggung_jawab'
            }, {
                data: 'nip'
            }, {
                render: function(data, type, full, meta) {
                    let label, status;
                    switch (Number(full['status'])) {
                        case 1:
                            label = "primary";
                            status = "Waiting List";
                            break;
                        case 2:
                            label = "warning";
                            status = "On Proggress";
                            break;
                        case 3:
                            label = "success";
                            status = "Selesai";
                            break;
                        default:
                            label = "danger";
                            status = "Belum divalidasi";
                    }
                    return `<label class="label label-${label}">${status}</label>`;
                }
            }, {
                render: function(data, type, full, meta) {
                    let html =
                        `<div class="btn-group row" role="group" data-placement="top" data-original-title=".btn-xlg">`
                    if (
                        `{{ hasAccess(Auth::user()->internal_role_id, 'Bahan Uji Labkon', 'Update') }}`
                    )
                        html +=
                        `<button class="button_edit btn btn-primary btn-sm waves-effect waves-light col-6" data-id="${full['id_pemohon']}" data-toggle="tooltip" title="Edit"><i class="icofont icofont-pencil"></i></button>`
                    if (
                        `{{ hasAccess(Auth::user()->internal_role_id, 'Bahan Uji Labkon', 'Delete') }}`
                    )
                        html +=
                        `<a href="#delModal" class="btn btn-danger btn-sm waves-effect waves-light col-6" data-href="/labkon/daftar_pemohon/delete/${full['id_pemohon']}" data-action="detail" data-toggle="modal" data-toggle="tooltip" title="Hapus"><i class="icofont icofont-trash"></i></a>`
                    html += `</div>`
                    return html;
                }

            }, ];
            const params = {
                data: results.data.daftar_pemohon,
                columns
            }
            initializeDataTable(params);
        }

        function mode({
            type,
            id
        }) {
            const headerHead = $('#headerHead');
            const breadcrumbText = $('#breadcrumbText');
            const headerBody = $('#headerBody');
            switch (type) {
                case 'create':
                    $('#index_component').hide();
                    $('#create_component').show().removeClass('d-none');
                    headerHead.text('Tambah data pemohon');
                    breadcrumbText.text('Tambah');
                    headerBody.text('Tambah data pemohon');
                    localStorage.setItem('mode', 'create');
                    $('form').each(function() {
                        this.reset();
                    });
                    $('#create_submit').unbind().bind('click', () => {
                        const formData = $('form').serializeArray()
                        const form = {};
                        formData.forEach(input => form[input.name] = input.value);
                        api.post('labkon/daftar_pemohon/create', form).then(data => {
                            console.log(data);
                            renderSession('success', 'Berhasil menambah data.');
                            mode({
                                type: 'index'
                            });
                        });
                    })
                    break;
                case 'edit':
                    api.get(`labkon/daftar_pemohon/show/${id}`).then(results => {
                        const pemohon = results.data.pemohon;
                        Object.keys(pemohon).forEach(key => {
                            console.log(pemohon[key]);
                            if ($(`#${key}`)) $(`#${key}`).val(pemohon[key]);
                        })
                    });
                    $('#index_component').hide();
                    $('#create_component').show().removeClass('d-none');
                    headerHead.text('Perbaharui data pemohon');
                    breadcrumbText.text('Perbaharui');
                    headerBody.text('Perbaharui data pemohon');
                    localStorage.setItem('mode', 'edit');
                    localStorage.setItem('mode_edit_id', id);
                    $('#create_submit').unbind().bind('click', () => {
                        const formData = $('form').serializeArray()
                        const form = {};
                        formData.forEach(input => form[input.name] = input.value);
                        api.put(`labkon/daftar_pemohon/edit/${id}`, form).then(data => {
                            console.log(data);
                            renderSession('success', 'Berhasil menperbaharui data.');
                            mode({
                                type: 'index'
                            });
                        });
                    })
                    break;
                default:
                    $('#index_component').show();
                    $('#create_component').hide();
                    headerHead.text('Daftar Pemohon Labolatorium Kontruksi');
                    breadcrumbText.text('Daftar Pemohon');
                    headerBody.text('Tabel Daftar Pemohon');
                    getDaftarPermohonan();
                    localStorage.setItem('mode', 'index');
            }
        }

        $(document).ready(() => {
            const type = localStorage.getItem('mode');
            type ? (type == 'edit') ? mode({
                type,
                id: localStorage.getItem('mode_edit_id')
            }) : mode({
                type
            }) : mode({
                type: 'index'
            });
            $('#create_add').bind('click', () => mode({
                type: 'create'
            }));
            $('#create_cancel').bind('click', () => mode({
                type: 'index'
            }));
        })

        $('#delModal').on('show.bs.modal', function(event) {
            const link = $(event.relatedTarget);
            const url = link.data('href');
            const modal = $(this);
            modal.find('.modal-footer #deleleConfirm').bind('click', function() {
                api.get(url).then((response) => {
                    if (response.status == 200) {
                        $('#delModal').modal('hide');
                        getDaftarPermohonan();
                        renderSession('success', 'Berhasil menghapus data.');
                    }
                })
            })
        });

    </script>
@endsection
