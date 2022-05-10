<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('test', function () {
    return view('admin.layout.index');
});
// {SiteURL}
Route::get('/', 'LandingController@login')->name('/');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('admin');

Route::get('403', function () {
    return view('403')->render();
})->name('403');
Route::get('login', 'LandingController@login')->name('login');
Route::get('logout', 'AuthController@logout');
Route::get('verify-email/{token}', 'AuthController@verifyEmail');

Route::post('auth', 'AuthController@login');
Route::get('forced-login/{encrypted_id}', 'AuthController@loginUsingId');

Route::get('paket-pekerjaan', 'LandingController@paketPekerjaan');
Route::get('progress-pekerjaan', 'LandingController@progressPekerjaan');
Route::post('tambah-laporan', 'LandingController@createLaporan')->name('tambah-laporan');
// Route::post('role-akses/store', 'MasterData\UserController@storeRoleAccess')->name('storeRoleAccess');

Route::post('tambah-pesan', 'LandingController@createPesan');
Route::get('admin/master/ruas_jalan', 'MasterController@getRuasJalan')->name('admin.master.ruas_jalan');
Route::get('map/map-dashboard-masyarakat', 'MapLandingController@mapMasyarakat')->name('landing.map.map-dashboard-masyarakat');
Route::get('map/map-dashboard-uptd/{uptd_id}', 'MapLandingController@mapUptd')->name('landing.map.map-dashboard-uptd');

Route::post('dependent-dropdown', 'DropdownAddressController@store')
    ->name('dependent-dropdown.store');
Route::get('getCity', 'DropdownAddressController@getCity');
Route::get('/announcement/show/{id}', 'AnnouncementController@show')->name('announcementShow');
Route::get('pemeliharaan/pekerjaan/{id}', 'InputData\PekerjaanController@detailPemeliharaan')->name('detailPemeliharaan');

Route::prefix('status_jalan')->group(function () {
    Route::get('/', 'StatusJalanController@index');
    Route::prefix('api')->group(function () {
        Route::get('/', 'StatusJalanController@api_index');
    });
});

// {SiteURL}/uptd/*
Route::group(['prefix' => 'uptd'], function () {
    Route::get('/{slug}', 'LandingController@uptd');
    Route::get('/labkon/home', 'LandingController@labkon');
    Route::get('/labkon/posts', 'LandingController@createpost');
    Route::post('/labkon/posts', 'LandingController@storepost');
});

Route::get('user', 'CobaController@index');
Route::get('user/json', 'CobaController@json');

