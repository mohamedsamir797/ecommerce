<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use App\Models\MainCategory;
use App\Http\Requests\MainCategoryRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;


class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $defualt_lang = get_default_lang();
        $mainCategories = MainCategory::where('translation_lang',$defualt_lang)->selection()->get();
        return view('admin.MainCategory.index',compact('mainCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.MainCategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MainCategoryRequest $request)
    {
        try {
            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });

            $default_category = array_values($filter->all())[0];

            $filepath = "";
            if ($request->has('photo')) {
                $filepath = uploadImage('maincategories', $request->photo);
            }
            DB::beginTransaction();

            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filepath
            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });

            if ($categories) {
                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filepath
                    ];
                }
                MainCategory::insert($categories_arr);
            }
            DB::commit();
            return redirect()->route('categories.index')->with(['success' => 'تم اضافة القسم بنجاح']);
        }catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('categories.index')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

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
       $category = MainCategory::with('categories')->selection()->find($id);
       if (!$category){
           return redirect()->route('categories.index')->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);
       }
       return view('admin.MainCategory.edit',compact('category'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MainCategoryRequest $request, $id)
    {
        try{


      $main_category =  MainCategory::find($id);
      if (!$main_category){
          return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);
      }

       $category =  array_values($request->category)[0];

        if (! $request->has('category.0.active')){
            $request->request->add(['active' => 0 ]);
        }else
            $request->request->add(['active' => 1 ]);


            $main_category->update([
            'name' => $category['name'] ,
            'active' => $request->active
            ]);

            if ($request->has('photo')) {
                $filepath = uploadImage('maincategories', $request->photo);
                $main_category->update([
                    'photo' => $filepath
                ]);
            }

        return redirect()->route('categories.index')->with(['success' => 'تم تحديث القسم بنجاح']);
        }catch (\Exception $e){
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
           $main_category =  MainCategory::find($id);

           if (!$main_category){
               return redirect()->back()->with(['error' => ' عفوا هذا القسم غير موجود ']);
           }
           $vendors = $main_category->vendors() ;

           if (isset($vendors) && $vendors->count() > 0){
               return redirect()->back()->with(['error' => ' لا يمكن حذف هذا القسم ']);
           }
              $image =  Str::after($main_category->photo,'assets/');
              $image = public_path('assets/'.$image);
              unlink($image);

               $main_category->categories()->delete();
               $main_category->delete();

               return redirect()->back()->with(['success' => ' تم حذف القسم بنجاح ']);

        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }
    public function changeStatus($id){
        try{
            $main_category =  MainCategory::find($id);

            if (!$main_category)
                return redirect()->back()->with(['error' => ' عفوا هذا القسم غير موجود ']);

           $status =  $main_category->active == 0 ? 1 : 0 ;

            $main_category->update(['active' => $status ]);
            return redirect()->back()->with(['success' => ' تم تحديث الحالة بنجاح ']);

        }catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'حدث خطأ ما برجاء المحاولة لاحقا']);

        }
    }
}
