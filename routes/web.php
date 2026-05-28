<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.index');
});

Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/signup', [AuthController::class, 'createUser'])->middleware('throttle:register')->name('user.create');
Route::middleware('auth')
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::middleware(['role:admin'])->group(function () {

    Route::get('/master/users', [MasterController::class, 'users'])
        ->name('master.users');

    Route::get('/master/outlet', [MasterController::class, 'outlet'])
        ->name('master.outlet');

    Route::get('/master/jabatan', [MasterController::class, 'jabatan'])->name('master.jabatan');
    Route::get('/master/unit', [MasterController::class, 'unit'])->name('master.unit');

    Route::get('/user', [UserController::class, 'index']);

    Route::post('/user/create', [UserController::class, 'create'])
        ->name('user.create');
    Route::patch('/user/update/{id}', [UserController::class, 'update'])
        ->name('user.update');
    Route::delete('/user/delete/{id}', [UserController::class, 'delete'])
        ->name('user.delete');

    Route::get('/outlet', [OutletController::class, 'index']);
    Route::post('/outlet', [OutletController::class, 'store']);
    Route::patch('/outlet/{id}', [OutletController::class, 'update']);
    Route::delete('/outlet/{id}', [OutletController::class, 'destroy']);


    Route::get('/jabatan', [JabatanController::class, 'jabatan'])
        ->name('jabatan');
    Route::post('/jabatan', [JabatanController::class, 'storeJabatan'])->name('jabatan.store');
    Route::patch('/jabatan/{id}', [JabatanController::class, 'updateJabatan'])->name('jabatan.update');
    Route::delete('/jabatan/{id}', [JabatanController::class, 'destroyJabatan'])->name('jabatan.destroy');

    Route::get('/unit', [UnitController::class, 'unit'])
        ->name('unit');
    Route::post('/unit', [UnitController::class, 'storeUnit'])->name('unit.store');
    Route::patch('/unit/{id}', [UnitController::class, 'updateUnit'])->name('unit.update');
    Route::delete('/unit/{id}', [UnitController::class, 'destroyUnit'])->name('unit.destroy');


    Route::get('/tpi', [MasterController::class, 'tpi'])
        ->name('tpi');
    Route::get('/idp', [MasterController::class, 'idp'])
        ->name('idp');


    Route::get('/users/request', [UserManagementController::class, 'userRequest'])->name('user.request');
    Route::get('/request', [UserManagementController::class, 'request'])->name('request');
    Route::post('/request/approve/{id}', [UserManagementController::class, 'approveRequest'])->name('request.approve');
    Route::delete('/request/reject/{id}', [UserManagementController::class, 'rejectRequest'])->name('request.reject');

    Route::get('/users/supervisor', [UserManagementController::class, 'userSupervisor'])->name('user.supervisor');
    Route::get('supervisor/data', [UserManagementController::class, 'supervisorData'])->name('supervisor.data');


});