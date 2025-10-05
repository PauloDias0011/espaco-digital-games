<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetSkillsadminController;
use App\Http\Controllers\StudentAuthController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rotas do tema GetSkills
Route::controller(GetSkillsadminController::class)->group(function () {
    Route::get('/',                 'dashboard_1');
    Route::get('/index',            'dashboard_1');
    Route::get('/index-2',          'dashboard_2');
    Route::get('/schedule',         'schedule');
    Route::get('/instructors',      'instructors');
    Route::get('/message',          'message');
    Route::get('/activity',         'activity');
    Route::get('/profile',          'profile');
    Route::get('/courses',          'courses');
    Route::get('/course-details-1', 'course_details_1');
    Route::get('/course-details-2', 'course_details_2');
    Route::get('/instructor-dashboard','instructor_dashboard');
    Route::get('/instructor-courses','instructor_courses');
    Route::get('/instructor-schedule','instructor_schedule');
    Route::get('/instructor-students','instructor_students');
    Route::get('/instructor-resources','instructor_resources');
    Route::get('/instructor-transactions','instructor_transactions');
    Route::get('/instructor-liveclass','instructor_liveclass');
    Route::get('/app-profile',      'app_profile');
    Route::match(['get','post'],'/post-details',     'post_details');
    Route::get('/email-compose',    'email_compose');
    Route::get('/email-inbox',      'email_inbox');
    Route::get('/email-read',       'email_read');
    Route::get('/app-calender',     'app_calender');
    Route::get('/ecom-product-grid','ecom_product_grid');
    Route::get('/ecom-product-list','ecom_product_list');
    Route::get('/ecom-product-detail','ecom_product_detail');
    Route::get('/ecom-product-order','ecom_product_order');
    Route::get('/ecom-checkout',    'ecom_checkout');
    Route::get('/ecom-invoice',     'ecom_invoice');
    Route::get('/ecom-customers',   'ecom_customers');
    Route::get('/chart-flot',       'chart_flot');
    Route::get('/chart-morris',     'chart_morris');
    Route::get('/chart-chartjs',    'chart_chartjs');
    Route::get('/chart-chartist',   'chart_chartist');
    Route::get('/chart-sparkline',  'chart_sparkline');
    Route::get('/chart-peity',      'chart_peity');
    Route::get('/ui-accordion',     'ui_accordion');
    Route::get('/ui-alert',         'ui_alert');
    Route::get('/ui-badge',         'ui_badge');
    Route::get('/ui-button',        'ui_button');
    Route::get('/ui-modal',         'ui_modal');
    Route::get('/ui-button-group',  'ui_button_group');
    Route::get('/ui-list-group',    'ui_list_group');
    Route::get('/ui-card',          'ui_card');
    Route::get('/ui-carousel',      'ui_carousel');
    Route::get('/ui-dropdown',      'ui_dropdown');
    Route::get('/ui-popover',       'ui_popover');
    Route::get('/ui-progressbar',   'ui_progressbar');
    Route::get('/ui-tab',           'ui_tab');
    Route::get('/ui-typography',    'ui_typography');
    Route::get('/ui-pagination',    'ui_pagination');
    Route::get('/ui-grid',          'ui_grid');
    Route::get('/uc-select2',       'uc_select2');
    Route::get('/uc-nestable',      'uc_nestable');
    Route::get('/uc-noui-slider',   'uc_noui_slider');
    Route::get('/uc-sweetalert',    'uc_sweetalert');
    Route::get('/uc-toastr',        'uc_toastr');
    Route::get('/map-jqvmap',       'map_jqvmap');
    Route::get('/uc-lightgallery',  'uc_lightgallery');
    Route::get('/widget-basic',     'widget_basic');
    Route::get('/form-element',     'form_element');
    Route::get('/form-wizard',      'form_wizard');
    Route::get('/form-ckeditor',    'form_ckeditor');
    Route::get('/form-pickers',     'form_pickers');
    Route::get('/form-validation',  'form_validation');
    Route::get('/table-bootstrap-basic', 'table_bootstrap_basic');
    Route::get('/table-datatable-basic', 'table_datatable_basic');
    Route::get('/page-login',       'page_login');
    Route::get('/page-register',    'page_register');
    Route::get('/page-error-400',   'page_error_400');
    Route::get('/page-error-403',   'page_error_403');
    Route::get('/page-error-404',   'page_error_404');
    Route::get('/page-error-500',   'page_error_500');
    Route::get('/page-error-503',   'page_error_503');
    Route::get('/page-forgot-password', 'page_forgot_password');
    Route::get('/page-lock-screen', 'page_lock_screen');
    Route::get('/empty-page',       'page_empty');
});

