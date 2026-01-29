<?php

use App\Enums\CandidatePosition;
use App\Enums\Role;
use App\Http\Controllers\AddCandidate;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use App\Livewire\LiveResults as LivewireLiveResults;
use App\Mail\LiveResults;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    // if (Auth::check()) {
    //     if (Auth::user()->role === Role::ADMIN) {
    //         return redirect()->route('admin-index');
    //     }

    //     if (config('election.status') === 'open' && !Auth::user()->has_voted) {
    //         return redirect()->route('vote-index');
    //     }
    // }
    return view('welcome');
})->middleware('no-cache')->name('welcome');

Route::get('/results/{token}', LivewireLiveResults::class)
    ->name('results.show');

Route::get('/test', function () {
    return view('success');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        return view('register');
    })->name('register-index');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
});
Route::get('/check-candidate', function () {
    $candidatesByPosition = [];
    $candidatePositions = collect(CandidatePosition::cases())
        ->filter(fn($position) => Candidate::where('position_applied', $position->value)->exists())
        ->values()
        ->toArray();

    foreach ($candidatePositions as $position) {
        $candidatesByPosition[$position->value] = Candidate::where('position_applied', $position->value)
            ->orderBy('full_name')
            ->get();
    }
    return view('check-candidate', compact('candidatesByPosition'));
})->name('check-candidate');

Route::post('/login', [LoginController::class, 'loginAuth'])->name('login-auth');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout-auth');

Route::middleware(['auth', 'role:user', 'election.open'])->group(function () {
    Route::get('/vote', function () {
        return view('vote');
    })->name('vote-index');
});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.index');
    })->name('admin-index');

    Route::post('/add-PreUsers', [AdminController::class, 'addPreUsers'])->name('add-preUsers');

    Route::get('/add-candidate', [AddCandidate::class, 'viewCandidate'])->name('add-candidate');

    Route::post('/add-candidate', [AddCandidate::class, 'store'])
        ->name('candidate-auth');

    Route::get('/edit-candidate/{id}', [AdminController::class, 'edit'])
        ->name('edit-candidate');

    Route::put('/edit-candidate/{id}', [AdminController::class, 'update'])
        ->name('candidate-edit');

    Route::get('/check-pre-users/{id}', [AdminController::class, 'checkPreUsers'])
        ->name('check-pre-users');

    Route::put('/check-pre-users/{id}', [AdminController::class, 'editPreUsers'])
        ->name('edit-pre-users');

    Route::get('/edit-users/{id}', [AdminController::class, 'editUser'])
        ->name('edit-users');

    Route::put('/edit-users/{id}', [AdminController::class, 'updateUser'])
        ->name('edit-users-auth');
});
