<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Tenancy\App\Http\Controllers\TenancyController;
use Modules\Tenancy\App\Http\Controllers\TenantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('superadmin')->name('superadmin.')->group(function () {
    // Rotas de gestão de tenants (unidades)
    Route::resource('tenants', TenantController::class)->except(['show']);
    
    // Rotas para gerenciar domínios dos tenants
    Route::prefix('tenants/{tenant}')->name('tenants.')->group(function () {
        Route::post('domains', [TenantController::class, 'storeDomain'])->name('domains.store');
        Route::delete('domains/{domain}', [TenantController::class, 'destroyDomain'])->name('domains.destroy');
    });
});

// Rota original do tenancy (mantida para compatibilidade)
Route::group([], function () {
    Route::resource('tenancy', TenancyController::class)->names('tenancy');
});
