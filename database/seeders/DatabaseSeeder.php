<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Project;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Continent;
use App\Models\Country;

class DatabaseSeeder extends Seeder
{
    /***
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('http://country.io/continent.json');

        foreach ($response->json() as $key => $value) {
            Continent::create(['country_code' => $key, 'continent_code' => $value]);
        }
        $response = Http::get('country.io/names.json');
        foreach ($response->json() as $key => $value) {
            $id = DB::table('continents')->select('id')->where('country_code', $key)->first();

            Country::create(['continent_id' => $id->id, 'name' => $value]);

        }

        $countries = Country::all();
        $users = \App\Models\User::factory(10)->make(['country_id' => null])->each(function ($user) use ($countries) {
            $user->country_id = $countries->random()->id;
            $user->save();
        });
       $projects =  Project::factory(10)->make(['user_id'=>null])->each(function ($project)use($users){
            $project->user_id = $users->random()->id;
            $project->save();
        });
        $labels = Label::factory(10)->make(['user_id'=>null])->each(function ($label)use($users){
            $label->user_id = $users->random()->id;
            $label->save();
        });

        foreach ($users as $user){
         $user->projects()->attach($projects->random(rand(2,5))->pluck('id'));
         //$project->labels()->attach($labels->random(rand(3,7))->pluck('id'));

        }


    }
}
