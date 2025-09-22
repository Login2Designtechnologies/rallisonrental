<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Custom;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Maintainer;
use App\Models\MaintenanceRequest;
use App\Models\NoticeBoard;
use App\Models\PackageTransaction;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Subscription;
use App\Models\Support;
use App\Models\Tenant;
use App\Models\User;
use App\Models\FAQ;
use App\Models\Page;
use App\Models\HomePage;

use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        if (\Auth::check()) {
            if (\Auth::user()->type == 'super admin') {
                $result['totalOrganization'] = User::where('type', 'owner')->count();
                $result['totalSubscription'] = Subscription::count();
                $result['totalTransaction'] = PackageTransaction::count();
                $result['totalIncome'] = PackageTransaction::sum('amount');
                $result['totalNote'] = NoticeBoard::where('parent_id', parentId())->count();
                $result['totalContact'] = Contact::where('parent_id', parentId())->count();

                $result['organizationByMonth'] = $this->organizationByMonth();
                $result['paymentByMonth'] = $this->paymentByMonth();
                return view('dashboard.super_admin', compact('result'));
            } else {
                $result['totalNote'] = NoticeBoard::where('parent_id', parentId())->count();
                $result['totalContact'] = Contact::where('parent_id', parentId())->count();


                if (\Auth::user()->type == 'tenant') {
                    $tenant = Tenant::where('user_id', \Auth::user()->id)->first();
                    if (!empty($tenant)) {
                        $result['totalInvoice'] = Invoice::where('property_id', $tenant->property)->where('unit_id', $tenant->unit)->count();
                        $result['unit'] = PropertyUnit::find($tenant->unit);
                    } else {
                        $result['totalInvoice'] = 0;
                        $result['unit'] ='';
                    }


                    return view('dashboard.tenant', compact('result', 'tenant'));
                }

                if (\Auth::user()->type == 'maintainer') {
                    $maintainer = Maintainer::where('user_id', \Auth::user()->id)->first();
                    $result['totalRequest'] = MaintenanceRequest::where('maintainer_id', \Auth::user()->id)->count();
                    $result['todayRequest'] = MaintenanceRequest::whereDate('request_date', '=', date('Y-m-d'))->where('maintainer_id', \Auth::user()->id)->count();

                    return view('dashboard.maintainer', compact('result', 'maintainer'));
                }

                $result['totalProperty'] = Property::where('parent_id', parentId())->count();
                $result['totalUnit'] = PropertyUnit::where('parent_id', parentId())->count();
                $result['totalIncome'] = InvoicePayment::where('parent_id', parentId())->sum('amount');
                $result['totalExpense'] = Expense::where('parent_id', parentId())->sum('amount');
                $result['recentProperty'] = Property::where('parent_id', parentId())->orderby('id', 'desc')->limit(5)->get();
                $result['recentTenant'] = Tenant::where('parent_id', parentId())->orderby('id', 'desc')->limit(5)->get();
                $result['incomeExpenseByMonth'] = $this->incomeByMonth();
                $result['settings'] = settings();



                return view('dashboard.index', compact('result'));
            }
        } else {
            if (!file_exists(setup())) {
                header('location:install');
                die;
            } else {
                $landingPage = getSettingsValByName('landing_page');
                if ($landingPage == 'on') {
                    $subscriptions = Subscription::get();
                    $menus = Page::where('enabled', 1)->get();
                    $FAQs = FAQ::where('enabled', 1)->get();
                    return view('layouts.landing', compact('subscriptions', 'menus', 'FAQs'));
                } else {
                    return redirect()->route('login');
                }
            }
        }
    }

    public function organizationByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));

        $currentdate = $start;

        $organization = [];
        while ($currentdate <= $end) {
            $organization['label'][] = date('M-Y', $currentdate);

            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $organization['data'][] = User::where('type', 'owner')->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
            $currentdate = strtotime('+1 month', $currentdate);
        }


        return $organization;
    }

    public function paymentByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));

        $currentdate = $start;

        $payment = [];
        while ($currentdate <= $end) {
            $payment['label'][] = date('M-Y', $currentdate);

            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $payment['data'][] = PackageTransaction::whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
            $currentdate = strtotime('+1 month', $currentdate);
        }

        return $payment;
    }

    public function incomeByMonth()
    {
        $start = strtotime(date('Y-01'));
        $end = strtotime(date('Y-12'));

        $currentdate = $start;

        $payment = [];
        while ($currentdate <= $end) {
            $payment['label'][] = date('M-Y', $currentdate);

            $month = date('m', $currentdate);
            $year = date('Y', $currentdate);
            $payment['income'][] = InvoicePayment::where('parent_id', parentId())->whereMonth('payment_date', $month)->whereYear('payment_date', $year)->sum('amount');
            $payment['expense'][] = Expense::where('parent_id', parentId())->whereMonth('date', $month)->whereYear('date', $year)->sum('amount');
            $currentdate = strtotime('+1 month', $currentdate);
        }

        return $payment;
    }


    public function payments() {
        // dd("Hello");
        return View('dashbaordpage.payments');
    } 
    public function manage_notice() {
        // dd("Hello");
        return View('dashbaordpage.manage-notice');
    } 
    public function add_notice() {
        // dd("Hello");
        return View('dashbaordpage.add-notice');
    } 
    public function edit_notice() {
        // dd("Hello");
        return View('dashbaordpage.edit-notice');
    } 

    public function manage_template() {
        return View('dashbaordpage.manage-template');
    } 
    public function add_template() {
        return View('dashbaordpage.add-template');
    } 
    public function edit_template() {
        return View('dashbaordpage.edit-template');
    } 

    public function ticket_support() {
        return View('dashbaordpage.ticket-support');
    } 
     public function add_ticket() {
        return View('dashbaordpage.add-ticket');
    } 
     public function view_ticket() {
        return View('dashbaordpage.view-ticket');
    } 
    public function late_fees() {
        return View('dashbaordpage.late-fees');
    } 
    public function add_fee() {
        return View('dashbaordpage.add-fee');
    } 
    public function edit_late_fee() {
        return View('dashbaordpage.edit-late-fee');
    } 
    public function other() {
        return View('dashbaordpage.other');
    } 
    public function add_other() {
        return View('dashbaordpage.add-other');
    } 
    public function edit_other_invoice() {
        return View('dashbaordpage.edit-other-invoice');
    } 


    // Tenant Dashbaord
    public function tenant_profile() {
        return View('tenant_dashboard.tenant-profile');
    } 

    public function property_details() {
        return View('tenant_dashboard.property-details');
    } 
    public function payment_section() {
        return View('tenant_dashboard.payment-section');
    } 
    
    public function tenant_ticket_support() {
        return View('tenant_dashboard.tenant-ticket-support');
    } 
    public function tenant_view_ticket() {
        return View('tenant_dashboard.tenant-view-ticket');
    } 
    public function add_tenant_ticket() {
        return View('tenant_dashboard.add-tenant-ticket');
    } 
    public function tenant_notices() {
        return View('tenant_dashboard.tenant-notices');
    } 
    public function tenant_documents() {
        return View('tenant_dashboard.tenant-documents');
    } 
    public function utilities_invoices() {
        return View('tenant_dashboard.utilities-invoices');
    }
    public function tenant_late_fees() {
        return View('tenant_dashboard.tenant-late-fees');
    }
    public function tenant_other_invoice() {
        return View('tenant_dashboard.tenant-other-invoice');
    }
     public function view_invoice() {
        return View('tenant_dashboard.view-invoice');
    }


}
