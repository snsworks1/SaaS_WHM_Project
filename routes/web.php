<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlansController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\UserServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceSettingsController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PatchnoteController;
use App\Http\Controllers\Admin\PatchnoteController as AdminPatchnoteController;
use App\Http\Controllers\Admin\NoticeController as AdminNoticeController;
use App\Http\Controllers\Admin\UploadController;
use App\Models\Notice;
use App\Http\Controllers\PlanUpgradeController;





Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/plans', [PlansController::class, 'index'])->name('plans.index');
    Route::post('/plans/select', [PlansController::class, 'select'])->name('plans.select');
    Route::post('/plans/check-username', [PlansController::class, 'checkUsername'])->name('plans.checkUsername');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('plans', \App\Http\Controllers\Admin\PlanController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store', 'destroy']);
    Route::resource('service', \App\Http\Controllers\Admin\ServiceController::class);
    
    Route::resource('whm-servers', \App\Http\Controllers\Admin\WhmServerController::class);

});

Route::post('/check-whm-username', [\App\Http\Controllers\Api\ProvisioningController::class, 'checkWhmUsername'])->name('check-whm-username');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/services', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::post('/services/{id}/extend', [ServiceController::class, 'extend'])->name('admin.services.extend');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::post('/services/{id}/update', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
    
});


Route::middleware(['auth'])->group(function () {
    Route::get('/services/{id}/change-plan', [PlanUpgradeController::class, 'showChangePlan'])->name('services.changePlan');
    Route::post('/services/{id}/confirm-upgrade', [PlanUpgradeController::class, 'confirmUpgrade'])->name('services.confirmUpgrade');
    Route::post('/services/{id}/process-upgrade', [PlanUpgradeController::class, 'processUpgrade'])->name('services.processUpgrade');
    Route::get('/services/{id}/upgrade-complete', [PlanUpgradeController::class, 'upgradeComplete'])->name('services.upgradeComplete');
});

Route::get('/services/{id}/upgrade/success', [PlanUpgradeController::class, 'confirmTossPayment'])->name('upgrade.payment.success');
Route::get('/services/{id}/upgrade/fail', function ($id) {
    return view('services.upgrade-fail', ['id' => $id]);
})->name('upgrade.payment.fail');




Route::get('/checkout/confirm', [\App\Http\Controllers\PaymentController::class, 'confirmGet']);

Route::get('/checkout/confirm', [PaymentController::class, 'confirmGet']);
Route::get('/checkout/fail', function () {
    return view('checkout.fail');
});

Route::middleware(['auth'])->group(function () { #대시보드->결제내역
    Route::get('/dashboard/payments', [\App\Http\Controllers\Dashboard\PaymentController::class, 'index'])->name('dashboard.payments');
});



# 대시보드 고객사 서버 설정버튼 라우트
Route::middleware(['auth'])->group(function () {
    Route::get('/services/{service}/settings', [ServiceSettingsController::class, 'settings'])
        ->name('services.settings');

    Route::post('/services/{service}/install-wordpress', [ServiceSettingsController::class, 'installWordPress'])
        ->name('services.installWordPress');

});

Route::get('/services/{id}/check-wp', [ServiceSettingsController::class, 'checkWordPress'])
    ->middleware('auth')
    ->name('services.checkWp');

Route::get('/services/{id}/cpanel-url', [UserServiceController::class, 'getCpanelUrl'])
    ->name('services.getCpanelUrl');

Route::middleware(['auth'])->group(function () {
    Route::get('/services/{id}/refund', [ServiceSettingsController::class, 'refundForm'])->name('services.refundForm');
    
});
Route::post('/services/{id}/process-refund', [ServiceSettingsController::class, 'processRefund'])
    ->name('services.processRefund')
    ->middleware('auth');

Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{id}', [NoticeController::class, 'show'])->name('notices.show');

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
   
    // 공지사항
    Route::resource('notices', AdminNoticeController::class);

    // Editor.js 이미지 업로드
    Route::post('/uploads/editorjs', [UploadController::class, 'editorjs'])->name('editorjs.upload');
});

Route::get('/api/notices/{id}', function ($id) {
    return Notice::findOrFail($id);
});