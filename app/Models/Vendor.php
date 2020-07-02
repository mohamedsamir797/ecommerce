<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable ;
    protected $table = 'vendors';
    protected $fillable = ['name','logo','password','mobile','address','email','active','category_id'];

    protected $hidden = ['category_id','password'];

    public function scopeActive($query){
        return $query->where('active',1);
    }

    public function getLogoAttribute($val){
        return ($val !== null) ? asset('/assets/'.$val) : '';
    }
    public function scopeSelection($query){
        return $query->select('id','name','logo','category_id','email','address','mobile','active');
    }
    public function Maincategory(){
        return $this->belongsTo(\App\Models\MainCategory::class,'category_id');
    }
    public function getActive(){
        return $this->active == 1 ? 'مفعل' : 'غير مفعل' ;
    }

    public function setPasswordAttribute($password){
        if (!empty($password)){
            $this->attributes['password'] = bcrypt($password) ;
        }
    }

}
