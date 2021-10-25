<nav class="navbar header-navbar pcoded-header">
  <div class="navbar-wrapper">
    <div class="navbar-logo">
      <a href="{{ url('admin/home') }}">
        <img class="img-fluid" width="150" src="{{ asset('assets/images/brand/text_putih.png') }}"
          alt="SYSTARUMIJA-Logo" />
      </a>
      <a class="mobile-menu" id="mobile-collapse" href="#!">
        <i class="feather icon-menu icon-toggle-right"></i>
      </a>
      <a class="mobile-options waves-effect waves-light">
        <i class="feather icon-more-horizontal"></i>
      </a>
    </div>
    <div class="navbar-container container-fluid">
      <ul class="nav-left">
        <li>
          <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light" data-toggle="tooltip"
            data-placement="bottom" title="Tampilkan Secara layar penuh">
            <i class="full-screen feather icon-maximize"></i>
          </a>
        </li>
      </ul>
      <ul class="nav-right">
        <li class="header-notification">
          <div class="dropdown-primary dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
              <i class="feather icon-bell"></i>
              @if ($jumlah_notif_internal)<span class="badge bg-c-red">{{ $jumlah_notif_internal }}</span>@endif
            </div>
            <ul id="scrollable" class="show-notification notification-view dropdown-menu" data-dropdown-in="fadeIn"
              data-dropdown-out="fadeOut">
              <li>
                <h6>Notifications</h6>
                @if ($jumlah_notif_internal)
                  <label class="label label-danger">New</label>
                @endif
              </li>
              @foreach ($utils_notif as $item)
                <li>
                  <a @if ($item->nama_notif == 'pengumuman') href="{{ route('announcement.show', $item->slug) }}" @endif target="_blank">
                    <div class="row">
                      <div class="col-10">
                        <div class="media">
                          <div class="media-body ">
                            <h5 class="notification-user font-weight-bold">{{ Str::title($item->nama_notif) }}</h5>
                            <p class="notification-msg">{{ $item->title }}</p>
                            <span
                              class="notification-time">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
                          </div>
                        </div>

                      </div>
                      @if (count($read_notif_internal) != 0)
                        @php
                          foreach ($read_notif_internal as $item_read_notif) {
                              if ($item->nama_notif == $item_read_notif->title && $item->id == $item_read_notif->pointer_id && $item->utils_notifikasi_id == $item_read_notif->utils_notifikasi_id) {
                                  $statts = '';
                                  break;
                              } else {
                                  $statts = 'read_notif';
                              }
                          }
                        @endphp
                        <div class="col-2 {{ $statts }}">
                        </div>
                      @else
                        <div class="col-2 read_notif">
                        </div>
                      @endif
                    </div>
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </li>
        <li class="user-profile header-notification">
          <div class="dropdown-primary dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
              <span>{{ @Str::title($profile_users->nama) }}</span>
              <i class="feather icon-chevron-down"></i>
            </div>
            <ul class="show-notification profile-notification dropdown-menu" data-dropdown-in="fadeIn"
              data-dropdown-out="fadeOut">
              <li>
                <a href="#" class="py-0">
                  {{ Auth::user()->email }} <br>
                </a>
              </li>
              <li>
                <a href="{{ url('admin/profile', Auth::user()->id) }}">
                  <i class="feather icon-user"></i> Setting Akun & Profil
                </a>
              </li>
              <li>
                <a href="{{ route('log.user.index', Auth::user()->id) }}">
                  <i class="feather icon-alert-octagon"></i> Activity
                </a>
              </li>
              <li>
                <a href="{{ url('logout') }}">
                  <i class="feather icon-log-out"></i> Logout
                </a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
