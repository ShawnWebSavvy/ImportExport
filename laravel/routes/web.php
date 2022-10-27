<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MercantileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Downloads;

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
Route::get('/welcome', function () {
    return view('FileImport.file-import');
})->middleware(['auth'])->middleware('admin')->name('dashboard');

Route::get('/', function () {
    return view('FileImport.file-import');
})->middleware(['auth'])->middleware('admin')->name('dashboard');

Route::get('/dashboard', function () {
    //return view('dashboard');
    return view('FileImport.file-import');
})->middleware(['auth'])->middleware('admin')->name('dashboard');

require __DIR__.'/auth.php';

Route::get('file-import', [FileController::class, 'fileImportIndex'])->name('file-import')->middleware('auth')->middleware('admin');
Route::post('file-upload', [FileController::class, 'fileUpload'])->name('file-upload');
Route::get('file-export-index', [FileController::class, 'fileExportIndex'])->name('file-export-index');

Route::get('file-export-index', [FileController::class, 'fileExportIndex'])->name('file-export-index');
Route::post('file-export', [FileController::class, 'fileExport'])->name('file-export');

Route::get('file-export-namibia-index', [FileController::class, 'fileExportNamibiaIndex'])->name('file-export-namibia-index')->middleware('auth')->middleware('admin');
Route::post('file-export-namibia', [FileController::class, 'fileExportNamibia'])->name('file-export-namibia');
Route::get('file-delete-namibia', [FileController::class, 'FileDeleteNamibia'])->name('file-delete-namibia');

Route::get('file-export-botswana-index', [FileController::class, 'fileExportBotswanaIndex'])->name('file-export-botswana-index')->middleware('auth')->middleware('admin');
Route::post('file-export-botswana', [FileController::class, 'fileExportBotswana'])->name('file-export-botswana');
Route::post('file-export-botswana-totext', [FileController::class, 'fileExportBotswanaToText'])->name('file-export-botswana-totext');

Route::get('file-export-mercantile-index', [MercantileController::class, 'fileExportMercantileIndex'])->name('file-export-mercantile-index')->middleware('auth')->middleware('admin');
Route::post('file-export-mercantile-nedbank', [MercantileController::class, 'fileExportMercantileNedbank'])->name('file-export-mercantile-nedbank');
Route::post('file-export-mercantile-capitec', [MercantileController::class, 'fileExportMercantileCapitec'])->name('file-export-mercantile-capitec');
Route::get('trialData-mercantile-capitec', [MercantileController::class, 'trialDataMercantileCapitec'])->name('trialData-mercantile-capitec');

Route::get('downloadsPage', [Downloads::class, 'mercantileDownloads'])->name('downloadsPage')->middleware('auth')->middleware('admin');
Route::post('download', [Downloads::class, 'download'])->name('download');
Route::get('downloadArchive/{file}', [Downloads::class, 'downloadArchive'])->name('downloadArchive')->middleware('auth')->middleware('admin');

// not used, but get so many error if deleted //  vvv // 
Route::get('file-export-botswana-install-headers-index', [FileController::class, 'fileExportBotswanaInstallHeadersIndex'])->name('file-export-botswana-install-headers-index');
Route::post('file-export-botswana-install-headers', [FileController::class, 'fileExportBotswanaInstallHeaders'])->name('file-export-botswana-install-headers');

Route::get('file-export-botswana-user-headers-index', [FileController::class, 'fileExportBotswanaUserHeadersIndex'])->name('file-export-botswana-user-headers-index');
Route::post('file-export-botswana-user-headers', [FileController::class, 'fileExportBotswanaUserHeaders'])->name('file-export-botswana-user-headers');

Route::get('file-export-botswana-contras-index', [FileController::class, 'fileExportBotswanaContrasIndex'])->name('file-export-botswana-contras-index');
Route::post('file-export-botswana-contras', [FileController::class, 'fileExportBotswanaContras'])->name('file-export-botswana-contras');

Route::get('file-export-botswana-transactions-index', [FileController::class, 'fileExportBotswanaTransactionsIndex'])->name('file-export-botswana-transactions-index');
Route::post('file-export-botswana-transactions', [FileController::class, 'fileExportBotswanaTransactions'])->name('file-export-botswana-transactions');

Route::get('file-export-botswana-install-trailers-index', [FileController::class, 'fileExportBotswanaInstallTrailersIndex'])->name('file-export-botswana-install-trailers-index');
Route::post('file-export-botswana-install-trailers', [FileController::class, 'fileExportBotswanaInstallTrailers'])->name('file-export-botswana-install-trailers');

Route::get('file-export-botswana-user-trailers-index', [FileController::class, 'fileExportBotswanaUserTrailersIndex'])->name('file-export-botswana-user-trailers-index');
Route::post('file-export-botswana-user-trailers', [FileController::class, 'fileExportBotswanaUserTrailers'])->name('file-export-botswana-user-trailers');
// not used, but get so many error if deleted // ^^^ //

/*
Route::get('/file-import-export', function () {
    return view('FileImport.file-import');
});

Route::get('file-import-export', [UserController::class, 'fileImportExport']);
Route::post('file-import', [UserController::class, 'fileImport'])->name('file-import');
Route::get('file-export', [UserController::class, 'fileExport'])->name('file-export');
*/