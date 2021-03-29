<?php

use App\Models\Continent;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get("/cont",function (){
    $response = Http::get('http://country.io/continent.json');

    foreach ($response->json() as $key=> $value){
        Continent::create(['country_code'=>$key,'continent_code'=>$value]);
    }


    $response = Http::get('country.io/names.json');

    foreach ($response->json() as $key=> $value){


        $id = DB::table('continents')->select('id')->where('country_code',$key)->first();
      //  dd($id->id);
        echo "Country = ".$value." Continent = ".$key." ID_cont = ".$id->id."<br>"."\\n";
        if($value!=null) {
            Country::create(['continent_id' => $id->id, 'name' => $value]);
        }

    }
});
