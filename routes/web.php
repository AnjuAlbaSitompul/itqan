<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\KpiMasterController;
use App\Http\Controllers\KpiTeamController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizationalUnitController;
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

Route::middleware(['auth'])->group(function () {
    Route::post('/signout', [AuthController::class, 'signout'])->name('signout');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});

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
    Route::post('/user/import', [UserController::class, 'import'])
        ->name('user.import');
    Route::get('/user/template', [UserController::class, 'downloadTemplate'])
        ->name('user.template');
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


    Route::get('/kpi', [MasterController::class, 'kpi'])
        ->name('key.performance.indicator');
    Route::get('/kpi/period', [KpiMasterController::class, 'kpiPeriod'])
        ->name('kpi.period');
    Route::post('/kpi/period', [KpiMasterController::class, 'storeKpiPeriod'])
        ->name('kpi.period.store');
    Route::get('/idp', [MasterController::class, 'idp'])
        ->name('idp');


    Route::get('/users/request', [UserManagementController::class, 'userRequest'])->name('user.request');
    Route::get('/request', [UserManagementController::class, 'request'])->name('request');
    Route::post('/request/approve/{id}', [UserManagementController::class, 'approveRequest'])->name('request.approve');
    Route::delete('/request/reject/{id}', [UserManagementController::class, 'rejectRequest'])->name('request.reject');

    Route::get('/organization/structure', [OrganizationalUnitController::class, 'index'])->name('organization.structure');
    Route::post('/organization/save', [OrganizationalUnitController::class, 'store'])->name('organization.save');

    Route::get('/attendance', [MasterController::class, 'attendance'])->name('attendance');
});

Route::middleware(['role:spv'])->group(function () {
    Route::get('/task/kpi', [KpiController::class, 'index'])->name('task.kpi');
    Route::get('/task/idp', [MasterController::class, 'myIdp'])->name('task.idp');

    Route::get('/team/kpi', [KpiTeamController::class, 'index'])->name('team.kpi');
    Route::post('/team/kpi/assign', [KpiTeamController::class, 'assignKpi'])->name('team.kpi.assign');
    Route::get('/team/idp', [MasterController::class, 'teamIdp'])->name('team.idp');
    Route::get('/kpi/master/me', [KpiMasterController::class, 'myKpi'])->name('kpi.master.me');
    Route::post('/kpi/master/me', [KpiMasterController::class, 'storeMyKpi'])->name('kpi.master.me.store');
});