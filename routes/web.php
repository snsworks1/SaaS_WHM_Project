<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PlansController,
    PaymentController,
    PlanUpgradeController,
    ServiceSettingsController,
    UserServiceController,
    ServiceExtensionController,
    NoticeController,
    PatchnoteController,
    DashboardController as UserDashboardController,
    ThemeInstallController

};
use App\Models\Notice;

// 관리자 컨트롤러
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    PlanController,
    UserController,
    ServiceController,
    WhmServerController,
    UploadController,
    NoticeController as AdminNoticeController,
    PatchnoteController as AdminPatchnoteController,
    AdminLogController,
    AdminStatsController,
};

use App\Http\Controllers\Admin\ThemeController;

// 🚪 기본 라우트
Route::get('/', fn() => view('welcome'));

// ✅ 인증된 사용자 그룹
Route::middleware(['auth', 'verified'])->group(function () {

    // 📌 사용자 대시보드
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // 📦 플랜 관련
    Route::get('/plans', [PlansController::class, 'index'])->name('plans.index');
    Route::post('/plans/select', [PlansController::class, 'select'])->name('plans.select');
    Route::post('/plans/check-username', [PlansController::class, 'checkUsername'])->name('plans.checkUsername');

    // 💳 플랜 업그레이드
    Route::get('/services/{id}/change-plan', [PlanUpgradeController::class, 'showChangePlan'])->name('services.changePlan');
    Route::post('/services/{id}/confirm-upgrade', [PlanUpgradeController::class, 'confirmUpgrade'])->name('services.confirmUpgrade');
    Route::get('/services/{id}/upgrade-complete', [PlanUpgradeController::class, 'upgradeComplete'])->name('services.upgradeComplete');

    // ⏳ 서비스 연장
    Route::post('/services/{id}/extend/request', [ServiceExtensionController::class, 'request'])->name('services.extend.request');
    Route::get('/services/{id}/extend/confirm', [ServiceExtensionController::class, 'confirm'])->name('services.extend.confirm');
    Route::get('/services/{id}/extend/fail', fn($id) => view('services.extend-fail', ['id' => $id]))->name('services.extend.fail');
    Route::get('/services/{id}/extend/complete', [ServiceExtensionController::class, 'complete'])->name('services.extend.complete');
    
    // 🔁 환불
    Route::get('/services/{id}/refund', [ServiceSettingsController::class, 'refundForm'])->name('services.refundForm');
    Route::post('/services/{id}/process-refund', [ServiceSettingsController::class, 'processRefund'])->name('services.processRefund');
    Route::post('/services/{id}/refund', [ServiceRefundController::class, 'refund'])->name('services.refund');

    // ⚙ 서비스 설정
    Route::get('/services/{service}/settings', [ServiceSettingsController::class, 'settings'])->name('services.settings');
    Route::post('/services/{service}/install-wordpress', [ServiceSettingsController::class, 'installWordPress'])->name('services.installWordPress');
    Route::get('/services/{id}/check-wp', [ServiceSettingsController::class, 'checkWordPress'])->name('services.checkWp');
    Route::post('/services/{id}/update-password', [ServiceSettingsController::class, 'updatePassword'])->name('services.updatePassword');


    // 🧾 대시보드 결제내역
    Route::get('/dashboard/payments', [\App\Http\Controllers\Dashboard\PaymentController::class, 'index'])->name('dashboard.payments');
    Route::get('/dashboard/payments/{order_id}/receipt', [\App\Http\Controllers\Dashboard\PaymentController::class, 'showReceipt'])
    ->name('dashboard.payments.receipt');

    // 테마 페이지
 
        Route::post('/services/{service}/themes/{theme}/install', [ThemeInstallController::class, 'install'])
    ->name('user.themes.install')
    ->middleware('auth');


});

// ✅ Toss 결제 콜백용 (인증 불필요)
Route::get('/checkout/confirm', [PaymentController::class, 'confirmGet']);
Route::get('/checkout/fail', fn() => view('checkout.fail'));

// ✅ 업그레이드 결제 콜백
Route::get('/services/{id}/upgrade/success', [PlanUpgradeController::class, 'confirmTossPayment'])->name('upgrade.payment.success');
Route::get('/services/{id}/upgrade/fail', fn($id) => view('services.upgrade-fail', ['id' => $id]))->name('upgrade.payment.fail');

// ✅ 공지사항 (모든 사용자 접근 가능)
Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
Route::get('/notices/{id}', [NoticeController::class, 'show'])->name('notices.show');
Route::get('/api/notices/{id}', fn($id) => Notice::findOrFail($id));

// ✅ 사용자용 서비스 기능
Route::get('/services/{id}/cpanel-url', [UserServiceController::class, 'getCpanelUrl'])->name('services.getCpanelUrl');

// ✅ 관리자 전용 라우트
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {

    // 📊 대시보드
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 🧑‍💼 관리기능
    Route::resource('plans', PlanController::class);
    Route::resource('users', UserController::class)->except(['create', 'store', 'destroy']);
    Route::resource('service', ServiceController::class);
    Route::resource('whm-servers', WhmServerController::class);

    // 🛠 개별 서비스 연장/수정
    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services/{id}/extend', [ServiceController::class, 'extend'])->name('services.extend');
    Route::get('/services/{id}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::post('/services/{id}/update', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');

    // 📢 공지사항 관리
    Route::resource('notices', AdminNoticeController::class);

    // 📝 패치노트 관리 (필요시 주석 해제)
    // Route::resource('patchnotes', AdminPatchnoteController::class);

    // 🖼 이미지 업로드 (에디터용)
    Route::post('/uploads/editorjs', [UploadController::class, 'editorjs'])->name('editorjs.upload');

    // 🪵 에러 로그 모니터링
    Route::get('/error-logs', [AdminLogController::class, 'index'])->name('error-logs.index');
    Route::get('/error-logs/json', [AdminLogController::class, 'json'])->name('error-logs.json');
    Route::post('/error-logs/{id}/toggle', [AdminLogController::class, 'toggle'])->name('error-logs.toggle');
    Route::get('/error-logs/export', [AdminLogController::class, 'export'])->name('errorLogs.export');

    // 📈 통계 대시보드
    Route::get('/stats', [AdminStatsController::class, 'index'])->name('stats.index');

    // 테마관리 페이지
Route::resource('themes', ThemeController::class);
    Route::delete('/themes/{theme}/screenshot/{index}', [ThemeController::class, 'deleteScreenshot'])
        ->name('admin.themes.deleteScreenshot');

        
});

Route::post('/user/themes/{service}/{theme}/install', [ThemeInstallController::class, 'install']);
Route::get('/user/themes/{service}/installed', [ThemeInstallController::class, 'getInstalledThemes']);



// ✅ API/비동기 체크
Route::post('/check-whm-username', [\App\Http\Controllers\Api\ProvisioningController::class, 'checkWhmUsername'])->name('check-whm-username');
