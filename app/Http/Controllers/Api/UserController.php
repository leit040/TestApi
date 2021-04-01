<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param null $name
     * @param null $email
     * @param null $isVerified
     * @param null $country_id
     * @return UserCollection
     */
    public function index($name = null,$email=null,$isVerified=null,$country_id=null):UserCollection
    {


      if($name!=null && $email!=null && $isVerified!=null && $country_id!=null)
      {
            //ALL
          $users = User::where('email',$email)->where('name',$name)->where('is_verified','!=',null)->where('country_id',$country_id)->get();
          return new UserCollection($users);
      }
        if($name!=null && $email!=null && $isVerified!=null)
        {
            //3 FIRST
            $users = User::where('email',$email)->where('name',$name)->where('is_verified','!=',null)->get();
            return new UserCollection($users);
        }

        if($name!=null && $email!=null)
        {
            //2 FIRST
            $users = User::where('email',$email)->where('name',$name)->get();
            return new UserCollection($users);
        }

        if($name!=null)
        {
            //ONLY ONE FIRST
            $users = User::where('email',$email)->get();
            return new UserCollection($users);

        }

        if($email!=null)
        {
            //ONLY second
            $users = User::where('name',$name)->get();
            return new UserCollection($users);

        }

        if($isVerified!=null)
        {
            //ONLY third
            $users = User::where('is_verified','!=',null)->get();
            return new UserCollection($users);

        }

        if($country_id!=null)
        {
            //ONLY fourth
            $users = User::where('country_id',$country_id)->get();
            return new UserCollection($users);
        }

        if($name!=null && $email!=null && $isVerified!=null && $country_id!=null)
        {
            //FIRST AND LAST
            $users = User::where('email',$email)->where('country_id',$country_id)->get();
            return new UserCollection($users);
        }
        if($name!=null && $email!=null && $isVerified!=null && $country_id!=null)
        {
            //FIRST AND THIRD
            $users = User::where('email',$email)->where('is_verified')->get();
            return new UserCollection($users);
        }

        if($email!=null && $isVerified!=null)
        {
            //SECOND AND THIRD
            $users = User::where('email',$email)->where('is_verified')->get();
            return new UserCollection($users);
        }

        if($email!=null && $isVerified!=null && $country_id!=null)
        {
            //forth, third and second
            $users = User::where('name',$name)->where('is_verified','!=',null)->where('country_id',$country_id)->get();
            return new UserCollection($users);
        }

        if($name!=null && $email!=null && $isVerified!=null && $country_id!=null)
        {
            //fourth and second
            $users = User::where('name',$name)->where('country_id',$country_id)->get();
            return new UserCollection($users);
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

           );

           $data['password'] = Hash::make($data['password']);

           User::create($data);
       }
    return response(['status:'=>'ok']);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       foreach ($request->all() as $data) {

            $validator = Validator::make($data, [
                    'id'=>['required', 'exists:users:id'],
                    'name' => ['required', 'min:10'],
                    'email' => ['required', 'unique:users,email', 'email:rfc,dns'],
                    'password' => ['required', 'min:8',],
                    'country_id' => ['required', 'exists:countries:id']

                ]

            );

            $data['password'] = Hash::make($data['password']);
       $data['password'] = Hash::make($data['password']);
        $user->update($data);}
        return response(['status:'=>'ok']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
