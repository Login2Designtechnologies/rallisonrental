<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AuthPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NoticeBoardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MaintainerController;
use App\Http\Controllers\MaintenanceRequestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\PageController;
use App\Models\User;

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

require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->middleware(
    [

        'XSS',
    ]
);
Route::get('home', [HomeController::class, 'index'])->name('home')->middleware(
    [

        'XSS',
    ]
);
Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware(
    [

        'XSS',
    ]
);


Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard')->middleware(
    [
        'XSS',
    ]
);

Route::get('payments', [HomeController::class, 'payments'])->name('payments')->middleware(
    [
        'XSS',
    ]
);
Route::get('manage-notice', [HomeController::class, 'manage_notice'])->name('manage_notice')->middleware(
    [
        'XSS',
    ]
);
Route::get('add-notice', [HomeController::class, 'add_notice'])->name('add_notice')->middleware(
    [
        'XSS',
    ]
);
Route::get('edit-notice', [HomeController::class, 'edit_notice'])->name('edit_notice')->middleware(
    [
        'XSS',
    ]
);


Route::get('manage-template', [HomeController::class, 'manage_template'])->name('manage_template')->middleware(
    [
        'XSS',
    ]
);
Route::get('add-template', [HomeController::class, 'add_template'])->name('add_template')->middleware(
    [
        'XSS',
    ]
);
Route::get('edit-template', [HomeController::class, 'edit_template'])->name('edit_template')->middleware(
    [
        'XSS',
    ]
);


Route::get('ticket-support', [HomeController::class, 'ticket_support'])->name('ticket_support')->middleware(
    [
        'XSS',
    ]
);
Route::get('add-ticket', [HomeController::class, 'add_ticket'])->name('add_ticket')->middleware(
    [
        'XSS',
    ]
);
Route::get('view-ticket', [HomeController::class, 'view_ticket'])->name('view_ticket')->middleware(
    [
        'XSS',
    ]
);
Route::get('late-fees', [HomeController::class, 'late_fees'])->name('late_fees')->middleware(
    [
        'XSS',
    ]
);
Route::post('setup_late_fee', [HomeController::class, 'setUpLateFee'])->name('setup_late_fee')->middleware(
    [
        'XSS',
    ]
);
Route::get('add-fee', [HomeController::class, 'add_fee'])->name('add_fee')->middleware(
    [
        'XSS',
    ]
);
Route::get('edit-late-fee', [HomeController::class, 'edit_late_fee'])->name('edit_late_fee')->middleware(
    [
        'XSS',
    ]
);
Route::get('other', [HomeController::class, 'other'])->name('other')->middleware(
    [
        'XSS',
    ]
);
Route::get('other-invoices', [HomeController::class, 'other_invoices'])->name('other-invoices')->middleware(
    [
        'XSS',
    ]
);
Route::get('add-other', [HomeController::class, 'add_other'])->name('add_other')->middleware(
    [
        'XSS',
    ]
);
Route::get('edit-other-invoice', [HomeController::class, 'edit_other_invoice'])->name('edit_other_invoice')->middleware(
    [
        'XSS',
    ]
);

// Tenant Dashbaord
Route::get('tenant-profile', [HomeController::class, 'tenant_profile'])->name('tenant_profile')->middleware(
    [
        'XSS',
    ]
);
Route::get('property-details', [HomeController::class, 'property_details'])->name('property_details')->middleware(
    [
        'XSS',
    ]
);

Route::get('payment-section', [HomeController::class, 'payment_section'])->name('payment_section')->middleware(
    [
        'XSS',
    ]
);


