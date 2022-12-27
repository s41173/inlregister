<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\UserOdoo;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // ------------------------ Custome 

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {

        // $this->validateLogin($request);
        // $credentials = $request->only('email', 'password');
 
        // if (Auth::attempt($credentials)) {
        //     return redirect()->intended('dashboard');
        // }

        $username = $request->get('email');
        $password = $request->get('password');
        $CheckUser = UserOdoo::where('login',$username)->first();
        if(empty($CheckUser)){
            $res['status'] =  false;
            $res['message'] =  'Username tidak ditemukan';
            return response()->json($res, 200);
        }

        $CheckPass = UserOdoo::where('password_ext',$password)->first();
        if(empty($CheckPass)){
            $res['status'] =  false;
            $res['message'] =  'Pasword tidak valid';
            return response()->json($res, 200);
        }
        $login_id = $CheckUser->id;
        $login_user = $CheckUser->login;
        $login_level = $CheckUser->level_ext;

        $log_temp = array(
            "username" => "userapi",
            "password" => "12345678"
        );
        if (! $token = auth()->attempt($log_temp)) {
            $res['status'] =  false;
            $res['message'] =  'Unauthorized Gagal, Username / Password Salah';
            return response()->json($res, 200);
        }
        Session::put('login_id', $login_id);
        Session::put('login_user', $login_user);
        Session::put('login_level', $login_level);

        return redirect()->intended('/home'); //Aftar Login

        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        // if (method_exists($this, 'hasTooManyLoginAttempts') &&
        //     $this->hasTooManyLoginAttempts($request)) {
        //     $this->fireLockoutEvent($request);

        //     return $this->sendLockoutResponse($request);
        //     }

        // if ($this->attemptLogin($request)) {
        //     if ($request->hasSession()) {
        //         $request->session()->put('auth.password_confirmed_at', time());
        //     }

        //     return $this->sendLoginResponse($request);
        // }

        // // If the login attempt was unsuccessful we will increment the number of attempts
        // // to login and redirect the user back to the login form. Of course, when this
        // // user surpasses their maximum number of attempts they will get locked out.
        // $this->incrementLoginAttempts($request);

        // return $this->sendFailedLoginResponse($request);
    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->intended($this->redirectPath());
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

        /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
