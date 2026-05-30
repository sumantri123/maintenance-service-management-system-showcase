<?php
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\BrandingController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportSanitasiController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\RoleController;
use App\Http\Middleware\CSP2;
use App\Http\Middleware\SanitizeInput;

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

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::middleware([CSP2::class,'throttle:60,1'])->group(function () {
	Route::get('/', [LoginController::class, 'index']);
	Route::get('/login', [LoginController::class, 'index']);    
	Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
	Route::post('/login', [LoginController::class, 'login'])->name('login'); 
	
	Route::get('/service',[ServiceController::class, 'service'])->name('service');
	Route::get('/sanitasi',[ServiceController::class, 'sanitasi'])->name('sanitasi');
	Route::post('/save', [ServiceController::class, 'storeSanitasi'])->name('sanitasi.upload')->middleware(SanitizeInput::class);
	Route::post('/area', [ServiceController::class, 'area'])->name('sanitasi.area');
	Route::post('/saveService', [ServiceController::class, 'storeService'])->name('service.upload')->middleware(SanitizeInput::class);
	
});

Route::group( ['middleware' => 'auth' ], function()
{	
	Route::get('/resetPassword',[ResetPasswordController::class, 'reset_view'])->name('reset_password');
	Route::put('/resetPassword/{id}',[ResetPasswordController::class, 'updatePassAdmin'])->name('updatePassword');	
	
	Route::get('/home',[ReportController::class, 'index'])->name('sanitasiReport');	

	Route::get('/area',[AreaController::class, 'index'])->name('area');
	Route::get('/getDataArea',[AreaController::class, 'getData'])->name('area.data');
	Route::post('/nonAktifArea',[AreaController::class, 'NonAktif'])->name('area.nonaktif');
	Route::post('/aktifArea',[AreaController::class, 'Aktif'])->name('area.aktif');
	Route::get('/delete/area/{id}',[AreaController::class, 'destroy'])->name('area.hapus');
	Route::post('/storeArea',[AreaController::class, 'store'])->name('area.save');
	Route::post('/updateArea',[AreaController::class, 'update'])->name('area.ubah');	

	Route::get('/pegawai',[PegawaiController::class, 'index'])->name('pegawai');	
	Route::get('/getDataPegawai',[PegawaiController::class, 'getData'])->name('pegawai.data');
	Route::post('/nonAktifPegawai',[PegawaiController::class, 'NonAktif'])->name('pegawai.nonaktif');
	Route::post('/aktifPegawai',[PegawaiController::class, 'Aktif'])->name('pegawai.aktif');
	Route::get('/detailPegawai/{id}',[PegawaiController::class, 'detail'])->name('pegawai.detail');
	Route::get('/delete/pegawai/{id}',[PegawaiController::class, 'destroy'])->name('pegawai.hapus');
	Route::post('/storePegawai',[PegawaiController::class, 'store'])->name('pegawai.save');
	Route::post('/updatePegawai',[PegawaiController::class, 'update'])->name('pegawai.ubah');	
	Route::get('/getDataDetPegawai/{id}',[PegawaiController::class, 'getDataDet'])->name('pegawai.detdata');
	Route::get('/delete/detPegawai/{id}',[PegawaiController::class, 'destroyDet'])->name('pegawai.dethapus');
	Route::post('/storeDetPegawai',[PegawaiController::class, 'storeDet'])->name('pegawai.detsave');

	Route::get('/aktivitas',[AktivitasController::class, 'index'])->name('aktivitas');
	Route::get('/getDataAktivitas',[AktivitasController::class, 'getData'])->name('aktivitas.data');
	Route::post('/nonAktifAktivitas',[AktivitasController::class, 'NonAktif'])->name('aktivitas.nonaktif');
	Route::post('/aktifAktivitas',[AktivitasController::class, 'Aktif'])->name('aktivitas.aktif');
	Route::get('/delete/aktivitas/{id}',[AktivitasController::class, 'destroy'])->name('aktivitas.hapus');
	Route::post('/storeAktivitas',[AktivitasController::class, 'store'])->name('aktivitas.save');
	Route::post('/updateAktivitas',[AktivitasController::class, 'update'])->name('aktivitas.ubah');	

	Route::get('/jabatan',[RoleController::class, 'index'])->name('role');
	Route::get('/getDataJabatan',[RoleController::class, 'getData'])->name('role.data');
	Route::post('/nonAktifJabatan',[RoleController::class, 'NonAktif'])->name('role.nonaktif');
	Route::post('/aktifJabatan',[RoleController::class, 'Aktif'])->name('role.aktif');
	Route::get('/delete/jabatan/{id}',[RoleController::class, 'destroy'])->name('role.hapus');
	Route::post('/storeJabatan',[RoleController::class, 'store'])->name('role.save');
	Route::post('/updateJabatan',[RoleController::class, 'update'])->name('role.ubah');
	Route::get('/detail/{id}',[RoleController::class, 'detail'])->name('role.detail');
	Route::get('/getDataDetRole/{id}',[RoleController::class, 'getDataDet'])->name('roledet.detdata');
	Route::post('/nonAktifAkses',[RoleController::class, 'nonAktifAkses'])->name('roledet.nonaktif');
	Route::post('/aktifAkses',[RoleController::class, 'aktifAkses'])->name('roledet.aktif');
	Route::post('/permission',[RoleController::class, 'permission'])->name('roledet.permission');
	
	Route::get('/addMenuUser/{id}/{id2}',[RoleController::class, 'saveMenu']);
	Route::get('/deleteMenuUser/{id}',[RoleController::class, 'deleteMenu']);
	
	Route::get('/branding',[BrandingController::class, 'index'])->name('branding');
	Route::get('/getDataBranding',[BrandingController::class, 'getData'])->name('branding.data');
	Route::post('/nonAktifBranding',[BrandingController::class, 'NonAktif'])->name('branding.nonaktif');
	Route::post('/aktifBranding',[BrandingController::class, 'Aktif'])->name('branding.aktif');
	Route::get('/delete/branding/{id}',[BrandingController::class, 'destroy'])->name('branding.hapus');
	Route::post('/storeBranding',[BrandingController::class, 'store'])->name('branding.save');
	Route::post('/updateBranding',[BrandingController::class, 'update'])->name('branding.ubah');	

	Route::get('/sparepart',[SparepartController::class, 'index'])->name('sparepart');
	Route::get('/getDataSparepart',[SparepartController::class, 'getData'])->name('sparepart.data');
	Route::post('/nonAktifSparepart',[SparepartController::class, 'NonAktif'])->name('sparepart.nonaktif');
	Route::post('/aktifSparepart',[SparepartController::class, 'Aktif'])->name('sparepart.aktif');
	Route::get('/delete/sparepart/{id}',[SparepartController::class, 'destroy'])->name('sparepart.hapus');
	Route::post('/storeSparepart',[SparepartController::class, 'store'])->name('sparepart.save');
	Route::post('/updateSparepart',[SparepartController::class, 'update'])->name('sparepart.ubah');	
	
	Route::get('/report',[ReportController::class, 'index'])->name('reportService');
	Route::post('/getDataService',[ReportController::class, 'getData'])->name('report.data');
	Route::post('/getDataFile',[ReportController::class, 'getDataFile'])->name('report.file');
	Route::post('/excelService',[ReportController::class, 'export'])->name('report.export');
	Route::get('/service/{id}',[ReportController::class, 'view_service'])->name('report.view');
	Route::post('/updateservice',[ReportController::class, 'updateService'])->name('report.update')->middleware(SanitizeInput::class);
	Route::get('/servicepdf/{id}', [ReportController::class, 'downloadPdfService'])->name('report.pdf');
	Route::get('/delete/service/{id}',[ReportController::class, 'destroy'])->name('report.hapus');
	Route::get('/delete/serviceFile/{id}',[ReportController::class, 'destroyFile'])->name('report.hapus_file');
	Route::post('/tambahFotoBefore', [ReportController::class, 'tambahFotoBefore'])->name('report.uploadBefore')->middleware(SanitizeInput::class);
	Route::post('/tambahFotoAfter', [ReportController::class, 'tambahFotoAfter'])->name('report.uploadAfter')->middleware(SanitizeInput::class);
	
	Route::get('/reportSanitasi',[ReportSanitasiController::class, 'index'])->name('reportSanitasi');
	Route::post('/getDataSanitasi',[ReportSanitasiController::class, 'getData'])->name('reportsanitasi.data');	
	Route::post('/excelSanitasi',[ReportSanitasiController::class, 'export'])->name('reportsanitasi.export');	
	Route::get('/sanitasi/{id}',[ReportSanitasiController::class, 'view_sanitasi'])->name('reportsanitasi.view');
	Route::post('/updatesanitasi',[ReportSanitasiController::class, 'updateSanitasi'])->name('reportsanitasi.update')->middleware(SanitizeInput::class);
	Route::get('/sanitasipdf/{id}', [ReportSanitasiController::class, 'downloadPdfSanitasi'])->name('reportsanitasi.pdf');
	Route::get('/areaselected', [ReportSanitasiController::class, 'areaselected'])->name('sanitasi.areaselected');
	Route::get('/delete/sanitasi/{id}',[ReportSanitasiController::class, 'destroy'])->name('reportsanitasi.hapus');
});