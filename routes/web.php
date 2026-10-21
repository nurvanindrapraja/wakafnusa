<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSite\LandingController;
use App\Http\Controllers\PublicSite\CatalogController;
use App\Http\Controllers\PublicSite\LandWaqfController;
use App\Http\Controllers\Wakif\DashboardController as WakifDashboardController;
use App\Http\Controllers\Wakif\DonationController as DonationController;
use App\Http\Controllers\Wakif\CertificateController as CertificateController;
use App\Http\Controllers\Pelaksana\DashboardController as PelaksanaDashboardController;
use App\Http\Controllers\Pelaksana\ProgramController as PelaksanaProgramController;
use App\Http\Controllers\Pelaksana\ReportController as PelaksanaReportController;
use App\Http\Controllers\Penilai\DashboardController as PenilaiDashboardController;
use App\Http\Controllers\Penilai\ReviewController as PenilaiReviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\DisbursementController as AdminDisbursementController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TeamMemberController;
use Illuminate\Support\Facades\Route;

// ── Public Portal ────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/tentang', [LandingController::class, 'about'])->name('about');
Route::get('/tim', [LandingController::class, 'team'])->name('team');
Route::get('/program', [CatalogController::class, 'index'])->name('catalog');
Route::get('/program/{slug}', [CatalogController::class, 'show'])->name('program.show');
Route::get('/verifikasi/{number}', [CertificateController::class, 'verify'])
    ->name('certificate.verify')
    ->where('number', '.*');
Route::get('/verifikasi-aiw/{code}', [DonationController::class, 'verifyAiw'])->name('wakaf.aiw.verify');

// ── Land Waqf Catalog ──────────────────────────────────────────
Route::get('/wakaf-tanah', [LandWaqfController::class, 'index'])->name('land-waqf.catalog');
Route::get('/wakaf-tanah/statistik', [LandWaqfController::class, 'statistics'])->name('land-waqf.stats');
Route::get('/wakaf-tanah/breakdown', [LandWaqfController::class, 'getBreakdown'])->name('land-waqf.breakdown');
Route::get('/wakaf-tanah/usage-breakdown', [LandWaqfController::class, 'getUsageBreakdown'])->name('land-waqf.usage-breakdown');
Route::get('/wakaf-tanah/locations', [LandWaqfController::class, 'getSubLocations'])->name('land-waqf.locations');
Route::get('/wakaf-tanah/{id}', [LandWaqfController::class, 'show'])->name('land-waqf.show');
Route::get('/verifikasi-wakaf/{nia}', [LandWaqfController::class, 'verifyWaqf'])->name('land-waqf.verify');

// ── Public Asset Registration (Multi-Step Form) ──────────────────
Route::get('/pendaftaran-aset-wakaf', [App\Http\Controllers\PublicSite\AssetRegistrationController::class, 'showForm'])->name('land-waqf.register.form');
Route::post('/pendaftaran-aset-wakaf', [App\Http\Controllers\PublicSite\AssetRegistrationController::class, 'store'])->name('land-waqf.register.store');
Route::get('/pendaftaran-aset-wakaf/cek-status', [App\Http\Controllers\PublicSite\AssetRegistrationController::class, 'showStatusForm'])->name('land-waqf.check-status.form');
Route::get('/pendaftaran-aset-wakaf/download/{id}', [App\Http\Controllers\PublicSite\AssetRegistrationController::class, 'downloadPdf'])->name('land-waqf.register.download');

