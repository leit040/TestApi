<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabelCollection;
use App\Http\Resources\ProjectCollection;
use App\Models\Label;
use App\Models\Project;
use Illuminate\Http\Request;
use League\Flysystem\SafeStorage;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LabelCollection
     */
    public function index(): LabelCollection
    {
        return new LabelCollection(Label::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {

           $data = $request->validate([

            'name'=> ['required'],
            'user_id'=>['required','exists:App\Models\User,id']
        ]);
        Label::create($data);
        return response()->json(['status'=>'Ok','message'=>'Label saved']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Label $label): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'name'=> ['required', 'unique:labels'],
            'user_id'=>['required','exists:App\Models\User,id']
        ]);
        $label->update($data);
        return response()->json(['status'=>'Ok','message'=>'Label updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Label $label
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Label $label)
    {   $label->projects()->detach();
        $label->delete();

    }

    public function link(ProjectCollection $projects,Label $label){
     foreach ($projects as $project){
         $label->projects()->attach($project->id);
     }
    }
}
