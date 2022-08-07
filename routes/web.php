<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\{RoleController, UserController, AccountController, ProfileController, MemberController ,TransactionController};
use App\Http\Controllers\Auth\LoginController;

// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/data-table-members', [App\Http\Controllers\HomeController::class, 'dataTableMembers'])->name('dataTableMembers');
Route::get('/data-table-transactions', [App\Http\Controllers\HomeController::class, 'dataTableTransactions'])->name('dataTableTransactions');

// Login Routes ...
Route::get('login', [LoginController::class, 'showLoginForm']);
Route::post('login', [LoginController::class,'login'])->name('login');
Route::post('logout',  [LoginController::class,'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Management User & Roles
    Route::resources(['users' => UserController::class]);
    Route::post('users/{user:id}/status', [UserController::class, 'changeStatus'])->name('users.status');
    Route::resources(['roles' => RoleController::class]);

    // Profil DKM
    Route::resources(['profiles' => ProfileController::class]);

    // Pengurus DKM
    Route::resources(['members' => MemberController::class]);
    Route::post('members/delete-selected', [MemberController::class, 'deleteSelected'])->name('members.deleteSelected');

    // Transaksi Keuangan
    Route::resources(['transactions' => TransactionController::class]);
    Route::post('transactions/delete-selected', [TransactionController::class, 'deleteSelected'])->name('transactions.deleteSelected');
    Route::prefix('trash')->group(function () {
        Route::get('transactions', [TransactionController::class, 'trash'])->name('trash.transactions');
        Route::get('transactions/restore/{id}', [TransactionController::class, 'restore'])->name('restore.transactions');
        Route::delete('transactions/delete/{id}', [TransactionController::class, 'deletePermanent'])->name('deletePermanent.transactions');
        Route::delete('transactions/deleteAll/', [TransactionController::class, 'deleteAll'])->name('deleteAll.transactions');
    });
});
