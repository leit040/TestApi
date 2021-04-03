<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\Project;
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
        $continent_array = $response->json();
        $response = Http::get('http://country.io/names.json');
        $country_array = $response->json();
        foreach (array_unique($continent_array) as $key => $code) {
            Continent::create(['continent_code' => $code]);
        }

        foreach ($country_array as $key => $value) {

            $id = DB::table('continents')->select('id')->where('continent_code', $continent_array[$key])->first();
            Country::create(['continent_id' => $id->id, 'name' => $value]);

        }


        $countries = Country::all();
        $users = \App\Models\User::factory(100)->make(['country_id' => null])->each(function ($user) use ($countries) {
            $user->country_id = $countries->random()->id;
            $user->save();
        });
        $projects = Project::factory(10)->make(['user_id' => null])->each(function ($project) use ($users) {
            $project->user_id = $users->random()->id;
            $project->save();
        });
        $labels = Label::factory(10)->make(['user_id' => null])->each(function ($label) use ($users) {
            $label->user_id = $users->random()->id;
            $label->save();
        });

        foreach ($projects as $project) {
            $project->users()->attach($users->random(rand(2, 5))->pluck('id'));
            $project->labels()->attach($labels->random(rand(3, 7))->pluck('id'));

        }


    }
}
