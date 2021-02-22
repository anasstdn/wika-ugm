<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Cookie;
use Illuminate\Http\Request;
use App\Traits\ActivityTraits;

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
    use ActivityTraits;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username=$this->findUsername();
    }

    public function findUsername()
    {
        $login=request()->input('email');
        $fieldType=filter_var($login,FILTER_VALIDATE_EMAIL)?'email':'username';
        request()->merge([$fieldType=>$login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    // protected function credentials(Request $request)
    // {
    //     return array_merge($request->only($this->username(), 'password'), ['status_aktif' => 1]);
    // }
    
    public function login(Request $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
  
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])))
        {
            // dd($requ);
            $this->logLoginDetails(\Auth::user());
            $pesan='';
            $pesan.='Pengguna '.strtoupper(strtolower (Auth::user()->name)).'';
            $pesan.='<br>Anda melakukan login ke sistem pada '.date('d-m-Y H:i:s');

            message(true,$pesan,'');
            return redirect('home');
        }else{
            $errors = [$this->username() => trans('auth.failed')];
            $user = \App\User::where($this->username(), $request->{$this->username()})->first();

        // Check if user was successfully loaded, that the password matches
        // and active is not 1. If so, override the default error message.
            if ($user && \Hash::check($request->password, $user->password) && $user->status_aktif != 1) {
                $errors = [$this->username() => trans('auth.inactive')];
            }


            if ($request->expectsJson()) {
                return response()->json($errors, 422);
            }

            message(false,'',$errors['username']);

            return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
        }
          
    }

    public function authenticated(Request $request, $user)
    {
        $remember_me = $request->has('remember') ? true : false; 
        if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')], 
            $remember_me))
        {
            $user = auth()->user();
        }

        $pesan='';
        $pesan.='Pengguna '.strtoupper(strtolower (Auth::user()->name)).'';
        $pesan.='<br>Anda melakukan login ke sistem pada '.date('d-m-Y H:i:s');

        message(true,$pesan,'');

        return redirect()->intended($this->redirectPath());
    }

    public function logout(Request $request)
    {
        // $token = \Auth::user()->api_token;
        // $url = (!empty(get_val_settings('api_access'))?get_val_settings('api_access'):'localhost:8000')."/api/v1/logout";
        // $get_data = get_data_with_param($data = array(), $token, $url, 'POST');

        $this->logLogoutDetails(Auth::user());

        $this->guard()->logout();
        
        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/home');
    }
}
