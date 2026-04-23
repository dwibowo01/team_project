<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Teams\DockingController;
use App\Http\Controllers\Teams\ManagementController;
use App\Http\Controllers\Teams\NewBuildingController;
use App\Http\Controllers\Teams\SiteController;
use App\Http\Controllers\Teams\TeamGroupController;
use App\Http\Controllers\Teams\TeamMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Management Team Routes (superadmin)
|--------------------------------------------------------------------------
*/
Route::prefix('management')
    ->middleware(['auth', 'verified', 'team.type:Management'])
    ->name('management.')
    ->group(function () {
        Route::get('/', [ManagementController::class, 'index'])->name('dashboard');
        Route::get('/teams/{team}', [ManagementController::class, 'viewTeam'])->name('view-team');
        Route::get('/teams/{team}/members', [ManagementController::class, 'members'])->name('team-members');
    });

/*
|--------------------------------------------------------------------------
| Docking Team Routes
|--------------------------------------------------------------------------
*/
Route::prefix('docking')
    ->middleware(['auth', 'verified', 'team.type:Docking'])
    ->name('docking.')
    ->group(function () {
        Route::get('/', [DockingController::class, 'index'])->name('dashboard');
        Route::get('/projects', [DockingController::class, 'projects'])->name('projects');
        Route::get('/members', [DockingController::class, 'members'])->name('members');
        Route::get('/groups', [DockingController::class, 'groups'])->name('groups');
    });

/*
|--------------------------------------------------------------------------
| New Building Team Routes
|--------------------------------------------------------------------------
*/
Route::prefix('new-building')
    ->middleware(['auth', 'verified', 'team.type:New Building'])
    ->name('new-building.')
    ->group(function () {
        Route::get('/', [NewBuildingController::class, 'index'])->name('dashboard');
        Route::get('/projects', [NewBuildingController::class, 'projects'])->name('projects');
        Route::get('/members', [NewBuildingController::class, 'members'])->name('members');
        Route::get('/groups', [NewBuildingController::class, 'groups'])->name('groups');
    });

/*
|--------------------------------------------------------------------------
| Site Team Routes
|--------------------------------------------------------------------------
*/
Route::prefix('site')
    ->middleware(['auth', 'verified', 'team.type:Site'])
    ->name('site.')
    ->group(function () {
        Route::get('/', [SiteController::class, 'index'])->name('dashboard');
        Route::get('/projects', [SiteController::class, 'projects'])->name('projects');
        Route::get('/members', [SiteController::class, 'members'])->name('members');
        Route::get('/groups', [SiteController::class, 'groups'])->name('groups');
    });

/*
|--------------------------------------------------------------------------
| Team Member & Group Management API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('teams/{team}')
    ->middleware(['auth', 'verified'])
    ->name('teams.')
    ->group(function () {
        // Member management
        Route::post('/members', [TeamMemberController::class, 'store'])->name('members.store');
        Route::patch('/members/{user}', [TeamMemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{user}', [TeamMemberController::class, 'destroy'])->name('members.destroy');

        // Group management
        Route::post('/groups', [TeamGroupController::class, 'store'])->name('groups.store');
        Route::patch('/groups/{groupCode}', [TeamGroupController::class, 'update'])->name('groups.update');
        Route::delete('/groups/{groupCode}', [TeamGroupController::class, 'destroy'])->name('groups.destroy');
        Route::post('/groups/{groupCode}/users', [TeamGroupController::class, 'addUser'])->name('groups.users.store');
        Route::delete('/groups/{groupCode}/users', [TeamGroupController::class, 'removeUser'])->name('groups.users.destroy');
        Route::post('/groups/{groupCode}/global-users', [TeamGroupController::class, 'addGlobalUser'])->name('groups.global-users.store');
    });

require __DIR__.'/auth.php';

