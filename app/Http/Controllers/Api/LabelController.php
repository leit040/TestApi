<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabelCollection;
use App\Http\Resources\ProjectCollection;
use App\Models\Label;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\SafeStorage;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return LabelCollection
     */
    public function index(Request $request): LabelCollection
    {
        $query = Label::query();


        if($request->has('email')){
         $query->join('users','labels.user_id','=','users.id',)
             ->where('email','=',$request->get('email'));

        }
        if($request->has('projects')){
            $query->join('label_project','labels.id','=','label_project.label_id')
                ->whereIn('project_id',$request->get('projects'));

        }
        return new LabelCollection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {

        $data_all = $request->all();
        foreach ($data_all as $data) {

            $validator = Validator::make($data, [
                    'name' => ['required', 'min:10','unique:projects,name'],
                    'user_id' => ['required', 'exists:users,id']
                ]
            )->validate();


            Label::create($data);


        }
        return response()->json(['status'=>'Ok','message'=>'Label saved']);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Label $label
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

            $label = Label::find($data['id']);
            $label->update($data);
        }
        return response()->json(['status'=>'Ok','message'=>'Label updated']);
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
            $project=Label::find($id);
            $project->delete();
        }
        return response()->json(['status'=>'Ok','message'=>'Label deleted']);
    }

    public function linkToProjects(Request $request)
    {
        foreach ($request->all() as $data) {
            Label::findOrFail($data['label'])->projects()->syncWithoutDetaching($data['projects']);

        }
    }

}
