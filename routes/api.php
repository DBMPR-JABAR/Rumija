<?php

use App\Http\Controllers\API\ReportInventarisRumijaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('laporan-masyarakat/store', 'API\LaporanMasyarakatController@store');
Route::get('/lap-masyarakat', 'API\LapMasyarakatController@index');


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'API\AuthController@login');
    Route::post('logout', 'API\AuthController@logout');
    Route::post('registerMail', 'API\AuthController@register');
    Route::post('reset-password', 'API\AuthController@resetPasswordMail');
    Route::post('new-password', 'API\AuthController@resetPassword');
    Route::post('refresh', 'API\AuthController@refresh');
    Route::post('user', 'API\AuthController@getUser');

    Route::post('change-password', 'API\AuthController@newPassword');
    Route::post('change-detail', 'API\AuthController@changeDetail');

    // Login OTP
    Route::post('loginOTP', 'API\AuthController@loginOTP');
    Route::post('verifyOTPLogin', 'API\AuthController@verifyOTPLogin');

    // Register
    Route::post('register', 'API\AuthController@registerOTP');
    Route::post('verifyOTP', 'API\AuthController@verifyOTP');
    Route::post('resendOTPMail', 'API\AuthController@resendOTPMail');
});

Route::prefix('jqr')->group(function () {
    Route::prefix('laporan_masyarakat')->group(function () {
        Route::get('/', 'API\LaporJQRController@index');
        Route::post('/', 'API\LaporJQRController@store');
        Route::put('/{id}/edit', 'API\LaporJQRController@update');
        Route::put('/{id}/edit_status', 'API\LaporJQRController@status_update');
        Route::get('/{id}/show', 'API\LaporJQRController@show');
        Route::delete('/{id}', 'API\LaporJQRController@destroy');
        Route::get('/cities', "API\LaporJQRController@get_cities");
        Route::get('/jenis_laporan', "API\LaporJQRController@get_jenis_laporan");
    });
});

Route::prefix('rumija')->group(function () {
    Route::prefix('report')->group(function () {
        Route::get('inventaris/{ruasJalanId}', [ReportInventarisRumijaController::class, 'getReportByRuasJalanId']);
    });
});

