<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\KelolaJadwalController; 
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SesiSiswaController;
use App\Http\Controllers\KoreksiPresensiController;
use App\Http\Controllers\AdminUserController; 
use App\Http\Controllers\AdminDashboardController; 
use App\Http\Controllers\SiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Tujuan: Mengatur Route berdasarkan peran (Admin/Guru/Aslab, Kepsek, Siswa).
*/

// ---------------------------------------------------------------------
// BLOK 1: RUTE PUBLIK & AUTH DASAR 
// ---------------------------------------------------------------------

// Redirect Root ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Otentikasi & Registrasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ---------------------------------------------------------------------
// BLOK 2: RUTE YANG DILINDUNGI (AUTH GROUP)
// ---------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    
    // --- A. RUTE MANAJEMEN OPERASIONAL (ADMIN, GURU, ASLAB) ---
    Route::middleware('role:Admin,Guru,AsistenLab')->group(function () {
        
        // 1. Dashboard Admin
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // 2. Kelola Jadwal (FULL CRUD UNTUK SEMUA)
        Route::resource('admin/jadwal', KelolaJadwalController::class)
            ->names([
                'index' => 'admin.jadwal.index',
                'create' => 'admin.jadwal.create',
                'store' => 'admin.jadwal.store',
                'edit' => 'admin.jadwal.edit',
                'update' => 'admin.jadwal.update',
                'destroy' => 'admin.jadwal.destroy',
            ]);
        
        // 3. Pembagian Sesi / Mapping Kuota
        Route::get('/admin/sesi-siswa', [SesiSiswaController::class, 'index'])->name('admin.sesi.index');
        Route::post('/admin/sesi-siswa', [SesiSiswaController::class, 'store'])->name('admin.sesi.store');

        // 4. Koreksi Presensi (Validasi Akhir)
        Route::get('/admin/koreksi', [KoreksiPresensiController::class, 'index'])->name('admin.koreksi.index');
        Route::post('/admin/koreksi', [KoreksiPresensiController::class, 'store'])->name('admin.koreksi.store');
    });

    // --- B. KHUSUS ADMIN DAN ASISTEN LAB ---
    Route::middleware('role:Admin,AsistenLab')->group(function () {

        // Persetujuan Registrasi Siswa
        Route::get('/admin/registrations', [RegistrationController::class, 'index'])
            ->name('admin.registrations.index');
        Route::post('/admin/registrations/approve-all', [RegistrationController::class, 'approveAll'])
            ->name('admin.registrations.approveAll');
        Route::post('/admin/registrations/{id}/approve', [RegistrationController::class, 'approve'])
            ->name('admin.registrations.approve');
        Route::post('/admin/registrations/{id}/reject', [RegistrationController::class, 'reject'])
            ->name('admin.registrations.reject');

        // Manajemen Akun Staf (CRUD)
        Route::prefix('admin/users')->name('admin.users.')->group(function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('index'); 
            Route::get('/create', [AdminUserController::class, 'create'])->name('create'); 
            Route::post('/', [AdminUserController::class, 'store'])->name('store'); 
            Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit'); 
            Route::put('/{user}', [AdminUserController::class, 'update'])->name('update'); 
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
        }); 

         // Data Siswa CRUD
        Route::resource('admin/siswa', SiswaController::class)->names([
            'index' => 'admin.siswa.index',
            'store' => 'admin.siswa.store',
            'edit' => 'admin.siswa.edit',
            'update' => 'admin.siswa.update',
            'destroy' => 'admin.siswa.destroy',
        ]);

        // ✅ ROUTE TOGGLE STATUS (BARU!)
        Route::post('admin/siswa/{id}/toggle-status', [SiswaController::class, 'toggleStatus'])
            ->name('admin.siswa.toggleStatus');

    });

    // Tambahkan middleware check.status ke semua route yang memerlukan auth
    Route::middleware(['check.status'])->group(function () {
        // Semua route dashboard, presensi, dll masuk ke sini
    });
    


    // --- C. RUTE LAPORAN (ADMIN, GURU, ASLAB, KEPSEK) ---
    Route::middleware('role:Admin,Guru,AsistenLab,Kepsek')->group(function () {
        Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
        Route::post('/admin/laporan/export', [LaporanController::class, 'export'])->name('admin.laporan.export');
    });


    // --- D. RUTE KHUSUS SISWA ---
    Route::middleware('role:Siswa')->group(function () {
        Route::get('/siswa/dashboard', [PresensiController::class, 'showSiswaDashboard'])->name('siswa.dashboard');
        Route::get('/siswa/jadwal', [PresensiController::class, 'showPresensiForm'])->name('siswa.presensi.form');
        Route::get('/siswa/presensi', [PresensiController::class, 'index'])->name('siswa.presensi.index');
        Route::post('/siswa/presensi', [PresensiController::class, 'storePresensi'])->name('siswa.presensi.store');
        Route::get('/siswa/riwayat', [PresensiController::class, 'showRiwayat'])->name('siswa.riwayat.index');
    });

});