<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Mail\VerifyEmail;
use App\Models\User;
use DateTime;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UserCollection
     */
      public function index(Request $request)
      {
        $data = $request->all();

                 if (isset($data['filter'])) {
                  if (array_key_exists('is_verified', $data['filter'])) {
                  $query = User::query()->where('email_verified_at', '!=', null);
                  unset($data['filter']['is_verified']);
              }else{$query=User::query();}
              if (count($data['filter']) > 0) {
                  {
                      foreach ($data['filter'] as $filter => $option) {
                          $query->where($filter, $option);
                      }
                  }
              }
              $users = $query->get();
              return  new UserCollection($users);
          }
          return new UserCollection(User::all());
      }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): \Illuminate\Http\Response
    {

       $data_all=$request->all();
       foreach ($data_all as $data) {

               $validator = Validator::make($data, [
                   'name' => ['required', 'min:10'],
                   'email' => ['required', 'unique:users,email', 'email:rfc,dns'],
                   'password' => ['required', 'min:8',],
                   'country_id' => ['required', 'exists:countries:id']
               ]
           )->validate();

           $data['password'] = Hash::make($data['password']);
            $data['verify_token'] = Str::random(45);
           $user = User::create($data);
            $queue = new \App\Jobs\VerifyEmail($user);
            $queue->onQueue('verifyEmail')->dispatch($user);
       }
    return response(['status:'=>'ok']);

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       foreach ($request->all() as $data) {

            $validator = Validator::make($data, [
                    'id'=>['required', 'exists:users:id'],
                    'name' => ['required', 'min:10'],
                    'email' => ['required', 'unique:users,email',$data['id'], 'email:rfc,dns'],
                    'password' => ['required', 'min:8',],
                    'country_id' => ['required', 'exists:countries:id']

                ]

            )->validate();

       $data['password'] = Hash::make($data['password']);
        $user = User::find($data['id']);
        $user->update($data);
       }

       return response(['status:'=>'ok']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $data=$request->json();
            foreach ($data as $id){
            $user=User::find($id);
            $user->delete();
                    }

    }


public function verify($user, Request $request){
    $user=User::find($user);
       $data = $request->only('token');
          if($user->verify_token===$data['token']){
          $user->email_verified_at = now();
          $user->save();

      }
    return response(['status:'=>'ok']);
}
}
