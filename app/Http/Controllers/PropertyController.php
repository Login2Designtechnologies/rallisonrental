<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyUnit;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\UtilityInvoice;
use App\Http\Requests\StoreUtilityInvoiceRequest;
use App\Http\Requests\UpdateUtilityInvoiceRequest;
use App\Models\UtilityInvoiceDetail;
use Illuminate\Support\Facades\Auth;

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
        return view('property.addUtilities',compact('id'));
    }
     public function addAmenities($id)
    {
        return view('property.addAmenities',compact('id'));
    }
     public function edit_Amenities($id,$propertyid)
    {
        $amenitydata = DB::table('amenity_catg')->where('id',$id)->first();
        return view('property.editAmenities',compact('amenitydata','propertyid'));
    }
     public function edit_Utilities($id,$propertyid)
    {
        $utilitiesdata = DB::table('utilities_catg')->where('id',$id)->first();
        return view('property.editutilities',compact('utilitiesdata','propertyid'));
    }


    //  public function selectProperty()
    // {
    //     return view('property.selectProperty');
    // }


     public function selectProperty()
    {
        $filters = request()->only(['q','status','property_id','tenant_id','month']);
        $invoices = UtilityInvoice::with(['property','user','tenant'])
            ->filters($filters)
            ->latest('invoice_date')
            ->paginate(15)
            ->withQueryString();

            $properties = DB::table('properties')
            ->where('properties.parent_id', auth()->id())
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('tenants')
                  ->whereColumn('tenants.property', 'properties.id')
                  ->where('tenants.parent_id', auth()->id());
            })
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('utilities_catg')
                  ->whereColumn('utilities_catg.property_id', 'properties.id')
                  ->where('utilities_catg.user_id', auth()->id());
            })
            ->get();


        return view('utility_invoices.index', [
            'invoices' => $invoices,
            'filters'  => $filters,
            'properties' => $properties
        ]);
    }

    public function all_invoices(){
        $invoices = UtilityInvoice::paginate(20);
        return view('utility_invoices.show', compact('invoices'));
    }


      public function all_invoicescreate()
    {
        $invoiceMonth = request('invoice_month') ?: now()->format('Y-m');

        // $property = Property::with(['utilities.categories', 'tenants.user'])
        //     ->where('parent_id', auth()->user()->id)
        //     ->where('id', request('property_id'))
        //     ->first();

        $property = DB::table('properties')
            ->where('properties.parent_id', auth()->id())
            ->where('properties.id', request('property_id'))
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('tenants')
                  ->whereColumn('tenants.property', 'properties.id')
                  ->where('tenants.parent_id', auth()->id());
            })
            ->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('utilities_catg')
                  ->whereColumn('utilities_catg.property_id', 'properties.id')
                  ->where('utilities_catg.user_id', auth()->id());
            })
            ->first();

        // Preload existing amounts from utility_invoice_details for this property and month
        $amounts = UtilityInvoiceDetail::selectRaw('property_utility_id, category, SUM(amount) as total_amount')
            ->whereHas('invoice', function($q) use ($property, $invoiceMonth) {
                $q->where('property_id', optional($property)->id)
                  ->where('invoice_month', $invoiceMonth);
            })
            ->groupBy('property_utility_id', 'category')
            ->get()
            ->keyBy(function($row){
                return ($row->property_utility_id ?: 'null').'|'.($row->category ?: '');
            });

        return view('utility_invoices.create', compact('property', 'invoiceMonth', 'amounts'));
    }


    public function create()
    {

        if (\Auth::user()->can('create property')) {
            $types = Property::$Type;
            $rentTypes = PropertyUnit::$rentTypes;
            $statesdata = DB::table('states')->get();
            $amenities = DB::table('amenity_catg')->where('falge','0')->get();
            $utilities = DB::table('utilities_catg')->where('falge','0')->get();

            return view('property.create', compact('types','rentTypes','statesdata','amenities','utilities'));
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
        if (\Auth::user()->can('create property')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    // 'description' => 'required',
                    'type' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                    'thumbnail' => 'required',
                    // 'property_images' => 'required',
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
            $totalProperty = $authUser->totalProperty();
            $subscription = Subscription::find($authUser->subscription);
            if ($totalProperty >= $subscription->property_limit && $subscription->property_limit != 0) {
                return response()->json([
                    'status' => 'error',
                    'msg' => __('Your property limit is over, please upgrade your subscription.'),
                    'id' => 0,
                ]);
            }
            $property = new Property();
            $property->name = $request->name;
            $property->description = $request->description;
            $property->type = $request->type;
            $property->country = $request->country;
            $property->state_id = $request->state;
            $property->city_id = $request->city;
            $property->zip_code = $request->zip_code;
            $property->address = $request->address;
            $property->is_billed = $request->is_billed;
            $property->utilities = $request->utilities;
            $property->parent_id = parentId();
            $property->save();

            if ($request->thumbnail != 'undefined') {
                $thumbnailFilenameWithExt = $request->file('thumbnail')->getClientOriginalName();
                $thumbnailFilename = pathinfo($thumbnailFilenameWithExt, PATHINFO_FILENAME);
                $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnailFileName = $thumbnailFilename . '_' . time() . '.' . $thumbnailExtension;
                $dir = storage_path('upload/thumbnail');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('thumbnail')->storeAs('upload/thumbnail/', $thumbnailFileName);
                $thumbnail = new PropertyImage();
                $thumbnail->property_id = $property->id;
                $thumbnail->image = $thumbnailFileName;
                $thumbnail->type = 'thumbnail';
                $thumbnail->save();
            }

            if (!empty($request->property_images)) {
                foreach ($request->property_images as $file) {
                    $propertyFilenameWithExt = $file->getClientOriginalName();
                    $propertyFilename = pathinfo($propertyFilenameWithExt, PATHINFO_FILENAME);
                    $propertyExtension = $file->getClientOriginalExtension();
                    $propertyFileName = $propertyFilename . '_' . time() . '.' . $propertyExtension;
                    $dir = storage_path('upload/property');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/property/', $propertyFileName);

                    $propertyImage = new PropertyImage();
                    $propertyImage->property_id = $property->id;
                    $propertyImage->image = $propertyFileName;
                    $propertyImage->type = 'extra';
                    $propertyImage->save();
                }
            }

            if ($request->has('unitname')) {
                foreach ($request->unitname as $key => $name) {
                    // Agar name empty hai to skip kar do
                    if (empty($name)) {
                        continue;
                    }

                    $unit = new PropertyUnit();
                    $unit->name   = $name ?? null;
                    $unit->status = $request->status[$key] ?? null;
                    $unit->notes  = $request->notes[$key] ?? null;
                    $unit->property_id = $property->id;
                    $unit->parent_id   = parentId();
                    $unit->save();
                }
            }

            // $unit = new PropertyUnit();
            // $unit->name = $request->unitname;
            // $unit->status = $request->status;
            // $unit->bedroom = $request->bedroom;
            // $unit->kitchen = $request->kitchen;
            // $unit->baths = $request->baths;
            // $unit->rent = $request->rent;
            // $unit->rent_type = $request->rent_type;
            // if ($request->rent_type == 'custom') {
            //     $unit->start_date = $request->start_date;
            //     $unit->end_date = $request->end_date;
            //     $unit->payment_due_date = $request->payment_due_date;
            // } else {
            //     $unit->rent_duration = $request->rent_duration;
            // }

            // $unit->deposit_type = $request->deposit_type;
            // $unit->deposit_amount = $request->deposit_amount;
            // $unit->late_fee_type = $request->late_fee_type;
            // $unit->late_fee_amount = $request->late_fee_amount;
            // $unit->incident_receipt_amount = $request->incident_receipt_amount;
            // $unit->notes = $request->notes;
            // $unit->property_id = $property->id;
            // $unit->parent_id = parentId();

            // dd($unit);
            // $unit->save();

            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully created.'),
                'id' => $property->id,
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }



    public function addAmenities_store(Request $request,$id)
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
    


       public function editAmenities_update(Request $request,$id,$propertyid)
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
        'name' => 'required|string|max:255',
        'status' => 'required|string',
        'price' => 'required',
    ]);

    $userId = Auth::id();

    // Check if amenity already exists
    $exists = DB::table('amenity_catg')
        ->where('name', $request->name)
        ->where('user_id', $userId)
        ->exists();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'Amenity already exists!'
        ]);
    }

    // Get last inserted property id (if exists)
    $lastProperty = DB::table('properties')->latest('id')->first();
    $propertyId = $lastProperty ? $lastProperty->id + 1 : 1;

    // Insert new amenity and get ID
    $newAmenityId = DB::table('amenity_catg')->insertGetId([
        'name' => $request->name,
        'price' => $request->price,
        'property_id' => $propertyId,
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
        ]
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

        return redirect('property/'.$request->propertyid.'/'.'edit')->with('error', 'Amenity already exists!');
        // return response()->json([
        //     'success' => false,
        //     'message' => 'Amenity already exists!'
        // ]);
    }

    // Insert new amenity and get ID
    $newAmenityId = DB::table('amenity_catg')->insertGetId([
        'name' => $request->name,
        'property_id' => $request->propertyid,
        'price' => $request->price,
        'status' => $request->status,
        'user_id' => $userId,
    ]);
    
    return redirect('property/'.$request->propertyid.'/'.'edit')->with('success', 'Amenities added successfully!');

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
        'name' => 'required|string|max:255',
        'sub_category' => 'required|in:0,1',
        'status' => 'required|in:0,1',
    ]);

    $userId = Auth::id();

    $insertedData = [];

    if ($request->sub_category == 1) {
        // ✅ Multiple sub_category_name handle
        foreach ($request->sub_category_name as $subCatName) {
            // Pehle check karo duplicate
            $exists = DB::table('utilities_catg')
                ->where('name', $request->name)
                ->where('sub_category', 1)
                ->where('sub_category_name', $subCatName)
                ->where('user_id', $userId)
                ->exists();

            if (!$exists) {

                // Get last inserted property id (if exists)
                $lastProperty = DB::table('properties')->latest('id')->first();
                $propertyId = $lastProperty ? $lastProperty->id + 1 : 1;

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
            'data' => $insertedData
        ]);

    } else {
        // ✅ sub_category = 0 → without sub_category_name
        $exists = DB::table('utilities_catg')
            ->where('name', $request->name)
            ->where('sub_category', 0)
            ->where('user_id', $userId)
            ->exists();

        if (!$exists) {

            // Get last inserted property id (if exists)
            $lastProperty = DB::table('properties')->latest('id')->first();
            $propertyId = $lastProperty ? $lastProperty->id + 1 : 1;

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
            'data' => $insertedData
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

    $insertedData = [];

    $userId = Auth::id();

    if ($request->sub_category == 1) {
        // ✅ Multiple sub_category_name handle
        foreach ($request->sub_category_name as $subCatName) {
            // Pehle check karo duplicate
            $exists = DB::table('utilities_catg')
                ->where('name', $request->name)
                ->where('property_id', $request->propertyid)
                ->where('user_id', $userId)
                ->where('sub_category', 1)
                ->where('sub_category_name', $subCatName)
                ->exists();

            if (!$exists) {

                $id = DB::table('utilities_catg')->insertGetId([
                    'name' => $request->name,
                    'sub_category' => 1,
                    'sub_category_name' => $subCatName,
                    'property_id' => $request->propertyid,
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
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Utility already exists!',
            // ]);
            return redirect('property/'.$request->propertyid.'/'.'edit')->with('error', 'Utility already exists!');
        }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Utility added successfully!',
        //     'data' => $insertedData
        // ]);
        return redirect('property/'.$request->propertyid.'/'.'edit')->with('success', 'Utility added successfully!');

    } else {

        $userId = Auth::id();
        // ✅ sub_category = 0 → without sub_category_name
        $exists = DB::table('utilities_catg')
            ->where('property_id', $request->propertyid)
            ->where('name', $request->name)
            ->where('user_id', $userId)
            ->where('sub_category', 0)
            ->exists();

        if (!$exists) {

            $id = DB::table('utilities_catg')->insertGetId([
                'name' => $request->name,
                'sub_category' => 0,
                'sub_category_name' => null,
                'property_id' => $request->propertyid,
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
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Utility already exists!',
            // ]);
            return redirect('property/'.$request->propertyid.'/'.'edit')->with('error', 'Utility already exists!');
        }

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Utility added successfully!',
        //     'data' => $insertedData
        // ]);

       return redirect('property/'.$request->propertyid.'/'.'edit')->with('success', 'Utility added successfully!');

    }

    
}




public function addUtilities_store(Request $request,$id)
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

            if (!$exists) {

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

        if (!$exists) {

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
            'message' => 'Amenity already exists!'
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
        ]
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
        'id' => 'required|integer|exists:utilities_catg,id',
        'name' => 'required|string|max:255',
        'sub_category' => 'required|in:0,1',
        'sub_category_name' => 'nullable|string|max:255',
        'status' => 'required|in:0,1',
    ]);

    // Check if combination already exists (except current record)
    $exists = DB::table('utilities_catg')
        ->where('name', $request->name)
        ->where('sub_category', $request->sub_category)
        ->where(function($query) use ($request) {
            if ($request->sub_category == 1) {
                $query->where('sub_category_name', $request->sub_category_name);
            } else {
                $query->whereNull('sub_category_name');
            }
        })
        ->where('id', '!=', $request->id)
        ->exists();

    if ($exists) {
        return response()->json([
            'success' => false,
            'message' => 'Utilities already exists!'
        ]);
    }

    // Update the record
    DB::table('utilities_catg')
        ->where('id', $request->id)
        ->update([
            'name' => $request->name,
            'sub_category' => $request->sub_category,
            'sub_category_name' => $request->sub_category == 1 ? $request->sub_category_name : null,
            'status' => $request->status,
        ]);

    return response()->json([
        'success' => true,
        'message' => 'Utilities updated successfully!',
        'data' => [
            'id' => $request->id,
            'name' => $request->name,
            'sub_category' => $request->sub_category,
            'sub_category_name' => $request->sub_category == 1 ? $request->sub_category_name : null,
            'status' => $request->status,
        ]
    ]);
}




public function propertyUtilities_update2(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:utilities_catg,id',
        'name' => 'required|string|max:255',
        'sub_category' => 'required|in:0,1',
        'sub_category_name' => 'nullable|string|max:255',
        'status' => 'required|in:0,1',
    ]);

    $userId = Auth::id();

    // Check if combination already exists (except current record)
    $exists = DB::table('utilities_catg')
        ->where('name', $request->name)
        ->where('property_id', $request->propertyid)
        ->where('sub_category', $request->sub_category)
        ->where('user_id', $userId)
        ->where(function($query) use ($request) {
            if ($request->sub_category == 1) {
                $query->where('sub_category_name', $request->sub_category_name);
            } else {
                $query->whereNull('sub_category_name');
            }
        })
        ->where('id', '!=', $request->id)
        ->exists();

    if ($exists) {
        // return response()->json([
        //     'success' => false,
        //     'message' => 'Utilities already exists!'
        // ]);
        return redirect('property/'.$request->propertyid.'/'.'edit')->with('error', 'Utilities already exists!');
    }

    // Update the record
    DB::table('utilities_catg')
        ->where('property_id', $request->propertyid)
        ->where('id', $request->id)
        ->update([
            'name' => $request->name,
            'sub_category' => $request->sub_category,
            'sub_category_name' => $request->sub_category == 1 ? $request->sub_category_name : null,
            'status' => $request->status,
        ]);

    return redirect('property/'.$request->propertyid.'/'.'edit')->with('success', 'Utilities updated successfully!');

    // return response()->json([
    //     'success' => true,
    //     'message' => 'Utilities updated successfully!',
    //     'data' => [
    //         'id' => $request->id,
    //         'name' => $request->name,
    //         'sub_category' => $request->sub_category,
    //         'sub_category_name' => $request->sub_category == 1 ? $request->sub_category_name : null,
    //         'status' => $request->status,
    //     ]
    // ]);
}



public function addUtilities_update(Request $request,$id,$propertyid)
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
        ->where(function($query) use ($request) {
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
            $statesdataview = DB::table('states')->where('id', $property->state)->first();
            $citiesview = DB::table('cities')->where('id', $property->city)->first();
            $amenities = DB::table('amenity_catg')->where('property_id',$property->id)->get();
            $utilities = DB::table('utilities_catg')->where('property_id',$property->id)->get();
            return view('property.show', compact('property', 'units','statesdataview','citiesview','amenities','utilities'));
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
            $propertyimages = DB::table('property_images')->where('property_id',$property->id)->where('type','thumbnail')->first();
            $propertyextraimages = DB::table('property_images')->where('property_id',$property->id)->where('type','extra')->get();
            $amenities = DB::table('amenity_catg')->where('property_id',$property->id)->where('user_id',$userId)->get();
            $utilities = DB::table('utilities_catg')->where('property_id',$property->id)->where('user_id',$userId)->get();
            return view('property.edit', compact('types', 'property','statesdata','propertyimages','propertyextraimages','amenities','utilities'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function update(Request $request, Property $property)
    {

        if (\Auth::user()->can('edit property')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    // 'description' => 'required',
                    'type' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',

                ]

            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);
            }

            $property->name = $request->name;
            $property->description = $request->description;
            $property->type = $request->type;
            $property->country = $request->country;
            $property->state = $request->state;
            $property->city = $request->city;
            $property->zip_code = $request->zip_code;
            $property->address = $request->address;
            $property->save();

            if (!empty($request->thumbnail)) {
                if (!empty($property->thumbnail) && isset($property->thumbnail->image)) {
                    $image_path = "storage/upload/thumbnail/" . $property->thumbnail->image;
                    if (\File::exists($image_path)) {
                        \File::delete($image_path);
                    }
                }

                $thumbnailFilenameWithExt = $request->file('thumbnail')->getClientOriginalName();
                $thumbnailFilename = pathinfo($thumbnailFilenameWithExt, PATHINFO_FILENAME);
                $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnailFileName = $thumbnailFilename . '_' . time() . '.' . $thumbnailExtension;
                $dir = storage_path('upload/thumbnail');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('thumbnail')->storeAs('upload/thumbnail/', $thumbnailFileName);
                $thumbnail = PropertyImage::where('property_id', $property->id)->where('type', 'thumbnail')->first();
                $thumbnail->image = $thumbnailFileName;
                $thumbnail->save();
            }

            if (!empty($request->property_images)) {
                foreach ($request->property_images as $file) {
                    $propertyFilenameWithExt = $file->getClientOriginalName();
                    $propertyFilename = pathinfo($propertyFilenameWithExt, PATHINFO_FILENAME);
                    $propertyExtension = $file->getClientOriginalExtension();
                    $propertyFileName = $propertyFilename . '_' . time() . '.' . $propertyExtension;
                    $dir = storage_path('upload/property');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/property/', $propertyFileName);

                    $propertyImage = new PropertyImage();
                    $propertyImage->property_id = $property->id;
                    $propertyImage->image = $propertyFileName;
                    $propertyImage->type = 'extra';
                    $propertyImage->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully updated.'),
                'id' => $property->id,
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
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

            $unit = new PropertyUnit();
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
        return view('unit.directcreate', compact('types', 'rentTypes', 'name','property'));
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
    if (!\Auth::user()->can('create unit')) {
        return redirect()->back()->with('error', __('Permission Denied!'));
    }

    $validator = \Validator::make(
        $request->all(),
        [
            'name'        => 'required',
            'status'      => 'required',
            'notes'       => 'required',
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
    $unit = new PropertyUnit();
    $unit->name       = $request->name;
    $unit->status     = $request->status;
    $unit->property_id= $request->property_id;
    $unit->notes      = $request->notes;
    $unit->parent_id  = parentId();
    $unit->save();

    return redirect()->back()->with('success', __('Unit successfully created.'));
}



    public function unitEdit($property_id, $unit_id)
    {
        $unit = PropertyUnit::find($unit_id);
        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
        return view('unit.edit', compact('types', 'property_id', 'rentTypes', 'unit','property'));
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
    if (!\Auth::user()->can('edit unit')) {
        return redirect()->back()->with('error', __('Permission Denied!'));
    }

    $validator = \Validator::make(
        $request->all(),
        [
            'name'        => 'required',
            'status'      => 'required',
            'notes'       => 'required',
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
    $unit->name        = $request->name;
    $unit->status      = $request->status;
    $unit->property_id = $request->property_id;
    $unit->notes       = $request->notes;
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
        $units = PropertyUnit::where('property_id', $property_id)->where('status','1')->get()->pluck('name', 'id');
        return response()->json($units);
    }



public function utility_invoicesgenerate(Request $request)
{
    $data = $request->validate([
        'property_id'    => ['required','integer','exists:properties,id'],
        'invoice_month'  => ['required','regex:/^\d{4}\-\d{2}$/'], // YYYY-MM
        'due_date'       => ['nullable','date'],
        'invoices'       => ['required','array','min:1'],
        'invoices.*.tenant_id' => ['required','integer','exists:tenants,id'],
        'invoices.*.amount'    => ['required','numeric'],
        'invoices.*.details'   => ['required','array','min:1'],
        'invoices.*.details.*.property_utility_id' => ['nullable','integer','exists:property_utilities,id'],
        'invoices.*.details.*.category'            => ['required','string','max:191'],
        'invoices.*.details.*.amount'              => ['required','numeric'],
        'invoices.*.details.*.start_date'          => ['nullable','date'],
        'invoices.*.details.*.end_date'            => ['nullable','date'],
    ]);

    $ownerId = optional(Auth::user()->owner)->id;
    $created = [];
    $updated = [];

    // Normalize payload: filter zero-amount details and recompute invoice amounts
    $normalizedInvoices = [];
    foreach ($data['invoices'] as $inv) {
        $details = array_values(array_filter($inv['details'], function ($row) {
            return isset($row['amount']) && (float)$row['amount'] > 0;
        }));
        if (empty($details)) {
            continue; // skip invoices with no payable details
        }
        $sum = 0;
        foreach ($details as $d) { $sum += (float)$d['amount']; }
        $inv['amount'] = round($sum, 2);
        $inv['details'] = array_map(function ($d) {
            $d['amount'] = round((float)$d['amount'], 2);
            return $d;
        }, $details);
        $normalizedInvoices[] = $inv;
    }

    if (empty($normalizedInvoices)) {
        return response()->json([
            'ok' => false,
            'message' => 'No payable items found. Please enter prices and tenant shares.'
        ], 422);
    }

    try {
        DB::transaction(function () use ($data, $ownerId, &$created, &$updated, $normalizedInvoices) {
        foreach ($normalizedInvoices as $inv) {

            // Check if invoice already exists for property + tenant + month
            $invoice = UtilityInvoice::where('property_id', $data['property_id'])
                ->where('tenant_id', $inv['tenant_id'])
                ->where('invoice_month', $data['invoice_month'])
                ->first();

            if ($invoice) {
                // Update existing invoice by adding amount
                $invoice->amount += $inv['amount'];
                $invoice->due_date = $data['due_date'] ?? $invoice->due_date;
                $invoice->status = 'draft';
                $invoice->save();

                // Update details or create new ones
                foreach ($inv['details'] as $row) {
                    $detail = $invoice->details()
                        ->where('property_utility_id', $row['property_utility_id'])
                        ->where('category', $row['category'])
                        ->first();

                    if ($detail) {
                        $detail->amount += $row['amount'];
                        $detail->start_date = $row['start_date'] ?? $detail->start_date;
                        $detail->end_date = $row['end_date'] ?? $detail->end_date;
                        $detail->save();
                    } else {
                        $invoice->details()->create([
                            'tenant_id'           => $inv['tenant_id'],
                            'property_utility_id' => $row['property_utility_id'] ?? null,
                            'category'            => $row['category'],
                            'amount'              => $row['amount'],
                            'start_date'          => $row['start_date'] ?? null,
                            'end_date'            => $row['end_date'] ?? null,
                        ]);
                    }
                }

                $updated[] = [
                    'invoice_id'    => $invoice->id,
                    'tenant_id'     => $invoice->tenant_id,
                    'amount'        => $invoice->amount,
                    'details_count' => count($inv['details']),
                ];
            } else {
                // Create new invoice
                $invoiceNo = $this->nextInvoiceNumber($data['invoice_month']);

                $invoice = UtilityInvoice::create([
                    'property_id'    => $data['property_id'],
                    'owner_id'       => $ownerId,
                    'tenant_id'      => $inv['tenant_id'],
                    'invoice_number' => $invoiceNo,
                    'invoice_date'   => now()->toDateString(),
                    'invoice_month'  => $data['invoice_month'],
                    'due_date'       => $data['due_date'],
                    'amount'         => $inv['amount'],
                    'status'         => 'draft',
                ]);

                foreach ($inv['details'] as $row) {
                    $invoice->details()->create([
                        'tenant_id'           => $inv['tenant_id'],
                        'property_utility_id' => $row['property_utility_id'] ?? null,
                        'category'            => $row['category'],
                        'amount'              => $row['amount'],
                        'start_date'          => $row['start_date'] ?? null,
                        'end_date'            => $row['end_date'] ?? null,
                    ]);
                }

                $created[] = [
                    'invoice_id'    => $invoice->id,
                    'tenant_id'     => $invoice->tenant_id,
                    'amount'        => $invoice->amount,
                    'details_count' => count($inv['details']),
                ];
            }
        }
    });
    } catch (\Throwable $e) {
        \Log::error('Utility invoice generate failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json([
            'ok' => false,
            'message' => 'Server error generating invoices. Please check data and try again.'
        ], 500);
    }

    // Send emails and mark as sent
    $affectedInvoiceIds = collect($created)->pluck('invoice_id')
        ->merge(collect($updated)->pluck('invoice_id'))
        ->unique()
        ->values();

    $invoices = UtilityInvoice::with(['tenant.user'])
        ->whereIn('id', $affectedInvoiceIds)
        ->get();

    foreach ($invoices as $inv) {
        // Mark as delivered (locked)
        $inv->status = 'delivered';
        $inv->save();

        $tenantEmail = optional(optional($inv->tenant)->user)->email;
        if ($tenantEmail) {
            $invoiceUrl = route('utility-invoices.show', $inv->id);
            try {
                Mail::to($tenantEmail)->send(new InvoiceMail([
                    'invoice_number' => $inv->invoice_number,
                    'invoice_date'   => optional($inv->invoice_date)->toDateString(),
                    'due_date'       => optional($inv->due_date)->toDateString(),
                    'amount'         => (string) $inv->amount,
                    'url'            => $invoiceUrl,
                    'property_id'    => $inv->property_id,
                ]));
            } catch (\Throwable $e) {
                // Log but do not fail the response
                \Log::warning('Invoice email failed: '.$e->getMessage(), ['invoice_id' => $inv->id]);
            }
        }
    }

    return response()->json([
        'ok'       => true,
        'message'  => sprintf(
            "%d invoice(s) created, %d invoice(s) updated, %d emailed",
            count($created),
            count($updated),
            $invoices->count()
        ),
        'created'  => $created,
        'updated'  => $updated,
    ]);
}








}
