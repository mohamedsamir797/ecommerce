<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\MainCategory ;
use App\Http\Requests\VendorRequest ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorCreated ;
use Illuminate\Support\Str;


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
                'password' => $request->password

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
        try{
            $vendor = Vendor::selection()->find($id);
            $mainCategories = MainCategory::active()->where('translation_of',0 )->get();

            if (!$vendor){
                return redirect()->back()->with(['error' => 'عفوا هذا المتجر غير موجود']);
            }

            return view('admin.vendors.edit',compact('vendor','mainCategories'));


        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VendorRequest $request, $id)
    {
        try{
            $vendor = Vendor::selection()->find($id);

            if (!$vendor){
                return redirect()->back()->with(['error' => 'عفوا هذا المتجر غير موجود']);
            }
             DB::beginTransaction();

            if ($request->has('logo')) {
                $filepath = uploadImage('vendors', $request->logo);
                Vendor::where('id',$id)
                       ->update([
                           'logo' => $filepath
                       ]);
            }

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $data = $request->except('_token', 'id', 'logo', 'password');


            if ($request->has('password') && !is_null($request->  password)) {

                $data['password'] = $request->password;
            }else{
                Vendor::where('id', $id)
                    ->update([
                        'name' => $request->name ,
                        'mobile' => $request->mobile ,
                        'address' => $request->address ,
                        'email' => $request->email ,
                        'category_id' => $request->category_id ,
                        'active' => $request->active ,
                    ]);
            }



            DB::commit();
            return redirect()->route('vendors.index')->with(['success' => 'تم التحديث المتجر بنجاح']);

        }catch (\Exception $ex){
            return $ex ;
            DB::rollBack();
            return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $vendor = Vendor::find($id);
            if (!$vendor){
                return redirect()->back()->with(['error' => 'عفوا هذا المتجر غير موجود']);
            }

            $image =  Str::after($vendor->logo,'assets/');
            $image = base_path('assets/'.$image);
            unlink($image);

            $vendor->delete();
            return redirect()->back()->with(['success' => 'تم حذف المتجر بنجاح']);


        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);
        }
    }
    public function changeStatus($id){
        try{
            $vendor = Vendor::find($id);

            if (!$vendor){
                return redirect()->back()->with(['error' => 'عفوا هذا المتجر غير موجود']);
            }
            $status = $vendor->active == 0 ? 1 : 0 ;

            $vendor->update(['active' => $status]);

            return redirect()->back()->with(['success' => 'تم تغيير حالة المتجر بنجاح']);

        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }
}
