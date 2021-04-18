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
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UserCollection
     */
    public function index(Request $request): UserCollection
    {
        $data = $request->all();
        if (isset($data['filter'])) {
            if (array_key_exists('is_verified', $data['filter'])) {
                $query = User::query()->where('email_verified_at', 'IS NOT', null);
                unset($data['filter']['is_verified']);
            } else {
                $query = User::query();
            }
            if (count($data['filter']) > 0) {
                {
                    foreach ($data['filter'] as $filter => $option) {
                        $query->where($filter, $option);
                    }
                }
            }
            $users = $query->get();
            return new UserCollection($users);
        }
        return new UserCollection(User::all());
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
        Validator::make($data_all, [
                '*.name' => ['required', 'min:10'],
                '*.email' => ['required', 'unique:users,email', 'email:rfc,dns'],
                '*.password' => ['required', 'min:8',],
                '*.country_id' => ['required', 'exists:countries:id']
            ]
        )->validate();


        foreach ($data_all as $data) {
            $data['password'] = Hash::make($data['password']);
            $data['verify_token'] = Str::random(45);
            $user = User::create($data);
            $queue = new \App\Jobs\VerifyEmail($user);
            $queue->onQueue('verifyEmail')->dispatch($user);
        }
        return response()->json(['status' => 'Ok', 'message' => 'Users saved']);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {


            Validator::make($request->all(), [
                    '*.id' => ['required', 'exists:users:id'],
                    '*.name' => ['required', 'min:10'],
                    '*.email' => ['required', 'unique:users,email', $request->all()['id'], 'email:rfc,dns'],
                    '*.password' => ['required', 'min:8',],
                    '*.country_id' => ['required', 'exists:countries:id']
                ]
            )->validate();
        foreach ($request->all() as $data) {
            $data['password'] = Hash::make($data['password']);
            $user = User::find($data['id']);
            $user->update($data);
        }
        return response()->json(['status' => 'Ok', 'message' => 'Users saved']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        User::destroy($request->json());
        return response()->json(['status' => 'Ok', 'message' => 'Users deleted']);
    }

    /**
     * User's email verification.
     *
     * @param User $user , Request $request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function verify(User $user, Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($user);
        $data = $request->only('token');
        if ($user->verify_token === $data['token']) {
            $user->email_verified_at = now();
            $user->save();
        }
        return response()->json(['status' => 'Ok', 'message' => 'User verified']);
    }

    /**
     * User's identification.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function authUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $user->createToken($request->device_name)->plainTextToken;

    }

}