// Rota temporária para validar tema
Route::get('/_getskills_demo', function () {
    return view('pages._demo_getskills_ok');
});

// Rota temporária para testar módulo Tenancy (sem middleware de tenancy)
Route::get('/test-tenancy', function () {
    return 'Módulo Tenancy funcionando!';
})->withoutMiddleware([
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
    \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
]);

// Rota para testar resolução de tenant
Route::get('/test-tenant-resolver', function () {
    try {
        $domain = \Modules\Tenancy\App\Models\TenantDomain::where('domain', '127.0.0.1')->with('tenant')->first();
        if ($domain) {
            return response()->json([
                'status' => 'success',
                'domain' => $domain->domain,
                'tenant_id' => $domain->tenant->id,
                'tenant_name' => $domain->tenant->getName(),
                'tenant_status' => $domain->tenant->getStatus()
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Domínio não encontrado']);
        }
    } catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
})->withoutMiddleware([
    \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,
    \Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains::class,
]);

// Rotas do Super Admin (Módulo Tenancy)
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index']);
    
    // API para monitoramento em tempo real
    Route::get('/api/resources', [\App\Http\Controllers\SuperAdmin\SystemMonitorController::class, 'resources'])->name('api.resources');
    
    // Gestão de Unidades
    Route::resource('tenants', \Modules\Tenancy\App\Http\Controllers\TenantController::class)->except(['show']);
    
    Route::prefix('tenants/{tenant}')->name('tenants.')->group(function () {
        Route::post('domains', [\Modules\Tenancy\App\Http\Controllers\TenantController::class, 'storeDomain'])->name('domains.store');
        Route::delete('domains/{domain}', [\Modules\Tenancy\App\Http\Controllers\TenantController::class, 'destroyDomain'])->name('domains.destroy');
    });
});

// Rotas de Autenticação de Alunos (dentro do contexto do tenant)
Route::middleware(['web', 'tenant'])->group(function () {
    // Rotas públicas de autenticação
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/register', [StudentAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [StudentAuthController::class, 'register'])->name('register');
        Route::get('/login', [StudentAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [StudentAuthController::class, 'login'])->name('login');
    });

    // Rotas protegidas para alunos
    Route::middleware(['auth', 'student.tenant'])->prefix('student')->name('student.')->group(function () {
        Route::get('/profile', [StudentAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [StudentAuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    });

    // Rota para trilha atual (placeholder)
    Route::middleware(['auth', 'student.tenant'])->get('/trilha/atual', function () {
        return view('student.trail.current')->with('message', 'Trilha atual será implementada em breve!');
    })->name('trail.current');
});

// Rotas Administrativas (dentro do contexto do tenant)
Route::middleware(['web', 'tenant', 'auth', 'admin.manage.students'])->prefix('admin')->name('admin.')->group(function () {
    // Gestão de Usuários
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/reset-progress', [UserController::class, 'resetProgress'])->name('users.reset-progress');
    Route::get('users/classrooms', [UserController::class, 'getClassrooms'])->name('users.classrooms');
    Route::post('users/bulk-update', [UserController::class, 'bulkUpdate'])->name('users.bulk-update');
});
