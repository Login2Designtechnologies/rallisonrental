<?php

namespace App\Http\Controllers;

use App\Mail\UtilityInvoiceMail;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyUnit;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UtilityBill;
use App\Models\UtilityInvoice;
use App\Models\UtilityInvoiceDetail;
use App\Models\UtilityMain;
use App\Models\UtilityShare;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Log;

class PropertyController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage property')) {
            $properties = Property::where('parent_id', parentId())->where('is_active', 1)->get();

            return view('property.index', compact('properties'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function addUtilities($id)
    {
        return view('property.addUtilities', compact('id'));
    }

    public function addAmenities($id)
    {
        return view('property.addAmenities', compact('id'));
    }

    public function edit_Amenities($id, $propertyid)
    {
        $amenitydata = DB::table('amenity_catg')->where('id', $id)->first();

        return view('property.editAmenities', compact('amenitydata', 'propertyid'));
    }

    public function edit_Utilities($id, $propertyid)
    {
        $utilitiesdata = DB::table('utilities_catg')->where('id', $id)->first();

        return view('property.editutilities', compact('utilitiesdata', 'propertyid'));
    }

    //  public function selectProperty()
    // {
    //     return view('property.selectProperty');
    // }

    public function selectProperty()
    {
        $filters = request()->only(['q', 'status', 'property_id', 'tenant_id', 'month']);
        $invoices = UtilityInvoice::with(['property', 'user', 'tenant'])
            ->filters($filters)
            ->latest('invoice_date')
            ->paginate(15)
            ->withQueryString();

        $properties = DB::table('properties')
            ->where('properties.parent_id', auth()->id())
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('tenants')
                    ->whereColumn('tenants.property_id', 'properties.id')
                    ->where('tenants.parent_id', auth()->id());
            })
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('utilities_main')
                    ->whereColumn('utilities_main.property_id', 'properties.id')
                    ->where('utilities_main.user_id', auth()->id());
            })
            ->get();

        return view('utility_invoices.index', [
            'invoices' => $invoices,
            'filters' => $filters,
            'properties' => $properties,
        ]);
    }

    public function all_invoices()
    {
        $invoices = UtilityInvoice::paginate(20);

        return view('utility_invoices.show', compact('invoices'));
    }

    public function all_invoicescreate()
    {
        $invoiceMonth = request('invoice_month') ?: now()->format('Y-m');

        $property = DB::table('properties')
            ->where('properties.parent_id', auth()->id())
            ->where('properties.id', request('property_id'))
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('tenants')
                    ->whereColumn('tenants.property_id', 'properties.id')
                    ->where('tenants.parent_id', auth()->id());
            })
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('utilities_main as m')
                    ->join('utilities_sub as s', 'm.id', '=', 's.utility_main_id')
                    ->whereColumn('m.property_id', 'properties.id')
                    ->where('m.user_id', auth()->id());
            })
            ->first();
        
        if (!$property) {
            return response()->json(['error' => 'Invalid property or access denied'], 404);
        }

        // Preload existing amounts from utility_invoice_details for this property and month
        $amounts = collect();
        if ($property) {
            $amounts = UtilityInvoiceDetail::selectRaw('property_utility_id, category, SUM(amount) as total_amount')
                ->whereHas('invoice', function ($q) use ($property, $invoiceMonth) {
                    $q->where('property_id', $property->id)
                        ->where('invoice_month', $invoiceMonth);
                })
                ->groupBy('property_utility_id', 'category')
                ->get()
                ->keyBy(fn ($row) => ($row->property_utility_id ?: 'null').'|'.($row->category ?: '')
                );
        }

        // 3️⃣ Add data from utility_shares for this month (saved split before invoice generation)
        $shares = DB::table('utility_shares')
            ->where('property_id', request('property_id'))
            ->where('invoice_month', $invoiceMonth)
            ->get();

        // 4️⃣ If there are saved shares, inject their data into $amounts
        foreach ($shares as $share) {
            $key = ($share->utility_id ?: 'null').'|';
            if (!isset($amounts[$key])) {
                $amounts[$key] = (object)[
                    'property_utility_id' => $share->utility_id,
                    'category'            => '',
                    'total_amount'        => $share->amount ?? 0,
                ];
            } else {
                // Merge or overwrite existing total
                $amounts[$key]->total_amount = max($amounts[$key]->total_amount, $share->amount ?? 0);
            }
        }

        $tenantShares = [];
        $utilityPrices = [];
        foreach ($shares as $share) {
            $tenantShares[$share->utility_id][$share->tenant_id] = $share->percentage;
            $utilityPrices[$share->utility_id] = $share->price ?? 0;
        }

        $uploadedBills = UtilityBill::where('property_id', request('property_id'))
            ->where('invoice_month', $invoiceMonth)
            ->where('uploaded_by', auth()->id())
            ->get()
            ->keyBy('utility_id');
        
        $allMainUtilities = DB::table('utilities_main')
            ->where('property_id', request('property_id'))
            ->pluck('id');

        foreach ($allMainUtilities as $id) {
            if (!isset($uploadedBills[$id])) {
                $uploadedBills[$id] = (object)[
                    'start_date' => null,
                    'end_date' => null,
                    'file_path' => null,
                ];
            }
        }
        
        $existingInvoice = DB::table('utility_invoices')
            ->where('property_id', request('property_id'))
            ->where('invoice_month', $invoiceMonth)
            ->where('status', 'delivered') 
            ->exists();
        return view('utility_invoices.create', compact('property', 'invoiceMonth', 'amounts', 'tenantShares', 'utilityPrices', 'uploadedBills', 'existingInvoice'));
    }

    public function create()
    {

        if (\Auth::user()->can('create property')) {
            $types = Property::$Type;
            $rentTypes = PropertyUnit::$rentTypes;
            $statesdata = DB::table('states')->get();
            $amenities = DB::table('amenity_catg')->where('falge', '0')->get();
            $utilities = DB::table('utilities_catg')->where('falge', '0')->get();

            return view('property.create', compact('types', 'rentTypes', 'statesdata', 'amenities', 'utilities'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function get_cities($state_id)
    {
        $cities = DB::table('cities')
            ->where('state_id', $state_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($cities);
    }

    public function store(Request $request)
    {
        if (! \Auth::user()->can('create property')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'country' => 'required|string',
                'state' => 'required',
                'city' => 'required',
                'zip_code' => 'required',
                'address' => 'required|string',
                'thumbnail' => 'required',
                'is_active' => 'required|in:0,1',
                'mortgage_amount' => 'nullable|numeric|min:0',
                'insurance_amount' => 'nullable|numeric|min:0',
                'amenities_amount' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                $messages = new \Exception($validator->getMessageBag()->first());

                return response()->json([
                    'status' => 'error',
                    'msg' => $messages,

                ]);
            }

            $ids = parentId();
            $authUser = User::find($ids);
            $totalProperty = $authUser->totalProperty();
            $subscription = Subscription::find($authUser->subscription);

            // if ($totalProperty >= $subscription->property_limit && $subscription->property_limit != 0) {
            //     return response()->json([
            //         'status' => 'error',
            //         'msg' => __('Your property limit is over, please upgrade your subscription.'),
            //         'id' => 0,
            //     ]);
            // }

            // 🏠 Create Property
            $property = Property::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'country' => $request->country,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
                'is_billed' => $request->is_billed,
                'utilities' => $request->utilities,
                'is_active' => $request->is_active,
                'mortgage_amount' => $request->mortgage_amount,
                'insurance_amount' => $request->insurance_amount,
                'amenities_amount' => $request->amenities_amount,
                'parent_id' => parentId(),
            ]);

            // 🖼️ Save Thumbnail
            if ($request->hasFile('thumbnail')) {
                $thumbnailFilenameWithExt = $request->file('thumbnail')->getClientOriginalName();
                $thumbnailFilename = pathinfo($thumbnailFilenameWithExt, PATHINFO_FILENAME);
                $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnailFileName = $thumbnailFilename.'_'.time().'.'.$thumbnailExtension;
                $dir = storage_path('upload/thumbnail');
                if (! file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('thumbnail')->storeAs('upload/thumbnail/', $thumbnailFileName);
                $thumbnail = new PropertyImage;
                $thumbnail->property_id = $property->id;
                $thumbnail->image = $thumbnailFileName;
                $thumbnail->type = 'thumbnail';
                $thumbnail->save();
            }

            // 🖼️ Save Extra Images
            if ($request->hasFile('property_images')) {
                if (! empty($request->property_images)) {
                    foreach ($request->property_images as $file) {
                        $propertyFilenameWithExt = $file->getClientOriginalName();
                        $propertyFilename = pathinfo($propertyFilenameWithExt, PATHINFO_FILENAME);
                        $propertyExtension = $file->getClientOriginalExtension();
                        $propertyFileName = $propertyFilename.'_'.time().'.'.$propertyExtension;
                        $dir = storage_path('upload/property');
                        if (! file_exists($dir)) {
                            mkdir($dir, 0777, true);
                        }
                        $file->storeAs('upload/property/', $propertyFileName);

                        $propertyImage = new PropertyImage;
                        $propertyImage->property_id = $property->id;
                        $propertyImage->image = $propertyFileName;
                        $propertyImage->type = 'extra';
                        $propertyImage->save();
                    }
                }
            }

            // 🧩 Save Units
            if ($request->has('unitname')) {
                foreach ($request->unitname as $key => $name) {
                    if (empty($name)) {
                        continue;
                    }

                    PropertyUnit::create([
                        'name' => $name,
                        'status' => 0,
                        'notes' => $request->notes[$key] ?? null,
                        'property_id' => $property->id,
                        'parent_id' => parentId(),
                    ]);
                }
            }

            // 🧱 Save Amenities (JSON array)
            if ($request->has('amenities')) {
                $amenities = json_decode($request->amenities, true);
                foreach ($amenities as $a) {
                    if (! empty($a['name'])) {
                        DB::table('amenity_catg')->updateOrInsert(
                            ['property_id' => $property->id, 'name' => $a['name']],
                            [
                                'price' => $a['price'],
                                'status' => $a['status'],
                                'user_id' => Auth::id(),
                            ]
                        );
                    }
                }
            }

            // Save Utilities (JSON array)
            // if ($request->has('utilities')) {
            //     $utilities = json_decode($request->utilities, true);
            //     foreach ($utilities as $u) {
            //         if (! empty($u['name'])) {
            //             DB::table('utilities_catg')->updateOrInsert(
            //                 ['property_id' => $property->id, 'name' => $u['name']],
            //                 [
            //                     'sub_category' => $u['sub_category'],
            //                     'sub_category_name' => $u['sub_category_names'] ?? null,
            //                     'status' => $u['status'],
            //                     'user_id' => Auth::id(),
            //                 ]
            //             );
            //         }
            //     }
            // }

            if ($request->has('utilities')) {
                $utilities = json_decode($request->utilities, true);

                foreach ($utilities as $u) {
                    if (!empty($u['name'])) {
                        $main = UtilityMain::updateOrCreate(
                            [
                                'property_id' => $property->id,
                                'name' => $u['name'],
                                'user_id' => Auth::id(),
                            ],
                            ['status' => $u['status']]
                        );

                        if (!empty($u['sub_category_names'])) {
                            $subs = explode(',', $u['sub_category_names']);
                            $main->subcategories()->delete();
                            foreach ($subs as $sub) {
                                $main->subcategories()->create([
                                    'sub_category_name' => trim($sub),
                                    'status' => $u['status'],
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully created.'),
                'id' => $property->id,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function addAmenities_store(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string',
        ]);

        // Check if amenity already exists
        $exists = DB::table('amenity_catg')
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', __('Amenity already exists!'));
        }

        // Insert new amenity and get ID
        $newAmenityId = DB::table('amenity_catg')->insertGetId([
            'name' => $request->name,
            'property_id' => $id,
            'status' => $request->status,
        ]);

        return redirect('property/'.$id)->with('success', 'Amenity added successfully!');
    }

    public function editAmenities_update(Request $request, $id, $propertyid)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        // Already exist check (except current record)
        $exists = DB::table('amenity_catg')
            ->where('name', $request->name)
            ->where('id', '!=', $id) // 👈 current id ko ignore karega
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', __('Amenity already exists!'));
        }

        // Update
        DB::table('amenity_catg')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'status' => $request->status,
            ]);

        return redirect('property/'.$propertyid)->with('success', 'Amenity updated successfully!');
    }

    public function property_amenities_store(Request $request)
    {
        // Validate request
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'price' => 'required',
        ]);

        $userId = Auth::id();

        // Check if amenity already exists
        $exists = DB::table('amenity_catg')
            ->where('name', $request->name)
            ->where('property_id', $request->property_id)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Amenity already exists!',
            ]);
        }

        // Insert new amenity and get ID
        $newAmenityId = DB::table('amenity_catg')->insertGetId([
            'name' => $request->name,
            'price' => $request->price,
            'property_id' => $request->property_id,
            'status' => $request->status,
            'user_id' => $userId,
        ]);

        // Return JSON response with data
        return response()->json([
            'success' => true,
            'message' => 'Amenity added successfully!',
            'data' => [
                'id' => $newAmenityId,
                'name' => $request->name,
                'status' => $request->status,
                'price' => $request->price,
            ],
        ]);
    }

    public function propertyamenities_store2(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required',
            'status' => 'required|string',
        ]);

        $userId = Auth::id();

        // Check if amenity already exists
        $exists = DB::table('amenity_catg')
            ->where('name', $request->name)
            ->where('user_id', $userId)
            ->where('property_id', $request->propertyid)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Amenity already exists!',
            ]);
        }

        // Insert new amenity and get ID
        $newAmenityId = DB::table('amenity_catg')->insertGetId([
            'name' => $request->name,
            'property_id' => $request->propertyid,
            'price' => $request->price,
            'status' => $request->status,
            'user_id' => $userId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Amenity added successfully!',
            'data' => [
                'id' => $newAmenityId,
                'name' => $request->name,
                'price' => $request->price,
                'status' => $request->status,
            ],
        ]);

        // Return JSON response with data
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Amenity added successfully!',
        //     'data' => [
        //         'id' => $newAmenityId,
        //         'name' => $request->name,
        //         'price' => $request->price,
        //         'status' => $request->status,
        //     ]
        // ]);
    }

    public function property_utilities_store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|string|max:255',
            'sub_category' => 'required|in:0,1',
            'status' => 'required|in:0,1',
            'sub_category_names' => 'nullable',
        ]);

        $userId = Auth::id();
        $propertyId = $request->property_id;
        $insertedData = [];

        if ($request->sub_category == 1) {
            // ✅ Multiple sub_category_name handle
            foreach ($request->sub_category_names as $subCatName) {
                // Pehle check karo duplicate
                $exists = DB::table('utilities_catg')
                    ->where('name', $request->name)
                    ->where('sub_category', 1)
                    ->where('sub_category_name', $subCatName)
                    ->where('user_id', $userId)
                    ->exists();

                if (! $exists) {
                    $id = DB::table('utilities_catg')->insertGetId([
                        'name' => $request->name,
                        'sub_category' => 1,
                        'sub_category_name' => $subCatName,
                        'property_id' => $propertyId,
                        'status' => $request->status,
                        'user_id' => $userId,
                    ]);

                    $insertedData[] = [
                        'id' => $id,
                        'name' => $request->name,
                        'sub_category' => 1,
                        'sub_category_name' => $subCatName,
                        'status' => $request->status,
                    ];
                }
            }

            if (empty($insertedData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utility already exists!',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Utility added successfully!',
                'data' => $insertedData,
            ]);

        } else {
            // ✅ sub_category = 0 → without sub_category_name
            $exists = DB::table('utilities_catg')
                ->where('name', $request->name)
                ->where('sub_category', 0)
                ->where('user_id', $userId)
                ->exists();

            if (! $exists) {

                if ($request->property_id) {
                    $propertyId = $request->property_id;
                } else {
                    $lastProperty = DB::table('properties')->latest('id')->first();
                    $propertyId = $lastProperty ? $lastProperty->id + 1 : 1;
                }

                $id = DB::table('utilities_catg')->insertGetId([
                    'name' => $request->name,
                    'sub_category' => 0,
                    'sub_category_name' => null,
                    'property_id' => $propertyId,
                    'status' => $request->status,
                    'user_id' => $userId,
                ]);

                $insertedData[] = [
                    'id' => $id,
                    'name' => $request->name,
                    'sub_category' => 0,
                    'sub_category_name' => null,
                    'status' => $request->status,
                ];
            }

            if (empty($insertedData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utility already exists!',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Utility added successfully!',
                'data' => $insertedData,
            ]);

        }

    }

    public function propertyutilities_store2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_category' => 'required|in:0,1',
            'status' => 'required|in:0,1',
        ]);

        $userId = Auth::id();
        $propertyId = $request->propertyid;

        // Normalize input
        $subCategories = is_array($request->sub_category_name)
            ? $request->sub_category_name
            : [$request->sub_category_name];

        $inserted = [];
        foreach ($subCategories as $subCat) {
            $subCat = trim($subCat);

            // Skip empty
            if (empty($subCat)) continue;

            // Check for duplicates
            $exists = DB::table('utilities_catg')
                ->where('name', $request->name)
                ->where('property_id', $propertyId)
                ->where('user_id', $userId)
                ->where('sub_category', $request->sub_category)
                ->where('sub_category_name', $subCat)
                ->exists();

            if ($exists) {
                // Skip duplicates instead of aborting whole insert
                continue;
            }

            $id = DB::table('utilities_catg')->insertGetId([
                'name' => $request->name,
                'sub_category' => $request->sub_category,
                'sub_category_name' => $subCat,
                'property_id' => $propertyId,
                'status' => $request->status,
                'user_id' => $userId,
                'created_at' => now(),
            ]);

            $inserted[] = [
                'id' => $id,
                'name' => $request->name,
                'sub_category' => $request->sub_category,
                'sub_category_name' => $subCat,
                'status' => $request->status,
            ];
        }

        if (empty($inserted)) {
            return response()->json([
                'success' => false,
                'message' => 'No new utilities added (duplicates or empty values).',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Utility added successfully!',
            'data' => $inserted,
        ]);
    }


    public function addUtilities_store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_category' => 'required|in:0,1',
            'status' => 'required|in:0,1',
        ]);

        $insertedData = [];

        if ($request->sub_category == 1) {
            // ✅ Multiple sub_category_name handle
            foreach ($request->sub_category_name as $subCatName) {
                // Pehle check karo duplicate
                $exists = DB::table('utilities_catg')
                    ->where('name', $request->name)
                    ->where('sub_category', 1)
                    ->where('sub_category_name', $subCatName)
                    ->exists();

                if (! $exists) {

                    $idnew = DB::table('utilities_catg')->insertGetId([
                        'name' => $request->name,
                        'sub_category' => 1,
                        'sub_category_name' => $subCatName,
                        'property_id' => $id,
                        'status' => $request->status,
                    ]);

                    $insertedData[] = [
                        'id' => $idnew,
                        'name' => $request->name,
                        'sub_category' => 1,
                        'sub_category_name' => $subCatName,
                        'status' => $request->status,
                    ];
                }
            }

            if (empty($insertedData)) {
                return redirect()->back()->with('error', __('Utility already exists!'));
            }

            return redirect('property/'.$id)->with('success', 'Utility added successfully!');

        } else {
            // ✅ sub_category = 0 → without sub_category_name
            $exists = DB::table('utilities_catg')
                ->where('name', $request->name)
                ->where('sub_category', 0)
                ->exists();

            if (! $exists) {

                $idnew = DB::table('utilities_catg')->insertGetId([
                    'name' => $request->name,
                    'sub_category' => 0,
                    'sub_category_name' => null,
                    'property_id' => $id,
                    'status' => $request->status,
                ]);

                $insertedData[] = [
                    'id' => $idnew,
                    'name' => $request->name,
                    'sub_category' => 0,
                    'sub_category_name' => null,
                    'status' => $request->status,
                ];
            }

            if (empty($insertedData)) {
                return redirect()->back()->with('error', __('Utility already exists!'));
            }

            return redirect('property/'.$id)->with('success', 'Utility added successfully!');

        }

    }

    public function property_amenities_update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:amenity_catg,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'price' => 'required',
        ]);

        // Already exist check (except current record)
        $exists = DB::table('amenity_catg')
            ->where('name', $request->name)
            ->where('id', '!=', $request->id) // 👈 current id ko ignore karega
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Amenity already exists!',
            ]);
        }

        // Update
        DB::table('amenity_catg')
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'status' => $request->status,
                'price' => $request->price,
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Amenity updated successfully!',
            'data' => [
                'id' => $request->id,
                'name' => $request->name,
                'price' => $request->price,
                'status' => $request->status,
            ],
        ]);
    }

    public function propertyamenities_update2(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:amenity_catg,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'price' => 'required',
        ]);

        // Already exist check (except current record)
        $exists = DB::table('amenity_catg')
            ->where('property_id', $request->propertyid)
            ->where('name', $request->name)
            ->where('id', '!=', $request->id) // 👈 current id ko ignore karega
            ->exists();

        if ($exists) {

            return redirect('property/'.$request->propertyid.'/'.'edit')->with('error', 'Amenity already exists!');
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Amenity already exists!'
            // ]);
        }

        // Update
        DB::table('amenity_catg')
            ->where('id', $request->id)
            ->where('property_id', $request->propertyid)
            ->update([
                'name' => $request->name,
                'status' => $request->status,
                'price' => $request->price,
            ]);

        return redirect('property/'.$request->propertyid.'/'.'edit')->with('success', 'Amenities updated successfully!');

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Amenity updated successfully!',
        //     'data' => [
        //         'id' => $request->id,
        //         'name' => $request->name,
        //         'status' => $request->status,
        //     ]
        // ]);
    }

    public function property_Utilities_update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'sub_category' => 'required|in:0,1',
            'sub_category_name' => 'nullable|array',
            'sub_category_name.*' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $id = $request->id;
        $name = $request->name;
        $status = $request->status;
        $subCategory = $request->sub_category;
        $subNames = $request->sub_category_name ?? [];
        $userId = Auth::id();

        // 🧹 Clean duplicate and empty names
        $subNames = array_filter(array_unique(array_map('trim', $subNames)));

        if ($subCategory == 0) {
            // ✅ If sub_category = No, update single record
            DB::table('utilities_catg')
                ->where('id', $id)
                ->update([
                    'name' => $name,
                    'sub_category' => 0,
                    'sub_category_name' => null,
                    'status' => $status,
                ]);

        } else {
            // ✅ If sub_category = Yes, remove old subcategories and re-insert new
            $record = DB::table('utilities_catg')->where('id', $id)->first();

            if ($record) {
                DB::table('utilities_catg')
                    ->where('name', $record->name)
                    ->where('sub_category', 1)
                    ->where('user_id', $userId)
                    ->delete();

                foreach ($subNames as $subName) {
                    DB::table('utilities_catg')->insert([
                        'name' => $name,
                        'sub_category' => 1,
                        'sub_category_name' => $subName,
                        'status' => $status,
                        'property_id' => $record->property_id ?? null,
                        'user_id' => $userId,
                    ]);
                }
            }
        }

        // ✅ Fetch updated list of this company's utilities
        $updatedRecords = DB::table('utilities_catg')
            ->select(
                'name',
                'sub_category',
                'status',
                DB::raw('GROUP_CONCAT(sub_category_name ORDER BY sub_category_name SEPARATOR ", ") AS sub_category_names'),
                DB::raw('MIN(id) AS id')
            )
            ->where('name', $name)
            ->where('user_id', $userId)
            ->groupBy('name', 'sub_category', 'status')
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Utilities updated successfully!',
            'data' => $updatedRecords,
        ]);
    }

    public function propertyUtilities_update2(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:utilities_main,id',
            'name' => 'required|string|max:255',
            'sub_category' => 'required|in:0,1',
            'sub_category_name' => 'nullable',
            'status' => 'required|in:0,1',
            'propertyid' => 'required|integer|exists:properties,id',
        ]);

        $userId = Auth::id();
        $propertyId = $request->propertyid;
        $name = trim($request->name);
        $subCategory = (int) $request->sub_category;
        $status = (int) $request->status;

        /**
         * STEP - Normalize sub_category_name
         * It can come as array OR string (like "New 1, New 2")
         */
        $raw = $request->input('sub_category_name');

        if (is_array($raw)) {
            $subCategoryNames = $raw;
        } elseif (is_string($raw)) {
            // Split by comma or newline, then trim each
            $subCategoryNames = preg_split('/[,|\n|\r]+/', $raw);
        } else {
            $subCategoryNames = [];
        }

        // Clean and remove empty or duplicate names
        $subCategoryNames = array_filter(array_unique(array_map('trim', $subCategoryNames)));

        /**
         * STEP - Check for duplicate main company name
         */
        $currentMain = DB::table('utilities_main')->where('id', $request->id)->first();

        if ($currentMain && strtolower(trim($currentMain->name)) !== strtolower($name)) {
            $duplicateMain = DB::table('utilities_main')
                ->where('property_id', $propertyId)
                ->where('user_id', $userId)
                ->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)])
                ->where('id', '!=', $request->id)
                ->exists();

            if ($duplicateMain) {
                return response()->json([
                    'success' => false,
                    'message' => 'Another utility company with this name already exists for this property!',
                ]);
            }
        }
        // $duplicateMain = DB::table('utilities_main')
        //     ->where('property_id', $propertyId)
        //     ->where('user_id', $userId)
        //     ->where('name', $name)
        //     ->where('id', '!=', $request->id)
        //     ->exists();

        // if ($duplicateMain) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Another utility company with this name already exists for this property!',
        //     ]);
        // }

        /**
         * STEP - Fetch existing main and its subcategories
         */
        $main = DB::table('utilities_main')->where('id', $request->id)->first();
        if (!$main) {
            return response()->json([
                'success' => false,
                'message' => 'Utility record not found!',
            ]);
        }

        $existingSubs = DB::table('utilities_sub')
            ->where('utility_main_id', $main->id)
            ->pluck('sub_category_name', 'id')
            ->toArray();

        $existingNames = array_values($existingSubs);
        $existingIds = array_keys($existingSubs);

        $toKeep = [];
        $toAdd = [];
        $toDelete = [];

        // Compare and separate which to keep/add/delete
        foreach ($subCategoryNames as $subName) {
            if (in_array($subName, $existingNames)) {
                $id = array_search($subName, $existingSubs);
                $toKeep[$id] = $subName;
            } else {
                $toAdd[] = $subName;
            }
        }

        foreach ($existingSubs as $id => $nameInDb) {
            if (!in_array($nameInDb, $subCategoryNames)) {
                $toDelete[] = $id;
            }
        }

        /**
         * STEP - Check for duplicates in $toAdd
         */
        if (!empty($toAdd)) {
            $existingDuplicates = DB::table('utilities_sub')
                ->join('utilities_main', 'utilities_sub.utility_main_id', '=', 'utilities_main.id')
                ->where('utilities_main.property_id', $propertyId)
                ->where('utilities_main.user_id', $userId)
                ->where('utilities_main.name', $name)
                ->whereIn('utilities_sub.sub_category_name', $toAdd)
                ->pluck('utilities_sub.sub_category_name')
                ->toArray();

            if (!empty($existingDuplicates)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some subcategories already exist!',
                    'duplicates' => $existingDuplicates,
                ]);
            }
        }

        /**
         * STEP - Update main company record
         */
        DB::table('utilities_main')
            ->where('id', $main->id)
            ->update([
                'name' => $name,
                'status' => $status,
                'updated_at' => now(),
            ]);

        /**
         * STEP - Update existing subcategories (keep)
         */
        foreach ($toKeep as $id => $subName) {
            DB::table('utilities_sub')
                ->where('id', $id)
                ->update([
                    'sub_category_name' => $subName,
                    'status' => $status,
                    'updated_at' => now(),
                ]);
        }

        /**
         * STEP - Add new subcategories
         */
        foreach ($toAdd as $subName) {
            DB::table('utilities_sub')->insert([
                'utility_main_id' => $main->id,
                'sub_category_name' => $subName,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /**
         * STEP - Delete removed subcategories
         */
        if (!empty($toDelete)) {
            DB::table('utilities_sub')
                ->whereIn('id', $toDelete)
                ->delete();
        }

        /**
         * STEP - Fetch updated subcategory list for response
         */
        $updatedSubNames = DB::table('utilities_sub')
            ->where('utility_main_id', $main->id)
            ->pluck('sub_category_name')
            ->toArray();

        return response()->json([
            'success' => true,
            'message' => 'Utilities updated successfully!',
            'data' => [
                'id' => $main->id,
                'name' => $name,
                'sub_category' => $subCategory,
                'sub_category_names' => implode(', ', $updatedSubNames),
                'status' => $status,
            ],
        ]);
    }
    public function propertyUtilities_update2_old(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:utilities_catg,id',
            'name' => 'required|string|max:255',
            'sub_category' => 'required|in:0,1',
            'sub_category_name' => 'nullable',
            'status' => 'required|in:0,1',
        ]);

        // $propertyId = $request->propertyid;
        // $name = $request->name;
        // $subCategory = $request->sub_category;
        // $status = $request->status;
        // $subCategoryNames = $request->sub_category_name ?? [null];

        $userId = Auth::id();
        $propertyId = $request->propertyid;
        $name = trim($request->name);
        $subCategory = $request->sub_category;
        $status = $request->status;

        $raw = $request->input('sub_category_name');
        if (is_array($raw)) {
            $subCategoryNames = $raw;
        } elseif (is_string($raw)) {
            $subCategoryNames = array_map('trim', preg_split('/[,\n\r]+/', $raw));
        } else {
            $subCategoryNames = [];
        }

        // clean and unique
        $subCategoryNames = array_filter(array_unique($subCategoryNames));        

        // Fetch existing subcategories for this property & company
        $existing = DB::table('utilities_catg')
            ->where('property_id', $propertyId)
            ->where('name', $name)
            ->where('user_id', $userId)
            ->pluck('sub_category_name', 'id')
            ->toArray();

        $existingNames = array_values($existing);
        $existingIds = array_keys($existing);

        $toKeep = [];
        $toAdd = [];
        $toDelete = [];

        // Identify what to update/add/delete
        foreach ($subCategoryNames as $subName) {
            if (in_array($subName, $existingNames)) {
                // Keep it
                $id = array_search($subName, $existing);
                $toKeep[$id] = $subName;
            } else {
                $toAdd[] = $subName;
            }
        }

        // Determine deletions
        foreach ($existing as $id => $nameInDb) {
            if (!in_array($nameInDb, $subCategoryNames)) {
                $toDelete[] = $id;
            }
        }

        /** STEP 4: Duplicate check only for $toAdd (not existing) **/
        if (!empty($toAdd)) {
            $existingDuplicates = DB::table('utilities_catg')
                ->where('property_id', $propertyId)
                ->where('user_id', $userId)
                ->where('name', $name)
                ->whereIn('sub_category_name', $toAdd)
                ->pluck('sub_category_name')
                ->toArray();

            if (!empty($existingDuplicates)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utility already exists!',
                    'duplicates' => $existingDuplicates,
                ]);
            }
        }
        
        // Update kept ones
        foreach ($toKeep as $id => $subName) {
            DB::table('utilities_catg')
                ->where('id', $id)
                ->update([
                    'name' => $name,
                    'sub_category' => $subCategory,
                    'sub_category_name' => $subName,
                    'status' => $status,
                    'updated_at' => now(),
                ]);
        }

        // Insert new ones
        foreach ($toAdd as $subName) {
            DB::table('utilities_catg')->insert([
                'property_id' => $propertyId,
                'user_id' => $userId,
                'name' => $name,
                'sub_category' => $subCategory,
                'sub_category_name' => $subName,
                'status' => $status,
                'falge' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Delete removed ones
        if (!empty($toDelete)) {
            DB::table('utilities_catg')
                ->whereIn('id', $toDelete)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Utilities updated successfully!',
            'data' => [
                'id' => $request->id,
                'name' => $request->name,
                'sub_category' => $request->sub_category,
                'sub_category_names' => implode(', ', array_filter($subCategoryNames)),
                'status' => $request->status,
            ],
        ]);

        // ✅ Force sub_category_name into array always
        // $subCategoryNames = is_array($request->sub_category_name)
        //     ? $request->sub_category_name
        //     : (empty($request->sub_category_name) ? [null] : [$request->sub_category_name]);

        // ✅ Check duplicates
        // foreach ($subCategoryNames as $subName) {
        //     $exists = DB::table('utilities_catg')
        //         ->where('property_id', $propertyId)
        //         ->where('user_id', $userId)
        //         ->where('name', $name)
        //         ->where('sub_category_name', $subName)
        //         ->exists();

        //     if ($exists) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Utility already exists!',
        //         ]);
        //     }
        // }

        // ✅ Delete old entries for this property & company
        // DB::table('utilities_catg')
        //     ->where('property_id', $propertyId)
        //     ->where('name', $name)
        //     ->where('user_id', $userId)
        //     ->delete();

        // // ✅ Insert new rows (one per subcategory)
        // foreach ($subCategoryNames as $subName) {
        //     DB::table('utilities_catg')->insert([
        //         'property_id' => $propertyId,
        //         'user_id' => $userId,
        //         'name' => $name,
        //         'sub_category' => $subCategory,
        //         'sub_category_name' => $subCategory == 1 ? $subName : null,
        //         'status' => $status,
        //         'falge' => 1,
        //         'created_at' => now(),
        //     ]);
        // }

        // $raw = $request->input('sub_category_name');

        // if (is_array($raw)) {
        //     $subCategoryNames = $raw;
        // } elseif (is_null($raw) || $raw === '') {
        //     $subCategoryNames = [$subCategory == 1 ? null : null]; // single null entry for no subcategory
        // } else {
        //     // if it's a comma-separated string, split on comma; otherwise treat as single item
        //     // also support newline separated values
        //     if (strpos($raw, ',') !== false || strpos($raw, "\n") !== false) {
        //         $parts = preg_split("/[,\n\r]+/", $raw);
        //         $subCategoryNames = $parts;
        //     } else {
        //         $subCategoryNames = [$raw];
        //     }
        // }

        // // trim and remove empty values
        // $subCategoryNames = array_values(array_filter(array_map(function($v){
        //     return is_null($v) ? null : trim($v);
        // }, $subCategoryNames), function($v){
        //     // keep null if we explicitly want null (for no sub_category case)
        //     return $v !== '' && $v !== null ? true : false;
        // }));

        // // If sub_category == 0, we should insert a single row with sub_category_name = null
        // if ($subCategory == 0) {
        //     $subCategoryNames = [null];
        // }

        // // Optional: check duplicates BEFORE deleting/inserting (prevent same subcategory duplicate)
        // foreach ($subCategoryNames as $subName) {
        //     $exists = DB::table('utilities_catg')
        //         ->where('property_id', $propertyId)
        //         ->where('user_id', $userId)
        //         ->where('name', $name)
        //         ->where(function($q) use ($subName) {
        //             if (is_null($subName)) {
        //                 $q->whereNull('sub_category_name');
        //             } else {
        //                 $q->where('sub_category_name', $subName);
        //             }
        //         })
        //         ->exists();

        //     if ($exists) {
        //         // if you want to allow duplicates when editing (i.e. update), you can skip this
        //         // for now return duplicate message
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'One or more utilities already exist for this property and company.',
        //         ]);
        //     }
        // }

        // // delete existing rows for this property + company + user
        // DB::table('utilities_catg')
        //     ->where('property_id', $propertyId)
        //     ->where('name', $name)
        //     ->where('user_id', $userId)
        //     ->delete();

        // // insert one row per subcategory (or single with null)
        // foreach ($subCategoryNames as $subName) {
        //     DB::table('utilities_catg')->insert([
        //         'property_id' => $propertyId,
        //         'user_id' => $userId,
        //         'name' => $name,
        //         'sub_category' => $subCategory,
        //         'sub_category_name' => $subCategory == 1 ? $subName : null,
        //         'status' => $status,
        //         'falge' => 1,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Utility updated successfully!',
        //     'data' => [
        //         'id' => $request->id,
        //         'name' => $request->name,
        //         'sub_category' => $request->sub_category,
        //         'sub_category_names' => implode(', ', array_filter($subCategoryNames)),
        //         'status' => $request->status,
        //     ],
        // ]);
        // $subCategoryName = is_array($request->sub_category_name)
        //     ? implode(', ', $request->sub_category_name)
        //     : $request->sub_category_name;

        // $exists = DB::table('utilities_catg')
        //     ->where('name', $request->name)
        //     ->where('property_id', $request->propertyid)
        //     ->where('sub_category', $request->sub_category)
        //     ->where('user_id', $userId)
        //     ->where(function ($query) use ($request, $subCategoryName) {
        //         if ($request->sub_category == 1) {
        //             $query->where('sub_category_name', $subCategoryName);
        //         } else {
        //             $query->whereNull('sub_category_name');
        //         }
        //     })
        //     ->where('id', '!=', $request->id)
        //     ->exists();

        // if ($exists) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Utility already exists!',
        //     ]);
        // }

        // DB::table('utilities_catg')
        //     ->where('id', $request->id)
        //     ->where('property_id', $request->propertyid)
        //     ->update([
        //         'name' => $request->name,
        //         'sub_category' => $request->sub_category,
        //         'sub_category_name' => $subCategoryName,
        //         'status' => $request->status,
        //     ]);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Utility updated successfully!',
        //     'data' => [
        //         'id' => $request->id,
        //         'name' => $request->name,
        //         'sub_category' => $request->sub_category,
        //         'sub_category_names' => implode(', ', $subCategoryNames),
        //         'status' => $request->status,
        //     ],
        // ]);
    }

    public function addUtilities_update(Request $request, $id, $propertyid)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_category' => 'required|in:0,1',
            'sub_category_name' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        // Check if combination already exists (except current record)
        $exists = DB::table('utilities_catg')
            ->where('name', $request->name)
            ->where('sub_category', $request->sub_category)
            ->where(function ($query) use ($request) {
                if ($request->sub_category == 1) {
                    $query->where('sub_category_name', $request->sub_category_name);
                } else {
                    $query->whereNull('sub_category_name');
                }
            })
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', __('Utilities already exists!'));
        }

        // Update the record
        DB::table('utilities_catg')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'sub_category' => $request->sub_category,
                'sub_category_name' => $request->sub_category == 1 ? $request->sub_category_name : null,
                'status' => $request->status,
            ]);

        return redirect('property/'.$propertyid)->with('success', 'Utilities updated successfully!');
    }

    public function show(Property $property)
    {
        if (\Auth::user()->can('show property')) {
            $units = PropertyUnit::where('property_id', $property->id)->orderBy('id', 'desc')->get();
            $statesdataview = DB::table('states')->where('id', $property->state_id)->first();
            $citiesview = DB::table('cities')->where('id', $property->city_id)->first();
            $amenities = DB::table('amenity_catg')->where('property_id', $property->id)->get();
            $utilities = DB::table('utilities_catg')->where('property_id', $property->id)->get();

            return view('property.show', compact('property', 'units', 'statesdataview', 'citiesview', 'amenities', 'utilities'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function edit(Property $property)
    {
        if (\Auth::user()->can('edit property')) {
            $userId = Auth::id();
            $types = Property::$Type;
            $statesdata = DB::table('states')->get();
            $propertyimages = DB::table('property_images')->where('property_id', $property->id)->where('type', 'thumbnail')->first();
            $propertyextraimages = DB::table('property_images')->where('property_id', $property->id)->where('type', 'extra')->get();
            $amenities = DB::table('amenity_catg')->where('property_id', $property->id)->where('user_id', $userId)->get();
            // $utilities = DB::table('utilities_catg')->where('property_id', $property->id)->where('user_id', $userId)->get();
            $utilities = DB::table('utilities_main as m')
                ->leftJoin('utilities_sub as s', 'm.id', '=', 's.utility_main_id')
                ->select(
                    'm.id',
                    'm.name',
                    'm.status',
                    DB::raw('GROUP_CONCAT(s.sub_category_name ORDER BY s.sub_category_name SEPARATOR ", ") as sub_category_name'),
                    DB::raw('CASE WHEN COUNT(s.id) > 0 THEN 1 ELSE 0 END as sub_category')
                )
                ->where('m.property_id', $property->id)
                ->where('m.user_id', $userId)
                ->groupBy('m.id', 'm.name', 'm.status')
                ->get();
            $units = DB::table('property_units')->where('property_id', $property->id)->get();

            return view('property.create', compact('types', 'property', 'statesdata', 'propertyimages', 'propertyextraimages', 'amenities', 'utilities', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function update(Request $request, Property $property)
    {
        if (! \Auth::user()->can('edit property')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        DB::beginTransaction();
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'country' => 'required|string',
                'state' => 'required',
                'city' => 'required',
                'zip_code' => 'required',
                'address' => 'required|string',
                'mortgage_amount' => 'nullable|numeric|min:0',
                'insurance_amount' => 'nullable|numeric|min:0',
                'amenities_amount' => 'nullable|numeric|min:0',
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);
            }

            // Update Property
            $property->update([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'country' => $request->country,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
                'mortgage_amount' => $request->mortgage_amount,
                'insurance_amount' => $request->insurance_amount,
                'amenities_amount' => $request->amenities_amount,
            ]);

            if ($request->has('unitname')) {
                foreach ($request->unitname as $key => $name) {
                    if (empty($name)) {
                        continue;
                    }

                    $unitId = $request->unit_id[$key] ?? null;

                    if ($unitId) {
                        // ✅ Update existing unit
                        $unit = PropertyUnit::find($unitId);
                        if ($unit) {
                            $unit->update([
                                'name' => $name,
                                'status' => $request->status[$key] ?? 0,
                                'notes' => $request->notes[$key] ?? null,
                            ]);
                        }
                    } else {
                        // ✅ Create new unit (only if name not empty)
                        PropertyUnit::create([
                            'name' => $name,
                            'status' => $request->status[$key] ?? 0,
                            'notes' => $request->notes[$key] ?? null,
                            'property_id' => $property->id,
                            'parent_id' => parentId(),
                        ]);
                    }
                }
            }

            // Thumbnail update
            if ($request->hasFile('thumbnail')) {
                if (! empty($property->thumbnail) && isset($property->thumbnail->image)) {
                    $image_path = 'storage/upload/thumbnail/'.$property->thumbnail->image;
                    if (\File::exists($image_path)) {
                        \File::delete($image_path);
                    }
                }

                $thumbnailFilenameWithExt = $request->file('thumbnail')->getClientOriginalName();
                $thumbnailFilename = pathinfo($thumbnailFilenameWithExt, PATHINFO_FILENAME);
                $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnailFileName = $thumbnailFilename.'_'.time().'.'.$thumbnailExtension;
                $dir = storage_path('upload/thumbnail');
                if (! file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('thumbnail')->storeAs('upload/thumbnail/', $thumbnailFileName);
                $thumbnail = PropertyImage::where('property_id', $property->id)->where('type', 'thumbnail')->first();
                $thumbnail->image = $thumbnailFileName;
                $thumbnail->save();
            }

            // Extra Images
            if ($request->hasFile('property_images')) {
                foreach ($request->property_images as $file) {
                    $propertyFilenameWithExt = $file->getClientOriginalName();
                    $propertyFilename = pathinfo($propertyFilenameWithExt, PATHINFO_FILENAME);
                    $propertyExtension = $file->getClientOriginalExtension();
                    $propertyFileName = $propertyFilename.'_'.time().'.'.$propertyExtension;
                    $dir = storage_path('upload/property');
                    if (! file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/property/', $propertyFileName);

                    $propertyImage = new PropertyImage;
                    $propertyImage->property_id = $property->id;
                    $propertyImage->image = $propertyFileName;
                    $propertyImage->type = 'extra';
                    $propertyImage->save();
                }
            }

            // // Update Amenities
            // if ($request->has('amenities')) {
            //     $amenities = json_decode($request->amenities, true);
            //     foreach ($amenities as $a) {
            //         DB::table('amenity_catg')->updateOrInsert(
            //             ['property_id' => $property->id, 'name' => $a['name']],
            //             [
            //                 'price' => $a['price'],
            //                 'status' => $a['status'],
            //                 'user_id' => Auth::id(),
            //             ]
            //         );
            //     }
            // }

            // // Update Utilities
            // if ($request->has('utilities')) {
            //     $utilities = json_decode($request->utilities, true);
            //     foreach ($utilities as $u) {
            //         DB::table('utilities_catg')->updateOrInsert(
            //             ['property_id' => $property->id, 'name' => $u['name']],
            //             [
            //                 'sub_category' => $u['sub_category'],
            //                 'sub_category_name' => $u['sub_category_names'] ?? null,
            //                 'status' => $u['status'],
            //                 'user_id' => Auth::id(),
            //             ]
            //         );
            //     }
            // }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg' => 'Property updated successfully.',
                'id' => $property->id,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Property $property)
    {
        if (\Auth::user()->can('delete property')) {
            $property->delete();

            return redirect()->back()->with('success', 'Property successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function units()
    {
        if (\Auth::user()->can('manage unit')) {
            $units = PropertyUnit::where('parent_id', parentId())->where('property_id', '!=', 0)->get();

            return view('unit.index', compact('units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function unitCreate($property_id)
    {

        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;

        return view('unit.create', compact('types', 'property_id', 'rentTypes'));
    }

    public function unitStore(Request $request, $property_id)
    {

        if (\Auth::user()->can('create unit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'bedroom' => 'required',
                    'kitchen' => 'required',
                    'baths' => 'required',
                    'rent' => 'required',
                    'rent_type' => 'required',
                    'deposit_type' => 'required',
                    'deposit_amount' => 'required',
                    'late_fee_type' => 'required',
                    'late_fee_amount' => 'required',
                    'incident_receipt_amount' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $unit = new PropertyUnit;
            $unit->name = $request->name;
            $unit->bedroom = $request->bedroom;
            $unit->kitchen = $request->kitchen;
            $unit->baths = $request->baths;
            $unit->rent = $request->rent;
            $unit->rent_type = $request->rent_type;
            if ($request->rent_type == 'custom') {
                $unit->start_date = $request->start_date;
                $unit->end_date = $request->end_date;
                $unit->payment_due_date = $request->payment_due_date;
            } else {
                $unit->rent_duration = $request->rent_duration;
            }

            $unit->deposit_type = $request->deposit_type;
            $unit->deposit_amount = $request->deposit_amount;
            $unit->late_fee_type = $request->late_fee_type;
            $unit->late_fee_amount = $request->late_fee_amount;
            $unit->incident_receipt_amount = $request->incident_receipt_amount;
            $unit->notes = $request->notes;
            $unit->property_id = $property_id;
            $unit->parent_id = parentId();
            $unit->save();

            return redirect()->back()->with('success', __('Unit successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function unitdirectCreate()
    {
        $name = Property::all('name', 'id')->pluck('name', 'id');
        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');

        return view('unit.directcreate', compact('types', 'rentTypes', 'name', 'property'));
    }

    // public function unitdirectStore(Request $request)
    // {
    //     if (\Auth::user()->can('create unit')) {
    //         $validator = \Validator::make(
    //             $request->all(),
    //             [
    //                 'name' => 'required',
    //                 'status' => 'required',
    //                 'notes' => 'required',
    //                 'property_id' => 'required',
    //                 // 'bedroom' => 'required',
    //                 // 'kitchen' => 'required',
    //                 // 'baths' => 'required',
    //                 // 'rent' => 'required',
    //                 // 'rent_type' => 'required',
    //                 // 'deposit_type' => 'required',
    //                 // 'deposit_amount' => 'required',
    //                 // 'late_fee_type' => 'required',
    //                 // 'late_fee_amount' => 'required',
    //                 // 'incident_receipt_amount' => 'required',
    //             ]
    //         );
    //         if ($validator->fails()) {
    //             $messages = $validator->getMessageBag();

    //             return redirect()->back()->with('error', $messages->first());
    //         }

    //         $unit = new PropertyUnit();
    //         $unit->name = $request->name;
    //         $unit->status = $request->status;
    //         $unit->property_id = $request->property_id;
    //         // $unit->bedroom = $request->bedroom;
    //         // $unit->kitchen = $request->kitchen;
    //         // $unit->baths = $request->baths;
    //         // $unit->rent = $request->rent;
    //         // $unit->rent_type = $request->rent_type;
    //         // if ($request->rent_type == 'custom') {
    //         //     $unit->start_date = $request->start_date;
    //         //     $unit->end_date = $request->end_date;
    //         //     $unit->payment_due_date = $request->payment_due_date;
    //         // } else {
    //         //     $unit->rent_duration = $request->rent_duration;
    //         // }

    //         // $unit->deposit_type = $request->deposit_type;
    //         // $unit->deposit_amount = $request->deposit_amount;
    //         // $unit->late_fee_type = $request->late_fee_type;
    //         // $unit->late_fee_amount = $request->late_fee_amount;
    //         // $unit->incident_receipt_amount = $request->incident_receipt_amount;
    //         $unit->notes = $request->notes;
    //         $unit->parent_id = parentId();
    //         $unit->save();
    //         return redirect()->back()->with('success', __('Unit successfully created.'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }

    public function unitdirectStore(Request $request)
    {
        if (! \Auth::user()->can('create unit')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'status' => 'required',
                'notes' => 'required',
                'property_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // 🔎 DB check if unit name already exists in this property
        $exists = PropertyUnit::where('property_id', $request->property_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Unit name already exists in this property.');
        }

        // ✅ Create new unit
        $unit = new PropertyUnit;
        $unit->name = $request->name;
        $unit->status = $request->status;
        $unit->property_id = $request->property_id;
        $unit->notes = $request->notes;
        $unit->parent_id = parentId();
        $unit->save();

        return redirect()->back()->with('success', __('Unit successfully created.'));
    }

    public function unitEdit($property_id, $unit_id)
    {
        $unit = PropertyUnit::find($unit_id);
        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');

        return view('unit.edit', compact('types', 'property_id', 'rentTypes', 'unit', 'property'));
    }

    // public function unitUpdate(Request $request, $property_id, $unit_id)
    // {
    //     if (\Auth::user()->can('edit unit')) {
    //         $validator = \Validator::make(
    //             $request->all(),
    //             [
    //                 'name' => 'required',
    //                 'status' => 'required',
    //                 'notes' => 'required',
    //                 'property_id' => 'required',
    //                 // 'bedroom' => 'required',
    //                 // 'kitchen' => 'required',
    //                 // 'baths' => 'required',
    //                 // 'rent' => 'required',
    //                 // 'rent_type' => 'required',
    //                 // 'deposit_type' => 'required',
    //                 // 'deposit_amount' => 'required',
    //                 // 'late_fee_type' => 'required',
    //                 // 'late_fee_amount' => 'required',
    //                 // 'incident_receipt_amount' => 'required',
    //             ]
    //         );
    //         if ($validator->fails()) {
    //             $messages = $validator->getMessageBag();

    //             return redirect()->back()->with('error', $messages->first());
    //         }

    //         $unit = PropertyUnit::find($unit_id);
    //         $unit->name = $request->name;
    //         $unit->status = $request->status;
    //         $unit->property_id = $request->property_id;
    //         // $unit->bedroom = $request->bedroom;
    //         // $unit->kitchen = $request->kitchen;
    //         // $unit->baths = $request->baths;
    //         // $unit->rent = $request->rent;
    //         // $unit->rent_type = $request->rent_type;
    //         // if ($request->rent_type == 'custom') {
    //         //     $unit->start_date = $request->start_date;
    //         //     $unit->end_date = $request->end_date;
    //         //     $unit->payment_due_date = $request->payment_due_date;
    //         // } else {
    //         //     $unit->rent_duration = $request->rent_duration;
    //         // }

    //         // $unit->deposit_type = $request->deposit_type;
    //         // $unit->deposit_amount = $request->deposit_amount;
    //         // $unit->late_fee_type = $request->late_fee_type;
    //         // $unit->late_fee_amount = $request->late_fee_amount;
    //         // $unit->incident_receipt_amount = $request->incident_receipt_amount;
    //         $unit->notes = $request->notes;
    //         $unit->save();
    //         return redirect()->back()->with('success', __('Unit successfully updated.'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }

    public function unitUpdate(Request $request, $property_id, $unit_id)
    {
        if (! \Auth::user()->can('edit unit')) {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }

        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'status' => 'required',
                'notes' => 'required',
                'property_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // 🔎 DB check if unit name already exists for this property (excluding current unit)
        $exists = PropertyUnit::where('property_id', $request->property_id)
            ->where('name', $request->name)
            ->where('id', '!=', $unit_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Unit name already exists in this property.');
        }

        // ✅ Update unit
        $unit = PropertyUnit::findOrFail($unit_id);
        $unit->name = $request->name;
        $unit->status = $request->status;
        $unit->property_id = $request->property_id;
        $unit->notes = $request->notes;
        $unit->save();

        return redirect()->back()->with('success', __('Unit successfully updated.'));
    }

    public function unitDestroy($property_id, $unit_id)
    {
        if (\Auth::user()->can('delete unit')) {
            $unit = PropertyUnit::find($unit_id);
            $unit->delete();

            return redirect()->back()->with('success', 'Unit successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function getPropertyUnit($property_id)
    {
        $units = PropertyUnit::where('property_id', $property_id)->where('status', '1')->get()->pluck('name', 'id');

        return response()->json($units);
    }

    public function utility_invoicesgenerate_bkp(Request $request)
    {
        \Log::info('⚡ utility_invoicesgenerate reached', [
            'headers' => $request->headers->all()
        ]);
        $payload = $request->all();

    // 🟢 Step 2: Normalize date strings before validation
    foreach ($payload['invoices'] ?? [] as &$inv) {
        foreach ($inv['details'] ?? [] as &$detail) {
            foreach (['start_date', 'end_date'] as $key) {
                if (!empty($detail[$key])) {
                    $val = trim($detail[$key]);
                    try {
                        // ✅ If format is mm-dd-yyyy → convert to yyyy-mm-dd
                        if (preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $val)) {
                            $detail[$key] = \Carbon\Carbon::createFromFormat('m-d-Y', $val)->format('Y-m-d');
                        }
                        // ✅ If format is dd-mm-yyyy → also handle
                        elseif (preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $val)) {
                            $detail[$key] = \Carbon\Carbon::createFromFormat('d-m-Y', $val)->format('Y-m-d');
                        }
                        // ✅ If already ISO or parseable
                        else {
                            $detail[$key] = \Carbon\Carbon::parse($val)->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        // 🚫 If not parseable → null it out
                        $detail[$key] = null;
                    }
                }
            }
        }
    }

    // 🟢 Step 3: Replace request data with normalized payload
    $request->replace($payload);

    try {
        $data = $request->validate([
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'invoice_month' => ['required', 'regex:/^\d{4}\-\d{2}$/'], // YYYY-MM
            'invoices' => ['required', 'array', 'min:1'],
            'invoices.*.tenant_id' => ['required', 'integer', 'exists:tenants,id'],
            'invoices.*.amount' => ['required', 'numeric', 'min:0.01'],
            'invoices.*.details' => ['required', 'array', 'min:1'],
            'invoices.*.details.*.property_utility_id' => ['nullable', 'integer'],
            'invoices.*.details.*.category' => ['required', 'string', 'max:191'],
            'invoices.*.details.*.amount' => ['required', 'numeric'],
            'invoices.*.details.*.start_date' => ['nullable', 'date'],
            'invoices.*.details.*.end_date' => ['nullable', 'date'],
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('❌ Validation failed', [
            'errors' => $e->errors(),
        ]);
        return response()->json([
            'ok' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    }

        $ownerId = optional(Auth::user()->owner)->id;
        $created = [];
        $updated = [];

        // Normalize payload: filter zero-amount details and recompute invoice amounts
        $normalizedInvoices = [];
        foreach ($data['invoices'] as $inv) {
            $details = array_values(array_filter($inv['details'], function ($row) {
                return isset($row['amount']) && (float) $row['amount'] > 0;
            }));
            if (empty($details)) {
                continue; // skip invoices with no payable details
            }
            $sum = 0;
            foreach ($details as $d) {
                $sum += (float) $d['amount'];
            }
            $inv['amount'] = round($sum, 2);
            $inv['details'] = array_map(function ($d) {
                $d['amount'] = round((float) $d['amount'], 2);

                return $d;
            }, $details);
            $normalizedInvoices[] = $inv;
        }

        if (empty($normalizedInvoices)) {
            return response()->json([
                'ok' => false,
                'message' => 'No payable items found. Please enter prices and tenant shares.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($data, $ownerId, &$created, &$updated, $normalizedInvoices) {
                foreach ($normalizedInvoices as $inv) {
                    // find existing invoice for property + tenant + month
                    $invoice = UtilityInvoice::where('property_id', $data['property_id'])
                        ->where('tenant_id', $inv['tenant_id'])
                        ->where('invoice_month', $data['invoice_month'])
                        ->first();

                    if ($invoice) {
                        // OVERWRITE total amount (do not accumulate blindly)
                        $invoice->amount = $inv['amount'];
                        $invoice->due_date = $data['due_date'] ?? $invoice->due_date;
                        $invoice->status = 'draft';
                        $invoice->save();

                        // Update details: overwrite existing detail amounts or create if missing
                        foreach ($inv['details'] as $row) {
                            $detailQuery = $invoice->details()
                                ->where('property_utility_id', $row['property_utility_id'] ?? null)
                                ->where('category', $row['category']);

                            $detail = $detailQuery->first();

                            if ($detail) {
                                $detail->amount = $row['amount'];
                                $detail->start_date = $row['start_date'] ?? $detail->start_date;
                                $detail->end_date   = $row['end_date'] ?? $detail->end_date;
                                $detail->save();
                            } else {
                                $invoice->details()->create([
                                    'tenant_id' => $inv['tenant_id'],
                                    'property_utility_id' => $row['property_utility_id'] ?? null,
                                    'category' => $row['category'],
                                    'amount' => $row['amount'],
                                    'start_date' => $row['start_date'] ?? null,
                                    'end_date' => $row['end_date'] ?? null,
                                ]);
                            }
                        }

                        $updated[] = [
                            'invoice_id' => $invoice->id,
                            'tenant_id' => $invoice->tenant_id,
                            'amount' => $invoice->amount,
                            'details_count' => count($inv['details']),
                        ];
                    } else {
                        // Create new invoice (same as before)
                        $invoiceNo = $this->nextInvoiceNumber($data['invoice_month']);

                        $invoice = UtilityInvoice::create([
                            'property_id' => $data['property_id'],
                            'owner_id' => $ownerId,
                            'tenant_id' => $inv['tenant_id'],
                            'invoice_number' => $invoiceNo,
                            'invoice_date' => now()->toDateString(),
                            'invoice_month' => $data['invoice_month'],
                            'amount' => $inv['amount'],
                            'status' => 'draft',
                        ]);

                        foreach ($inv['details'] as $row) {
                            $invoice->details()->create([
                                'tenant_id' => $inv['tenant_id'],
                                'property_utility_id' => $row['property_utility_id'] ?? null,
                                'category' => $row['category'],
                                'amount' => $row['amount'],
                                'start_date' => $row['start_date'] ?? null,
                                'end_date' => $row['end_date'] ?? null,
                            ]);
                        }

                        $created[] = [
                            'invoice_id' => $invoice->id,
                            'tenant_id' => $invoice->tenant_id,
                            'amount' => $invoice->amount,
                            'details_count' => count($inv['details']),
                        ];
                    }
                }
            });
            $tenantMap = []; // map tenant_id => normalized invoice data
                foreach ($normalizedInvoices as $inv) {
                    $tenantMap[$inv['tenant_id']] = $inv;
                }

                // then iterate tenantMap to send mail
                foreach ($tenantMap as $tenantId => $invPayload) {
                    $tenant = Tenant::with('user')->find($tenantId);
                    if (!$tenant || !optional($tenant->user)->email) continue;

                    $mailData = [
                        'invoice_number' => $inv->invoice_number/* find actual invoice number from DB if needed: */,
                        'invoice_date' => now()->toDateString(),
                        'due_date' => $data['due_date'] ?? null,
                        'tenant_name' => trim(optional($tenant->user)->first_name . ' ' . optional($tenant->user)->last_name),
                        'tenant_email' => optional($tenant->user)->email,
                        'property_name' => optional($tenant->property)->name ?? $property->name ?? '',
                        'property_address' => $property->address ?? '',
                        'items' => array_map(function($d){
                            return [
                                'category' => $d['category'],
                                'amount' => round((float)$d['amount'], 2),
                                'start_date' => $d['start_date'] ?? null,
                                'end_date' => $d['end_date'] ?? null,
                            ];
                        }, $invPayload['details']),
                        'total_amount' => round((float)$invPayload['amount'], 2),
                    ];

                    // If you need the real invoice_number (INV-...), fetch it:
                    $dbInvoice = UtilityInvoice::where('property_id', $data['property_id'])
                        ->where('tenant_id', $tenantId)
                        ->where('invoice_month', $data['invoice_month'])
                        ->first();
                    if ($dbInvoice) $mailData['invoice_number'] = $dbInvoice->invoice_number;

                    // Mail::to($mailData['tenant_email'])->send(new UtilityInvoiceMail($mailData));
                    Mail::to('komalshani1997@gmail.com')->send(new UtilityInvoiceMail($mailData));
                }

        } catch (\Throwable $e) {
            \Log::error('Utility invoice generate failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Server error generating invoices. Please check data and try again.',
            ], 500);
        }

        // Send emails and mark as sent
        // $affectedInvoiceIds = collect($created)->pluck('invoice_id')
        //     ->merge(collect($updated)->pluck('invoice_id'))
        //     ->unique()
        //     ->values();

        // $invoices = UtilityInvoice::with(['tenant.user', 'property', 'details'])
        //     ->whereIn('id', $affectedInvoiceIds)
        //     ->get();

        // foreach ($invoices as $inv) {
        //     // Mark as delivered (locked)
        //     $inv->status = 'delivered';
        //     $inv->save();

        //     $tenantUser = optional($inv->tenant)->user;
        //     $tenantEmail = $tenantUser->email ?? null;
        //     if ($tenantEmail) {
                
        //         try {
        //             $mailData = [
        //                 'invoice_number' => $inv->invoice_number,
        //                 'invoice_date'   => $inv->invoice_date,
        //                 'due_date'       => $inv->due_date,
        //                 'tenant_name'    => trim(($tenantUser->first_name ?? '') . ' ' . ($tenantUser->last_name ?? '')),
        //                 'tenant_email'   => $tenantEmail,
        //                 'property_name'  => $inv->property->name ?? 'N/A',
        //                 'property_address' => $inv->property->address ?? '',
        //                 'items'          => $inv->details->map(function ($d) {
        //                     return [
        //                         'category'    => $d->category,
        //                         'amount'      => $d->amount,
        //                         'start_date'  => $d->start_date,
        //                         'end_date'    => $d->end_date,
        //                     ];
        //                 }),
        //                 'total_amount'   => $inv->amount,
        //             ];

        //             // Mail::to($tenantEmail)->send(new UtilityInvoiceMail($mailData));
        //             Mail::to('komalshani1997@gmail.com')->send(new UtilityInvoiceMail($mailData));
        //             // Mail::to($tenantEmail)->send(new InvoiceMail([
        //             //     'invoice_number' => $inv->invoice_number,
        //             //     'invoice_date' => optional($inv->invoice_date)->toDateString(),
        //             //     'due_date' => optional($inv->due_date)->toDateString(),
        //             //     'amount' => (string) $inv->amount,
        //             //     'url' => $invoiceUrl,
        //             //     'property_id' => $inv->property_id,
        //             // ]));
        //         } catch (\Throwable $e) {
        //             // Log but do not fail the response
        //             \Log::warning('Invoice email failed: '.$e->getMessage(), ['invoice_id' => $inv->id]);
        //         }
        //     }
        // }

        return response()->json([
            'ok' => true,
            'message' => sprintf(
                '%d invoice(s) created, %d invoice(s) updated, %d emailed',
                count($created),
                count($updated),
                $invoices->count()
            ),
            'created' => $created,
            'updated' => $updated,
        ]);
    }

    public function utility_invoicesgenerate(Request $request)
    {
        \Log::info('⚡ utility_invoicesgenerate reached', [
            'headers' => $request->headers->all()
        ]);

        $payload = $request->all();

        // 🟢 Step 2: Normalize date strings
        foreach ($payload['invoices'] ?? [] as &$inv) {
            foreach ($inv['details'] ?? [] as &$detail) {
                foreach (['start_date', 'end_date'] as $key) {
                    if (!empty($detail[$key])) {
                        $val = trim($detail[$key]);
                        try {
                            if (preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $val)) {
                                $detail[$key] = \Carbon\Carbon::createFromFormat('m-d-Y', $val)->format('Y-m-d');
                            } else {
                                $detail[$key] = \Carbon\Carbon::parse($val)->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            $detail[$key] = null;
                        }
                    }
                }
            }
        }
        $request->replace($payload);

        try {
            $data = $request->validate([
                'property_id' => ['required', 'integer', 'exists:properties,id'],
                'invoice_month' => ['required', 'regex:/^\d{4}\-\d{2}$/'], // YYYY-MM
                'invoices' => ['required', 'array', 'min:1'],
                'invoices.*.tenant_id' => ['required', 'integer', 'exists:tenants,id'],
                'invoices.*.amount' => ['required', 'numeric', 'min:0.01'],
                'invoices.*.details' => ['required', 'array', 'min:1'],
                'invoices.*.details.*.category' => ['required', 'string', 'max:191'],
                'invoices.*.details.*.amount' => ['required', 'numeric'],
                'invoices.*.details.*.start_date' => ['nullable', 'date'],
                'invoices.*.details.*.end_date' => ['nullable', 'date'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $ownerId = optional(Auth::user()->owner)->id;
        $created = [];
        $updated = [];

        // 🟢 Step 3: Normalize payload
        $normalizedInvoices = [];
        foreach ($data['invoices'] as $inv) {
            $details = array_values(array_filter($inv['details'], fn($row) => (float)($row['amount'] ?? 0) > 0));
            if (empty($details)) continue;

            $inv['amount'] = round(array_sum(array_column($details, 'amount')), 2);
            $inv['details'] = array_map(fn($d) => ['amount' => round($d['amount'], 2)] + $d, $details);
            $normalizedInvoices[] = $inv;
        }

        if (empty($normalizedInvoices)) {
            return response()->json([
                'ok' => false,
                'message' => 'No payable items found.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($data, $ownerId, &$created, &$updated, $normalizedInvoices) {
                foreach ($normalizedInvoices as $inv) {
                    $invoice = UtilityInvoice::where('property_id', $data['property_id'])
                        ->where('tenant_id', $inv['tenant_id'])
                        ->where('invoice_month', $data['invoice_month'])
                        ->first();

                    if ($invoice) {
                        // 🔁 Overwrite total amount (not add)
                        $invoice->amount = $inv['amount'];
                        $invoice->status = 'draft';
                        $invoice->save();

                        foreach ($inv['details'] as $row) {
                            $detail = $invoice->details()
                                ->where('category', $row['category'])
                                ->first();

                            if ($detail) {
                                $detail->update([
                                    'amount' => $row['amount'],
                                    'start_date' => $row['start_date'] ?? $detail->start_date,
                                    'end_date' => $row['end_date'] ?? $detail->end_date,
                                ]);
                            } else {
                                $invoice->details()->create([
                                    'tenant_id' => $inv['tenant_id'],
                                    'category' => $row['category'],
                                    'amount' => $row['amount'],
                                    'start_date' => $row['start_date'] ?? null,
                                    'end_date' => $row['end_date'] ?? null,
                                ]);
                            }
                        }

                        $updated[] = [
                            'invoice_id' => $invoice->id,
                            'tenant_id' => $invoice->tenant_id,
                            'amount' => $invoice->amount,
                        ];
                    } else {
                        $invoiceNo = $this->nextInvoiceNumber($data['invoice_month']);
                        $invoice = UtilityInvoice::create([
                            'property_id' => $data['property_id'],
                            'owner_id' => $ownerId,
                            'tenant_id' => $inv['tenant_id'],
                            'invoice_number' => $invoiceNo,
                            'invoice_date' => now()->toDateString(),
                            'invoice_month' => $data['invoice_month'],
                            'amount' => $inv['amount'],
                            'status' => 'draft',
                        ]);

                        foreach ($inv['details'] as $row) {
                            $invoice->details()->create([
                                'tenant_id' => $inv['tenant_id'],
                                'category' => $row['category'],
                                'amount' => $row['amount'],
                                'start_date' => $row['start_date'] ?? null,
                                'end_date' => $row['end_date'] ?? null,
                            ]);
                        }

                        $created[] = [
                            'invoice_id' => $invoice->id,
                            'tenant_id' => $invoice->tenant_id,
                            'amount' => $invoice->amount,
                        ];
                    }
                }
            });

            // 🟢 Mail Sending - Based on normalized data
            foreach ($normalizedInvoices as $invPayload) {
                $tenant = Tenant::with('user')->find($invPayload['tenant_id']);
                if (!$tenant || !optional($tenant->user)->email) continue;

                $dbInvoice = UtilityInvoice::where('property_id', $data['property_id'])
                    ->where('tenant_id', $invPayload['tenant_id'])
                    ->where('invoice_month', $data['invoice_month'])
                    ->first();

                $property = Property::find($data['property_id']);

                $mailData = [
                    'invoice_number' => $dbInvoice->invoice_number ?? 'TEMP',
                    'invoice_date' => now()->toDateString(),
                    'due_date' => $data['due_date'] ?? null,
                    'tenant_name' => trim(optional($tenant->user)->first_name . ' ' . optional($tenant->user)->last_name),
                    'tenant_email' => optional($tenant->user)->email,
                    'property_name' => $property->name ?? 'N/A',
                    'property_address' => $property->address ?? '',
                    'items' => array_map(fn($d) => [
                        'category' => $d['category'],
                        'amount' => round((float)$d['amount'], 2),
                        'start_date' => $d['start_date'] ?? null,
                        'end_date' => $d['end_date'] ?? null,
                    ], $invPayload['details']),
                    'total_amount' => round((float)$invPayload['amount'], 2),
                ];

                // Mail::to($mailData['tenant_email'])->send(new UtilityInvoiceMail($mailData));
                Mail::to('komalshani1997@gmail.com')->send(new UtilityInvoiceMail($mailData));

                // Mark as delivered
                if ($dbInvoice) {
                    $dbInvoice->update(['status' => 'delivered']);
                }
            }

        } catch (\Throwable $e) {
            \Log::error('Utility invoice generate failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Server error generating invoices. Please check data and try again.',
            ], 500);
        }

        return response()->json([
            'ok' => true,
            'message' => sprintf(
                '%d invoice(s) created, %d invoice(s) updated, %d emailed',
                count($created),
                count($updated),
                count($normalizedInvoices)
            ),
            'created' => $created,
            'updated' => $updated,
        ]);
    }

    private function nextInvoiceNumber(string $invoiceMonth): string
    {
        $prefix = 'INV-' . str_replace('-', '', $invoiceMonth);

        // Find the latest invoice number for this month
        $lastInvoice = UtilityInvoice::where('invoice_number', 'like', "{$prefix}-%")
            ->orderByDesc('id')
            ->value('invoice_number');

        $lastNumber = 0;
        if ($lastInvoice && preg_match('/-(\d+)$/', $lastInvoice, $matches)) {
            $lastNumber = (int) $matches[1];
        }

        $newNumber = $lastNumber + 1;
        return sprintf('%s-%04d', $prefix, $newNumber);
    }

    public function getInvoicePreview(Request $request)
    {
        try {
            $data = $request->all();
            $property = Property::find($data['property_id']);
            if (!$property) {
                return response("<div class='alert alert-danger'>Invalid property.</div>");
            }

            $tenantIds = collect($data['invoices'])->pluck('tenant_id')->unique();
            $tenants = Tenant::with('user')->whereIn('id', $tenantIds)->get()->keyBy('id');

            // ✅ Add billing period (start–end date) for each detail line
            foreach ($data['invoices'] as &$invoice) {
                foreach ($invoice['details'] as &$detail) {
                    $bill = UtilityBill::where('property_id', $property->id)
                        ->where('utility_id', $detail['property_utility_id'])
                        ->where('invoice_month', $data['invoice_month'])
                        ->first();

                    $detail['start_date'] = $bill->start_date ?? $detail['start_date'] ?? null;
                    $detail['end_date'] = $bill->end_date ?? $detail['end_date'] ?? null;
                }
            }

            return view('utility_invoices.preview', compact('data', 'property', 'tenants'))->render();
        } catch (\Throwable $e) {
            \Log::error("Invoice preview error: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response("<div class='alert alert-danger'>Preview failed: " . $e->getMessage() . "</div>");
        }
    }   


    public function saveUtilityShares(Request $request)
    {
        $data = $request->validate([
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'invoice_month' => ['required', 'regex:/^\d{4}\-\d{2}$/'],
            'utilities' => ['required', 'array', 'min:1'],
            'utilities.*.utility_id' => ['required', 'integer', 'exists:utilities_sub,id'],
            'utilities.*.price' => ['required', 'numeric', 'min:0'],
            'utilities.*.renters' => ['required', 'array'],
            'utilities.*.renters.*' => ['numeric', 'min:0'],
        ]);

        try {
            DB::transaction(function () use ($data) {
                foreach ($data['utilities'] as $util) {
                    $utilityId = $util['utility_id'];
                    $price = $util['price'];

                    foreach ($util['renters'] as $tenantId => $pct) {
                        UtilityShare::updateOrCreate(
                            [
                                'property_id' => $data['property_id'],
                                'utility_id' => $utilityId,
                                'tenant_id' => $tenantId,
                                'invoice_month' => $data['invoice_month'], // ✅ add this
                            ],
                            [
                                'price' => $price,
                                'percentage' => $pct,
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
                // foreach ($data['utilities'] as $u) {
                //     $utilityId = $u['utility_id'];
                //     $price = (float) $u['price'];
                //     $month = $data['invoice_month'];

                //     foreach ($u['renters'] as $tenantId => $pct) {
                //         $amount = round(($price * ($pct / 100)), 2);

                //         DB::table('utility_shares')->updateOrInsert(
                //             [
                //                 'property_id'   => $data['property_id'],
                //                 'utility_id'    => $utilityId,
                //                 'tenant_id'     => $tenantId,
                //                 'invoice_month' => $month,
                //             ],
                //             [
                //                 'price'       => $price,
                //                 'percentage'  => $pct,
                //                 'amount'      => $amount,
                //                 'updated_at'  => now(),
                //                 'created_at'  => now(),
                //             ]
                //         );
                //     }
                // }
            });

            return response()->json([
                'status'  => true,
                'message' => 'Utility shares saved successfully',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Utility shares save failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Failed to save utility shares. Please try again.',
            ], 500);
        }
    }

    public function uploadBill(Request $request)
    {
        $request->validate([
            'property_id'    => 'required|exists:properties,id',
            'utility_id' => 'required|exists:utilities_main,id',
            'invoice_month'  => 'required|date_format:Y-m',
            'bill_file'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $file = $request->file('bill_file');
        $month = $request->invoice_month;

        $basePath = storage_path("upload/utility_bills/{$month}");
        if (!file_exists($basePath)) {
            mkdir($basePath, 0775, true); // recursively create folder if not exists
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move($basePath, $fileName);

        $existing = UtilityBill::where([
            'property_id'   => $request->property_id,
            'utility_id'    => $request->utility_id,
            'invoice_month' => $month,
        ])->first();

        if ($existing && file_exists(public_path($existing->file_path))) {
            @unlink(public_path($existing->file_path)); 
        }

        $bill = UtilityBill::updateOrCreate(
            [
                'property_id'   => $request->property_id,
                'utility_id'    => $request->utility_id,
                'invoice_month' => $month,
            ],
            [
                'file_path'   => "storage/upload/utility_bills/{$month}/{$fileName}",
                'file_name'   => $file->getClientOriginalName(),
                'uploaded_by' => auth()->id(),
            ]
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Bill uploaded successfully!',
            'path'    => $bill->file_path,
        ]);
    }

    public function saveDates(Request $request)
    {
        try {
            $validated = $request->validate([
                'property_id' => 'required|integer|exists:properties,id',
                'utility_id' => 'required|integer|exists:utilities_main,id',
                'invoice_month' => 'required|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
            ]);

            UtilityBill::updateOrCreate(
                [
                    'property_id' => $validated['property_id'],
                    'utility_id' => $validated['utility_id'],
                    'invoice_month' => $validated['invoice_month'],
                    'uploaded_by' => auth()->id(),
                ],
                [
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                ]
            );

            return response()->json(['status' => 'success', 'message' => 'Dates saved successfully.']);
        } catch (\Throwable $e) {
            \Log::error('❌ Failed to save dates', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function deleteImage(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        try {
            $image = PropertyImage::find($request->id);

            if (! $image) {
                return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
            }

            $filePath = storage_path('upload/property/'.$image->image);

            if (File::exists($filePath)) {
                if (! File::delete($filePath)) {
                    Log::error("Failed to delete file: {$filePath}");

                    return response()->json(['success' => false, 'message' => 'Failed to delete physical file.'], 500);
                }
            } else {
                Log::warning("File not present when deleting image: {$filePath}");
            }

            $image->delete();

            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
        } catch (\Exception $e) {
            Log::error('Delete image error: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Something went wrong: '.$e->getMessage()], 500);
        }
    }
}
