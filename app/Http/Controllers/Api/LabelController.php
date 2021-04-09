<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabelCollection;
use App\Http\Resources\ProjectCollection;
use App\Models\Label;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
        $query = Label::query()->select('labels.*')->join('label_project', 'label_project.label_id', '=', 'labels.id')->
        join('project_user', 'project_user.project_id', '=', 'label_project.project_id')->where('project_user.user_id', '=', auth()->id());


        if ($request->has('email')) {
            $query->join('users', 'labels.user_id', '=', 'users.id',)
                ->where('email', '=', $request->get('email'));

        }
        if ($request->has('projects')) {
            $query->
            whereIn('label_project.project_id', $request->get('projects'));

        }
        return new LabelCollection($query->get());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function store(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {

        $data_all = $request->all();
        foreach ($data_all as $data) {

            $validator = Validator::make($data, [
                    'name' => ['required', 'min:10', 'unique:projects,name'],

                ]
            )->validate();

            $data->user_id = Auth::id();
            Label::create($data);


        }
        return response()->json(['status' => 'Ok', 'message' => 'Label saved']);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */


    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        foreach ($request->all() as $data) {

            $validator = Validator::make($data, [
                'id' => ['required', 'exists:projects,id'],
                'name' => ['required', 'min:10'],
                        ])->validate();

            $label = Label::find($data['id']);
            $label->update($data);
        }
        return response()->json(['status' => 'Ok', 'message' => 'Label updated']);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->json();
        foreach ($data as $id) {
            $label = Label::find($id);
            if ($request->user()->cannot('delete', $label)) {
                abort(403);
            }
            $label->delete();
        }
        return response()->json(['status' => 'Ok', 'message' => 'Label deleted']);
    }



    /**
     * Link labels to projects.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkToProjects(Request $request): \Illuminate\Http\JsonResponse
    {
        foreach ($request->all() as $data) {
            Label::findOrFail($data['label'])->projects()->syncWithoutDetaching($data['projects']);
            return response()->json(['status' => 'Ok', 'message' => 'Labels Linked']);
        }
        return response()->json(['status' => 'Ok', 'message' => 'Labels linked']);
    }

}
