<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AdminController;

// ── Redirect home to login
Route::get('/', function () {
    return redirect()->route('login');
});

// ── Language switcher (FR / EN / ES)
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['fr', 'en', 'es'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// ── Authenticated routes
Route::middleware(['auth'])->group(function () {

    // ── Dashboard (data injected per role in route)
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $total      = \App\Models\Appointment::count();
            $pending    = \App\Models\Appointment::where('status', 'pending')->count();
            $confirmed  = \App\Models\Appointment::where('status', 'confirmed')->count();
            $patients   = \App\Models\User::where('role', 'patient')->count();
            $doctors    = \App\Models\User::where('role', 'medecin')->count();
            $appointments = \App\Models\Appointment::with(['service','user'])
                ->latest()->take(8)->get();
        } elseif ($user->role === 'medecin') {
            $total      = \App\Models\Appointment::count();
            $pending    = \App\Models\Appointment::where('status', 'pending')->count();
            $confirmed  = \App\Models\Appointment::where('status', 'confirmed')->count();
            $patients   = \App\Models\User::where('role', 'patient')->count();
            $doctors    = 0;
            $appointments = \App\Models\Appointment::with(['service','user'])
                ->where('status', 'pending')->latest()->take(8)->get();
        } else {
            // patient
            $total      = \App\Models\Appointment::where('user_id', $user->id)->count();
            $pending    = \App\Models\Appointment::where('user_id', $user->id)->where('status', 'pending')->count();
            $confirmed  = \App\Models\Appointment::where('user_id', $user->id)->where('status', 'confirmed')->count();
            $patients   = 0;
            $doctors    = 0;
            $appointments = \App\Models\Appointment::with('service')
                ->where('user_id', $user->id)->latest()->take(5)->get();
        }

        return view('dashboard', compact('appointments','total','pending','confirmed','patients','doctors'));
    })->name('dashboard');

    // ── Profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    // ── Appointments CRUD
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/',                  [AppointmentController::class, 'index'])->name('index');
        Route::get('/search/results',    [AppointmentController::class, 'search'])->name('search');
        Route::get('/create',            [AppointmentController::class, 'create'])->name('create');
        Route::post('/',                 [AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}',     [AppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/edit',[AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment}',     [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{appointment}',  [AppointmentController::class, 'destroy'])->name('destroy');
        // Doctor/Admin: confirm or complete
        Route::patch('/{appointment}/confirm',  [AppointmentController::class, 'confirm'])->name('confirm');
        Route::patch('/{appointment}/complete', [AppointmentController::class, 'complete'])->name('complete');
    });

    // ── Admin routes
    Route::prefix('admin')->name('admin.')->middleware('can:admin-only')->group(function () {
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
    });
});

require __DIR__.'/auth.php';
