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
                        <li>
                            <a href="{{route('admin.maps')}}" target="_blank" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Executive Dashboard</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            </ul>
            @endif

            @if (hasAccess(Auth::user()->internal_role_id, 'Data Master', 'View'))
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'master-data' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-grid"></i></span>
                        <span class="pcoded-mtext">Data Master</span>
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
                    </ul>
                </li>
            </ul>
            @endif
            @if (hasAccess(Auth::user()->internal_role_id, 'Input Pekerjaan', 'View'))
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'input-data' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                        <span class="pcoded-mtext">Bidang Harbang</span>
                    </a>
                    <ul class="pcoded-submenu">
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
                                        <span class="pcoded-mtext">Pemanfaatan Rumija</span>
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
            <ul class="pcoded-item pcoded-left-item">
                <li class="pcoded-hasmenu {{ Request::segment(2) == 'inventarisasi' ? 'pcoded-trigger active' : '' }}">
                    <a href="javascript:void(0)" class="waves-effect waves-dark">
                        <span class="pcoded-micon"><i class="feather icon-clipboard"></i></span>
                        <span class="pcoded-mtext">Inventarisasi</span>
                    </a>
                    <ul class="pcoded-submenu">
                        @if (hasAccess(Auth::user()->internal_role_id, 'Rumija', 'View'))
                        <li
                            class="@if (Request::segment(3) == 'list' || Request::segment(3) == 'create') active @endif">
                            <a href="{{ route('rumija.inventarisasi.index') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Data</span>
                            </a>
                        </li>
                        @endif
                        {{-- @if (hasAccess(Auth::user()->internal_role_id, 'Permohonan Rumija', 'View'))
                        <li class="{{ Request::segment(3) == 'kategori' ? 'active' : '' }}">
                            <a href="{{ route('rumija.inventarisasi.kategori.index') }}"
                                class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Kategori</span>
                            </a>
                        </li>
                        @endif --}}
                        @if (hasAccess(Auth::user()->internal_role_id, 'Rumija', 'View'))
                        <li class="@if (Request::segment(3) == 'report') active @endif">
                            <a href="{{ route('rumija.inventarisasi.report.index') }}" class="waves-effect waves-dark">
                                <span class="pcoded-mtext">Laporan</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
            </ul>
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