Route::group(['middleware' => ['jwt.auth']], function () {

    Route::get('has_access/{permission}', 'API\UtilsController@has_access');
    Route::get('uptd_list', 'API\UtilsController@uptd_list');

    Route::get('/pengumuman/masyarakat', 'API\AnnouncementController@getDataMasyarakat');
    Route::get('/pengumuman/{slug?}', 'API\AnnouncementController@show');
    Route::get('/pengumuman/internal', 'API\AnnouncementController@getDataInternal');

    Route::resource('laporan-masyarakat', 'API\LaporanMasyarakatController');
    Route::get('laporan-masyarakat/getNotifikasiByUserId/{userId}', 'API\LaporanMasyarakatController@getNotifikasiByUserId');

    Route::post('laporan-masyarakat/approve', 'API\LaporanMasyarakatController@approve');
    Route::post('laporan-masyarakat/progress', 'API\LaporanMasyarakatController@createProgress');
    Route::get('laporan-masyarakat/progress/{id}', 'API\LaporanMasyarakatController@getOnProgress');
    Route::get('laporan-masyarakat/status/{status}', 'API\LaporanMasyarakatController@getListLaporan');

    Route::post('laporan-bencana/store', 'API\LaporanMasyarakatController@storeBencana');
    Route::get('laporan-bencana/get/{userId}', 'API\LaporanMasyarakatController@getBencana');
    Route::get('laporan-bencana/delete/{id}', 'API\LaporanMasyarakatController@destroyBencana');
    Route::get('laporan-bencana/get-ruas-jalan', 'API\LaporanMasyarakatController@getRuasJalan');
    Route::get('laporan-bencana/get-icon', 'API\LaporanMasyarakatController@getIcon');


    Route::get('utils/petugas', 'API\LaporanMasyarakatController@getPetugas');
    Route::get('utils/lokasi', 'API\LaporanMasyarakatController@getLokasi');
    Route::get('utils/uptd', 'API\LaporanMasyarakatController@getUPTD');
    Route::get('utils/jenis-laporan', 'API\LaporanMasyarakatController@getJenisLaporan');
    Route::get('utils/notifikasi', 'API\LaporanMasyarakatController@getNotifikasi');

    Route::post('perbaikan-jalan', 'API\MapDashboardController@showPerbaikan');
    Route::get('kemantapan-jalan', 'API\RuasJalanController@getKemantapanJalan');
    Route::get('kemantapan-jalan/{id}', 'API\RuasJalanController@getDetailKemantapanJalan');
    Route::get('kemantapan-jalan-rekap', 'API\RuasJalanController@getRekapKemantapanJalan');

    Route::get('proyek-kontrak', 'API\ProyekController@index');
    Route::get('proyek-kontrak/count', 'API\ProyekController@count');
    Route::get('proyek-kontrak/status/{status}', 'API\ProyekController@getByStatus');

    Route::group(['prefix' => 'pekerjaan'], function () {
        Route::get('get-nama-kegiatan-pekerjaan', 'API\PekerjaanController@getNamaKegiatanPekerjaan');
        Route::get('get-sup', 'API\PekerjaanController@getSUP');
        Route::get('get-ruas-jalan', 'API\PekerjaanController@getRuasJalan');
        Route::get('get-jenis-pekerjaan', 'API\PekerjaanController@getJenisPekerjaan');
        Route::get('get-jenis-kegiatan', 'API\PekerjaanController@getJenisKegiatan');

        Route::group(['prefix' => 'material_pekerjaan'], function () {
            Route::get('bahan_material', 'API\MaterialPekerjaanController@bahanMaterial');
            Route::get('satuan_material', 'API\MaterialPekerjaanController@satuanMaterial');
            Route::get('get-alat-operasional', 'API\MaterialPekerjaanController@getAlatOperasional');
            Route::get('get-bahan-operasional', 'API\MaterialPekerjaanController@getBahanMaterialOperasional');
        });
        Route::resource('material_pekerjaan', 'API\MaterialPekerjaanController')->except('index');
    });
    Route::resource('pekerjaan', 'API\PekerjaanController');
    Route::prefix('progress-pekerjaan')->group(function () {
        Route::get('get-paket-dan-penyedia', 'API\ProgressPekerjaanController@getPaketDanPenyedia');
    });
    Route::resource('progress-pekerjaan', 'API\ProgressPekerjaanController');

    Route::prefix('labkon')->group(function () {
        Route::prefix('daftar_pemohon')->group(function () {
            Route::get('/', 'API\LabKonController@daftar_pemohon')->name('api_labkon_pemohon_index');
            Route::post('create', 'API\LabKonController@create_pemohon')->name('api_labkon_pemohon_create');
            Route::get('delete/{id}', 'API\LabKonController@delete_pemohon')->name('api_labkon_pemohon_delete');
            Route::put('edit/{id}', 'API\LabKonController@edit_pemohon')->name('api_labkon_pemohon_edit');
            Route::get('show/{id}', 'API\LabKonController@show_pemohon')->name('api_labkon_pemohon_show');
            Route::delete('delete/{id}', 'API\LabKonController@delete_pemohon')->name('api_labkon_pemohon_delete');
        });
        Route::prefix('permohonan')->group(function () {
            Route::get('/', 'API\LabKonController@daftar_permohonan');
            Route::post('create', 'API\LabKonController@create_permohonan');
            Route::get('show/{id}', 'API\LabKonController@show_permohonan');
            Route::put('edit/{id}', 'API\LabKonController@edit_permohonan');
            Route::delete('delete/{id}', 'API\LabKonController@delete_permohonan');
            Route::post('upload_dokumen_persyaratan_permohonan/{id}', 'API\LabKonController@upload_dokumen_persyaratan_permohonan');
            Route::get('cetak_formulir_data/{id}', 'API\LabKonController@cetak_formulir_data');
            Route::get('dokumen_persyaratan_permohonan/{id}', 'API\LabKonController@dokumen_persyaratan_permohonan');
            Route::put('update_status_permohonan/{id}', 'API\LabKonController@update_status_permohonan');
            Route::put('pengkajian_permohonan/{id}', 'API\LabKonController@pengkajian_permohonan');
            Route::get('riwayat_permohonan/{id}', 'API\LabKonController@riwayat_permohonan');
            Route::post('catatan_status_progress/{id}', 'API\LabKonController@catatan_status_progress');
            Route::get('catatan_status_progress_last/{id}', 'API\LabKonController@catatan_status_progress_last');
            Route::post('create_questioner/{id}', 'API\LabKonController@create_questioner');
            Route::get('formulir_pengaduan_data_exits/{id}', 'API\LabKonController@formulir_pengaduan_data_exits');
            Route::get('kuesioner/{id}', 'API\LabKonController@kuesioner');
            Route::post('upload_dokumen_hasil_pengujian/{id}', 'API\LabKonController@upload_dokumen_hasil_pengujian');
            Route::get('dokumen_hasil_pengujian/{id}', 'API\LabKonController@dokumen_hasil_pengujian');
        });
        Route::post('tambah_nama_pengujian', 'API\LabKonController@tambah_nama_pengujian');
        Route::get('nama_pengujian', 'API\LabKonController@nama_pengujian');
        Route::get('metode_pengujian', 'API\LabKonController@metode_pengujian');
    });
});

