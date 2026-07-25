<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IdpMasterController;
use App\Http\Controllers\IdpTeamController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\KpiMasterController;
use App\Http\Controllers\KpiReportController;
use App\Http\Controllers\KpiTeamController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizationalUnitController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PeringatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskIdpController;
use App\Http\Controllers\TeamRequestController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserKpiApprovalController;
use App\Http\Controllers\UserKpiRealizationController;
use App\Http\Controllers\UserManagementController;
use App\Models\UserKpiApproval;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.index');
})->name('login');

Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/signup', [AuthController::class, 'createUser'])->middleware('throttle:register')->name('user.create');
Route::middleware('auth')
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/signout', [AuthController::class, 'signout'])->name('signout');
    Route::get('/profile/me', [ProfileController::class, 'index'])->name('profile.me');
    // Route untuk menampilkan form edit
    // Route untuk memproses update (via AJAX)
    Route::post('/profile/me/update', [ProfileController::class, 'update'])->name('profile.me.update');
    Route::post('/profile/me/change-password', [ProfileController::class, 'changePassword'])->name('profile.me.change-password');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});

Route::middleware(['role:admin,manager_hc,spv_hc'])->group(function () {

    Route::get('/idp', [MasterController::class, 'idp'])
        ->name('idp');
    Route::get('/kpi/report', [KpiReportController::class, 'kpiReport'])->name('kpi.report');

    Route::get('/reports/kpi/data', [KpiReportController::class, 'getData'])->name('reports.kpi.data');
    Route::get('/reports/approval', [KpiReportController::class, 'approvalRequest'])->name('hc.approval');
    Route::get('/reports/approval/data', [KpiReportController::class, 'approvalData'])->name('approval.data');
    Route::post('/approval/action', [KpiReportController::class, 'processApproval'])->name('approval.action');

    Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi');
    Route::get('/mutasi/data', [MutasiController::class, 'getData'])->name('mutasi.data');
    Route::post('/mutasi/approval-hr', [MutasiController::class, 'processHrApproval'])->name('mutasi.approval.hr');
    Route::post('/mutasi/store-direct', [MutasiController::class, 'storeDirectApproved'])->name('mutasi.storeDirect');

    Route::get('/peringatan', [PeringatanController::class, 'index'])->name('peringatan');
    Route::post('/peringatan', [PeringatanController::class, 'store'])->name('peringatan.store');
    Route::get('/peringatan/data', [PeringatanController::class, 'getData'])->name('peringatan.data');
    Route::post('/peringatan/approval-hr', [PeringatanController::class, 'processHrApproval'])->name('peringatan.approval.hr');

    Route::get('/attendance', [MasterController::class, 'attendance'])->name('attendance');
});

Route::middleware(['role:spv,manager,direksi,spv_hc,manager_hc'])->group(function () {

    Route::get('/team/kpi', [KpiTeamController::class, 'index'])->name('team.kpi');
    Route::post('/team/kpi/assign', [KpiTeamController::class, 'assignKpi'])->name('team.kpi.assign');
    Route::put('/team/kpi/assign/{id}', [KpiTeamController::class, 'updateAssignment'])->name('team.kpi.update');

    Route::get('/team/kpi/approval', [KpiTeamController::class, 'approvalList'])->name('team.kpi.approval');
    Route::get('/team/kpi/report', [KpiTeamController::class, 'report'])->name('team.kpi.report');

    Route::get('/team/idp', [IdpTeamController::class, 'teamIdp'])->name('team.idp');
    Route::post('/team/idp/book-proposal/{id}/approve', [IdpTeamController::class, 'approveBookProposal'])->name('team.idp.approve-book');
    Route::get('/team/idp/get-data', [IdpTeamController::class, 'getUserData'])->name('team.idp.get-data');

    Route::get('/team/request', [TeamRequestController::class, 'index'])->name('team.request');
    Route::get('/team/request/detail', [TeamRequestController::class, 'detailUser'])->name('team.request.detail');
    Route::post('/team/request/peringatan', [TeamRequestController::class, 'storePeringatan'])->name('team.request.peringatan.store');
    Route::post('/team/request/mutasi', [TeamRequestController::class, 'storeMutasi'])->name('team.request.mutasi.store');
    Route::post('/team/request/man-power', [TeamRequestController::class, 'storeManPower'])->name('team.request.manpower.store');


    Route::get('/kpi/master/me', [KpiMasterController::class, 'myKpi'])->name('kpi.master.me');
    Route::post('/kpi/master/me', [KpiMasterController::class, 'storeMyKpi'])->name('kpi.master.me.store');
});

