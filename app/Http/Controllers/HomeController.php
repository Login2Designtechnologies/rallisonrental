<?php

namespace App\Http\Controllers;

use App\Mail\OtherInvoiceMail;
use App\Mail\OtherInvoiceRemovedMail;
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
use Auth;
use App\Models\OtherInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\TenantDocument;
use App\Models\UtilityInvoice;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Ticket;
use App\Models\TenantContract;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Support\Str;

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

                    $contract = TenantContract::where('tenant_id', Auth::user()->tenants->id)
                        ->with('renewals')
                        ->first();
                    
                    // For Next Payment Date
                    $nextPaymentDate = null;
                    $rentAmount = 0;

                    if ($contract && $contract->start_date) {
                        $startDate = Carbon::parse($contract->start_date);
                        $today = Carbon::today();

                        $nextPaymentDate = $startDate->copy();
                        while ($nextPaymentDate->lessThanOrEqualTo($today)) {
                            $nextPaymentDate->addMonth();
                        }

                        $rentAmount = $contract->standard_rent ?? 0;
                    }
                    $result['nextPaymentDate'] = $nextPaymentDate;
                    $result['rentAmount'] = $rentAmount;

                    $result['utilitiesDue'] = UtilityInvoice::where('tenant_id', $tenant->id)
                        ->where('status', '!=', 'paid')
                        ->sum('amount');

                    $pastDueAmount = 0;

                    if ($contract) {
                        $dueDay = $contract->invoice_due_date ?? 1;
                        $standardRent = $contract->standard_rent ?? 0;

                        // Current month's due date
                        $dueDate = Carbon::now()->day($dueDay);

                        // If due date already passed in this month → mark as past due
                        if ($dueDate->isPast()) {
                            $pastDueAmount = $standardRent;
                        }

                        // Optional: If contract end date exists, ensure it’s still active
                        if ($contract->end_date && Carbon::now()->greaterThan($contract->end_date)) {
                            $pastDueAmount = 0;
                        }
                    }
                    $result['pastDueAmount'] = $pastDueAmount;
                    return view( 'dashboard.tenant', compact('result', 'tenant'));
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
				
				$ownerId = auth()->id();
                $today = Carbon::today();

                // Fetch all invoices for this owner (excluding draft/cancelled)
                $invoices = Invoice::with(['types', 'payments'])
                    ->where('parent_id', $ownerId)
                    ->whereNotIn('status', ['draft','cancelled'])
                    ->get();

                $currentDue = 0;
                $pastDue    = 0;

                foreach ($invoices as $invoice) {
                    $itemsTotal   = $invoice->types->sum('amount');        // from invoice_items
                    $paymentsMade = $invoice->payments->sum('amount');     // from invoice_payments
                    $outstanding  = max(0, $itemsTotal - $paymentsMade);

                    if ($outstanding > 0) {
                        if ($invoice->due_date && Carbon::parse($invoice->due_date)->lt($today)) {
                            $pastDue += $outstanding;     // overdue
                        } else {
                            $currentDue += $outstanding;  // still due
                        }
                    }
                }
                $result['currentDue'] = $currentDue;
                $result['pastDue'] = $pastDue;
                
                // Utilities Due (delivered but not paid, due date in future or today)
                $result['utilitiesDue'] = UtilityInvoice::where('owner_id', $ownerId)
                    ->whereIn('status', ['delivered']) // still unpaid
                    ->whereDate('due_date', '>=', $today)
                    ->sum('amount');

                // Utilities Past Due (overdue)
                $result['utilitiesPastDue'] = UtilityInvoice::where('owner_id', $ownerId)
                    ->whereIn('status', ['delivered', 'overdue']) // unpaid or marked overdue
                    ->whereDate('due_date', '<', $today)
                    ->sum('amount');


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
        $propertiesdata = DB::table('properties')->where('is_active','1')->where('parent_id',Auth::user()->id)->get();
        $data = [
           'propertiesdata' => $propertiesdata,
        ];
        return View('payments.index',$data);
    } 

    public function payments_search(Request $request) {

        $propertiesdata = DB::table('properties')->where('is_active','1')->where('parent_id',Auth::user()->id)->get();
        $propertiesearch = DB::table('properties')->where('id',$request->property)->where('parent_id',Auth::user()->id)->first();
        $tenantssearch = DB::table('tenants')->where('property_id',$request->property)->where('parent_id',Auth::user()->id)->get();

        $data = [
           'propertiesdata' => $propertiesdata,
           'propertiesearch' => $propertiesearch,
           'tenantssearch' => $tenantssearch,
        ];

        return View('payments.index',$data);
    } 
    

    public function ticket_support() {
        $userscheck = DB::table('users')->where('id', Auth::user()->id)->first();
        $tenantscheck = DB::table('tenants')->where('parent_id', $userscheck->id)->first();
        $ticketsall = DB::table('tickets')->where('tenant_id',$tenantscheck->user_id)->get();
        return View('owner_ticket_support.index',compact('ticketsall'));
    } 

    public function view_ticket($id) {
        $ticketalldata = DB::table('tickets')->where('id',$id)->first();
        $useralldata = DB::table('users')->where('id',$ticketalldata->tenant_id)->first();
        $ticketsupportall = DB::table('ticket_support')->where('ticket_id',$id)->get();
        return View('owner_ticket_support.view-ticket',compact('ticketalldata','useralldata','ticketsupportall'));
    } 

    public function ownerviewticket_store(Request $request)
    {
        $request->validate([
            'photo'=> 'required',
            'description'=> 'required',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time().'_'.$file->getClientOriginalName();

            $destinationPath = storage_path('upload/tickets');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, $fileName);

            // Save relative path in DB
            $photoPath = $fileName;
        }
    
        $randomticketId3 = '#' . time() . rand(1000, 9999);

        DB::table('ticket_support')->insert([
            'random_id' => $randomticketId3,
            'ticket_id' => $request->ticket_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status, 
            'photo' => $photoPath,
            'tenant_id' => Auth::user()->id, 
            'owner_id' => Auth::user()->id, 
            'property_id' => $request->property_id ?? null,
        ]);

        $ticketupdate = [
           'status' => $request->status,
        ];

        DB::table('tickets')->where('tenant_id',$request->tenant_id)->where('id',$request->id)->update($ticketupdate);

        return redirect('view-ticket/'.$request->id)->with('success', 'Ticket submitted successfully!');
    }

    public function add_ticket() {
        return View('dashbaordpage.add-ticket');
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

    public function other(Request $request)
    {
        $query = OtherInvoice::with(['property', 'tenant.user'])
            ->where('owner_id', Auth::user()->id);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%$search%")
                ->orWhere('subject', 'like', "%$search%")
                ->orWhere('terms', 'like', "%$search%")
                ->orWhereHas('tenant.user', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
                })
                ->orWhereHas('property', function ($q3) use ($search) {
                    $q3->where('name', 'like', "%$search%");
                });
            });
        }

        $otherInvoices = $query->latest()->get();

        if ($request->ajax()) {
            return view('dashbaordpage.partials.other_table', compact('otherInvoices'))->render();
        }

        return view('dashbaordpage.other', compact('otherInvoices'));
    } 
	
    public function add_other() {
        // Generate unique invoice number server-side
        $invoice_no = OtherInvoice::generateInvoiceNo();

        // Fetch properties and tenants dynamically
        $properties = Property::all();
        $tenants = Tenant::with('user')->get();  

        return view('dashbaordpage.add-other', compact('invoice_no', 'properties', 'tenants'));
    } 
	
	public function store_other_invoice(Request $request)
    {
        $request->validate([
            'invoice_no'  => 'required|unique:other_invoices,invoice_no',
            'property_id' => 'required|exists:properties,id',
            'tenant_id'   => 'required|exists:tenants,id',
            'invoice_date'=> 'required|date',
            'due_date'    => 'required|date',
            'subject'     => 'required|string',
            'items'       => 'required|array|min:1',
            'items.*.detail' => 'required|string',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

         $invoice = OtherInvoice::create([
            'invoice_no' => $request->invoice_no,
            'property_id' => $request->property_id,
            'owner_id' => Auth::user()->id,
            'tenant_id' => $request->tenant_id,
            'subject' => $request->subject,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'terms' => $request->terms,
            'status' => 'draft',
            'amount' => collect($request->items)->sum('amount')
        ]);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'item'       => $item['detail'],
                'qty'        => 1, // Assuming quantity is always 1
                'price'      => $item['amount'],
                'line_total' => $item['amount'], // Since qty is 1, line_total is just the amount
            ]);
        }
        return redirect()->route('other')->with('preview_invoice_id', $invoice->id);
    }
	
	public function emailPreview(OtherInvoice $otherInvoice)
    {
        $otherInvoice->load(['tenant', 'owner', 'property', 'items']);

        return view('email.other_invoice', [
            'otherInvoice' => $otherInvoice,
            'isPreview' => true // flag to show buttons in preview only
        ]);
    }

    public function sendEmail(OtherInvoice $otherInvoice)
    {
        // Mail::to($invoice->tenant->email)->send(new \App\Mail\OtherInvoiceMail($invoice));
        try {
            $otherInvoice->load(['tenant', 'owner', 'property', 'items']);

            Mail::to($otherInvoice->tenant->user->email)
                ->send(new OtherInvoiceMail($otherInvoice));

            return redirect()->route('other')
                ->with('success', 'Invoice sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error sending invoice: ' . $e->getMessage());
        }

    }

    public function remove_other_invoice(Request $request, $id)
    {
        $otherInvoice = OtherInvoice::with('tenant.user')->findOrFail($id);
        $message = $request->input('removal_message');

        // Send mail to tenant
        if ($otherInvoice->tenant && $otherInvoice->tenant->user && $otherInvoice->tenant->user->email) {
            Mail::to($otherInvoice->tenant->user->email)->send(new OtherInvoiceRemovedMail($otherInvoice, $message));            
        }

        // Delete invoice and its items
        DB::transaction(function () use ($otherInvoice) {
            $otherInvoice->items()->delete();
            $otherInvoice->delete();
        });

        return redirect()->route('other')->with('success', 'Invoice removed and notification sent to tenant.');
    }

    public function payNow(OtherInvoice $invoice)
    {
        return response()->json('Payment gateway integration pending');
    }    
	
    public function edit_other_invoice($id) {
        $otherInvoice = OtherInvoice::with('items')->findOrFail($id);
        $properties = Property::where('parent_id', Auth::id())->get();
        $tenants = Tenant::where('parent_id', Auth::id())->get();

        return View('dashbaordpage.edit-other-invoice', compact('otherInvoice', 'properties', 'tenants'));
    } 

    public function update_other_invoice(Request $request, $id)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'terms' => 'required|string',
            'property_id' => 'required|integer',
            'tenant_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0.01',
        ]);
        $otherInvoice = OtherInvoice::findOrFail($id);

        $otherInvoice->update([
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'terms' => $request->terms,
            'property_id' => $request->property_id,
            'tenant_id' => $request->tenant_id,
            'subject' => $request->subject,
            'amount' => collect($request->items)->sum('price')
        ]);

        $otherInvoice->items()->delete();

        foreach ($request->items as $item) {
            $otherInvoice->items()->create([
                'item' => $item['item'],
                'qty' => 1,
                'price' => $item['price'],
                'line_total' => $item['price'],
            ]);
        }

        return redirect()->route('other')->with('success', 'Invoice updated successfully.');
    }

    public function view_payment() {
        return View('dashbaordpage.view-payment');
    } 
    public function make_payment() {
        return View('dashbaordpage.make-payment');
    } 

    public function downloadInvoice(OtherInvoice $otherInvoice)
    {
        // Load related data
        $otherInvoice->load(['tenant', 'owner', 'property', 'items']);

        // Generate the PDF using the same Blade view
        $pdf = Pdf::loadView('email.other_invoice', [
            'otherInvoice' => $otherInvoice,
            'isPreview' => false // Hide buttons when downloading
        ]);

        // Define file name
        $fileName = 'Invoice_' . $otherInvoice->id . '.pdf';

        // Return the PDF as a download
        return $pdf->download($fileName);
    }

    // Tenant Dashbaord
    public function tenant_profile() {
        $auth_tenant = Tenant::whereUserId(Auth::user()->id)->with(['user', 'documents'])->first();
        return View('tenant_dashboard.tenant-profile', compact('auth_tenant'));
    } 
	
	public function update(Request $request, Tenant $tenant)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $tenant->user->id,
            'phone_number' => 'required|string|max:20',
			'address' => 'nullable|string|max:255',
            'emergency_phone_number' => 'required|string|max:20',
            'emergency_contact_name' => 'required|string|max:100',
            'emergency_contact_relationship' => 'required|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'personal_document' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:4096',
            'ic_document' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:4096',
            'miscellaneous' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $data = $request->all();
        if (!empty($data['full_name'])) {
            $parts = preg_split('/\s+/', trim($data['full_name']), -1, PREG_SPLIT_NO_EMPTY);
            $firstName = $parts[0] ?? '';
            $lastName  = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
        } else {
            $firstName = $tenant->user->first_name;
            $lastName  = $tenant->user->last_name;
        }

        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $file = $request->file('profile_image');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('upload/profile', $filename);

            $tenant->user->profile = $filename;
        }

        // Documents (3 types)
        $docFields = [
            'personal_document',
            'ic_document',
            'miscellaneous',
        ];

        foreach ($docFields as $field) {
            if ($request->hasFile($field)) {
                $request->validate([
                    $field => 'file|mimes:jpeg,png,jpg,pdf,doc,docx|max:4096',
                ]);

                $file = $request->file($field);
                $filename = time().'_'.$file->getClientOriginalName();
                $file->storeAs('upload/tenantdocument', $filename);

                // Assign to user model
                $tenant->user->{$field} = $filename;
            }
        }
        
        // Example mapping (match your DB fields)
        $tenant->user->update([
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email' => $data['email'] ?? $tenant->user->email,
            'phone_number' => $data['phone_number'] ?? $tenant->user->phone_number,
            'emergency_phone_number' => $data['emergency_phone_number'] ?? $tenant->user->emergency_phone_number,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? $tenant->user->emergency_contact_name,
            'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? $tenant->user->emergency_contact_relationship,
        ]);
		
		$tenant->update([
            'address' => $data['address'] ?? $tenant->address,
			'payment_method' => $data['payment_method'] ?? $tenant->payment_method,
        ]);

        return response()->json(['success' => true]);
    }

    public function property_details() {
		$property = Tenant::where('user_id', Auth::user()->id)
            ->with(['properties', 'units', 'properties.city', 'properties.state'])
            ->first();

        return View('tenant_dashboard.property-details', compact('property'));
    } 
	
	public function payment_section() {
        // Fetch the tenant contract
        $contract = TenantContract::where('tenant_id', Auth::user()->tenants->id)
            ->with('renewals')
            ->first();
                
        if (!$contract) {
            return view('tenant_dashboard.payment-section', ['payments' => []]);
        }

        $startDate = Carbon::parse($contract->start_date)->startOfMonth();
        $endDate = Carbon::parse($contract->end_date)->startOfMonth();

        $months = [];

        // Base contract months
        $monthCounter = 1;
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $months[] = [
                'month_number' => $monthCounter,
				'month' => $current->format('F-Y'),
                'rent' => $contract->standard_rent,
                'security' => $contract->security_deposit,
                'last_month_rent' => $contract->standard_rent,
                'amenities' => 110.00,
                'status' => 'Pending'
            ];
            $monthCounter++;
            $current->addMonth();
        }

        // Renewal months (numeric start/end like 13, 20, etc.)
        foreach ($contract->renewals as $renewal) {
            $renewalStartMonth = (int)$renewal->start_month;
            $renewalEndMonth = (int)$renewal->end_month;

            // Calculate real calendar month for start of renewal
            $renewalStartDate = Carbon::parse($contract->start_date)->addMonths($renewalStartMonth - 1)->startOfMonth();
            $renewalEndDate = Carbon::parse($contract->start_date)->addMonths($renewalEndMonth - 1)->startOfMonth();

            $renewalCurrent = $renewalStartDate->copy();
            $counter = $renewalStartMonth;

            while ($renewalCurrent <= $renewalEndDate) {
                $months[] = [
                    'month_number' => $counter,
					'month' => $renewalCurrent->format('F-Y'),					
                    'rent' => $contract->standard_rent + $renewal->amount_increase,
                    'security' => $contract->security_deposit,
                    'last_month_rent' => $contract->standard_rent + $renewal->amount_increase,
                    'amenities' => 110.00,
                    'status' => 'Pending'
                ];
                $renewalCurrent->addMonth();
                $counter++;
            }
        }

        // Sort months by month_number
        usort($months, fn($a, $b) => $a['month_number'] <=> $b['month_number']);

        // For Next Payment Due Block
        $dueDay = $contract->invoice_due_date;
        $dueDate = Carbon::now()->day($dueDay);        
        if ($dueDate->isPast()) {
            $dueDate->addMonth();
        }
        $daysRemaining = Carbon::now()->diffInDays($dueDate, false);

        // For Next Payment
        $rentAmount = $contract->standard_rent;

        return view('tenant_dashboard.payment-section', [
            'payments' => $months, 
            'dueDate' => $dueDate, 
            'daysRemaining' => $daysRemaining,
            'rentAmount' => $rentAmount,
        ]);
    } 
    
    public function tenant_ticket_support() {
        $tickets = Ticket::where('tenant_id', Auth::user()->id)->get();
        return View('tenant_dashboard.tickets_upport.index', compact('tickets'));
    } 

    public function tenant_view_ticket($id) {

        $ticketsdata = DB::table('tickets')->where('id',$id)->where('tenant_id', Auth::user()->id)->first();
        $userdata = DB::table('users')->where('id',$ticketsdata->tenant_id)->first();
        $ticketsupportdata = DB::table('ticket_support')->where('ticket_id',$id)->get();

        return View('tenant_dashboard.tickets_upport.view-ticket',compact('ticketsdata','ticketsupportdata','userdata'));
    } 

    public function add_tenant_ticket() {
        return View('tenant_dashboard.tickets_upport.add-ticket');
    } 
	
	public function store(Request $request)
    {
        // Validation
        $request->validate([
            'subject' => 'required|string|max:200',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string|in:' . implode(',', \App\Enums\TicketCategory::ALL),
        ]);

        // Handle file upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time().'_'.$file->getClientOriginalName();

            $destinationPath = storage_path('upload/tickets');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, $fileName);

            // Save relative path in DB
            $photoPath = $fileName;
        }
    
        $randomticketId = '#' . time() . rand(1000, 9999);
        $randomticketId2 = '#' . time() . rand(1000, 9999);
        // Create ticket
        $ticket = Ticket::create([
            'random_id' => $randomticketId,
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            /*'status' => \App\Enums\TicketStatus::OPEN,*/ 
            'status' => '1', 
            'photo' => $photoPath,
            'tenant_id' => Auth::user()->id, 
            'property_id' => Auth::user()->tenants->property_id ?? null,
        ]);

        DB::table('ticket_support')->insert([
            'random_id' => $randomticketId2,
            'ticket_id' => $ticket->id,
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'status' => '1', 
            'photo' => $photoPath,
            'tenant_id' => Auth::user()->id, 
            'property_id' => Auth::user()->tenants->property_id ?? null,
        ]);

        return redirect()->route('tenant_ticket_support')->with('success', 'Ticket submitted successfully!');
    }



    public function viewticket_store(Request $request)
    {
        $request->validate([
            'photo'=> 'required',
            'description'=> 'required',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time().'_'.$file->getClientOriginalName();

            $destinationPath = storage_path('upload/tickets');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $file->move($destinationPath, $fileName);

            // Save relative path in DB
            $photoPath = $fileName;
        }
    
        $randomticketId3 = '#' . time() . rand(1000, 9999);

        DB::table('ticket_support')->insert([
            'random_id' => $randomticketId3,
            'ticket_id' => $request->ticket_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status, 
            'photo' => $photoPath,
            'tenant_id' => Auth::user()->id, 
            'property_id' => Auth::user()->tenants->property_id ?? null,
        ]);

        return redirect('tenant-view-ticket/'.$request->id)->with('success', 'Ticket submitted successfully!');
    }

	
    public function tenant_notices() {
        $generatenotice = DB::table('owner-generatenotice')->where('tenant_id', Auth::user()->tenants->id)->get();
        return View('tenant_dashboard.notices.index',compact('generatenotice'));
    } 

    public function tenantnotices_detail($id) {
        $generatenoticedetail = DB::table('owner-generatenotice')->where('tenant_id', Auth::user()->tenants->id)->first();
        $managennotice = DB::table('managen-notice')->where('id',$generatenoticedetail->notice_id)->first();
        return View('tenant_dashboard.notices.detail', compact('generatenoticedetail','managennotice'));
    } 

    public function tenant_documents() {
		/*$tenantDocuments = TenantDocument::where('tenant_id', Auth::user()->tenants->id)->get();*/
        $tenantDocuments = DB::table('owner-send-doc')->where('tenant_id', Auth::user()->tenants->id)->get();
        return View('tenant_dashboard.documents.tenant-documents', compact('tenantDocuments'));
    }  
    public function tenant_documents_detail($id) {
        $tenantdocdetail = DB::table('owner-send-doc')->where('tenant_id', Auth::user()->tenants->id)->where('id', $id)->first();
        return View('tenant_dashboard.documents.tenant-doc-detail', compact('tenantdocdetail'));
    } 
	
	public function download($id)
    {
        $tenantDocument = TenantDocument::findOrFail($id);

        $filePath = 'upload/tenantdocument/' . $tenantDocument->document;

        if (Storage::exists($filePath)) {
            return Storage::download($filePath, $tenantDocument->document);
        }

        return back()->with('error', 'File not found.');
    }
	
    public function utilities_invoices() {
        $utilityInvoices = UtilityInvoice::where('tenant_id', Auth::user()->tenants->id)->get();
        return View('tenant_dashboard.utilities-invoices', compact('utilityInvoices'));
    }
    public function tenant_late_fees() {
        return View('tenant_dashboard.tenant-late-fees');
    }
    
    public function tenant_other_invoice() {        
        $otherInvoices = OtherInvoice::with(['property', 'tenant', 'tenant.user'])
            ->where('tenant_id', auth()->user()->tenants->id)
            ->orderBy('invoice_date', 'desc')
            ->get();

        return view('tenant_dashboard.tenant-other-invoice', compact('otherInvoices'));
    }

     public function view_invoice() {
        return View('tenant_dashboard.view-invoice');
    }


}