// {SiteURL}/admin/*
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/', function () {
        return redirect(route('monitoring-kontrak'));
    });
    


    Route::group(['prefix' => 'pengujian_bahan'], function () {
        Route::get('dashboard_pengujian', 'LabKonController@index');
        Route::get('input_data_pengujian', 'LabKonController@show')->name('listPengujianLabKon');
        Route::get('input_data_pengujian/add', 'LabKonController@add')->name('addPengujianLabkon');
        Route::post('input_data_pengujian/bahan_uji', 'LabKonController@bahan_uji')->name('bahanUjiPengujianLabkon');
        Route::post('input_data_pengujian/pengkajian', 'LabKonController@pengkajian')->name('pengkajianPengujianLabkon');
        Route::get('input_data_pengujian/cetak_permohonan/{id}', 'LabKonController@cetak_permohonan')->name('cetakPermohonanPengujianLabkon');
        Route::get('input_data_pengujian/pengkajian/{id}', 'LabKonController@pengkajian')->name('pengkajianPengujianLabkon');
    });

    Route::prefix('labkon')->group(function () {
        Route::prefix('daftar_pemohon')->group(function () {
            Route::get('/', 'LabKonController@daftar_pemohon')->name('labkon_index_pemohon');
        });
    });

    Route::prefix('pdf')->group(function () {
        Route::get('laporan_pekerjaan', 'PrintPDFController@laporanPekerjaan');
    });
    Route::get('announcement/destroy/{id}', 'AnnouncementController@destroy');
    Route::resource('/announcement', 'AnnouncementController');

    Route::get('profile/{id}', 'DetailUserController@show')->name('editProfile');
    Route::get('activity/{id}', 'LandingController@getLogUser')->name('log.user.index');

    Route::get('edit/profile/{id}', 'DetailUserController@edit')->name('editDetailProfile');
    Route::put('edit/profile/{id}', 'DetailUserController@update');
    Route::post('user/account/{id}', 'DetailUserController@updateaccount');
    Route::get('pesan', 'LandingController@getPesan');
    Route::get('log', 'LandingController@getLog')->middleware('role:Log,View');
    Route::get('home', 'Home@index')->name('admin-home');
    Route::get('/', 'Home@index');
    Route::get('file', 'Home@downloadFile');
    Route::view('map-dashboard', 'admin.map.map-dashboard')->middleware('role:Executive Dashboard,View');
    Route::view('map-dashboard-canggih', 'admin.map.map-dashboard-canggih');
    // {SiteURL}/admin/monitoring/*
    Route::group(['prefix' => 'monitoring'], function () {
        Route::get('maps', 'MapsController@index')->name('admin.maps');
        Route::get('progress-pekerjaan', 'MonitoringController@getProgressPekerjaan');
        Route::get('pekerjaan_resume', 'Monitoring\ResumeController@pekerjaan')->name('resume_pekerjaan');

        Route::view('survey-kondisi-jalan', 'admin.monitoring.survey-kondisi-jalan');
        Route::view('survey-kondisi-jalan/{uptd}', 'admin.monitoring.survey-kondisi-jalan-uptd')->name('kondisiJalanUPTD');
        Route::view('survey-kondisi-jalan/{uptd}/{jalan}', 'admin.monitoring.survey-kondisi-jalan-uptd-detail')->name('kondisiJalanUPTDDetail');

        // Route::get('kendali-kontrak', 'ProyekController@getKendaliKontrak')->name('monitoring-kontrak');
        Route::get('kendali-kontrak', 'IntegrasiTalikuatController@curva_s')->name('monitoring-kontrak');
        Route::get('kendali-kontrak/progress', 'ProyekController@getKendaliKontrakProgress')->name('monitoring-kontrak-progress');
        // Route::view('proyek-kontrak', 'admin.monitoring.proyek-kontrak')->name('monitoring-kontrak');

        Route::get('kendali-kontrak/status/{status}', 'ProyekController@getProyekStatus')->name('detailProyekKontrak');
        Route::get('kendali-kontrak/detail/{id}', 'ProyekController@getProyekDetail')->name('detailProyekKontrakID');

        Route::get('main-dashboard', 'MonitoringController@getMainDashboard');

        Route::view('realisasi-keuangan', 'admin.monitoring.realisasi-keuangan')->middleware('role:Anggaran & Realisasi Keuangan,View');
        Route::view('audit-keuangan', 'audit-keuangan');
        Route::get('kemantapan-jalan', 'MonitoringController@getKemantapanJalan');
        // Route::view('kemantapan-jalan-detail', 'admin.monitoring.kemantapan-jalan-detail');
        Route::get('cctv', 'SurveiController@getCCTV');
        // parameternya dari id ruas jalan
        Route::get('roadroid-survei-kondisi-jalan/{id}', 'SurveiController@getRoadroidSKJ');
        Route::view('roadroid-survei-kondisi-jalan', 'admin.map.map-roaddroid')->middleware('role:Monitoring Survei Kondisi Jalan,View');

        Route::get('progress_mingguan', 'Monitoring\ProgressMingguanController@getProggressMingguan')->name('getProgressMingguan');
        Route::get('progress_bulanan', 'Monitoring\ProgressMingguanController@getProggressBulanan')->name('getProgressBulanan');
        // dummy rekomendasi penyedia jasa dan konsultan
        Route::get('rekomendasi_penyedia_jasa_konsultan', function () {
            return view('admin.monitoring.penilaiain.rekomendasi_kontraktor_konsultan.index');
        });
    });

    // {SiteURL}/admin/rekomendasi/*
    Route::group(['prefix' => 'rekomendasi'], function () {
        Route::view('rekomendasi-kontraktor', 'admin.rekomendasi.rekomendasi-kontraktor');
        Route::view('rekomendasi-konsultan', 'admin.rekomendasi.rekomendasi-konsultan');
        Route::view('rekomendasi-perbaikan', 'admin.rekomendasi.rekomendasi-perbaikan');
    });

    // {SiteURL}/admin/landing-page/
    Route::group(['prefix' => 'landing-page'], function () {
        // {SiteURL}/admin/landing-page/profil
        Route::group(['prefix' => 'profil'], function () {
            Route::get('/', 'LandingController@getProfil');
            Route::post('update', 'LandingController@updateProfil')->name('updateLandingProfil');
        });

        // {SiteURL}/admin/landing-page/slideshow
        Route::group(['prefix' => 'slideshow'], function () {
            Route::get('/', 'LandingController@getSlideshow')->name('getLandingSlideshow');
            Route::get('edit/{id}', 'LandingController@editSlideshow')->name('editLandingSlideshow');
            Route::post('create', 'LandingController@createSlideshow')->name('createLandingSlideshow');
            Route::post('update', 'LandingController@updateSlideshow')->name('updateLandingSlideshow');
            Route::get('delete/{id}', 'LandingController@deleteSlideshow')->name('deleteLandingSlideshow');
        });

        // {SiteURL}/admin/landing-page/fitur
        Route::group(['prefix' => 'fitur'], function () {
            Route::get('/', 'LandingController@getFitur')->name('getLandingFitur');
            Route::get('edit/{id}', 'LandingController@editFitur')->name('editLandingFitur');
            Route::post('create', 'LandingController@createFitur')->name('createLandingFitur');
            Route::post('update', 'LandingController@updateFitur')->name('updateLandingFitur');
            Route::get('delete/{id}', 'LandingController@deleteFitur')->name('deleteLandingFitur');
        });

        // {SiteURL}/admin/landing-page/video-controls
        Route::resource('video-news', 'Landing\NewsVideoController');

        // {SiteURL}/admin/landing-page/uptd
        Route::group(['prefix' => 'uptd'], function () {
            Route::get('/', 'LandingController@getUPTD')->name('getLandingUPTD');
            Route::get('edit/{id}', 'LandingController@editUPTD')->name('editLandingUPTD');
            Route::post('create', 'LandingController@createUPTD')->name('createLandingUPTD');
            Route::post('update', 'LandingController@updateUPTD')->name('updateLandingUPTD');
            Route::get('delete/{id}', 'LandingController@deleteUPTD')->name('deleteLandingUPTD');
        });

        // {SiteURL}/admin/landing-page/laporan-masyarakat
        Route::group(['prefix' => 'laporan-masyarakat'], function () {
            Route::get('/', 'LandingController@getLaporanMasyarakat')->name('getLandingLaporanMasyarakat');
            Route::get('detail/{id}', 'LandingController@detailLaporanMasyarakat')->name('detailLandingLaporanMasyarakat');
            Route::get('edit/{id}', 'LandingController@editLaporanMasyarakat')->name('editLandingLaporanMasyarakat');
            Route::post('create', 'LandingController@createLaporanMasyarakat')->name('createLandingLaporanMasyarakat');
            Route::post('update', 'LandingController@updateLaporanMasyarakat')->name('updateLandingLaporanMasyarakat');
            Route::get('delete/{id}', 'LandingController@deleteLaporanMasyarakat')->name('deleteLaporanMasyarakat');
        });
    });

    Route::group(['prefix' => 'disposisi'], function () {
        Route::get('/', 'DisposisiController@getDaftarDisposisi')->name('daftar-disposisi');
        Route::get('masuk', 'DisposisiController@getInboxDisposisi')->name('disposisi-masuk');
        Route::get('tindaklanjut', 'DisposisiController@getDisposisiTindakLanjut')->name('disposisi-tindak-lanjut');
        Route::post('saveDisposisiLevel2', 'DisposisiController@saveDisposisiLevel2')->name('saveDisposisiLevel2');
        Route::get('instruksi', 'DisposisiController@getDaftarDisposisiInstruksi')->name('disposisi-instruksi');
        Route::get('download/{id}', 'DisposisiController@downloadFile')->name('download');
        Route::get('edit/{id}', 'DisposisiController@edit')->name('editDisposisi');

        Route::post('createTindakLanjut', 'DisposisiController@createTindakLanjut')->name('createTindakLanjut');

        Route::post('updateDisposisi', 'DisposisiController@updateDisposisi')->name('updateDisposisi');
        Route::post('create', 'DisposisiController@create')->name('saveInsertDisposisi');
        Route::post('createDisposisiInstruksi', 'DisposisiController@createInstruksi')->name('saveInsertInstruksi');
        Route::get('accepted/{id}', 'DisposisiController@getAcceptedRequest')->name('getAcceptedRequest');
        Route::get('detail/disposisi/{id}', 'DisposisiController@getdetailDisposisi')->name('getdetailDisposisi');

        Route::get('detail/disposisi-instruksi/{id}', 'DisposisiController@getdetailDisposisiInstruksi')->name('getdetailDisposisiInstruksi');
        Route::get('disposisi-instruksi/getData/{id}', 'DisposisiController@getDataDisposisiInstruksi')->name('getDataDisposisiInstruksi');
        Route::post('disposisi-instruksi/update', 'DisposisiController@updateDisposisiInstruksi')->name('saveUpdateInstruksi');
        Route::get('disposisi-instruksi/delete/{id}', 'DisposisiController@deleteDisposisiInstruksi')->name('deleteDisposisiInstruksi');
    });
    Route::group(['prefix' => 'master-data'], function () {
        Route::group(['prefix' => 'tipe-pengawasan-pemanfaatan'], function () {
        });

        Route::resource('tipebangunanatas', 'MasterData\TipeBangunanAtasController');
        Route::group(['prefix' => 'jembatan'], function () {
            Route::get('/', 'MasterData\JembatanController@index')->name('getMasterJembatan');
            Route::get('edit/{id}', 'MasterData\JembatanController@edit')->name('editJembatan');
            Route::get('editPhoto/{id}', 'MasterData\JembatanController@editPhoto')->name('editPhotoJembatan');
            Route::get('viewPhoto/{id}', 'MasterData\JembatanController@viewPhoto')->name('viewPhotoJembatan');
            Route::get('add', 'MasterData\JembatanController@add')->name('addJembatan');
            Route::post('create', 'MasterData\JembatanController@store')->name('createJembatan');
            Route::post('update', 'MasterData\JembatanController@update')->name('updateJembatan');
            Route::post('updatePhoto', 'MasterData\JembatanController@updatePhoto')->name('updatePhotoJembatan');
            // Route::get('deletePhoto/{id}', 'MasterData\JembatanController@deletePhoto')->name('deletePhotoJembatan');
            Route::get('delPhoto/{id}', 'MasterData\JembatanController@delPhoto')->name('delPhotoJembatan');
            Route::get('delete/{id}', 'MasterData\JembatanController@delete')->name('deleteJembatan');
            Route::get('getTipeBangunan', 'MasterData\JembatanController@getTipeBangunan')->name('getTipeBangunan');
            Route::get('json', 'MasterData\JembatanController@json')->name('getJsonJembatan');
        });

        Route::group(['prefix' => 'ruas-jalan'], function () {
            Route::get('/', 'MasterData\RuasJalanController@index')->name('getMasterRuasJalan');
            Route::get('edit/{id}', 'MasterData\RuasJalanController@edit')->name('editMasterRuasJalan');
            Route::post('create', 'MasterData\RuasJalanController@create')->name('createMasterRuasJalan');
            Route::post('update', 'MasterData\RuasJalanController@update')->name('updateMasterRuasJalan');
            Route::get('delete/{id}', 'MasterData\RuasJalanController@delete')->name('deleteRuasJalan');
            Route::get('getSUP', 'MasterData\RuasJalanController@getSUP')->name('getSUPRuasJalan');
            Route::get('getCITIES', 'MasterData\RuasJalanController@getCITIES')->name('getSUPRuasJalan');

            Route::get('json', 'MasterData\RuasJalanController@json')->name('getJsonRuasJalan');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('permission', 'MasterData\UserController@getPermission')->name('getAkses')->middleware('role:User,View');
            Route::post('add-permission/store', 'MasterData\UserController@storePermission')->name('createPermis');
            Route::get('destroy-permission/{id}', 'MasterData\UserController@destroyPermission')->name('deletePermission');
            Route::get('edit-permission/{id}', 'MasterData\UserController@editPermission')->name('editPermission');
            Route::post('update-permission/update', 'MasterData\UserController@updatePermission')->name('updatePermis');

            Route::post('add-menu/store', 'MasterData\UserController@storeMenu')->name('createMenu');
            Route::get('edit-menu/{id}', 'MasterData\UserController@editMenu')->name('editMenu');
            Route::post('update-menu/update', 'MasterData\UserController@updateMenu')->name('updateMenu');
            Route::get('destroy-menu/{id}', 'MasterData\UserController@destroyMenu')->name('deleteMenu');

            Route::get('role-akses', 'MasterData\UserController@getDaftarRoleAkses')->name('getRoleAkses');
            Route::get('role-akses/create', 'MasterData\UserController@createRoleAccess')->name('createRoleAccess');
            Route::post('role-akses/store', 'MasterData\UserController@storeRoleAccess')->name('storeRoleAccess');
            Route::get('role-akses/edit/{id}', 'MasterData\UserController@editRoleAccess')->name('editRoleAccess');
            Route::post('role-akses/update/{id}', 'MasterData\UserController@updateRoleAccess')->name('updateRoleAccess');

            // Route::post('role-akses/create', 'MasterData\UserController@createRoleAkses')->name('createRoleAkses');

            Route::get('role-akses/detail/{id}', 'MasterData\UserController@detailRoleAkses')->name('detailRoleAkses');
            Route::get('role-akses/delete/{id}', 'MasterData\UserController@deleteRoleAkses')->name('deleteRoleAkses');
            Route::get('role-akses/getData/{id}', 'MasterData\UserController@getDataRoleAkses')->name('getDataRoleAkses');
            Route::post('role-akses/updateData', 'MasterData\UserController@updateDataRoleAkses')->name('updateDataRoleAkses');

            Route::get('user-role', 'MasterData\UserController@getDataUserRole')->name('getDataUserRole');
            Route::post('user-role/create', 'MasterData\UserController@createUserRole')->name('createUserRole');
            Route::get('user-role/detail/{id}', 'MasterData\UserController@detailUserRole')->name('detailUserRole');
            Route::get('user-role/getData/{id}', 'MasterData\UserController@getUserRoleData')->name('getUserRoleData');
            Route::get('user-role/getDataParent', 'MasterData\UserController@getAllforParent')->name('getAllforParent');
            Route::post('user-role/updateData', 'MasterData\UserController@updateUserRole')->name('updateUserRole');
            Route::get('user-role/delete/{id}', 'MasterData\UserController@deleteUserRole')->name('deleteUserRole');
            // Route::get('edit/{id}', 'LandingController@editUPTD')->name('editLandingUPTD');
            // Route::post('create', 'LandingController@createUPTD')->name('createLandingUPTD');
            // Route::post('update', 'LandingController@updateUPTD')->name('updateLandingUPTD');
            // Route::get('delete/{id}', 'LandingController@deleteUPTD')->name('deleteLandingUPTD');
            Route::get('/manajemen', 'MasterData\UserController@getUser')->name('getMasterUser');
            Route::get('/manajemen/trash', 'MasterData\UserController@getUserTrash')->name('getMasterUserTrash');

            Route::get('/manajemen/detail/{id}', 'DetailUserController@showall')->name('detailMasterUser');
            Route::get('/manajemen/edit/{id}', 'MasterData\UserController@edit')->name('editUser');
            Route::post('/manajemen/create', 'MasterData\UserController@store')->name('createUser');
            Route::post('/manajemen/update', 'MasterData\UserController@update')->name('updateUser');
            Route::get('/manajemen/delete/{id}', 'MasterData\UserController@delete')->name('deleteUser');
            Route::get('/manajemen/restore/{id}', 'MasterData\UserController@restore')->name('restoreUser');
            Route::get('/manajemen/deletepermanent/{id}', 'MasterData\UserController@deletepermanent')->name('deletepermanentUser');
        });

        Route::group(['prefix' => 'rawanbencana'], function () {
            Route::get('/', 'MasterData\RawanBencanaController@getData')->name('getDataBencana');
            Route::get('edit/{id}', 'MasterData\RawanBencanaController@editData')->name('editDataBencana');
            Route::post('update/{id}', 'MasterData\RawanBencanaController@updateData')->name('updateDataBencana');
            Route::post('create', 'MasterData\RawanBencanaController@createData')->name('createDataBencana');
            Route::get('delete/{id}', 'MasterData\RawanBencanaController@deleteData')->name('deleteDataBencana');
            Route::get('json', 'MasterData\RawanBencanaController@json')->name('getJsonDataBencana');
            Route::get('getDataSUP/{id}', 'MasterData\RawanBencanaController@getDataSUP')->name('getDataSUP');
            Route::get('getURL/{id}', 'MasterData\RawanBencanaController@getURL');
        });

        Route::group(['prefix' => 'laporan_bencana'], function () {
            Route::get('/', 'MasterData\LaporanBencanaController@getData')->name('getDataLaporanBencana');
            Route::get('edit/{id}', 'MasterData\LaporanBencanaController@editData')->name('editDataLaporanBencana');
            Route::post('update/{id}', 'MasterData\LaporanBencanaController@updateData')->name('updateDataLaporanBencana');
            Route::post('create', 'MasterData\LaporanBencanaController@createData')->name('createDataLaporanBencana');
            Route::get('delete/{id}', 'MasterData\LaporanBencanaController@deleteData')->name('deleteDataLaporanBencana');
            Route::get('json', 'MasterData\LaporanBencanaController@json')->name('getJsonDataLaporanBencana');
            Route::get('getDataSUP/{id}', 'MasterData\LaporanBencanaController@getDataSUP')->name('getDataSUP');
            Route::get('getURL/{id}', 'MasterData\LaporanBencanaController@getURL');
        });

        Route::group(['prefix' => 'CCTV'], function () {
            Route::get('/', 'MasterData\CCTVController@index')->name('getDataCCTV');
            Route::get('detail/{id}', 'MasterData\CCTVController@detail')->name('detailDataCCTV');
            Route::post('create', 'MasterData\CCTVController@create')->name('createCCTV');
            Route::get('edit/{id}', 'MasterData\CCTVController@edit')->name('editCCTV');
            Route::post('update', 'MasterData\CCTVController@update')->name('updateDataCCTV');
            Route::get('delete/{id}', 'MasterData\CCTVController@delete')->name('deleteDataCCTV');
            Route::get('getDataSUP/{id}', 'MasterData\CCTVController@getDataSUP')->name('getDataCCTVSUP');
        });
        Route::group(['prefix' => 'sup'], function () {
            Route::get('/', 'MasterData\SUPController@index')->name('goSUP');
            Route::get('create', 'MasterData\SUPController@create')->name('createSUP');
            Route::get('edit/{id}', 'MasterData\SUPController@edit')->name('editSUP');
            Route::post('store', 'MasterData\SUPController@store')->name('storeSUP');
            Route::put('update/{id}', 'MasterData\SUPController@update')->name('updateSUP');
            Route::get('delete/{id}', 'MasterData\SUPController@destroy')->name('deleteSUP');
        });
        Route::group(['prefix' => 'icon'], function () {
            Route::get('/', 'MasterData\IconController@index');
            Route::post('create', 'MasterData\IconController@create')->name('createIcon');
            Route::get('detail/{id}', 'MasterData\IconController@detail')->name('detailIcon');
            Route::get('edit/{id}', 'MasterData\IconController@edit')->name('detailIcon');
            Route::post('update', 'MasterData\IconController@update')->name('updateIcon');
            Route::get('delete/{id}', 'MasterData\IconController@delete')->name('deleteIcon');
        });
        Route::group(['prefix' => 'uptd'], function () {
            Route::get('/', 'LandingController@getUPTD')->name('getMasterUPTD');
            Route::get('edit/{id}', 'LandingController@editUPTD')->name('editMasterUPTD');
            Route::post('create', 'LandingController@createUPTD')->name('createLandingUPTD');
            Route::post('update', 'LandingController@updateUPTD')->name('updateLandingUPTD');
            Route::get('delete/{id}', 'LandingController@deleteUPTD')->name('deleteLandingUPTD');
        });
        Route::get('/jenis_laporan/delete/{id}', 'MasterData\JenisLaporanController@destroy');
        Route::resource('/jenis_laporan', 'MasterData\JenisLaporanController');

        Route::get('/item_bahan_material/delete/{id}', 'MasterData\ItemBahanMaterialController@destroy');
        Route::resource('/item_bahan_material', 'MasterData\ItemBahanMaterialController');

        Route::get('/item_peralatan/delete/{id}', 'MasterData\ItemPeralatanController@destroy');
        Route::resource('/item_peralatan', 'MasterData\ItemPeralatanController');

        Route::get('/item_satuan/delete/{id}', 'MasterData\ItemSatuanController@destroy');
        Route::resource('/item_satuan', 'MasterData\ItemSatuanController');
        Route::get('/nama_kegiatan_pekerjaan/delete/{id}', 'MasterData\NamaKegiatanPekerjaanController@destroy');
        Route::resource('/nama_kegiatan_pekerjaan', 'MasterData\NamaKegiatanPekerjaanController');

        # Bahan uji Lab Kontruksi
        Route::prefix('labkon')->group(function () {
            Route::prefix('bahan_uji_labkon')->group(function () {
                Route::get('delete/{id}', 'MasterData\BahanUjiLabKonController@destroy');
                Route::prefix('detail')->group(function () {
                    Route::get('edit/{id}', 'MasterData\BahanUjiLabKonController@editDetail')->name('detail_bahan_uji_labkon.edit');
                    Route::get('create', 'MasterData\BahanUjiLabKonController@createDetail')->name('detail_bahan_uji_labkon.create');
                    Route::post('store', 'MasterData\BahanUjiLabKonController@storeDetail')->name('detail_bahan_uji_labkon.store');
                    Route::put('edit/{id}', 'MasterData\BahanUjiLabKonController@updateDetail')->name('detail_bahan_uji_labkon.update');
                    Route::get('delete/{id}', 'MasterData\BahanUjiLabKonController@destroyDetail');
                });
            });
            Route::resource('bahan_uji_labkon', 'MasterData\BahanUjiLabKonController');
            Route::get('metode_pengujian_labkon/delete/{id}', 'MasterData\MetodePengujianLabKonController@destroy');
            Route::resource('metode_pengujian_labkon', 'MasterData\MetodePengujianLabKonController');
        });
    });

    Route::prefix('bidtek')->group(function () {
        Route::prefix('bankeu')->group(function () {
            Route::get('delete/{id}', 'BidTek\BantuanKeuanganController@destroy');
            Route::get('get_ruas_jalan_by_geo_id/{id}', 'BidTek\BantuanKeuanganController@getRuasJalanByGeoId')->name('getRuasJalanByGeoId');
            Route::prefix('progres')->group(function () {
                Route::get('/', 'BidTek\BantuanKeuanganController@progres_index')->name('bankeu.progres');
                Route::get('/verifikasi/{id}/{target}', 'BidTek\BantuanKeuanganController@progres_verifikasi')->name('bankeu.verifikasi');
                Route::post('/verifikasi/{id}/{target}/edit', 'BidTek\BantuanKeuanganController@progres_verifikasi_update')->name('bankeu.verifikasi.update');
            });
        });
        Route::resource('bankeu', 'BidTek\BantuanKeuanganController');
    });

    Route::group(['prefix' => 'input-data'], function () {
        Route::resource('/mandor', 'InputData\MandorController');
        Route::group(['prefix' => 'pekerjaan'], function () {

            Route::get('/', 'InputData\PekerjaanController@getData')->name('getDataPekerjaan');

            Route::get('edit/{id}', 'InputData\PekerjaanController@editData')->name('editDataPekerjaan');
            Route::get('status/{id}', 'InputData\PekerjaanController@statusData')->name('detailStatusPekerjaan');

            Route::get('report', 'InputData\PekerjaanController@reportrekap');

            Route::get('material/{id}', 'InputData\PekerjaanController@materialData')->name('materialDataPekerjaan');
            Route::post('creatematerial/{id}', 'InputData\PekerjaanController@createDataMaterial')->name('createDataMaterialPekerjaan');
            Route::post('updatematerial/{id}', 'InputData\PekerjaanController@updateDataMaterial')->name('updateDataMaterialPekerjaan');
            Route::post('update/{id}', 'InputData\PekerjaanController@updateData')->name('updateDataPekerjaan');
            Route::post('create', 'InputData\PekerjaanController@createData')->name('createDataPekerjaan');
            Route::get('delete/{id}', 'InputData\PekerjaanController@deleteData')->name('deleteDataPekerjaan');
            Route::get('restore/{id}', 'InputData\PekerjaanController@restoreData')->name('restoreDataPekerjaan');

            Route::get('/trash', 'InputData\PekerjaanController@getPekerjaanTrash')->name('getPekerjaanTrash');

            Route::get('submit/{id}', 'InputData\PekerjaanController@submitData')->name('submitDataPekerjaan');
            Route::get('jugment/{id}', 'InputData\PekerjaanController@show')->name('jugmentDataPekerjaan');
            Route::post('jugment/{id}', 'InputData\PekerjaanController@jugmentLaporan')->name('jugmentLaporanMandor');

            Route::get('laporan', 'InputData\PekerjaanController@laporanPekerjaan')->name('LaporanPekerjaan');
            Route::post('laporan/entry', 'InputData\PekerjaanController@laporanEntry')->name('LaporanRekapEntry');

            Route::post('laporan', 'InputData\PekerjaanController@generateLaporanPekerjaan')->name('generateLapPekerjaan');

            Route::get('json', 'InputData\PekerjaanController@json')->name('getJsonDataBencana');
        });

        Route::group(['prefix' => 'progresskerja'], function () {
            Route::get('/', 'InputData\ProgressPekerjaanController@getDataProgress')->name('getDataProgress');
            Route::get('edit/{id}', 'InputData\ProgressPekerjaanController@editDataProgress')->name('editDataProgress');
            Route::post('update/{id}', 'InputData\ProgressPekerjaanController@updateDataProgress')->name('updateDataProgress');
            Route::post('create', 'InputData\ProgressPekerjaanController@createDataProgress')->name('createDataProgress');
            Route::get('delete/{id}', 'InputData\ProgressPekerjaanController@deleteDataProgress')->name('deleteDataProgress');
            Route::get('json', 'InputData\ProgressPekerjaanController@json')->name('json');
        });

        Route::group(['prefix' => 'keuangan'], function () {
            Route::get('/', 'InputData\KeuanganController@index')->name('getKeuangan');
            Route::get('edit/{id}', 'InputData\KeuanganController@edit')->name('editKeuangan');
            Route::post('update', 'InputData\KeuanganController@update')->name('updateKeuangan');
            Route::get('delete/{id}', 'MasterData\PekerjaanController@deleteData')->name('deleteDataBencana');
        });

        Route::group(['prefix' => 'kondisi-jalan'], function () {
            // Route::get('/', 'InputData\KondisiJalanController@index')->name('getIDKondisiJalan');
            // Route::get('edit/{id}', 'InputData\KondisiJalanController@edit')->name('editIDKondisiJalan');
            // Route::get('add', 'InputData\KondisiJalanController@add')->name('addIDKondisiJalan');
            // Route::post('create', 'InputData\KondisiJalanController@create')->name('createIDKondisiJalan');
            // Route::post('update', 'InputData\KondisiJalanController@update')->name('updateIDKondisiJalan');
            // Route::get('delete/{id}', 'InputData\KondisiJalanController@delete')->name('deleteIDKondisiJalan');
            Route::get('getRuasJalan', 'InputData\KondisiJalanController@getRuasJalan')->name('getRuasJalanKJ');
            Route::get('getRuasJalanBySup', 'InputData\KondisiJalanController@getRuasJalanBySup')->name('getRuasJalanBySupKJ');

            Route::get('json', 'InputData\KondisiJalanController@getRJ')->name('getRJ');
        });
        Route::get('kondisi_jalan/delete/{id}', 'InputData\KondisiKemantapanJalanController@destroy');
        Route::get('kondisi_jalan/get_ruas_jalan/{id}', 'InputData\KondisiKemantapanJalanController@getDataRuasJalan')->name('getRuasJalan');
        Route::resource('kondisi_jalan', 'InputData\KondisiKemantapanJalanController');

        Route::group(['prefix' => 'data-paket'], function () {
            Route::get('/', 'InputData\DataPaketController@index')->name('getIDDataPaket');
            Route::get('edit/{id}', 'InputData\DataPaketController@edit')->name('editIDDataPaket');
            Route::post('create', 'InputData\DataPaketController@create')->name('createIDDataPaket');
            Route::get('add', 'InputData\DataPaketController@add')->name('addIDDataPaket');
            Route::post('update', 'InputData\DataPaketController@update')->name('updateIDDataPaket');
            Route::get('delete/{id}', 'InputData\DataPaketController@delete')->name('deleteIDDataPaket');
            Route::get('json', 'InputData\DataPaketController@json')->name('json');
        });

        Route::group(['prefix' => 'rekap'], function () {
            Route::get('/', 'InputData\RekapController@index')->name('getDataRekap');
            Route::get('edit/{id}', 'InputData\RekapController@editData')->name('editDataRekap');
            Route::post('create', 'InputData\RekapController@createData')->name('createDataRekap');
            Route::post('update', 'InputData\RekapController@updateData')->name('updateDataRekap');
            Route::get('delete/{id}', 'InputData\RekapController@deleteData')->name('deleteDataRekap');
            Route::get('json', 'InputData\RekapController@json')->name('json');
        });

        Route::group(['prefix' => 'survei_kondisi_jalan'], function () {
            Route::get('delete/{id}', 'InputData\SurveiKondisiJalanController@destroy');
            Route::post('import', 'InputData\SurveiKondisiJalanController@import')->name('importSurveiRuasJalan');
        });
        Route::resource('survei_kondisi_jalan', 'InputData\SurveiKondisiJalanController');

        Route::prefix('dpa')->group(function () {
            Route::get('delete/{id}', 'InputData\DPAController@destroy');
            Route::get('report/{id}', 'InputData\DPAController@report');
        });
        Route::resource('dpa', 'InputData\DPAController');

        Route::view('master_ruas_jalan', 'admin.input_data.master_ruas_jalan.index');

        Route::prefix('rumija')->group(function () {
            Route::get('rumija/delete/{id}', 'InputData\RumijaController@destroy');
            Route::resource('rumija', "InputData\RumijaController");
            Route::get('/permohonan_rumija/surat_permohonan/{id}', 'InputData\PermohonanRumijaController@surat_permohonan_rumija')->name('surat_permohonan_rumija');
            Route::get('/permohonan_rumija/delete/{id}', 'InputData\PermohonanRumijaController@destroy');
            Route::resource('/permohonan_rumija', 'InputData\PermohonanRumijaController');
            //Pelaporan Rumija
            Route::group(['prefix' => 'pelaporan'], function () {
                Route::get('', 'InputData\RumijaReportController@index')->name('admin.rumija.report.index');
                Route::post('/create','InputData\RumijaReportController@store')->name('admin.rumija.report.store');
                Route::get('/{id}/edit','InputData\RumijaReportController@edit')->name('admin.rumija.report.edit');
                Route::get('/{id}/show','InputData\RumijaReportController@show')->name('admin.rumija.report.show');
                Route::post('/{id}/update','InputData\RumijaReportController@update')->name('admin.rumija.report.update');
                Route::get('/{id}/delete','InputData\RumijaReportController@delete')->name('admin.rumija.report.delete');
            });
        });
    });
    Route::prefix('inventarisasi')->group(function () {
        Route::get('/list', 'InputData\RumijaInventarisasiController@index')->name('rumija.inventarisasi.index');
        Route::get('/create', 'InputData\RumijaInventarisasiController@create')->name('rumija.inventarisasi.create');
        Route::post('/store', 'InputData\RumijaInventarisasiController@store')->name('rumija.inventarisasi.store');
        Route::get('/edit/{id}', 'InputData\RumijaInventarisasiController@edit')->name('rumija.inventarisasi.edit');
        Route::put('/update/{id}', 'InputData\RumijaInventarisasiController@update')->name('rumija.inventarisasi.update');
        Route::get('/delete/{id}', 'InputData\RumijaInventarisasiController@destroy')->name('rumija.inventarisasi.delete');

        Route::get('/kategori/', 'InputData\RumijaInventarisasiController@get_category')->name('rumija.inventarisasi.kategori.index');
        Route::get('/kategori/create', 'InputData\RumijaInventarisasiController@create_category')->name('rumija.inventarisasi.kategori.create');
        Route::post('/kategori/store', 'InputData\RumijaInventarisasiController@store_category')->name('rumija.inventarisasi.kategori.store');
        Route::get('/kategori/edit/{id}', 'InputData\RumijaInventarisasiController@edit_category')->name('rumija.inventarisasi.kategori.edit');
        Route::put('/kategori/update/{id}', 'InputData\RumijaInventarisasiController@update_category')->name('rumija.inventarisasi.kategori.update');
        Route::get('/kategori/delete/{id}', 'InputData\RumijaInventarisasiController@destroy_category')->name('rumija.inventarisasi.kategori.delete');

        Route::get('/report', 'InputData\ReportInventarisRumijaController@index')->name('rumija.inventarisasi.report.index');
        Route::get('/download', 'InputData\ReportInventarisRumijaController@download')->name('rumija.inventarisasi.report.download');
    });
    Route::group(['prefix' => 'lapor'], function () {
        Route::get('/', 'LaporController@index')->name('getLapor');
        // Route::get('/add', 'LaporController@create')->name('addLapor');
        // Route::get('edit/{id}', 'LaporController@edit')->name('editLapor');
        // Route::post('/create', 'LaporController@store')->name('createLapor');
        Route::get('update/{no_aduan}/{status}', 'LaporController@update_jqr')->name('updateLaporJQR');
        // Route::get('delete/{id}', 'LaporController@delete')->name('deleteLapor');
        Route::get('json', 'LaporController@json')->name('getJsonLapor');

        Route::get('/add', 'LandingController@addLaporanMasyarakat')->name('addLapor');
        Route::post('create', 'LandingController@createLaporanMasyarakat')->name('createLapor');
        Route::get('edit/{id}', 'LandingController@editLaporanMasyarakat')->name('editLapor');
        Route::post('update', 'LandingController@updateLaporanMasyarakat')->name('updateLapor');
        Route::get('delete/{id}', 'LandingController@deleteLaporanMasyarakat')->name('deleteLapor');
        Route::get('pemetaan', 'LaporController@pemetaanLaporanMasyarakat')->name('pemetaanLaporanMasyarakat');
        Route::get('laporan-kerusakan', 'MonitoringController@getLaporan');
    });
});
Route::get('map/target-realisasi', 'ProyekController@getTargetRealisasiAPI')->name('api.targetrealisasi');
Route::get('map/kendali-kontrak', 'ProyekController@getProyekKontrakAPI')->name('api.proyekkontrak');
Route::get('map/proyek-kontrak-progress', 'ProyekController@getProgressProyekKontrakAPI')->name('api.proyekkontrakprogress');