Route::middleware(['role:spv,manager,direksi,spv_hc,manager_hc'])->group(function () {
    Route::get('/approval/kpi', [UserKpiApprovalController::class, 'index'])->name('approval.kpi');
    Route::get('/approval/kpi/list', [UserKpiApprovalController::class, 'list'])->name('approval.kpi.list');
    Route::post('/approval/kpi/{id}/{action}', [UserKpiApprovalController::class, 'approval'])->name('approval.kpi');

    Route::get('/approval/request', [ApprovalController::class, 'approvalRequest'])->name('approval.request');
    Route::get('/approval/request/list', [ApprovalController::class, 'list'])->name('approval.request.list');
    Route::post('/approval/request/{type}/{id}/approve', [ApprovalController::class, 'approve'])->name('approval.request.approve');
    Route::post('/approval/request/{type}/{id}/reject', [ApprovalController::class, 'reject'])->name('approval.request.reject');
});


Route::middleware('role:pegawai,spv,manager,direksi,admin_hc,spv_hc,manager_hc')->group(function () {
    Route::get('/task/kpi', [UserKpiRealizationController::class, 'index'])->name('task.kpi');
    Route::post('/task/kpi/realization', [UserKpiRealizationController::class, 'store'])->name('kpi.realization.store');

    Route::get('/task/idp', [TaskIdpController::class, 'index'])->name('task.idp');

    // Endpoint untuk Fetch Server Time (Untuk validasi Shalat)
    Route::get('/api/server-time', [TaskIdpController::class, 'getServerTime'])->name('api.server-time');

    // AJAX Endpoints untuk submit data IDP
    Route::post('/task/idp/jogging', [TaskIdpController::class, 'storeJogging'])->name('task.idp.jogging.store');
    Route::post('/task/idp/prayer', [TaskIdpController::class, 'storePrayer'])->name('task.idp.prayer.store');
    Route::post('/task/idp/book-proposal', [TaskIdpController::class, 'storeBookProposal'])->name('task.idp.book-proposal.store');
    Route::post('/task/idp/book-log', [TaskIdpController::class, 'storeBookLog'])->name('task.idp.book-log.store');
});

Route::middleware(['role:admin,admin_hc,spv_hc,manager_hc'])->group(function () {
    Route::get('/kpi', [MasterController::class, 'kpi'])
        ->name('key.performance.indicator');
    Route::get('/kpi/period', [KpiMasterController::class, 'kpiPeriod'])
        ->name('kpi.period');
    Route::post('/kpi/period', [KpiMasterController::class, 'storeKpiPeriod'])
        ->name('kpi.period.store');
    Route::get('/kpi/period/{id}', [KpiMasterController::class, 'show'])
        ->name('kpi.period');
    Route::put('/kpi/period/{id}/update', [KpiMasterController::class, 'updateKpiPeriod'])
        ->name('kpi.period.update');

    Route::post('/announcement/store', [AnnouncementController::class, 'store'])->name('announcements.store');

    Route::get('/idp/shalat-schedule', [IdpMasterController::class, 'shalatSchedule'])->name('sholat.schedule');
    Route::post('/idp/shalat-schedule', [IdpMasterController::class, 'store'])->name('sholat.schedule.store');
    Route::put('/idp/shalat-schedule/{id}', [IdpMasterController::class, 'update'])->name('sholat.schedule.update');
    Route::get('/idp/shalat-schedule/list', [IdpMasterController::class, 'shalatScheduleList'])->name('sholat.schedule.list');


    Route::get('/organization/structure', [OrganizationalUnitController::class, 'index'])->name('organization.structure');
    Route::post('/organization/save', [OrganizationalUnitController::class, 'store'])->name('organization.save');

    Route::get('/outlet', [OutletController::class, 'index']);
    Route::post('/outlet', [OutletController::class, 'store']);
    Route::patch('/outlet/{id}', [OutletController::class, 'update']);
    Route::delete('/outlet/{id}', [OutletController::class, 'destroy']);

    Route::get('/master/users', [MasterController::class, 'users'])
        ->name('master.users');
    Route::get('/master/outlet', [MasterController::class, 'outlet'])
        ->name('master.outlet');
    Route::get('/master/jabatan', [MasterController::class, 'jabatan'])->name('master.jabatan');
    // Route::get('/master/unit', [MasterController::class, 'unit'])->name('master.unit');


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
    Route::patch('/user/activate/{id}', [UserController::class, 'activate'])
        ->name('user.activate');

    Route::get('/jabatan', [JabatanController::class, 'jabatan'])
        ->name('jabatan');
    Route::post('/jabatan', [JabatanController::class, 'storeJabatan'])->name('jabatan.store');
    Route::patch('/jabatan/{id}', [JabatanController::class, 'updateJabatan'])->name('jabatan.update');
    Route::delete('/jabatan/{id}', [JabatanController::class, 'destroyJabatan'])->name('jabatan.destroy');

    Route::get('/users/request', [UserManagementController::class, 'userRequest'])->name('user.request');
    Route::get('/request', [UserManagementController::class, 'request'])->name('request');
    Route::post('/request/approve/{id}', [UserManagementController::class, 'approveRequest'])->name('request.approve');
    Route::delete('/request/reject/{id}', [UserManagementController::class, 'rejectRequest'])->name('request.reject');
});