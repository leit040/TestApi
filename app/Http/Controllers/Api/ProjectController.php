<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Continent;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * @OA\Get(
     *      path="/projects",
     *      operationId="getProjectsList",
     *      tags={"Projects"},
     *      summary="Get list of projects",
     *      description="Returns list of projects",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     **/
    public function index(Request $request): ProjectCollection
    {
       $query = Project::query()->select('projects.*')->join('project_user', 'project_user.project_id', '=', 'projects.id')->where('project_user.user_id', '=', auth()->id());
        if ($request->has('email')) {
            $query->join('users', 'projects.user_id', '=', 'users.id')
                ->where('email', '=', $request->get('email'));
        }
        if ($request->has('labels')) {
            $query->join('label_project', 'projects.id', '=', 'label_project.project_id')
                ->whereIn('label_id', $request->get('labels'));

        }
        if ($request->has('continent')) {
            if ($request->has('email')){$query->join('countries', 'users.country_id', '=', 'countries.id')->join('continents',
                'countries.continent_id', '=', 'continents.id')->
            where('continents.id', '=', $request->get('continent'));}
            else {
                $query->join('users', 'projects.user_id', '=', 'users.id')->
                join('countries', 'users.country_id', '=', 'countries.id')->join('continents',
                    'countries.continent_id', '=', 'continents.id')->
                where('continents.id', '=', $request->get('continent'));
            }
        }
        return new ProjectCollection($query->get());
    }


    /**
     * @OA\Post(
     *      path="/projects",
     *      operationId="storeProject",
     *      tags={"Projects"},
     *      summary="Store new projects",
     *      description="Returns project data",
     *      @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     * ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $data_all = $request->all();
            Validator::make($data_all, [
            '*.name' => ['required', 'min:10', 'unique:projects,name'],
                                    ]
            )->validate();
            foreach ($data_all as $data) {
            $data->user_id = Auth::id();
            $project = Project::create($data);
            $project->linked_users()->attach($data['user_id']);

        }
        return response()->json(['status' => 'Ok', 'message' => 'Projects saved']);
    }

    /**
     * Link projects to users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function linkToUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        foreach ($request->all() as $data) {
            Project::findOrFail($data['project'])->linked_users()->syncWithoutDetaching($data['users']);
        }
        return response()->json(['status' => 'Ok', 'message' => 'Projects linked']);
    }

    /**
     * @OA\Put(
     *      path="/projects/",
     *      operationId="updateProjects",
     *      tags={"Projects"},
     *      summary="Update existing projects",
     *      description="Returns updated project data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Project id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateProjectRequest")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Project")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {

            Validator::make($request->all(), [
                '*.id' => ['required', 'exists:projects,id'],
                '*.name' => ['required', 'min:10'],
                ])->validate();

        foreach ($request->all() as $data) {
            $project = Project::find($data['id']);
            abort_if($request->user()->cannot('update', $project), 403);
            $project->update($data);
        }
        return response()->json(['status' => 'Ok', 'message' => 'Projects saved']);


    }

    /**
     * @OA\Delete(
     *      path="/projects/",
     *      operationId="deleteProjects",
     *      tags={"Projects"},
     *      summary="Delete existing project",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Project id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->json();
        foreach ($data as $id) {
            $project = Project::find($id);
            abort_if($request->user()->cannot('delete', $project), 403);
            $project->delete();
        }
        return response()->json(['status' => 'Ok', 'message' => 'Projects deleted']);
    }
}
