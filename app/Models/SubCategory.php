<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MainCategory ;

class SubCategory extends Model
{
    protected $fillable = ['parent_id','category_id','translation_lang','translation_of','name','slug','photo','active'];
    protected $table = 'sub-categories';
    protected $guarded = [] ;

    public function scopeActive($query){
        return $query->where('active', 1) ;
    }
    public function scopeSelection($query){
        return $query->select('parent_id','category_id','id','translation_lang','translation_of','name','slug','photo','active');
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

    public function mainCategory(){
        return $this->belongsTo(MainCategory::class,'category_id');
    }

}
