<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class LabelPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Label  $label
     * @return mixed
     */
    public function view(User $user, Label $label): bool
    {
        if( $user->id === $label->user_id)
        {return true;}
        if(DB::table('label_project')->
            join('project_user','label_project.project_id','=','project_user.project_id')->
            where('project_user.user_id','=',$user->id)->get()->count()>0)
        { return true;}
        return false;
    }



    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Label  $label
     * @return mixed
     */
    public function update(User $user, Label $label): bool
    {
        return $user->id === $label->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Label  $label
     * @return mixed
     */
    public function delete(User $user, Label $label): bool
    {
        return $user->id === $label->user_id;
    }


    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Label  $label
     * @return mixed
     */
    public function forceDelete(User $user, Label $label): bool
    {
        return $user->id === $label->user_id;
    }
}