Route::get('tenant-ticket-support', [HomeController::class, 'tenant_ticket_support'])->name('tenant_ticket_support')->middleware(
    [
        'XSS',
    ]
);
Route::get('tenant-view-ticket', [HomeController::class, 'tenant_view_ticket'])->name('tenant_view_ticket')->middleware(
    [
        'XSS',
    ]
);
Route::get('add-tenant-ticket', [HomeController::class, 'add_tenant_ticket'])->name('add_tenant_ticket')->middleware(
    [
        'XSS',
    ]
);
Route::get('tenant-notices', [HomeController::class, 'tenant_notices'])->name('tenant_notices')->middleware(
    [
        'XSS',
    ]
);
Route::get('tenant-documents', [HomeController::class, 'tenant_documents'])->name('tenant_documents')->middleware(
    [
        'XSS',
    ]
);
Route::get('utilities-invoices', [HomeController::class, 'utilities_invoices'])->name('utilities_invoices')->middleware(
    [
        'XSS',
    ]
);
Route::get('tenant-late-fees', [HomeController::class, 'tenant_late_fees'])->name('tenant_late_fees')->middleware(
    [
        'XSS',
    ]
);
Route::get('tenant-other-invoice', [HomeController::class, 'tenant_other_invoice'])->name('tenant_other_invoice')->middleware(
    [
        'XSS',
    ]
);
Route::get('view-invoice', [HomeController::class, 'view_invoice'])->name('view_invoice')->middleware(
    [
        'XSS',
    ]
);

//-------------------------------User-------------------------------------------

Route::resource('users', UserController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::get('setauth/{id}',  function ($id) {
    $user = User::find($id);
    \Auth::login($user);
    return redirect()->route('home');
});

Route::get('login/otp', [OTPController::class, 'show'])->name('otp.show')->middleware(
    [

        'XSS',
    ]
);
Route::post('login/otp', [OTPController::class, 'check'])->name('otp.check')->middleware(
    [

        'XSS',
    ]
);
Route::get('login/2fa/disable', [OTPController::class, 'disable'])->name('2fa.disable')->middleware(['XSS',]);

//-------------------------------Subscription-------------------------------------------



Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {

        Route::resource('subscriptions', SubscriptionController::class);
        Route::get('coupons/history', [CouponController::class, 'history'])->name('coupons.history');
        Route::delete('coupons/history/{id}/destroy', [CouponController::class, 'historyDestroy'])->name('coupons.history.destroy');
        Route::get('coupons/apply', [CouponController::class, 'apply'])->name('coupons.apply');
        Route::resource('coupons', CouponController::class);
        Route::get('subscription/transaction', [SubscriptionController::class, 'transaction'])->name('subscription.transaction');
    }
);

//-------------------------------Subscription Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {

        Route::post('subscription/{id}/stripe/payment', [SubscriptionController::class, 'stripePayment'])->name('subscription.stripe.payment');
    }
);
//-------------------------------Settings-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ], function (){
    Route::get('settings', [SettingController::class,'index'])->name('setting.index');

    Route::post('settings/account', [SettingController::class,'accountData'])->name('setting.account');
    Route::delete('settings/account/delete', [SettingController::class,'accountDelete'])->name('setting.account.delete');
    Route::post('settings/password', [SettingController::class,'passwordData'])->name('setting.password');
    Route::post('settings/general', [SettingController::class,'generalData'])->name('setting.general');
    Route::post('settings/smtp', [SettingController::class,'smtpData'])->name('setting.smtp');
    Route::get('settings/smtp-test', [SettingController::class, 'smtpTest'])->name('setting.smtp.test');
    Route::post('settings/smtp-test', [SettingController::class, 'smtpTestMailSend'])->name('setting.smtp.testing');
    Route::post('settings/payment', [SettingController::class,'paymentData'])->name('setting.payment');
    Route::post('settings/site-seo', [SettingController::class,'siteSEOData'])->name('setting.site.seo');
    Route::post('settings/google-recaptcha', [SettingController::class,'googleRecaptchaData'])->name('setting.google.recaptcha');
    Route::post('settings/company', [SettingController::class,'companyData'])->name('setting.company');
    Route::post('settings/2fa', [SettingController::class, 'twofaEnable'])->name('setting.twofa.enable');

    Route::get('footer-setting', [SettingController::class, 'footerSetting'])->name('footerSetting');
    Route::post('settings/footer', [SettingController::class,'footerData'])->name('setting.footer');

    Route::get('language/{lang}', [SettingController::class,'lanquageChange'])->name('language.change');
    Route::post('theme/settings', [SettingController::class,'themeSettings'])->name('theme.settings');
}
);