Route::post('pembangunan_talikuat', 'API\PembangunanTalikuatController@getPembangunanTalikuat');

Route::resource('ruas-jalan', 'API\RuasJalanController');
Route::resource('pembangunan', 'API\PembangunanController');
Route::resource('proyek-kontrak', 'API\ProyekController');
Route::resource('paket-pekerjaan', 'API\PaketController');
Route::resource('progress-mingguan', 'API\ProgressController');
Route::get('progress-mingguan/status/{status}', 'API\ProgressController@showStatus');
Route::get('progress-mingguan/status/{status}/count', 'API\ProgressController@showStatusCount');

Route::get('pembangunan/category/{category}', 'API\PembangunanController@showByType');
Route::get('kemandoran/category/{category}', 'API\KemandoranController@showByType');

Route::post('map/dashboard/sup', 'API\MapDashboardController@getSUP')->name('api.supdata');
Route::post('map/dashboard/filter', 'API\MapDashboardController@filter');
Route::post('map/dashboard/data', 'API\MapDashboardController@getData');
Route::post('map/dashboard/data-proyek', 'API\MapDashboardController@getDataProyek');
Route::post('map/dashboard/jembatan', 'API\MapDashboardController@getJembatan');
Route::post('map/kemantapan-jalan', 'MonitoringController@getKemantapanJalanAPI')->name('api.kemantapanjalan');
Route::get('map/pemeliharaan', 'API\MapDashboardController@getPemeliharaan')->name('api.map.pemeliharaan');
Route::get('map/pembangunan', 'API\MapDashboardController@getPembangunan')->name('api.map.pembangunan');
Route::get('map/rumija', 'API\MapDashboardController@getRumija')->name('api.map.rumija');
Route::get('map/bankeu', 'API\MapDashboardController@getBankeu')->name('api.map.bankeu');


Route::resource('vehicle-counting', 'API\VehicleCountingController');

Route::post('save-token', 'API\PushNotifController@saveToken')->name('save-token');
Route::post('send-notification-user', 'API\PushNotifController@sendNotificationUser')->name('send.notification');
Route::post('debug-notification', 'API\PushNotifController@debugNotification')->name('debug.notification');

Route::get('map/geojson/ruas_jalan_propinsi', 'API\GeoJsonController@getRuasJalanProvinsi');
Route::get('map/geojson/ruas_jalan_custom', 'API\GeoJsonController@getRuasJalanCustom');

Route::fallback(function () {
    return response()->json([
        'status' => 'false',
        'data' => [
            'message' => 'Page Not Found'
        ]
    ], 404);
});
