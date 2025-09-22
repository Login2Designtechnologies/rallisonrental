<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Property;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DB;
use Carbon\Carbon;

class TenantController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage tenant')) {
            $tenants = Tenant::where('parent_id', parentId())->get();
            return view('tenant.index', compact('tenants'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
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
            $tenant->property = $request->property;
            $tenant->unit = $request->unit;
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

        $tenantcontracts = DB::table('tenant_contracts')->where('property_id', $tenant->property)->where('tenant_id', $tenant->id)->where('owner_id', $tenant->user_id)->first();

        $period = null;
        if ($tenantcontracts && $tenantcontracts->start_date && $tenantcontracts->end_date) {
            $period = \Carbon\CarbonPeriod::create(
                \Carbon\Carbon::parse($tenantcontracts->start_date)->startOfMonth(),
                '1 month',
                \Carbon\Carbon::parse($tenantcontracts->end_date)->startOfMonth()
            );
        }

        // Total active amenities for this property
        $propertyAmenitiesTotal = 0;
        if ($tenantcontracts && $tenantcontracts->property_id) {
            $propertyAmenitiesTotal = DB::table('amenity_catg')
                ->where('property_id', $tenantcontracts->property_id)
                ->where('status', 1)
                ->sum('price');
        }

        return view('tenant.show', compact('tenant', 'contract', 'period', 'propertyAmenitiesTotal','tenantcontracts'));
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
        // dd($request->all());
        // 🔹 Validate input
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
            'contract_renewal_amount' => 'nullable|numeric',
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
