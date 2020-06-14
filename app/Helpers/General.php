<?php


use Illuminate\Support\Facades\Config;

function show_name(){
    App\Models\Language::active()->selection()->get();

}

function get_default_lang(){
    return Config::get('app.locale');
}