<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterEventController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\CompanyEventController;
use App\Http\Controllers\MasterUserController;
use App\Http\Controllers\VisitorEventController;
use App\Http\Controllers\WhatsappEventController;
use App\Http\Controllers\EmailEventController;
use App\Http\Controllers\ReportVisitorEventController;

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

// Register Visitor
Route::get('/register-visitor/{page}/{id}', [VisitorEventController::class, 'index_register'])->name('index_register');
Route::post('/add-visitor/{page}', [VisitorEventController::class, 'add'])->name('add-visitor');

// Login
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/{page}', [LoginController::class, 'index_parameter'])->name('login_param');
Route::post('/login', [LoginController::class, 'login_action'])->name('login_action');

// Logout
Route::get('/logout/{page?}', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Register Admin
Route::get('/register/{page?}', [LoginController::class, 'register'])->name('register');
Route::post('register-add', [LoginController::class, 'register_action'])->name('register.action');

// QR Code
Route::get('/visitor-event/qrcode/{id}', [VisitorEventController::class, 'showQRCode'])->name('visitor.event.qrcode');
Route::get('/visitor-event/landing-page-qr/{page?}', [VisitorEventController::class, 'index_landing_page'])->name('landing.page');
Route::post('/visitor-event/verify-qr', [VisitorEventController::class, 'verify_qr'])->name('verify.qr');

Route::group(['middleware' => ['auth', 'preventBackHistory']], function () {

    // Dashboard
    Route::get('/dashboard/{page?}', [DashboardController::class, 'index'])->name('dashboard');

    // Divisi Event
    Route::get('/company-event/{page?}', [CompanyEventController::class, 'index'])->name('company_event.index');
    Route::get('/company-event/add-company-event/{page?}', [CompanyEventController::class, 'add_company_index'])->name('add_company_index');
    Route::post('/edit-company/edit/{id}', [CompanyEventController::class, 'edit'])->name('edit-company');
    Route::post('/add-company', [CompanyEventController::class, 'add'])->name('add-company');
    Route::post('/update-company', [CompanyEventController::class, 'update'])->name('update-company');
    Route::delete('/delete-company/{id}', [CompanyEventController::class, 'delete'])->name('delete-company');

    // Master Event
    Route::get('/master-event/{page?}', [MasterEventController::class, 'index'])->name('index');
    Route::get('/master-event/add-event/{page?}', [MasterEventController::class, 'add_index'])->name('add_index');
    Route::post('/master-event/edit/{id}', [MasterEventController::class, 'edit'])->name('edit');
    Route::post('/add', [MasterEventController::class, 'add'])->name('add');
    Route::post('/update', [MasterEventController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [MasterEventController::class, 'delete'])->name('delete');

    // Visitor Event
    Route::get('/visitor-event/{page?}', [VisitorEventController::class, 'index'])->name('visitor_event.index');
    Route::get('/visitor-event/add-visitor-event/{page?}', [VisitorEventController::class, 'add_visitor_index'])->name('add_visitor_index');
    Route::post('/edit-visitor/edit/{page?}/{id}', [VisitorEventController::class, 'edit'])->name('edit-visitor');
    Route::post('/update-visitor', [VisitorEventController::class, 'update'])->name('update-visitor');
    Route::delete('/delete-visitor/{id}', [VisitorEventController::class, 'delete'])->name('delete-visitor');
    Route::get('/visitor-event/cetak-invoice/{id}', [VisitorEventController::class, 'generate_pdf'])->name('generate.pdf');
    // Route::get('/visitor-event/cetak-invoice/{page?}/{id}', [VisitorEventController::class, 'generate_pdf'])->name('generate.pdf');
    Route::get('/visitor-event/download-qr/{id}', [VisitorEventController::class, 'downloadQR'])->name('visitor.event.downloadQR');
    Route::get('/visitor-event/export-excel/{page?}', [VisitorEventController::class, 'export_excel'])->name('export.excel');
    Route::post('/visitor-event/import-excel/{page?}', [VisitorEventController::class, 'import_excel'])->name('import.excel');
    Route::get('/visitor-event/template-excel/{page?}', [VisitorEventController::class, 'template_excel'])->name('template.excel');
    Route::post('/delete-multiple-visitors', [VisitorEventController::class, 'deleteMultipleVisitors'])->name('delete-multiple-visitors');
    Route::post('/approval-multiple-visitors', [VisitorEventController::class, 'approvalMultipleVisitors'])->name('approval-multiple-visitors');
    Route::post('/visitor-event/send-email', [VisitorEventController::class, 'sendEmail'])->name('send.email');
    Route::post('/visitor-event/send-whatsapp', [VisitorEventController::class, 'sendWhatsapp'])->name('send.whatsapp');
    Route::get('/visitor-event/send-email/{id}', [VisitorEventController::class, 'sendEmailId'])->name('send.email.id');
    Route::get('/visitor-event/send-whatsapp/{id}', [VisitorEventController::class, 'sendWhatsappId'])->name('send.whatsapp.id');
    Route::post('/visitor-event/approval-visitor', [VisitorEventController::class, 'approval'])->name('approval.visitor');
    Route::post('/visitor-event/arrival', [VisitorEventController::class, 'storeArrival'])->name('visitor.arrival');
    Route::post('/visitor-event/check-approval', [VisitorEventController::class, 'checkApproval'])->name('check.approval');
    Route::get('/visitor-event/check-approval/{id}', [VisitorEventController::class, 'checkApprovalId'])->name('check.approval.id');

    // Master User
    Route::get('/master-user/{page?}', [MasterUserController::class, 'index'])->name('master_user.index');
    Route::get('/master-user/template-excel/{page?}', [MasterUserController::class, 'template_excel_master_user'])->name('template.excel.master.user');
    Route::post('/master-user/import-excel/{page?}', [MasterUserController::class, 'import_excel'])->name('import.excel.master.user');
    Route::post('/master-user/edit/{page?}/{id}', [MasterUserController::class, 'edit'])->name('edit-master-user');
    Route::post('/master-user/update-master-user', [MasterUserController::class, 'update'])->name('update-master-user');
    Route::delete('/master-user/delete-visitor/{id}', [MasterUserController::class, 'delete'])->name('delete-master-user');
    Route::post('/master-user/delete-multiple-master-user', [MasterUserController::class, 'deleteMultipleVisitors'])->name('delete-multiple-master-user');
    Route::post('/master-user/send-email', [MasterUserController::class, 'sendEmail'])->name('send.email.master.user');
    Route::post('/master-user/send-email/{id}', [MasterUserController::class, 'sendEmailId'])->name('send.email.id.master.user');
    Route::post('/master-user/send-whatsapp', [MasterUserController::class, 'sendWhatsapp'])->name('send.whatsapp.master.user');
    Route::post('/master-user/send-whatsapp/{id}', [MasterUserController::class, 'sendWhatsappId'])->name('send.whatsapp.id.master.user');
    Route::post('/master-user/list-event/{id}', [MasterUserController::class, 'listEvent'])->name('list_event');

    // Whatsapp Event
    Route::get('/whatsapp-event/{page?}', [WhatsappEventController::class, 'index'])->name('whatsapp_event.index');
    Route::get('/whatsapp-event/add-whatsapp-event/{page?}', [WhatsappEventController::class, 'add_index'])->name('add_whatsapp_event');
    Route::post('/whatsapp-event/add-whatsapp-event', [WhatsappEventController::class, 'add'])->name('add-whatsapp-event');
    Route::post('/whatsapp-event/update-whatsapp-event', [WhatsappEventController::class, 'update'])->name('update-whatsapp-event');
    Route::post('/whatsapp-event/edit-whatsapp-event/{page?}/{id}', [WhatsappEventController::class, 'edit'])->name('edit-whatsapp-event');

    // Email Event
    Route::get('/email-event/{page?}', [EmailEventController::class, 'index'])->name('email_event.index');
    Route::get('/email-event/add-email-event/{page?}', [EmailEventController::class, 'add_index'])->name('add_email_event');
    Route::post('/email-event/add-email-event', [EmailEventController::class, 'add'])->name('add-email-event');
    Route::post('/email-event/update-email-event', [EmailEventController::class, 'update'])->name('update-email-event');
    Route::post('/email-event/edit-email-event/{page?}/{id}', [EmailEventController::class, 'edit'])->name('edit-email-event');

    // Report Visitor
    Route::get('/report-visitor/{page?}', [ReportVisitorEventController::class, 'index'])->name('report_visitor_event.index');
    Route::get('/report-visitor/export-excel/{page?}', [ReportVisitorEventController::class, 'export_excel'])->name('export.excel.report.visitor');

    // Admin Event
    Route::get('/admin-event/{page?}', [AdminEventController::class, 'index'])->name('admin_event.index');
    Route::get('/admin-event/add-admin-event/{page?}', [AdminEventController::class, 'add_admin_index'])->name('add_admin_index');
    Route::post('/edit-admin/edit/{page?}/{id}', [AdminEventController::class, 'edit'])->name('edit-admin');
    Route::post('/add-admin', [AdminEventController::class, 'add'])->name('add-admin');
    Route::post('/update-admin', [AdminEventController::class, 'update'])->name('update-admin');
    Route::delete('/delete-admin/{id}', [AdminEventController::class, 'delete'])->name('delete-admin');
});

// // Dashboard
// Route::get('/dashboard-general-dashboard', function () {
//     return view('pages.dashboard-general-dashboard', ['type_menu' => 'dashboard']);
// });
// Route::get('/dashboard-ecommerce-dashboard', function () {
//     return view('pages.dashboard-ecommerce-dashboard', ['type_menu' => 'dashboard']);
// });


// // Layout
// Route::get('/layout-default-layout', function () {
//     return view('pages.layout-default-layout', ['type_menu' => 'layout']);
// });

// // Blank Page
// Route::get('/blank-page', function () {
//     return view('pages.blank-page', ['type_menu' => '']);
// });

// // Bootstrap
// Route::get('/bootstrap-alert', function () {
//     return view('pages.bootstrap-alert', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-badge', function () {
//     return view('pages.bootstrap-badge', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-breadcrumb', function () {
//     return view('pages.bootstrap-breadcrumb', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-buttons', function () {
//     return view('pages.bootstrap-buttons', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-card', function () {
//     return view('pages.bootstrap-card', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-carousel', function () {
//     return view('pages.bootstrap-carousel', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-collapse', function () {
//     return view('pages.bootstrap-collapse', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-dropdown', function () {
//     return view('pages.bootstrap-dropdown', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-form', function () {
//     return view('pages.bootstrap-form', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-list-group', function () {
//     return view('pages.bootstrap-list-group', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-media-object', function () {
//     return view('pages.bootstrap-media-object', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-modal', function () {
//     return view('pages.bootstrap-modal', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-nav', function () {
//     return view('pages.bootstrap-nav', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-navbar', function () {
//     return view('pages.bootstrap-navbar', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-pagination', function () {
//     return view('pages.bootstrap-pagination', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-popover', function () {
//     return view('pages.bootstrap-popover', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-progress', function () {
//     return view('pages.bootstrap-progress', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-table', function () {
//     return view('pages.bootstrap-table', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-tooltip', function () {
//     return view('pages.bootstrap-tooltip', ['type_menu' => 'bootstrap']);
// });
// Route::get('/bootstrap-typography', function () {
//     return view('pages.bootstrap-typography', ['type_menu' => 'bootstrap']);
// });


// // components
// Route::get('/components-article', function () {
//     return view('pages.components-article', ['type_menu' => 'components']);
// });
// Route::get('/components-avatar', function () {
//     return view('pages.components-avatar', ['type_menu' => 'components']);
// });
// Route::get('/components-chat-box', function () {
//     return view('pages.components-chat-box', ['type_menu' => 'components']);
// });
// Route::get('/components-empty-state', function () {
//     return view('pages.components-empty-state', ['type_menu' => 'components']);
// });
// Route::get('/components-gallery', function () {
//     return view('pages.components-gallery', ['type_menu' => 'components']);
// });
// Route::get('/components-hero', function () {
//     return view('pages.components-hero', ['type_menu' => 'components']);
// });
// Route::get('/components-multiple-upload', function () {
//     return view('pages.components-multiple-upload', ['type_menu' => 'components']);
// });
// Route::get('/components-pricing', function () {
//     return view('pages.components-pricing', ['type_menu' => 'components']);
// });
// Route::get('/components-statistic', function () {
//     return view('pages.components-statistic', ['type_menu' => 'components']);
// });
// Route::get('/components-tab', function () {
//     return view('pages.components-tab', ['type_menu' => 'components']);
// });
// Route::get('/components-table', function () {
//     return view('pages.components-table', ['type_menu' => 'components']);
// });
// Route::get('/components-user', function () {
//     return view('pages.components-user', ['type_menu' => 'components']);
// });
// Route::get('/components-wizard', function () {
//     return view('pages.components-wizard', ['type_menu' => 'components']);
// });

// // forms
// Route::get('/forms-advanced-form', function () {
//     return view('pages.forms-advanced-form', ['type_menu' => 'forms']);
// });
// Route::get('/forms-editor', function () {
//     return view('pages.forms-editor', ['type_menu' => 'forms']);
// });
// Route::get('/forms-validation', function () {
//     return view('pages.forms-validation', ['type_menu' => 'forms']);
// });

// // google maps
// // belum tersedia

// // modules
// Route::get('/modules-calendar', function () {
//     return view('pages.modules-calendar', ['type_menu' => 'modules']);
// });
// Route::get('/modules-chartjs', function () {
//     return view('pages.modules-chartjs', ['type_menu' => 'modules']);
// });
// Route::get('/modules-datatables', function () {
//     return view('pages.modules-datatables', ['type_menu' => 'modules']);
// });
// Route::get('/modules-flag', function () {
//     return view('pages.modules-flag', ['type_menu' => 'modules']);
// });
// Route::get('/modules-font-awesome', function () {
//     return view('pages.modules-font-awesome', ['type_menu' => 'modules']);
// });
// Route::get('/modules-ion-icons', function () {
//     return view('pages.modules-ion-icons', ['type_menu' => 'modules']);
// });
// Route::get('/modules-owl-carousel', function () {
//     return view('pages.modules-owl-carousel', ['type_menu' => 'modules']);
// });
// Route::get('/modules-sparkline', function () {
//     return view('pages.modules-sparkline', ['type_menu' => 'modules']);
// });
// Route::get('/modules-sweet-alert', function () {
//     return view('pages.modules-sweet-alert', ['type_menu' => 'modules']);
// });
// Route::get('/modules-toastr', function () {
//     return view('pages.modules-toastr', ['type_menu' => 'modules']);
// });
// Route::get('/modules-vector-map', function () {
//     return view('pages.modules-vector-map', ['type_menu' => 'modules']);
// });
// Route::get('/modules-weather-icon', function () {
//     return view('pages.modules-weather-icon', ['type_menu' => 'modules']);
// });

// // auth
// Route::get('/auth-forgot-password', function () {
//     return view('pages.auth-forgot-password', ['type_menu' => 'auth']);
// });
// Route::get('/auth-login', function () {
//     return view('pages.auth-login', ['type_menu' => 'auth']);
// });
// Route::get('/auth-login2', function () {
//     return view('pages.auth-login2', ['type_menu' => 'auth']);
// });
// Route::get('/auth-register', function () {
//     return view('pages.auth-register', ['type_menu' => 'auth']);
// });
// Route::get('/auth-reset-password', function () {
//     return view('pages.auth-reset-password', ['type_menu' => 'auth']);
// });

// // error
// Route::get('/error-403', function () {
//     return view('pages.error-403', ['type_menu' => 'error']);
// });
// Route::get('/error-404', function () {
//     return view('pages.error-404', ['type_menu' => 'error']);
// });
// Route::get('/error-500', function () {
//     return view('pages.error-500', ['type_menu' => 'error']);
// });
// Route::get('/error-503', function () {
//     return view('pages.error-503', ['type_menu' => 'error']);
// });

// // features
// Route::get('/features-activities', function () {
//     return view('pages.features-activities', ['type_menu' => 'features']);
// });
// Route::get('/features-post-create', function () {
//     return view('pages.features-post-create', ['type_menu' => 'features']);
// });
// Route::get('/features-post', function () {
//     return view('pages.features-post', ['type_menu' => 'features']);
// });
// Route::get('/features-profile', function () {
//     return view('pages.features-profile', ['type_menu' => 'features']);
// });
// Route::get('/features-settings', function () {
//     return view('pages.features-settings', ['type_menu' => 'features']);
// });
// Route::get('/features-setting-detail', function () {
//     return view('pages.features-setting-detail', ['type_menu' => 'features']);
// });
// Route::get('/features-tickets', function () {
//     return view('pages.features-tickets', ['type_menu' => 'features']);
// });

// // utilities
// Route::get('/utilities-contact', function () {
//     return view('pages.utilities-contact', ['type_menu' => 'utilities']);
// });
// Route::get('/utilities-invoice', function () {
//     return view('pages.utilities-invoice', ['type_menu' => 'utilities']);
// });
// Route::get('/utilities-subscribe', function () {
//     return view('pages.utilities-subscribe', ['type_menu' => 'utilities']);
// });

// // credits
// Route::get('/credits', function () {
//     return view('pages.credits', ['type_menu' => '']);
// });
