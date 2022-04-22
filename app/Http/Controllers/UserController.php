<?php

namespace App\Http\Controllers;

use App\Http\CustomResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    use CustomResponse;

    public function register(RegisterRequest $request)
    {

        if (User::where('email', '=', $request['email'])->get()->isEmpty()) {

            Gate::authorize('create-user');

            $request['password'] = bcrypt($request->input('password'));

            if ($request['role'] == 'partner') {

                DB::beginTransaction();

                try {
                    $user = User::create([
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => $request['password'],
                        'role' => $request['role']
                    ]);

                    $user->partnerCompany()->create([
                        'name' => $request['company_name'],
                        'company_code' => $request['national_code'],
                        'webhook_url' => $request['webhook_url'],
                        'user_id' => $user->id
                    ]);
                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong
                }
                return $this->onSuccess(200, 'User Created');

            } else if ($request['role'] == 'delivery man') {


                DB::beginTransaction();

                try {

                    $user = User::create([
                        'name' => $request['name'],
                        'email' => $request['email'],
                        'password' => $request['password'],
                        'role' => $request['role']
                    ]);

                    $user->deliveryMan()->create([
                        'name' => $request['name'],
                        'national_code' => $request['national_code'],
                        'user_id' => $user->id
                    ]);


                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong
                }

                return $this->onSuccess(200, 'User Created');

            } else {
                return $this->onError(403, 'What Are You Doing?');
            }
        } else {
            return $this->onError(400, 'Duplicated Email');
        }


    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'error' => 'Invalid login'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!Auth::check()) {
            $this->onError(400, "User Can't Login");
        }


        $user = Auth::user();

        $token = $user->createToken('auth_token', ["$user->role"])->plainTextToken;

        return response([
            'token' => $token
        ]);
    }
}