// ── LWP Admin Portal ──────────────────────────────────────────
Route::prefix('lwp')->group(function() {
    Route::get('/login', [App\Http\Controllers\Lwp\AuthController::class, 'showLogin'])->name('lwp.login');
    Route::post('/login', [App\Http\Controllers\Lwp\AuthController::class, 'login'])->name('lwp.login.post');
    Route::get('/register', [App\Http\Controllers\Lwp\AuthController::class, 'showRegister'])->name('lwp.register');
    Route::post('/register', [App\Http\Controllers\Lwp\AuthController::class, 'register'])->name('lwp.register.post');
    Route::get('/verify-email/{token}', [App\Http\Controllers\Lwp\AuthController::class, 'verifyEmail'])->name('lwp.verify-email');
    Route::post('/logout', [App\Http\Controllers\Lwp\AuthController::class, 'logout'])->name('lwp.logout');

    Route::middleware(['auth:lwp'])->group(function() {
        Route::get('/dashboard', [App\Http\Controllers\Lwp\DashboardController::class, 'index'])->name('lwp.dashboard');
        Route::get('/dashboard/kabupaten-table', [App\Http\Controllers\Lwp\DashboardController::class, 'dashboardKabupatenTable'])->name('lwp.dashboard.kabupaten-table');
        Route::get('/profil', [App\Http\Controllers\Lwp\DashboardController::class, 'profil'])->name('lwp.profil');
        Route::post('/profil', [App\Http\Controllers\Lwp\DashboardController::class, 'updateProfil'])->name('lwp.profil.update');
        Route::get('/peta-sebaran', [App\Http\Controllers\Lwp\DashboardController::class, 'petaSebaran'])->name('lwp.peta-sebaran');
        Route::get('/peta-sebaran/data', [App\Http\Controllers\Lwp\DashboardController::class, 'petaSebaranData'])->name('lwp.peta-sebaran.data');

        Route::middleware(['lwp.not.level1'])->group(function() {
            Route::get('/manajemen-wakaf', [App\Http\Controllers\Lwp\DashboardController::class, 'manajemen'])->name('lwp.manajemen');
            Route::get('/manajemen-wakaf/export', [App\Http\Controllers\Lwp\DashboardController::class, 'exportManajemenWakaf'])->name('lwp.manajemen.export');
            Route::get('/laporan', [App\Http\Controllers\Lwp\DashboardController::class, 'laporan'])->name('lwp.laporan');
            Route::get('/laporan/export', [App\Http\Controllers\Lwp\DashboardController::class, 'exportLaporan'])->name('lwp.laporan.export');

            // Land Waqf Actions
            Route::get('/wakaf/create', [App\Http\Controllers\Lwp\DashboardController::class, 'create'])->name('lwp.wakaf.create');
            Route::post('/wakaf', [App\Http\Controllers\Lwp\DashboardController::class, 'store'])->name('lwp.wakaf.store');
            Route::post('/wakaf/validate-legalitas', [App\Http\Controllers\Lwp\DashboardController::class, 'validateLegalitas'])->name('lwp.wakaf.validate-legalitas');
            Route::get('/wakaf/{id}', [App\Http\Controllers\Lwp\DashboardController::class, 'show'])->name('lwp.wakaf.show');
            Route::get('/wakaf/{id}/edit', [App\Http\Controllers\Lwp\DashboardController::class, 'edit'])->name('lwp.wakaf.edit');
            Route::post('/wakaf/{id}', [App\Http\Controllers\Lwp\DashboardController::class, 'update'])->name('lwp.wakaf.update');
            Route::post('/wakaf/{id}/verify', [App\Http\Controllers\Lwp\DashboardController::class, 'verify'])->name('lwp.wakaf.verify');
            Route::delete('/wakaf/{id}', [App\Http\Controllers\Lwp\DashboardController::class, 'destroy'])->name('lwp.wakaf.destroy');
            Route::get('/wakaf/{id}/images', [App\Http\Controllers\Lwp\DashboardController::class, 'getImages'])->name('lwp.wakaf.images');
            Route::get('/wakaf/{id}/pdf', [App\Http\Controllers\Lwp\DashboardController::class, 'generatePdf'])->name('lwp.wakaf.pdf');
            Route::delete('/wakaf/image/{id}', [App\Http\Controllers\Lwp\DashboardController::class, 'deleteImage'])->name('lwp.wakaf.image.destroy');

            // Public Registrations (Pengajuan Wakif) Actions
            Route::get('/pengajuan', [App\Http\Controllers\Lwp\PengajuanController::class, 'index'])->name('lwp.pengajuan.index');
            Route::get('/pengajuan/{id}', [App\Http\Controllers\Lwp\PengajuanController::class, 'show'])->name('lwp.pengajuan.show');
            Route::post('/pengajuan/{id}/verify-lapangan', [App\Http\Controllers\Lwp\PengajuanController::class, 'verifyLapangan'])->name('lwp.pengajuan.verify-lapangan');
            Route::post('/pengajuan/{id}/complete', [App\Http\Controllers\Lwp\PengajuanController::class, 'complete'])->name('lwp.pengajuan.complete');
            Route::post('/pengajuan/{id}/cancel', [App\Http\Controllers\Lwp\PengajuanController::class, 'cancel'])->name('lwp.pengajuan.cancel');
            Route::post('/pengajuan/{id}/export', [App\Http\Controllers\Lwp\PengajuanController::class, 'exportToSiwaknu'])->name('lwp.pengajuan.export');

            // User Management Routes
            Route::get('/users', [App\Http\Controllers\Lwp\UserController::class, 'index'])->name('lwp.users.index');
            Route::post('/users', [App\Http\Controllers\Lwp\UserController::class, 'store'])->name('lwp.users.store');
            Route::get('/users/{id}', [App\Http\Controllers\Lwp\UserController::class, 'show'])->name('lwp.users.show');
            Route::post('/users/{id}/update', [App\Http\Controllers\Lwp\UserController::class, 'update'])->name('lwp.users.update');
            Route::delete('/users/{id}', [App\Http\Controllers\Lwp\UserController::class, 'destroy'])->name('lwp.users.destroy');
            Route::post('/users/{id}/reset-password', [App\Http\Controllers\Lwp\UserController::class, 'resetPassword'])->name('lwp.users.reset-password');
            Route::get('/cabang-helper', [App\Http\Controllers\Lwp\UserController::class, 'getCabang'])->name('lwp.cabang-helper');

            // Master Penggunaan Routes (Hanya Admin PBNU [91] atau Admin APP [9])
            Route::prefix('master-penggunaan')->name('lwp.penggunaan.')->group(function() {
                Route::get('/', [App\Http\Controllers\Lwp\PenggunaanController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\Lwp\PenggunaanController::class, 'store'])->name('store');
                Route::get('/{kode_peng}', [App\Http\Controllers\Lwp\PenggunaanController::class, 'show'])->name('show');
                Route::post('/{kode_peng}/update', [App\Http\Controllers\Lwp\PenggunaanController::class, 'update'])->name('update');
                Route::delete('/{kode_peng}', [App\Http\Controllers\Lwp\PenggunaanController::class, 'destroy'])->name('destroy');
                Route::post('/{kode_peng}/restore', [App\Http\Controllers\Lwp\PenggunaanController::class, 'restore'])->name('restore');
            });

            // Master Lokasi Routes (Hanya Admin PBNU [91] atau Admin APP [9])
            Route::prefix('master-lokasi')->name('lwp.lokasi.')->group(function() {
                Route::get('/', [App\Http\Controllers\Lwp\LokasiController::class, 'index'])->name('index');
                Route::post('/', [App\Http\Controllers\Lwp\LokasiController::class, 'store'])->name('store');
                Route::get('/suggest-code', [App\Http\Controllers\Lwp\LokasiController::class, 'suggestCode'])->name('suggest-code');
                Route::get('/{kode_lokasi}', [App\Http\Controllers\Lwp\LokasiController::class, 'show'])->name('show');
                Route::post('/{kode_lokasi}/update', [App\Http\Controllers\Lwp\LokasiController::class, 'update'])->name('update');
                Route::delete('/{kode_lokasi}', [App\Http\Controllers\Lwp\LokasiController::class, 'destroy'])->name('destroy');
            });

            // Trash Routes
            Route::get('/data-sampah', [App\Http\Controllers\Lwp\DashboardController::class, 'trash'])->name('lwp.trash');
            Route::post('/data-sampah/wakaf/{id}/restore', [App\Http\Controllers\Lwp\DashboardController::class, 'restoreWakaf'])->name('lwp.trash.wakaf.restore');
            Route::delete('/data-sampah/wakaf/{id}/permanent', [App\Http\Controllers\Lwp\DashboardController::class, 'permanentDeleteWakaf'])->name('lwp.trash.wakaf.permanent');
            Route::post('/data-sampah/user/{id}/restore', [App\Http\Controllers\Lwp\DashboardController::class, 'restoreUser'])->name('lwp.trash.user.restore');
            Route::delete('/data-sampah/user/{id}/permanent', [App\Http\Controllers\Lwp\DashboardController::class, 'permanentDeleteUser'])->name('lwp.trash.user.permanent');
        });
    });

    Route::get('/lokasi-helper', [App\Http\Controllers\Lwp\DashboardController::class, 'getLokasi'])->name('lwp.get-lokasi');
});

