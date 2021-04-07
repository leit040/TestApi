<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class ProjectPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return mixed
     */
    public function view(User $user, Project $project): bool
    {
        if (DB::table('project_user')->where('project_id', '=', $project->id)->where('user_id', '=', $user->id)->get()->count() > 0) {
            return true;
        }
        return false;
    }



    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return mixed
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }



    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Project $project
     * @return mixed
     */
    public function forceDelete(User $user, Project $project)
    {
        return $user->id === $project->user_id;
    }
}
