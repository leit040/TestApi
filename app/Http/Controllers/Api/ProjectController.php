<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ProjectCollection
     */
    public function index(Request $request)
    {
        $data = $request->all();
        if (isset($data['filter'])){


        }

        return new ProjectCollection(Project::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_all = $request->all();
        foreach ($data_all as $data) {

            $validator = Validator::make($data, [
                    'name' => ['required', 'min:10','unique:projects,name'],
                    'user_id' => ['required', 'exists:users,id']
                ]
            )->validate();


        Project::create($data);


        }
        return response(['status:'=>'ok']);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        foreach ($request->all() as $data) {

            $validator = Validator::make($data, [
                'id' => ['required', 'exists:projects,id'],
                'name' => ['required', 'min:10'],
                'user_id' => ['required', 'exists:users,id'],
            ])->validate();

            $project = Project::find($data['id']);
            $project->update($data);
        }
return response(['status:'=>'ok']);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data=$request->json();
        foreach ($data as $id){
            $project=Project::find($id);
            $project->delete();
        }
        return response(['status:'=>'ok']);
    }
}