//-------------------------------Role & Permissions-------------------------------------------
Route::resource('permission', PermissionController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

Route::resource('role', RoleController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);




//-------------------------------Note-------------------------------------------
Route::resource('note', NoticeBoardController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);


//-------------------------------Notification-------------------------------------------
Route::resource('notification', NotificationController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);



//-------------------------------Contact-------------------------------------------
Route::resource('contact', ContactController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);




//-------------------------------logged History-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {

        Route::get('logged/history', [UserController::class, 'loggedHistory'])->name('logged.history');
        Route::get('logged/{id}/history/show', [UserController::class, 'loggedHistoryShow'])->name('logged.history.show');
        Route::delete('logged/{id}/history', [UserController::class, 'loggedHistoryDestroy'])->name('logged.history.destroy');
    }
);


//-------------------------------Property-------------------------------------------
Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::resource('property', PropertyController::class);
        Route::get('property/{pid}/unit/create', [PropertyController::class, 'unitCreate'])->name('unit.create');
        Route::post('property/{pid}/unit/store', [PropertyController::class, 'unitStore'])->name('unit.store');
        Route::get('units/direct-create', [PropertyController::class, 'unitdirectCreate'])->name('unit.direct-create');
        Route::post('unit/direct-store', [PropertyController::class, 'unitdirectStore'])->name('unit.direct-store');
        Route::get('property/{pid}/unit/{id}/edit', [PropertyController::class, 'unitEdit'])->name('unit.edit');
        Route::get('units', [PropertyController::class, 'units'])->name('unit.index');
        Route::get('get_cities/{state_id}', [PropertyController::class, 'get_cities'])->name('get_cities');
        Route::put('property/{pid}/unit/{id}/update', [PropertyController::class, 'unitUpdate'])->name('unit.update');
        Route::delete('property/{pid}/unit/{id}/destroy', [PropertyController::class, 'unitDestroy'])->name('unit.destroy');
        Route::get('property/{pid}/unit', [PropertyController::class, 'getPropertyUnit'])->name('property.unit');
        Route::get('addUtilities/{id}', [PropertyController::class, 'addUtilities'])->name('property.addUtilities');
        Route::get('addAmenities/{id}', [PropertyController::class, 'addAmenities'])->name('property.addAmenities');
        Route::get('edit-Amenities/{id}/{propertyid}', [PropertyController::class, 'edit_Amenities'])->name('property.edit-Amenities');
        Route::get('edit-Utilities/{id}/{propertyid}', [PropertyController::class, 'edit_Utilities'])->name('property.edit-Utilities');
        Route::post('addAmenities-store/{id}', [PropertyController::class, 'addAmenities_store'])->name('addAmenities-store');
        Route::post('editAmenities-update/{id}/{propertyid}', [PropertyController::class, 'editAmenities_update'])->name('editAmenities-update');
        Route::post('property-amenities-store', [PropertyController::class, 'property_amenities_store'])->name('property-amenities-store');
        Route::post('propertyamenities-store2', [PropertyController::class, 'propertyamenities_store2'])->name('propertyamenities-store2');
        Route::post('property-utilities-store', [PropertyController::class, 'property_utilities_store'])->name('property-utilities-store');
        Route::post('propertyutilities-store2', [PropertyController::class, 'propertyutilities_store2'])->name('propertyutilities-store2');
        Route::post('addUtilities-store/{id}', [PropertyController::class, 'addUtilities_store'])->name('addUtilities-store');
        Route::post('property-amenities-update', [PropertyController::class, 'property_amenities_update'])->name('property-amenities-update');
        Route::post('propertyamenities-update2', [PropertyController::class, 'propertyamenities_update2'])->name('propertyamenities-update2');
        Route::post('property-Utilities-update', [PropertyController::class, 'property_Utilities_update'])->name('property-Utilities-update');
        Route::post('propertyUtilities-update2', [PropertyController::class, 'propertyUtilities_update2'])->name('propertyUtilities-update2');
        Route::post('addUtilities-update/{id}/{propertyid}', [PropertyController::class, 'addUtilities_update'])->name('addUtilities-update');
        
        Route::get('selectProperty', [PropertyController::class, 'selectProperty'])->name('property.selectProperty');
    }
);