Route::get('map/laporan-masyarakat', 'MonitoringController@getLaporanAPI')->name('api.laporan');
Route::view('map/kemantapan-jalan', 'admin.map.map-kemantapan-jalan')->name('map.kemantapanjalan');
Route::view('map/pemetaan_laporan_masyarakat', 'admin.map.pemetaan_laporan_masyarakat')->name('map.pemetaanLaporanMasyarakat');

Route::post('getSupData', 'MonitoringController@getSupData')->name('getSupData.filter');

Route::view('debug/mail/disposisi', 'mail.notifikasiDisposisi');
Route::view('debug/mail/tindaklanjut', 'mail.notifikasiTindakLanjut');

Route::view('debug/push-notification', 'debug.push-notif');

Route::get('debug/pushnow', 'API\PushNotifController@debug');

Route::view('debug/map-dashboard', 'debug.map-dashboard');
Route::view('debug/map-filter', 'debug.map-filter');
Route::view('coba-map', 'debug.coba-map');
Route::view('coba-roaddroid', 'debug.map-roaddroid');
Route::view('map-progress-mingguan', 'debug.map-progress-mingguan');
Route::view('map-ruas-jalan', 'debug.map-ruas-jalan');



Route::get('debug', 'Backup\DebugController@debug');

Route::middleware(['auth'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('bankeu')->group(function () {
            Route::get('pre', 'MockupController@bankeu_create_pre');
        });
    });
});
