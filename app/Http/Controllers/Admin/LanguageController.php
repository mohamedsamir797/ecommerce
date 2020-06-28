<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Requests\LanguageRequest ;
use mysql_xdevapi\Exception;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::selection()->paginate(PAGINATION);
        return view('admin.languages.index',compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request)
    {
        try{
            Language::create($request->all());
            return redirect()->route('admin.languages')->with(['success'=> 'تم اضافة اللفة بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('admin.languages')->with(['error'=> 'هناك خطأ ما يرجي المحاولة لاحقا']);

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
        $language = Language::find($id);
        if (!$language){
            return redirect()->route('admin.languages')->with(['error' => 'هذه الصفحة غير موجودة']);
        }
        return view('admin.languages.edit',compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request, $id)
    {
        try{
            $language = Language::find($id);
            if (!$language){
                return redirect()->route('admin.languages.edit',$id)->with(['error' => 'هذه الصفحة غير موجودة']);
            }
            if (! $request->has('active'))
            $request->request->add(['active' => 0 ]);

            $language->update($request->all());
            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث البيانات بنجاح']);

        }catch (\Exception $ex){
            return redirect()->route('admin.languages.edit',$id)->with(['error' => 'هذه الصفحة غير موجودة']);
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
            $language = Language::find($id);
            if (!$language){
                return redirect()->route('admin.languages')->with(['error' => 'هذه الصفحة غير موجودة']);
            }
            $language->delete();
            return redirect()->route('admin.languages')->with(['success'=>'تم مسح البيانات بنجاح']);

        }catch (\Exception $ex){
            return redirect()->route('admin.languages')->with(['error'=>'هناك خطأ حاول في وقت لاحق']);

        }

    }
}
