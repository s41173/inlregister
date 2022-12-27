<?php

namespace App\Http\Controllers\API\V1;

use App\Models\UserOdoo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class UserOdooController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        // $this->authorize('isAdmin');

        $users = UserOdoo::whereNotNull("level_ext")->paginate(10);

        return $this->sendResponse($users, 'Users list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //login, company_id=1, partner_id=1, notification_type=email, odoobot_state=onboarding_emoji, 
        //password_ext, level_ext, 
        $user = UserOdoo::create([
            'login' => $request['login'],
            'company_id' => 1,
            'partner_id' => 1,
            'notification_type' => "email",
            'odoobot_state' => "onboarding_emoji",
            'password_ext' => $request['password_ext'],
            'level_ext' => $request['level_ext'],
        ]);

        return $this->sendResponse($user, 'User Created Successfully');
    }

    /**
     * Update the resource in storage
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $user = UserOdoo::findOrFail($id);

        // if (!empty($request->password)) {
        //     $request->merge(['password' => Hash::make($request['password'])]);
        // }

        $user->update($request->all());

        return $this->sendResponse($user, 'User Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $user = UserOdoo::findOrFail($id);
        // delete the user

        $user->delete();

        return $this->sendResponse([$user], 'User has been Deleted');
    }
}
