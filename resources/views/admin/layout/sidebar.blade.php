<nav class="pcoded-navbar">
    <div class="nav-list">
        <div class="pcoded-inner-navbar main-menu">
            <ul class="pcoded-item pcoded-left-item">
                <li class="{{ Request::segment(2) == 'home' ? 'active' : '' }}">
                    <a href="{{ url('admin/home') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-home"></i>
                        </span>
                        <span class="pcoded-mtext">Halaman Utama</span>
                    </a>
                </li>
            </ul>
            @if (hasAccess(Auth::user()->internal_role_id, 'Monitoring', 'View'))
            {{-- <div class="pcoded-navigation-label">Dashboard Analysis</div> --}}
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'monitoring' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-monitor"></i>
                        </span>
                        <span class="pcoded-mtext">Monitoring</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if (hasAccess(Auth::user()->internal_role_id, 'Executive Dashboard', 'View'))
                        <li class="{{ Request::segment(3) == 'map-dashboard' ? 'active' : '' }}">
                            <a href="{{ url('admin/map-dashboard') }}" target="_blank" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Executive Dashboard</span>
                            </a>
                        </li>
                        @endif
                        {{-- @if (hasAccess(Auth::user()->internal_role_id, 'Kendali Kontrak', 'View'))
                                <li class="{{ Request::segment(3) == 'kendali-kontrak' ? 'active' : '' }}">
                        <a href="{{ url('admin/monitoring/kendali-kontrak') }}" class="waves-effect waves-dark">
                            <span class="pcoded-mtext">Kendali Kontrak</span>
                        </a>
                </li>
                @endif --}}
                {{-- @if (hasAccess(Auth::user()->internal_role_id, 'Kemantapan Jalan', 'View'))
                <li class="{{ Request::segment(3) == 'kemantapan-jalan' ? 'active' : '' }}">
                <a href="{{ url('admin/monitoring/kemantapan-jalan') }}" class="waves-effect waves-dark">
                    <span class="pcoded-mtext">Kemantapan Jalan</span>
                </a>
                </li>
                @endif --}}
                @if (hasAccess(Auth::user()->internal_role_id, 'Anggaran & Realisasi Keuangan', 'View'))
                <li class="{{ Request::segment(3) == 'realisasi-keuangan' ? 'active' : '' }}">
                    <a href="{{ url('admin/monitoring/realisasi-keuangan') }}" class="waves-effect waves-dark">
                        <span class="pcoded-mtext">Target & Realisasi</span>
                    </a>
                </li>
                @endif
                @if (hasAccess(Auth::user()->internal_role_id, 'CCTV Control Room', 'View'))
                <li class="{{ Request::segment(3) == 'cctv' ? 'active' : '' }}">
                    <a href="{{ url('admin/monitoring/cctv') }}" class="waves-effect waves-dark">
                        <span class="pcoded-mtext">CCTV Control Room</span>
                    </a>
                </li>
                @endif
                @if (hasAccess(Auth::user()->internal_role_id, 'Monitoring Survei Kondisi Jalan', 'View'))
                <li class="{{ Request::segment(3) == 'roadroid-survei-kondisi-jalan' ? 'active' : '' }}">
                    <a href="{{ url('/admin/monitoring/roadroid-survei-kondisi-jalan') }}"
                        class="waves-effect waves-dark">
                        <span class="pcoded-mtext">Survei Kondisi Jalan</span>
                    </a>
                </li>
                @endif
                @if (hasAccess(Auth::user()->internal_role_id, 'Pekerjaan', 'View'))
                <li class="{{ Request::segment(3) == 'pekerjaan_resume' ? 'active' : '' }}">
                    <a href="{{ url('/admin/monitoring/pekerjaan_resume') }}">
                        <span class="pcoded-mtext">Pekerjaan</span>
                    </a>
                </li>
                @endif
            </ul>
            </li>
            </ul>
            @endif
            </ul>

            @if (hasAccess(Auth::user()->internal_role_id, 'Data Master', 'View'))
            {{-- <div class="pcoded-navigation-label">Data Master</div> --}}
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'master-data' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-grid"></i></span>
                        <span class="pcoded-mtext">Data Master</span>
                        {{-- <span class="pcoded-badge label label-warning">NEW</span> --}}
                    </a>
                    <ul class="pcoded-submenu">
                        @if (hasAccess(Auth::user()->internal_role_id, 'User', 'View'))
                        <li class=" pcoded-hasmenu  {{ Request::segment(3) == 'user' ? 'pcoded-trigger active' : '' }}">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">User</span>
                            </a>
                            <ul class="pcoded-submenu">
                                @if (hasAccess(Auth::user()->internal_role_id, 'Manajemen User', 'View'))
                                <li class="{{ Request::segment(4) == 'manajemen' ? 'active' : '' }}">
                                    <a href="{{ route('getMasterUser') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Manajemen User</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'User Role', 'View'))
                                <li class="{{ Request::segment(4) == 'user-role' ? 'active' : '' }}">
                                    <a href="{{ route('getDataUserRole') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">User Role</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'Role Akses', 'View'))
                                <li class="{{ Request::segment(4) == 'role-akses' ? 'active' : '' }}">
                                    <a href="{{ route('getRoleAkses') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Role Akses</span>
                                    </a>
                                </li>
                                @endif
                                @if (Auth::user() && Auth::user()->id == 1)
                                <li class="{{ Request::segment(4) == 'permission' ? 'active' : '' }}">
                                    <a href="{{ route('getAkses') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Permission</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Ruas Jalan', 'View'))
                        <li class="{{ Request::segment(3) == 'ruas-jalan' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/ruas-jalan') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Ruas Jalan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Jembatan', 'View'))
                        <li class="{{ Request::segment(3) == 'jembatan' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/jembatan') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Jembatan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Rawan Bencana', 'View'))
                        <li class="{{ Request::segment(3) == 'rawanbencana' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/rawanbencana') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Rawan Bencana</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'CCTV', 'View'))
                        <li class="{{ Request::segment(3) == 'CCTV' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/CCTV') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">CCTV</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Icon Rawan Bencana', 'View'))
                        <li class="{{ Request::segment(3) == 'icon' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/icon') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Icon Rawan Bencana</span>
                            </a>
                        </li>
                        @endif

                        @if (hasAccess(Auth::user()->internal_role_id, 'UPTD', 'View'))
                        <li class="{{ Request::segment(3) == 'uptd' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/uptd') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">UPTD</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'SUP', 'View'))
                        <li class="{{ Request::segment(3) == 'sup' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/sup') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">SUP</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Tipe Bangunan Atas', 'View'))
                        <li class="{{ Request::segment(3) == 'tipebangunanatas' ? 'active' : '' }}">
                            <a href="{{ route('tipebangunanatas.index') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Tipe Bangunan Atas</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Jenis Laporan', 'View'))
                        <li class="{{ Request::segment(3) == 'jenis_laporan' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/jenis_laporan') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Jenis Laporan Pekerjaan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Nama Kegiatan Pekerjaan', 'View'))
                        <li class="{{ Request::segment(3) == 'nama_kegiatan_pekerjaan' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/nama_kegiatan_pekerjaan') }}"
                                class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Nama Kegiatan Pekerjaan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Bahan Material', 'View') ||
                        hasAccess(Auth::user()->internal_role_id, 'Peralatan', 'View'))

                        <li
                            class=" pcoded-hasmenu  {{ Request::segment(3) == 'item_bahan_material' ||Request::segment(3) == 'peralatan' ? 'pcoded-trigger active' : '' }}">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Peralatan & Material</span>
                            </a>
                            <ul class="pcoded-submenu">
                                @if (hasAccess(Auth::user()->internal_role_id, 'Bahan Material', 'View'))
                                <li class="{{ Request::segment(4) == 'item_bahan_material' ? 'active' : '' }}">
                                    <a href="{{ url('admin/master-data/item_bahan_material') }}"
                                        class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Bahan Material</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'Peralatan', 'View'))
                                <li class="{{ Request::segment(4) == 'peralatan' ? 'active' : '' }}">
                                    <a href="{{ route('item_peralatan.index') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Peralatan Operasional</span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Item Satuan', 'View'))
                        <li class="{{ Request::segment(3) == 'item_satuan' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/item_satuan') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Item Satuan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Data Master LabKon', 'View') ||
                        hasAccess(Auth::user()->internal_role_id, 'Bahan Uji LabKon', 'View'))
                        <li
                            class=" pcoded-hasmenu  {{ Request::segment(3) == 'labkon' ? 'pcoded-trigger active' : '' }}">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Labolatorium Kontruksi</span>
                            </a>
                            <ul class="pcoded-submenu">
                                @if (hasAccess(Auth::user()->internal_role_id, 'Bahan Uji LabKon', 'View'))
                                <li class="{{ Request::segment(4) == 'bahan_uji_labkon' ? 'active' : '' }}">
                                    <a href="{{ url('admin/master-data/labkon/bahan_uji_labkon') }}"
                                        class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Bahan Uji</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
            </ul>
            @endif
            @if (hasAccess(Auth::user()->internal_role_id, 'Input Pekerjaan', 'View'))
            {{-- <div class="pcoded-navigation-label">Input</div> --}}
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'input-data' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                        <span class="pcoded-mtext">Bidang Harbang</span>
                        {{-- <span class="pcoded-badge label label-warning">NEW</span> --}}
                    </a>
                    <ul class="pcoded-submenu">
                        @if (hasAccess(Auth::user()->internal_role_id, 'Pekerjaan', 'View'))
                        <li
                            class=" pcoded-hasmenu  {{ Request::segment(3) == 'pekerjaan' || Request::segment(3) == 'kondisi_jalan' || Request::segment(3) == 'survei_kondisi_jalan' || Request::segment(3) == 'mandor' ? 'pcoded-trigger active' : '' }}">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Pemeliharaan</span>
                            </a>
                            <ul class="pcoded-submenu">
                                <li class="{{ Request::segment(3) == 'pekerjaan' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/pekerjaan') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Pekerjaan</span>
                                    </a>
                                </li>
                                @if (hasAccess(Auth::user()->internal_role_id, 'Kondisi Jalan', 'View'))
                                <li class="{{ Request::segment(3) == 'kondisi_jalan' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/kondisi_jalan') }}"
                                        class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Kondisi Jalan</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'Survey Kondisi Jalan', 'View'))
                                <li class="{{ Request::segment(3) == 'survei_kondisi_jalan' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/survei_kondisi_jalan') }}"
                                        class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Survei Kondisi Jalan</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'Mandor', 'View'))
                                <li class="{{ Request::segment(3) == 'mandor' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/mandor') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Mandor</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if (hasAccess(Auth::user()->internal_role_id, 'Data Paket', 'View') ||
                        hasAccess(Auth::user()->internal_role_id, 'Progress Kerja', 'View') ||
                        hasAccess(Auth::user()->internal_role_id, 'Permohonan Rumija', 'View'))

                        <li
                            class=" pcoded-hasmenu  {{ Request::segment(3) == 'data-paket' ? 'pcoded-trigger active' : '' }}">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Pembangunan</span>
                            </a>
                            <ul class="pcoded-submenu">
                                @if (hasAccess(Auth::user()->internal_role_id, 'Data Paket', 'View'))
                                <li class="{{ Request::segment(3) == 'data-paket' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/data-paket') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Data Paket</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'Progress Kerja', 'View'))
                                <li class="{{ Request::segment(3) == 'progresskerja' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/progresskerja') }}"
                                        class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Progress Kerja</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'DPA', 'View'))
                                <li class="{{ Request::segment(3) == 'dpa' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/dpa') }}" class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">DPA</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif

                        @if (hasAccess(Auth::user()->internal_role_id, 'Rumija', 'View') ||
                        hasAccess(Auth::user()->internal_role_id, 'Permohonan Rumija', 'View'))
                        <li
                            class=" pcoded-hasmenu  {{ Request::segment(3) == 'rumija' ? 'pcoded-trigger active' : '' }}">
                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Pengawasan dan Pemanfaatan</span>
                            </a>
                            <ul class="pcoded-submenu">
                                @if (hasAccess(Auth::user()->internal_role_id, 'Rumija', 'View'))
                                <li class="{{ Request::segment(4) == 'rumija' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/rumija/rumija') }}"
                                        class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Rumija</span>
                                    </a>
                                </li>
                                @endif
                                @if (hasAccess(Auth::user()->internal_role_id, 'Permohonan Rumija', 'View'))
                                <li class="{{ Request::segment(4) == 'permohonan_rumija' ? 'active' : '' }}">
                                    <a href="{{ url('admin/input-data/rumija/permohonan_rumija') }}"
                                        class="waves-effect waves-dark">
                                        <span class="pcoded-mtext">Permohonan Rumija</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
            </ul>
            @endif

            @if (hasAccess(Auth::user()->internal_role_id, 'Bantuan Keuangan', 'View'))
            {{-- <div class="pcoded-navigation-label">Input</div> --}}
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'bidtek' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                        <span class="pcoded-mtext">Bidang Teknik</span>
                        {{-- <span class="pcoded-badge label label-warning">NEW</span> --}}
                    </a>
                    <ul class="pcoded-submenu">
                        @if (hasAccess(Auth::user()->internal_role_id, 'Bidang Teknik', 'View') ||
                        hasAccess(Auth::user()->internal_role_id, 'Bantuan Keuangan', 'View'))
                        @if (hasAccess(Auth::user()->internal_role_id, 'Bantuan Keuangan', 'View'))
                        <li class="{{ Request::segment(3).Request::segment(4) == 'bankeu' ? 'active' : '' }}">
                            <a href="{{ url('admin/bidtek/bankeu') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Bantuan Keuangan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Laporan Bantuan Keuangan', 'View'))
                        <li class="{{ Request::segment(3).Request::segment(4) == 'bankeuprogres' ? 'active' : '' }}">
                            <a href="{{ url('admin/bidtek/bankeu/progres') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Laporan BanKeu</span>
                            </a>
                        </li>
                        @endif
                        @endif
                    </ul>
                </li>
            </ul>
            @endif

            @if (hasAccess(Auth::user()->internal_role_id, 'Lapor', 'View'))
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'lapor' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-alert-circle"></i></span>
                        <span class="pcoded-mtext">Lapor</span>
                        {{-- <span class="pcoded-badge label label-warning">NEW</span> --}}
                    </a>
                    <ul class="pcoded-submenu">
                        @if (hasAccess(Auth::user()->internal_role_id, 'Input Laporan', 'Create'))
                        <li class="{{ Request::segment(3) == 'add' ? 'active' : '' }}">
                            <a href="{{ url('admin/lapor/add') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Input Laporan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Daftar Laporan', 'View'))
                        <li class="{{ Request::segment(2) == 'lapor' && Request::segment(3) == null ? 'active' : '' }}">
                            <a href="{{ url('admin/lapor') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Daftar Laporan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Laporan Kerusakan', 'View'))
                        <li class="{{ Request::segment(3) == 'laporan-kerusakan' ? 'active' : '' }}">
                            <a href="{{ url('admin/lapor/laporan-kerusakan') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Laporan Kerusakan</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Laporan Bencana', 'View'))
                        <li class="{{ Request::segment(3) == 'laporan_bencana' ? 'active' : '' }}">
                            <a href="{{ url('admin/master-data/laporan_bencana') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Laporan Bencana</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            </ul>
            @endif
            {{-- <div class="pcoded-navigation-label">Landing Page</div> --}}
            @if (hasAccess(Auth::user()->internal_role_id, 'Landing Page', 'View'))
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'landing-page' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-layout"></i>
                        </span>
                        <span class="pcoded-mtext">Landing Page</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if (!Auth::user()->internalRole->uptd)
                        @if (hasAccess(Auth::user()->internal_role_id, 'Profil WEB', 'View'))
                        <li class="{{ Request::segment(3) == 'profil' ? 'active' : '' }}">
                            <a href="{{ url('admin/landing-page/profil') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Profil</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Slideshow', 'View'))
                        <li class="{{ Request::segment(3) == 'slideshow' ? 'active' : '' }}">
                            <a href="{{ url('admin/landing-page/slideshow') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Slideshow</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Fitur', 'View'))
                        <li class="{{ Request::segment(3) == 'fitur' ? 'active' : '' }}">
                            <a href="{{ url('admin/landing-page/fitur') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Fitur</span>
                            </a>
                        </li>
                        @endif
                        @if (hasAccess(Auth::user()->internal_role_id, 'Video News', 'View'))
                        <li class="{{ Request::segment(3) == 'video-news' ? 'active' : '' }}">
                            <a href="{{ url('admin/landing-page/video-news') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Video</span>
                            </a>
                        </li>
                        @endif
                        @endif
                    </ul>
                </li>
                @endif

                @if (hasAccess(Auth::user()->internal_role_id, 'Pesan', 'View'))
                <li class="{{ Request::segment(2) == 'pesan' ? 'active' : '' }}">
                    <a href="{{ url('admin/pesan') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-message-circle"></i>
                        </span>
                        <span class="pcoded-mtext">Pesan Kontak Kami</span>
                    </a>
                </li>
                @endif
                @if (hasAccess(Auth::user()->internal_role_id, 'Log', 'View'))
                <li class="{{ Request::segment(2) == 'log' ? 'active' : '' }}">
                    <a href="{{ url('admin/log') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-alert-octagon"></i>
                        </span>
                        <span class="pcoded-mtext">Log</span>
                    </a>
                </li>
                @endif
                @if (hasAccess(Auth::user()->internal_role_id, 'Pengumuman', 'View'))
                <li class="{{ Request::segment(2) == 'announcement' ? 'active' : '' }}">
                    <a href="{{ url('admin/announcement') }}" class="waves-effect waves-dark">
                        <span class="pcoded-micon">
                            <i class="feather icon-bell"></i>
                        </span>
                        <span class="pcoded-mtext">Pengumuman</span>
                    </a>
                </li>
                @endif
        </div>
    </div>
</nav>
<script>
    const uls = document.querySelectorAll('.pcoded-item');

    uls.forEach(function(ul) {
        ul.addEventListener('click', function() {
            this.classList.remove('pcoded-trigger');
        });
    });

</script>
