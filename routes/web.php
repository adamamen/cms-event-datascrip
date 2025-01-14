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
use App\Http\Controllers\UserAccessController;

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

    // User Access
    Route::get('/user-access/{page?}', [UserAccessController::class, 'index'])->name('user_access.index');
    Route::get('/user-access/add-user-access/{page?}', [UserAccessController::class, 'add_user_access_index'])->name('add_user_access_index');
    Route::post('/add-user-access', [UserAccessController::class, 'add'])->name('add-user-access');
    Route::post('/fetch-division-owners', [UserAccessController::class, 'fetchDivisionOwners'])->name('fetch-division-owners');
    Route::post('/user-access/edit-user-access/{page}/{id}/{id_divisi}', [UserAccessController::class, 'edit'])->name('edit-user-access');
    Route::post('/user-access/update-user-access', [UserAccessController::class, 'update'])->name('update-user-access');
    Route::get('/user-access/view-user-access/{page}/{id}/{id_divisi}', [UserAccessController::class, 'view'])->name('view-user-access');
    Route::delete('/user-access/delete-user-access/{id}', [UserAccessController::class, 'delete'])->name('delete-user-access');
    Route::get('/user-access/export-excel/{page?}', [UserAccessController::class, 'export_excel'])->name('export.excel.user.access');
});
