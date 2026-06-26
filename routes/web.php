<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Kasir\KasirController;
use App\Http\Controllers\Pelanggan\PelangganController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


// =======================
// HALAMAN UTAMA
// =======================

Route::get('/', function () {
    return redirect()->route('login');
});


// =======================
// AUTH
// =======================

Route::get('/login', [
    AuthenticatedSessionController::class,
    'create'
])->name('login');


Route::post('/login', [
    AuthenticatedSessionController::class,
    'store'
]);


Route::get('/register', [
    RegisteredUserController::class,
    'create'
])->name('register');


Route::post('/register', [
    RegisteredUserController::class,
    'store'
]);


Route::post('/logout', [
    AuthenticatedSessionController::class,
    'destroy'
])->name('logout');



// =======================
// DASHBOARD REDIRECT ROLE
// =======================

Route::middleware('auth')->get('/dashboard', function () {
$user = Auth::user();

    if ($user->role == 'admin') {

        return redirect()->route('admin.dashboard');

    } elseif ($user->role == 'kasir') {

        return redirect()->route('kasir.dashboard');

    } else {

        return redirect()->route('pelanggan.dashboard');

    }

})->name('dashboard');




// =======================
// PROFILE
// =======================

Route::middleware('auth')->group(function(){

    Route::get('/profile',
        [ProfileController::class,'edit']
    )->name('profile.edit');


    Route::patch('/profile',
        [ProfileController::class,'update']
    )->name('profile.update');


    Route::post('/profile/password',
        [ProfileController::class,'updatePassword']
    )->name('profile.password.update');

});




// =======================
// ADMIN ROUTES
// =======================

Route::middleware(['auth'])
->prefix('admin')
->name('admin.')
->group(function(){


    Route::get('/dashboard',
        [AdminController::class,'dashboard']
    )->name('dashboard');



    // USER MANAGEMENT

    Route::get('/users',
        [\App\Http\Controllers\Admin\UserController::class,'index']
    )->name('users.index');


    Route::get('/users/create',
        [\App\Http\Controllers\Admin\UserController::class,'create']
    )->name('users.create');


    Route::post('/users',
        [\App\Http\Controllers\Admin\UserController::class,'store']
    )->name('users.store');


    Route::get('/users/{user}',
        [\App\Http\Controllers\Admin\UserController::class,'show']
    )->name('users.show');


    Route::get('/users/{user}/edit',
        [\App\Http\Controllers\Admin\UserController::class,'edit']
    )->name('users.edit');


    Route::put('/users/{user}',
        [\App\Http\Controllers\Admin\UserController::class,'update']
    )->name('users.update');


    Route::delete('/users/{user}',
        [\App\Http\Controllers\Admin\UserController::class,'destroy']
    )->name('users.destroy');




    // SERVICE MANAGEMENT


    Route::get('/services',
        [\App\Http\Controllers\Admin\ServiceController::class,'index']
    )->name('services.index');


    Route::get('/services/create',
        [\App\Http\Controllers\Admin\ServiceController::class,'create']
    )->name('services.create');


    Route::post('/services',
        [\App\Http\Controllers\Admin\ServiceController::class,'store']
    )->name('services.store');


    Route::get('/services/{service}/edit',
        [\App\Http\Controllers\Admin\ServiceController::class,'edit']
    )->name('services.edit');


    Route::put('/services/{service}',
        [\App\Http\Controllers\Admin\ServiceController::class,'update']
    )->name('services.update');


    Route::delete('/services/{service}',
        [\App\Http\Controllers\Admin\ServiceController::class,'destroy']
    )->name('services.destroy');




    // TRANSACTIONS


    Route::get('/transactions',
        [\App\Http\Controllers\Admin\TransactionController::class,'index']
    )->name('transactions.index');


    Route::get('/transactions/{transaction}',
        [\App\Http\Controllers\Admin\TransactionController::class,'show']
    )->name('transactions.show');




    // REPORT


    Route::get('/reports',
        [AdminController::class,'reports']
    )->name('reports.index');


    // EXPORT REPORT
    Route::get('/reports/export',
        [ReportController::class,'export']
    )->name('reports.export');


});





// =======================
// KASIR ROUTES
// =======================

Route::middleware(['auth'])
->prefix('kasir')
->name('kasir.')
->group(function(){


    // == {

    Route::get(
        'dashboard',
        [KasirController::class, 'dashboard']
    )->name('dashboard');


    // TRANSAKSI
    Route::get(
        'transactions/create',
        [KasirController::class, 'createTransaction']
    )->name('transactions.create');


    Route::post(
        'transactions',
        [KasirController::class, 'storeTransaction']
    )->name('transactions.store');


    Route::get(
        'transactions',
        [KasirController::class, 'indexTransaction']
    )->name('transactions.index');


    Route::get(
        'transactions/{transaction}',
        [KasirController::class, 'showTransaction']
    )->name('transactions.show');



    // CUSTOMER
    Route::get(
        'customers',
        [KasirController::class, 'indexCustomer']
    )->name('customers.index');


    Route::get(
        'customers/create',
        [KasirController::class, 'createCustomer']
    )->name('customers.create');


    Route::post(
        'customers',
        [KasirController::class, 'storeCustomer']
    )->name('customers.store');


    Route::get(
        'customers/{customer}',
        [KasirController::class, 'showCustomer']
    )->name('customers.show');

});






// =======================
// PELANGGAN ROUTES
// =======================

Route::middleware(['auth'])
->prefix('pelanggan')
->name('pelanggan.')
->group(function(){


    Route::get('/dashboard',
        [PelangganController::class,'dashboard']
    )->name('dashboard');


    Route::get('/transactions',
        [PelangganController::class,'transactions']
    )->name('transactions.index');


    Route::get('/transactions/{transaction}',
        [PelangganController::class,'showTransaction']
    )->name('transactions.show');


    Route::get('/history',
        [PelangganController::class,'history']
    )->name('history.index');


    Route::get('/tracking',
        [PelangganController::class,'trackLaundry']
    )->name('tracking.index');


});
