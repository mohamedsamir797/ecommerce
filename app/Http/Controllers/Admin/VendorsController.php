<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\MainCategory ;
use App\Http\Requests\VendorRequest ;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorCreated ;

class VendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendors = Vendor::selection()->paginate(PAGINATION);
        return view('admin.vendors.index',compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mainCategories = MainCategory::active()->where('translation_of',0 )->get();
        return view('admin.vendors.create',compact('mainCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendorRequest $request)
    {
        try{

            if (! $request->has('active')){
                $request->request->add(['active' => 0 ]);
            }else
                $request->request->add(['active' => 1 ]);


            $filepath = "";
            if ($request->has('logo')) {
                $filepath = uploadImage('vendors', $request->logo);
            }

            $vendor = Vendor::create([
                'name' => $request->name ,
                'logo' => $filepath ,
                'mobile' => $request->mobile ,
                'address' => $request->address ,
                'email' => $request->email ,
                'category_id' => $request->category_id ,
                'active' => $request->active ,

            ]);

            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('vendors.index')->with(['success' => 'تم حفظ المتجر بنجاح']);

        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