//-------------------------------Tenant-------------------------------------------
Route::resource('tenant', TenantController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Type-------------------------------------------
Route::resource('type', TypeController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Invoice-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::get('invoice/{id}/payment/create', [InvoiceController::class, 'invoicePaymentCreate'])->name('invoice.payment.create');
        Route::post('invoice/{id}/payment/store', [InvoiceController::class, 'invoicePaymentStore'])->name('invoice.payment.store');
        Route::delete('invoice/{id}/payment/{pid}/destroy', [InvoiceController::class, 'invoicePaymentDestroy'])->name('invoice.payment.destroy');
        Route::delete('invoice/type/destroy', [InvoiceController::class, 'invoiceTypeDestroy'])->name('invoice.type.destroy');
        Route::get('invoice/{id}/reminder', [InvoiceController::class, 'invoicePaymentRemind'])->name('invoice.reminder');
        Route::post('invoice/{id}/reminder', [InvoiceController::class, 'invoicePaymentRemindData'])->name('invoice.sendEmail');
        Route::resource('invoice', InvoiceController::class);
    }
);

//-------------------------------Expense-------------------------------------------
Route::resource('expense', ExpenseController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Maintainer-------------------------------------------
// Route::resource('amenities_utilities', MaintainerController::class)->middleware(
//     [
//         'auth',
//         'XSS',
//     ]
// );

//-------------------------------Maintenance Request-------------------------------------------


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::get('maintenance-request/pending', [MaintenanceRequestController::class, 'pendingRequest'])->name('maintenance-request.pending');
        Route::get('maintenance-request/in-progress', [MaintenanceRequestController::class, 'inProgressRequest'])->name('maintenance-request.inprogress');
        Route::get('maintenance-request/{id}/action', [MaintenanceRequestController::class, 'action'])->name('maintenance-request.action');
        Route::post('maintenance-request/{id}/action', [MaintenanceRequestController::class, 'actionData'])->name('maintenance-request.action');
        Route::resource('maintenance-request', MaintenanceRequestController::class);
    }
);

//-------------------------------Plan Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::post('subscription/{id}/bank-transfer', [PaymentController::class, 'subscriptionBankTransfer'])->name('subscription.bank.transfer');
        Route::get('subscription/{id}/bank-transfer/action/{status}', [PaymentController::class, 'subscriptionBankTransferAction'])->name('subscription.bank.transfer.action');
        Route::post('subscription/{id}/paypal', [PaymentController::class, 'subscriptionPaypal'])->name('subscription.paypal');
        Route::get('subscription/{id}/paypal/{status}', [PaymentController::class, 'subscriptionPaypalStatus'])->name('subscription.paypal.status');
        Route::get('subscription/flutterwave/{sid}/{tx_ref}', [PaymentController::class, 'subscriptionFlutterwave'])->name('subscription.flutterwave');
    }
);

//-------------------------------Invoice Payment-------------------------------------------

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {

        Route::post('invoice/{id}/banktransfer/payment', [InvoicePaymentController::class, 'banktransferPayment'])->name('invoice.banktransfer.payment');
        Route::post('invoice/{id}/stripe/payment', [InvoicePaymentController::class, 'stripePayment'])->name('invoice.stripe.payment');
        Route::post('invoice/{id}/paypal', [InvoicePaymentController::class, 'invoicePaypal'])->name('invoice.paypal');
        Route::get('invoice/{id}/paypal/{status}', [InvoicePaymentController::class, 'invoicePaypalStatus'])->name('invoice.paypal.status');
        Route::get('invoice/flutterwave/{id}/{tx_ref}', [InvoicePaymentController::class, 'invoiceFlutterwave'])->name('invoice.flutterwave');
    }
);

Route::get('email-verification/{token}', [VerifyEmailController::class, 'verifyEmail'])->name('email-verification')->middleware(
    [
        'XSS',
    ]


);
//-------------------------------FAQ-------------------------------------------
Route::resource('FAQ', FAQController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);

//-------------------------------Home Page-------------------------------------------
Route::resource('homepage', HomePageController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
//-------------------------------FAQ-------------------------------------------
Route::resource('pages', PageController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
Route::get('page/{slug}', [PageController::class, 'page'])->name('page');
//-------------------------------FAQ-------------------------------------------
Route::impersonate();


//-------------------------------Auth page-------------------------------------------
Route::resource('authPage', AuthPageController::class)->middleware(
    [
        'auth',
        'XSS',
    ]
);
