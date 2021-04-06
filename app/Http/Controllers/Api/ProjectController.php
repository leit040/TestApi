<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Models\Continent;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ProjectCollection
     */
    public function index(Request $request): ProjectCollection
    {
       $query = Project::query()->select('projects.*');
       if($request->has('email')){
           $query->join('users','projects.user_id','=','users.id')
               ->where('email','=',$request->get('email'));

       }

        if($request->has('labels')){
            $query->join('label_project','projects.id','=','label_project.project_id')
                ->whereIn('label_id',$request->get('labels'));

        }

        if($request->has('continent'))
        {
            $query->join('users','projects.user_id','=','users.id')->
            join('countries','users.country_id','=','countries.id')->join('continents',
                'countries.continent_id','=','continents.id')->
            where('continents.id','=',$request->get('continent'));

        }
         return new ProjectCollection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
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
        return response()->json(['status'=>'Ok','message'=>'Projects saved']);
    }

public function linkToUsers(Request $request){

        foreach($request->all() as $data){
            Project::findOrFail($data['project'])->linked_users()->syncWithoutDetaching($data['users']);

        }

}
       /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
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
        return response()->json(['status'=>'Ok','message'=>'Projects saved']);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $data=$request->json();
        foreach ($data as $id){
            $project=Project::find($id);
            $project->delete();
        }
        return response()->json(['status'=>'Ok','message'=>'Projects deleted']);
    }
}