// ── Dashboard (Role-Based Redirect) ─────────────────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'ADMIN' => redirect()->route('admin.dashboard'),
        'PELAKSANA' => redirect()->route('pelaksana.dashboard'),
        'PENILAI' => redirect()->route('penilai.dashboard'),
        default => redirect()->route('wakif.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Public Donation Flow ─────────────────────────────────────────
Route::prefix('wakaf')->name('wakaf.')->group(function () {
    Route::get('/{slug}', [DonationController::class, 'create'])->name('create');
    Route::match(['get', 'post'], '/{slug}/aiw', [DonationController::class, 'aiw'])->name('aiw');
    Route::post('/store', [DonationController::class, 'store'])->name('store');
    Route::get('/payment/{donation}', [DonationController::class, 'payment'])->name('payment');
    Route::post('/confirm/{donation}', [DonationController::class, 'confirmMock'])->name('confirm');
    Route::get('/success/{donation}', [DonationController::class, 'success'])->name('success');
    Route::get('/download-aiw/{donation}', [DonationController::class, 'aiwDownload'])->name('aiw.download');

    // Certificates
    Route::get('/certificate/{certificate}/download', [CertificateController::class, 'download'])->name('certificate.download');
});

// ── Wakif Dashboard ──────────────────────────────────────────────
Route::middleware(['auth', 'role:WAKIF'])->prefix('dashboard/wakif')->name('wakif.')->group(function () {
    Route::get('/', [WakifDashboardController::class, 'index'])->name('dashboard');
    
    // Nazhir Application
    Route::get('/nazhir-application', [\App\Http\Controllers\Wakif\NazhirApplicationController::class, 'index'])->name('nazhir-application.index');
    Route::post('/nazhir-application', [\App\Http\Controllers\Wakif\NazhirApplicationController::class, 'store'])->name('nazhir-application.store');
});

// ── Pelaksana Dashboard ──────────────────────────────────────────
Route::middleware(['auth', 'role:PELAKSANA'])->prefix('dashboard/pelaksana')->name('pelaksana.')->group(function () {
    Route::get('/', [PelaksanaDashboardController::class, 'index'])->name('dashboard');
    Route::resource('programs', PelaksanaProgramController::class);
    Route::get('reports', [PelaksanaReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{report}', [PelaksanaReportController::class, 'show'])->name('reports.show');
    Route::get('programs/{program}/reports/create', [PelaksanaReportController::class, 'create'])->name('reports.create');
    Route::post('programs/{program}/reports', [PelaksanaReportController::class, 'store'])->name('reports.store');
    Route::post('programs/{program}/pks-upload', [PelaksanaProgramController::class, 'uploadPks'])->name('programs.pks.upload');
    Route::get('templates', [App\Http\Controllers\Pelaksana\TemplateController::class, 'index'])->name('templates.index');
});

// ── Penilai Dashboard ────────────────────────────────────────────
Route::middleware(['auth', 'role:PENILAI'])->prefix('dashboard/penilai')->name('penilai.')->group(function () {
    Route::get('/', [PenilaiDashboardController::class, 'index'])->name('dashboard');
    Route::get('/queue', [PenilaiDashboardController::class, 'queue'])->name('queue');
    Route::get('/history', [PenilaiDashboardController::class, 'history'])->name('history');
    Route::get('reviews/{program}', [PenilaiReviewController::class, 'show'])->name('reviews.show');
    Route::post('reviews/{program}', [PenilaiReviewController::class, 'store'])->name('reviews.store');
    Route::get('reviews/{program}/history', [PenilaiReviewController::class, 'history'])->name('reviews.history');
});

// ── Admin Dashboard ──────────────────────────────────────────────
Route::middleware(['auth', 'role:ADMIN'])->prefix('dashboard/admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/programs', [AdminProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/{program}', [AdminProgramController::class, 'show'])->name('programs.show');
    Route::post('/programs/{program}/pks', [AdminProgramController::class, 'generatePks'])->name('programs.pks');
    Route::post('/programs/{program}/pks-verify', [AdminProgramController::class, 'verifyPks'])->name('programs.pks.verify');
    Route::post('/programs/{program}/publish', [AdminProgramController::class, 'publish'])->name('programs.publish');
    Route::post('/programs/{program}/unpublish', [AdminProgramController::class, 'unpublish'])->name('programs.unpublish');
    Route::post('/programs/{program}/extend', [AdminProgramController::class, 'extendPeriod'])->name('programs.extend');
    Route::post('/programs/{program}/offline-donation', [AdminProgramController::class, 'storeOfflineDonation'])->name('programs.offline-donation');
    
    // Penyaluran Dana
    Route::get('/disbursements', [AdminDisbursementController::class, 'index'])->name('disbursements.index');
    Route::post('/disbursements/{program}', [AdminDisbursementController::class, 'store'])->name('disbursements.store');
    
    // Dana Offline
    Route::get('/offline-donations', [App\Http\Controllers\Admin\OfflineDonationController::class, 'index'])->name('offline-donations.index');
    
    // Laporan Keuangan
    Route::get('/financial-reports', [App\Http\Controllers\Admin\FinancialReportController::class, 'index'])->name('financial-reports.index');
    
    // Laporan Realisasi
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/{report}/validate', [\App\Http\Controllers\Admin\ReportController::class, 'validateReport'])->name('reports.validate');
    Route::get('/infaq', [\App\Http\Controllers\Admin\InfaqController::class, 'index'])->name('infaq.index');
    Route::post('/infaq/withdraw', [\App\Http\Controllers\Admin\InfaqController::class, 'storeWithdrawal'])->name('infaq.withdraw');
    
    // Nazhir Applications
    Route::get('/nazhir-applications', [\App\Http\Controllers\Admin\NazhirApplicationController::class, 'index'])->name('nazhir-applications.index');
    Route::post('/nazhir-applications/{application}/approve', [\App\Http\Controllers\Admin\NazhirApplicationController::class, 'approve'])->name('nazhir-applications.approve');
    Route::post('/nazhir-applications/{application}/reject', [\App\Http\Controllers\Admin\NazhirApplicationController::class, 'reject'])->name('nazhir-applications.reject');

    Route::resource('users', AdminUserController::class);
    Route::resource('team-members', TeamMemberController::class)->except(['show']);
    Route::post('/users/{user}/verify-email', [AdminUserController::class, 'verifyEmail'])->name('users.verify-email');
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    // Templates
    Route::get('/templates', [App\Http\Controllers\Admin\TemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [App\Http\Controllers\Admin\TemplateController::class, 'store'])->name('templates.store');
    Route::delete('/templates/{template}', [App\Http\Controllers\Admin\TemplateController::class, 'destroy'])->name('templates.destroy');
});

// Pelaksana Template Route (Add inside Pelaksana group)
// I'll add it in the next chunk or just find the Pelaksana group.


// ── Profile ──────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
