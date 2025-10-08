<?php

namespace App\Http\Controllers;

use App\Models\ContractRenewal;
use App\Models\Invoice;
use App\Models\LatePaymentRule;
use App\Models\Notification;
use App\Models\Property;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantDocument;
use App\Models\User;
use App\Models\UtilityInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TenantController extends Controller
{

    public function index()
    {
        if (!\Auth::user()->can('manage tenant')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $ownerId = parentId(); // Owner’s auth_id
        $today = Carbon::today()->toDateString();

        $tenants = Tenant::where('parent_id', $ownerId)->get();

        foreach ($tenants as $tenant) {
            // ----- Rent/Lease Amount Due -----
            $tenant->amount_due = DB::table('invoices')
                ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
                ->where('invoices.parent_id', $ownerId)
                ->where('invoices.property_id', $tenant->property_id)
                ->when(!empty($tenant->unit_id), function ($q) use ($tenant) {
                    $q->where('invoices.unit_id', $tenant->unit_id);
                })
                ->whereDate('invoices.due_date', '>=', $today)
                ->where('invoices.status', '!=', 'paid')
                ->sum('invoice_items.amount');

            // ----- Rent/Lease Amount Past Due -----
            $tenant->amount_past_due = DB::table('invoices')
                ->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')
                ->where('invoices.parent_id', $ownerId)
                ->where('invoices.property_id', $tenant->property_id)
                ->when(!empty($tenant->unit_id), function ($q) use ($tenant) {
                    $q->where('invoices.unit_id', $tenant->unit_id);
                })
                ->whereDate('invoices.due_date', '<', $today)
                ->where('invoices.status', '!=', 'paid')
                ->sum('invoice_items.amount');

            // ----- Utilities Due -----
            $tenant->utilities_due = DB::table('utility_invoices')
                ->where('utility_invoices.owner_id', $ownerId)
                ->where('utility_invoices.property_id', $tenant->property_id)
                ->when(!empty($tenant->unit_id), function ($q) use ($tenant) {
                    $q->where('utility_invoices.unit_id', $tenant->unit_id);
                })
                ->whereDate('utility_invoices.due_date', '>=', $today)
                ->where('utility_invoices.status', '!=', 'paid')
                ->sum('utility_invoices.amount');

            // ----- Utilities Past Due -----
            $tenant->utilities_past_due = DB::table('utility_invoices')
                ->where('utility_invoices.owner_id', $ownerId)
                ->where('utility_invoices.property_id', $tenant->property_id)
                ->when(!empty($tenant->unit_id), function ($q) use ($tenant) {
                    $q->where('utility_invoices.unit_id', $tenant->unit_id);
                })
                ->whereDate('utility_invoices.due_date', '<', $today)
                ->where('utility_invoices.status', '!=', 'paid')
                ->sum('utility_invoices.amount');

            // ----- Months Left on Lease -----
            $latestEnd = DB::table('invoices')
                ->where('parent_id', $ownerId)
                ->where('property_id', $tenant->property_id)
                ->when(!empty($tenant->unit_id), function ($q) use ($tenant) {
                    $q->where('unit_id', $tenant->unit_id);
                })
                ->max('end_date');

            $tenant->months_left = $latestEnd
                ? Carbon::parse($latestEnd)->diffInMonths(Carbon::today())
                : 0;
        }

        return view('tenant.index', compact('tenants'));
    }


    public function create()
    {
        if (\Auth::user()->can('create tenant')) {
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            // $property->prepend(__('Select Property'), 0);
            $statesdata = DB::table('states')->get();
            return view('tenant.create', compact('property','statesdata'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    // public function store(Request $request)
    // {
    //     // dd($request->all());
        
    //     if (\Auth::user()->can('create tenant')) {
    //         $validator = \Validator::make(
    //             $request->all(),
    //             [
    //                 'first_name' => 'required',
    //                 'last_name' => 'required',
    //                 'email' => 'required|email|unique:users',
    //                 'password' => 'required',
    //                 'phone_number' => 'required',
    //                 // 'family_member' => 'required',
    //                 'emergency_phone_number' => 'required',
    //                 'country' => 'required',
    //                 'state' => 'required',
    //                 'city' => 'required',
    //                 'zip_code' => 'required',
    //                 'address' => 'required',
    //                 'property' => 'required',
    //                 // 'unit' => 'required',
    //                 'lease_start_date' => 'required',
    //                 'lease_end_date' => 'required',
    //             ]
    //         );
    //         if ($validator->fails()) {
    //             $messages = $validator->getMessageBag();
    //             return response()->json([
    //                 'status' => 'error',
    //                 'msg' => $messages->first(),
    //             ]);
    //         }
    //         $ids = parentId();
    //         $authUser = \App\Models\User::find($ids);
    //         $totalTenant = $authUser->totalTenant();
    //         $subscription = Subscription::find($authUser->subscription);
    //         if ($totalTenant >= $subscription->tenant_limit && $subscription->tenant_limit != 0) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'msg' => __('Your tenant limit is over, please upgrade your subscription.'),
    //                 'id' => 0,
    //             ]);
    //         }

    //         $userRole = Role::where('parent_id', parentId())->where('name', 'tenant')->first();
    //         $setting = settings();

    //         $user = new User();
    //         $user->first_name = $request->first_name;
    //         $user->last_name = $request->last_name;
    //         $user->email = $request->email;
    //         $user->password = \Hash::make($request->password);
    //         $user->phone_number = $request->phone_number;
    //         $user->type = $userRole->name;
    //         $user->email_verified_at = now();
    //         $user->profile = 'avatar.png';
    //         $user->lang = 'english';
    //         $user->parent_id = parentId();
    //         $user->save();
    //         $user->assignRole($userRole);

    //         if ($request->profile != 'undefined') {
    //             $tenantFilenameWithExt = $request->file('profile')->getClientOriginalName();
    //             $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
    //             $tenantExtension = $request->file('profile')->getClientOriginalExtension();
    //             $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
    //             $dir = storage_path('upload/profile');
    //             if (!file_exists($dir)) {
    //                 mkdir($dir, 0777, true);
    //             }
    //             $request->file('profile')->storeAs('upload/profile/', $tenantFileName);
    //             $user->profile = $tenantFileName;
    //             $user->save();
    //         }

    //         $tenant = new Tenant();
    //         $tenant->user_id = $user->id;
    //         // $tenant->family_member = $request->family_member;
    //         $tenant->emergency_phone_number = $request->emergency_phone_number;
    //         $tenant->country = $request->country;
    //         $tenant->state = $request->state;
    //         $tenant->city = $request->city;
    //         $tenant->zip_code = $request->zip_code;
    //         $tenant->address = $request->address;
    //         $tenant->property = $request->property;
    //         $tenant->unit = $request->unit;
    //         $tenant->lease_start_date = $request->lease_start_date;
    //         $tenant->lease_end_date = $request->lease_end_date;
    //         $tenant->parent_id = parentId();
    //         $tenant->save();


    //         if (!empty($request->tenant_images)) {
    //             foreach ($request->tenant_images as $file) {
    //                 $tenantFilenameWithExt = $file->getClientOriginalName();
    //                 $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
    //                 $tenantExtension = $file->getClientOriginalExtension();
    //                 $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
    //                 $dir = storage_path('upload/tenant');
    //                 if (!file_exists($dir)) {
    //                     mkdir($dir, 0777, true);
    //                 }
    //                 $file->storeAs('upload/tenant/', $tenantFileName);

    //                 $tenantImage = new TenantDocument();
    //                 $tenantImage->property_id = $request->property;
    //                 $tenantImage->tenant_id = $tenant->id;
    //                 $tenantImage->document = $tenantFileName;
    //                 $tenantImage->parent_id = parentId();
    //                 $tenantImage->save();
    //             }
    //         }

    //         $module = 'tenant_create';
    //         $notification = Notification::where('parent_id', parentId())->where('module', $module)->first();
    //         $notification->password=$request->password;
    //         $errorMessage='';
    //         if (!empty($notification) && $notification->enabled_email == 1) {
    //             $notification_responce = MessageReplace($notification, $user->id);
    //             $datas['subject'] = $notification_responce['subject'];
    //             $datas['message'] = $notification_responce['message'];
    //             $datas['module'] = $module;
    //             $datas['logo'] =  $setting['company_logo'];
    //             $to = $user->email;
    //             $response = commonEmailSend($to, $datas);
    //                 if ($response['status'] == 'error') {
    //                     $errorMessage=$response['message'];
    //                 }
    //         }


    //         return response()->json([
    //             'status' => 'success',
    //             'msg' => __('Tenant successfully created.'). '</br>' . $errorMessage,

    //         ]);
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }


       public function store(Request $request)
    {
        // dd($request->all());
        
        if (\Auth::user()->can('create tenant')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required',
                    'phone_number' => 'required',
                    // 'family_member' => 'required',
                    'emergency_phone_number' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                    'property' => 'required',
                    // 'unit' => 'required',
                    // 'lease_start_date' => 'required',
                    // 'lease_end_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),
                ]);
            }
            $ids = parentId();
            $authUser = \App\Models\User::find($ids);
            $totalTenant = $authUser->totalTenant();
            $subscription = Subscription::find($authUser->subscription);
            // if ($totalTenant >= $subscription->tenant_limit && $subscription->tenant_limit != 0) {
            //     return response()->json([
            //         'status' => 'error',
            //         'msg' => __('Your tenant limit is over, please upgrade your subscription.'),
            //         'id' => 0,
            //     ]);
            // }

            $userRole = Role::where('parent_id', parentId())->where('name', 'tenant')->first();
            $setting = settings();

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = \Hash::make($request->password);
            $user->phone_number = $request->phone_number;
            $user->emergency_phone_number = $request->emergency_phone_number;
            $user->type = $userRole->name;
            $user->email_verified_at = now();
            $user->profile = 'avatar.png';
            $user->lang = 'english';
            $user->parent_id = parentId();
            $user->save();
            $user->assignRole($userRole);
            
            if ($request->hasFile('profile')) {
            // if (!empty($request->profile != 'undefined')) {
                $tenantFilenameWithExt = $request->file('profile')->getClientOriginalName();
                $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                $tenantExtension = $request->file('profile')->getClientOriginalExtension();
                $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                $dir = storage_path('upload/profile');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('profile')->storeAs('upload/profile/', $tenantFileName);
                $user->profile = $tenantFileName;
                $user->save();
            }

            // if ($request->contract_document != 'undefined') {
            //     $tenantFilenameWithExt = $request->file('contract_document')->getClientOriginalName();
            //     $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
            //     $tenantExtension = $request->file('contract_document')->getClientOriginalExtension();
            //     $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
            //     $dir = storage_path('upload/tenantdocument');
            //     if (!file_exists($dir)) {
            //         mkdir($dir, 0777, true);
            //     }
            //     $request->file('contract_document')->storeAs('upload/tenantdocument/', $tenantFileName);
            //     $user->contract_document = $tenantFileName;
            //     $user->save();
            // }
            
            if ($request->hasFile('personal_document')) {
            // if (!empty($request->personal_document != 'undefined')) {
                $tenantFilenameWithExt = $request->file('personal_document')->getClientOriginalName();
                $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                $tenantExtension = $request->file('personal_document')->getClientOriginalExtension();
                $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                $dir = storage_path('upload/tenantdocument');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('personal_document')->storeAs('upload/tenantdocument/', $tenantFileName);
                $user->personal_document = $tenantFileName;
                $user->save();
            }
             
            if ($request->hasFile('ic_document')) {
            // if (!empty($request->ic_document != 'undefined')) {
                $tenantFilenameWithExt = $request->file('ic_document')->getClientOriginalName();
                $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                $tenantExtension = $request->file('ic_document')->getClientOriginalExtension();
                $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                $dir = storage_path('upload/tenantdocument');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('ic_document')->storeAs('upload/tenantdocument/', $tenantFileName);
                $user->ic_document = $tenantFileName;
                $user->save();
            }
            
            if ($request->hasFile('miscellaneous')) {
            // if (!empty($request->miscellaneous != 'undefined')) {
                $tenantFilenameWithExt = $request->file('miscellaneous')->getClientOriginalName();
                $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                $tenantExtension = $request->file('miscellaneous')->getClientOriginalExtension();
                $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                $dir = storage_path('upload/tenantdocument');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('miscellaneous')->storeAs('upload/tenantdocument/', $tenantFileName);
                $user->miscellaneous = $tenantFileName;
                $user->save();
            }

            $tenant = new Tenant();
            $tenant->user_id = $user->id;
            // $tenant->family_member = $request->family_member;
            $tenant->country = $request->country;
            $tenant->state = $request->state;
            $tenant->city = $request->city;
            $tenant->zip_code = $request->zip_code;
            $tenant->address = $request->address;
            $tenant->property_id = $request->property;
            $tenant->property_unit_id = $request->unit ?? 0;
            $tenant->lease_start_date = $request->lease_start_date;
            $tenant->lease_end_date = $request->lease_end_date;
            $tenant->parent_id = parentId();
            $tenant->save();

            if ($request->hasFile('tenant_images')) {
            // if (!empty($request->tenant_images)) {
                foreach ($request->tenant_images as $file) {
                    $tenantFilenameWithExt = $file->getClientOriginalName();
                    $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                    $tenantExtension = $file->getClientOriginalExtension();
                    $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                    $dir = storage_path('upload/tenant');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/tenant/', $tenantFileName);

                    $tenantImage = new TenantDocument();
                    $tenantImage->property_id = $request->property;
                    $tenantImage->tenant_id = $tenant->id;
                    $tenantImage->document = $tenantFileName;
                    $tenantImage->parent_id = parentId();
                    $tenantImage->save();
                }
            }

            $module = 'tenant_create';
            $notification = Notification::where('parent_id', parentId())->where('module', $module)->first();
            $notification->password=$request->password;
            $errorMessage='';
            if (!empty($notification) && $notification->enabled_email == 1) {
                $notification_responce = MessageReplace($notification, $user->id);
                $datas['subject'] = $notification_responce['subject'];
                $datas['message'] = $notification_responce['message'];
                $datas['module'] = $module;
                $datas['logo'] =  $setting['company_logo'];
                $to = $user->email;
                $response = commonEmailSend($to, $datas);
                    if ($response['status'] == 'error') {
                        $errorMessage=$response['message'];
                    }
            }


            // return response()->json([
            //     'status' => 'success',
            //     'msg' => __('Tenant successfully created.'). '</br>' . $errorMessage,

            // ]);
            return redirect('tenant')->with('success', __('Tenant successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    // public function show(Tenant $tenant)
    // {
    //     if (\Auth::user()->can('show tenant')) {
    //         $statesdata = DB::table('states')->where('id',$tenant->state)->first();
    //         $cities = DB::table('cities')->where('id', $tenant->city)->first();
    //         return view('tenant.show', compact('tenant','statesdata','cities'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }

    public function show(Tenant $tenant)
    {
        $contract = Tenant::where('id', $tenant->id)->first();

        $tenantcontracts = DB::table('tenant_contracts')->where('property_id', $tenant->property_id)->where('tenant_id', $tenant->id)->where('owner_id', $tenant->user_id)->first();
        $contractRenewals = DB::table('contract_renewals')->where('tenant_contract_id', $tenantcontracts->id ?? 0)->orderBy('id')->get();
        // compute amenities total (only if tenantcontracts has property_id)
        
        // default value so compact() never gets an undefined var
        $propertyAmenitiesTotal = 0;
        if ($tenantcontracts && !empty($tenantcontracts->property_id)) {
            $propertyAmenitiesTotal = DB::table('amenity_catg')
                ->where('property_id', $tenantcontracts->property_id)
                ->where('status', 1)
                ->sum('price') ?: 0;
        }
        // build combined periods
        $periods = collect();

        // base contract months
        if ($tenantcontracts && $tenantcontracts->start_date && $tenantcontracts->end_date) {
            try {
                $baseStart = Carbon::parse($tenantcontracts->start_date)->startOfMonth();
                $baseEnd = Carbon::parse($tenantcontracts->end_date)->startOfMonth();

                $basePeriod = CarbonPeriod::create($baseStart, '1 month', $baseEnd);

                foreach ($basePeriod as $m) {
                    $periods->push([
                        'month_label' => $m->format('F Y'),
                        'ym' => $m->format('Y-m'),
                        'rent' => (float) ($tenantcontracts->standard_rent ?? 0),
                        'security' => (float) ($tenantcontracts->security_deposit ?? 0),
                        'type' => 'base',
                        'source' => null,
                    ]);
                }
            } catch (\Exception $e) {
                // ignore invalid dates, keep periods empty
            }
        }

$contractStart = null;
$contractEnd = null;
if (!empty($tenantcontracts->start_date)) {
    try {
        $contractStart = Carbon::parse($tenantcontracts->start_date)->startOfMonth();
    } catch (\Exception $e) {
        $contractStart = null;
    }
}
if (!empty($tenantcontracts->end_date)) {
    try {
        $contractEnd = Carbon::parse($tenantcontracts->end_date)->startOfMonth();
    } catch (\Exception $e) {
        $contractEnd = null;
    }
}

// Determine base contract month count (1-based). If base dates exist:
$baseMonthsCount = 0;
if ($contractStart && $contractEnd) {
    $baseMonthsCount = $contractStart->diffInMonths($contractEnd) + 1; // e.g. 12 for 12 months
}

foreach ($contractRenewals as $renewal) {
    // parse offsets as integers
    $startIdx = (int) ($renewal->start_month ?: 0);
    $endIdx   = (int) ($renewal->end_month ?: 0);

    // skip invalid offsets
    if ($startIdx <= 0 || $endIdx <= 0 || $endIdx < $startIdx) {
        // invalid, skip this renewal
        continue;
    }

    // If we have a valid contractStart, compute absolute months from that
    if ($contractStart) {
        // startIdx = 1 means contractStart, so addMonths(startIdx - 1)
        $startDate = $contractStart->copy()->addMonths($startIdx - 1)->startOfMonth();
        $endDate   = $contractStart->copy()->addMonths($endIdx - 1)->startOfMonth();
    } else {
        // fallback: if no contractStart use contractEnd if present, else now()
        $anchor = $contractEnd ?? Carbon::now()->startOfMonth();
        // Here we treat startIdx as offset from the anchor; you can tweak this behavior
        $startDate = $anchor->copy()->addMonths($startIdx - 1)->startOfMonth();
        $endDate   = $anchor->copy()->addMonths($endIdx - 1)->startOfMonth();
    }

    // Ensure endDate is not before startDate
    if ($endDate->lessThan($startDate)) {
        $endDate = $startDate->copy();
    }

    // If renewal starts within base months (overlap), optionally shift it to begin after base end:
    // (comment out if you want overlapping behavior)
    if ($baseMonthsCount > 0 && $startIdx <= $baseMonthsCount) {
        // move start to first month after base contract if that's what you want:
        $startDate = $contractStart->copy()->addMonths($baseMonthsCount)->startOfMonth();
        // adjust endDate to maintain same length (optional), or use provided endIdx:
        $endDate = $contractStart->copy()->addMonths($endIdx - 1)->startOfMonth();
        if ($endDate->lessThan($startDate)) {
            $endDate = $startDate->copy();
        }
    }

    // Now add each month in the renewal period
    try {
        $renewalPeriod = CarbonPeriod::create($startDate, '1 month', $endDate);

        foreach ($renewalPeriod as $m) {
            $periods->push([
                'month_label' => $m->format('F Y'),
                'ym' => $m->format('Y-m'),
                'rent' => (float) (($tenantcontracts->standard_rent ?? 0) + ($renewal->amount_increase ?? 0)),
                'security' => 0.0,
                'type' => 'renewal',
                'source' => [
                    'renewal_id' => $renewal->id,
                    'amount_increase' => (float) ($renewal->amount_increase ?? 0),
                    'start_idx' => $startIdx,
                    'end_idx' => $endIdx,
                ],
            ]);
        }

        // shift contractEnd forward so subsequent renewals treat the anchor correctly
        $contractEnd = $endDate->copy();
    } catch (\Exception $e) {
        // skip invalid periods silently (or log)
    }

}

// Optionally remove duplicate months (keep first occurrence)
$periods = $periods->unique('ym')->values();

        return view('tenant.show', compact('tenant', 'contract', 'periods', 'contractRenewals', 'propertyAmenitiesTotal','tenantcontracts'));
    }


    public function resendInvoice($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Check if tenant has a valid email
        if (empty($tenant->email) || !filter_var($tenant->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Tenant email is missing or invalid!');
        }

        $invoiceData = [
            'name' => $tenant->name,
            'email_text' => 'Here is your invoice again.',
        ];

        try {
            // Send email
            Mail::to($tenant->email)->send(new InvoiceMail($invoiceData));
        } catch (\Exception $e) {
            // Handle mail sending errors
            return redirect()->back()->with('error', 'Failed to resend invoice: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Invoice resent successfully!');
    }


    public function tenant_contractsupdate(Request $request, $id = null)
    {
        // 🔹 Validate input
        if (empty($request->property_id)) {
            $tenant = DB::table('tenants')->where('id', $request->tenant_id)->first();
            if ($tenant && !empty($tenant->property_id)) {
                $request->merge(['property_id' => $tenant->property_id]);
            }
        }
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|integer',
            'owner_id' => 'required|integer',
            'start_date' => 'nullable|string',  // We'll store as m-d-Y
            'end_date' => 'nullable|string',
            'standard_rent' => 'required|numeric',
            'late_fee' => 'nullable|numeric',
            'security_deposit' => 'nullable|numeric',
            'notice_period_months' => 'nullable|integer',
            'contract_renewal_month' => 'nullable|integer',
            'contract_renewal_amount' => 'nullable|array',
            'contract_renewal_amount.*' => 'nullable|numeric',
        ]);

        // 🔹 Fill default end_date if empty using start_date + contract_renewal_month
        if (empty($request->end_date) && !empty($validated['contract_renewal_month'])) {
            try {
                $endDate = Carbon::createFromFormat('m-d-Y', $validated['start_date'])
                    ->addMonths((int) $validated['contract_renewal_month'])
                    ->format('m-d-Y');
                $validated['end_date'] = $endDate;
            } catch (\Exception $e) {
                // return back()->withErrors(['start_date' => 'Invalid start date'])->withInput();
            }
        }

        // 🔹 Check if contract exists
        $contract = DB::table('tenant_contracts')
            ->where('property_id', $request->property_id)
            ->where('tenant_id', $request->tenant_id)
            ->where('owner_id', $request->owner_id)
            ->first();

        // 🔹 Insert / Update
        if ($contract) {
            DB::table('tenant_contracts')
                ->where('id', $contract->id)
                ->update(array_merge($validated, ['updated_at' => now()]));
            $contractId = $contract->id;
        } else {
            if (isset($validated['contract_renewal_amount']) && is_array($validated['contract_renewal_amount'])) {
                unset($validated['contract_renewal_amount']);
            }
            $contractId = DB::table('tenant_contracts')
                ->insertGetId(array_merge($validated, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 🔹 Handle file upload
        if ($request->hasFile('contract_doc')) {
            $file = $request->file('contract_doc');
            $filename = 'contract_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/contracts', $filename);

            DB::table('tenant_contracts')
                ->where('id', $contractId)
                ->update(['contract_doc' => $filename]);
        }

        ContractRenewal::where('tenant_contract_id', $contractId)->delete();

        if (!empty($request->contract_renewal_amount)) {
            foreach ($request->contract_renewal_amount as $index => $amount) {
                ContractRenewal::create([
                    'tenant_contract_id' => $contractId,
                    'amount_increase' => $amount ?? 0,
                    'start_month' => $request->start_months[$index] ?? null,
                    'end_month' => $request->end_months[$index] ?? null,
                ]);
            }
        }

        LatePaymentRule::where('tenant_contract_id', $contractId)->delete();

        if (!empty($request->tier)) {
            foreach ($request->tier as $i => $tier) {
                LatePaymentRule::create([
                    'tenant_contract_id' => $contractId,
                    'tier' => $tier,
                    'grace_days' => $request->grace_days[$i] ?? null,
                    'time' => $request->time[$i] ?? null,
                    'amount' => $request->amount[$i] ?? null,
                ]);
            }
        }


        // 🔹 Redirect to tenant page
        return redirect(url('tenant/' . $request->tenant_id))
            ->with('success', $contract ? 'Contract updated successfully.' : 'Contract created successfully.');
    }




        public function edit(Tenant $tenant)
        {
            if (\Auth::user()->can('edit tenant')) {
                $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
                $property->prepend(__('Select Property'), 0);

                $user = User::find($tenant->user_id);
                $statesdata = DB::table('states')->get();
                $tenantsedit = DB::table('tenants')->where('user_id',$tenant->user_id)->first();
                return view('tenant.edit', compact('property', 'tenant', 'user','statesdata','tenantsedit'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied!'));
            }
        }


    public function update(Request $request, $id)
{
    if (\Auth::user()->can('edit tenant')) {

        // Get tenant and user
        $tenant = Tenant::findOrFail($id);
        $user   = User::findOrFail($tenant->user_id);

        // Validation
        $validator = \Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name'  => 'required',
                'email'      => 'required|email|unique:users,email,' . $user->id, // ignore current user
                'phone_number' => 'required',
                'emergency_phone_number' => 'required',
                'state'   => 'required',
                'city'    => 'required',
                'zip_code'=> 'required',
                'address' => 'required',
                'property'=> 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        // Update user
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        if (!empty($request->password)) {
            $user->password = \Hash::make($request->password);
        }
        $user->phone_number          = $request->phone_number;
        $user->emergency_phone_number= $request->emergency_phone_number;

        // Profile photo
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/profile/', $fileName);
            $user->profile = $fileName;
        }

        // Personal document
        if ($request->hasFile('personal_document')) {
            $file = $request->file('personal_document');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/tenantdocument/', $fileName);
            $user->personal_document = $fileName;
        }

        // IC document
        if ($request->hasFile('ic_document')) {
            $file = $request->file('ic_document');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/tenantdocument/', $fileName);
            $user->ic_document = $fileName;
        }

        // Miscellaneous
        if ($request->hasFile('miscellaneous')) {
            $file = $request->file('miscellaneous');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('upload/tenantdocument/', $fileName);
            $user->miscellaneous = $fileName;
        }

        $user->save();

        // Update tenant info
        $tenant->state   = $request->state;
        $tenant->city    = $request->city;
        $tenant->zip_code= $request->zip_code;
        $tenant->address = $request->address;
        $tenant->property= $request->property;
        $tenant->unit    = $request->unit;
        $tenant->lease_start_date = $request->lease_start_date;
        $tenant->lease_end_date   = $request->lease_end_date;
        $tenant->save();

        // Tenant images (multiple)
        if ($request->hasFile('tenant_images')) {
            foreach ($request->file('tenant_images') as $file) {
                $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('upload/tenant/', $fileName);

                $tenantImage = new TenantDocument();
                $tenantImage->property_id = $request->property;
                $tenantImage->tenant_id   = $tenant->id;
                $tenantImage->document    = $fileName;
                $tenantImage->parent_id   = parentId();
                $tenantImage->save();
            }
        }

        return redirect('tenant')->with('success', __('Tenant successfully updated.'));

    } else {
        return redirect()->back()->with('error', __('Permission Denied!'));
    }
}



    // public function update(Request $request, Tenant $tenant)
    // {
    //     // dd($request->all());
    //     if (\Auth::user()->can('edit tenant')) {
    //         $validator = \Validator::make(
    //             $request->all(),
    //             [
    //                 'first_name' => 'required',
    //                 'last_name' => 'required',
    //                 'email' => 'required|email|unique:users,email,' . $tenant->user_id,
    //                 'phone_number' => 'required',
    //                 // 'family_member' => 'required',
    //                 // 'country' => 'required',
    //                 'state' => 'required',
    //                 'city' => 'required',
    //                 'zip_code' => 'required',
    //                 'address' => 'required',
    //                 'property' => 'required',
    //                 'unit' => 'required',
    //                 'lease_start_date' => 'required',
    //                 'lease_end_date' => 'required',
    //             ]
    //         );
    //         if ($validator->fails()) {
    //             $messages = $validator->getMessageBag();
    //             return response()->json([
    //                 'status' => 'error',
    //                 'msg' => $messages->first(),

    //             ]);
    //         }

    //         $user = User::find($tenant->user_id);
    //         $user->first_name = $request->first_name;
    //         $user->last_name = $request->last_name;
    //         $user->email = $request->email;
    //         $user->phone_number = $request->phone_number;
    //         $user->save();

    //         if ($request->profile != '') {
    //             $tenantFilenameWithExt = $request->file('profile')->getClientOriginalName();
    //             $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
    //             $tenantExtension = $request->file('profile')->getClientOriginalExtension();
    //             $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
    //             $dir = storage_path('upload/profile');
    //             if (!file_exists($dir)) {
    //                 mkdir($dir, 0777, true);
    //             }
    //             $request->file('profile')->storeAs('upload/profile/', $tenantFileName);
    //             $user->profile = $tenantFileName;
    //             $user->save();
    //         }

    //         $tenant->family_member = $request->family_member;
    //         $tenant->country = $request->country;
    //         $tenant->state = $request->state;
    //         $tenant->city = $request->city;
    //         $tenant->zip_code = $request->zip_code;
    //         $tenant->address = $request->address;
    //         $tenant->property = $request->property;
    //         $tenant->unit = $request->unit;
    //         $tenant->lease_start_date = $request->lease_start_date;
    //         $tenant->lease_end_date = $request->lease_end_date;
    //         $tenant->save();



    //         if (!empty($request->tenant_images)) {
    //             foreach ($request->tenant_images as $file) {
    //                 $tenantFilenameWithExt = $file->getClientOriginalName();
    //                 $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
    //                 $tenantExtension = $file->getClientOriginalExtension();
    //                 $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
    //                 $dir = storage_path('upload/tenant');
    //                 if (!file_exists($dir)) {
    //                     mkdir($dir, 0777, true);
    //                 }
    //                 $file->storeAs('upload/tenant/', $tenantFileName);

    //                 $tenantImage = new TenantDocument();
    //                 $tenantImage->property_id = $request->property;
    //                 $tenantImage->tenant_id = $tenant->id;
    //                 $tenantImage->document = $tenantFileName;
    //                 $tenantImage->parent_id = parentId();
    //                 $tenantImage->save();
    //             }
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'msg' => __('Tenant successfully updated.'),
    //         ]);
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }


    public function destroy(Tenant $tenant)
    {
        if (\Auth::user()->can('delete tenant')) {
            User::where('id',$tenant->user_id)->delete();
            $tenant->delete();
            return redirect()->back()->with('success', 'Tenant successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
}
