<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\MainCategoryObserver;
use App\Models\SubCategory ;
class MainCategory extends Model
{
    protected $fillable = ['translation_lang','translation_of','name','slug','photo','active'];
    protected $table = 'main_categories';
    protected $guarded = [] ;

    protected static function boot()
    {
        parent::boot();
        MainCategory::observe(MainCategoryObserver::class);
    }

    public function scopeActive($query){
        return $query->where('active', 1) ;
    }
    public function scopeSelection($query){
        return $query->select('id','translation_lang','translation_of','name','slug','photo','active');
    }

    public function getActive(){
        return $this->active == 1 ? 'مفعل' : 'غير مفعل' ;
    }
    public function getPhotoAttribute($val){
        return ($val !== null) ? asset('/assets/'.$val) : '';
    }
    public function categories(){
        return $this->hasMany(self::class,'translation_of');
    }
    public function vendors(){
        return $this->hasMany(\App\Models\Vendor::class,'category_id');
    }

    public function subCategory(){
        return $this->hasMany(SubCategory::class,'category_id');
    }

}
