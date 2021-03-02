<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Cookie;
use Illuminate\Http\Request;
use App\Traits\ActivityTraits;
use Jenssegers\Agent\Agent;

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
            $profil = getProfileByUserId(\Auth::user()->id);

            if(!empty($profil))
            {
              $agent = new Agent();
              $browser = $agent->browser();
              $version_browser = $agent->version($browser);

              $platform = $agent->platform();
              $version_platform = $agent->version($platform);

              $emoticon_smile = "\ud83d\ude0a";
              $text = "Permintaan Masuk Baru. Halo <b>" . $profil->nama . "</b> kami mendeteksi adanya permintaan masuk ke akun Wika Web Anda.\n\n"
              . "Perangkat : ".$platform." ".$version_platform."\n"
              . "Browser : ".$browser."\n"
              . "IP Address : ".$this->getIp()."\n"
              . "Waktu : ".date('d/M/Y')." pukul ".date('H:i:s')."\n\n"
              . "Jika ini bukan Anda, Silahkan ubah password anda atau hubungi admin yang bersangkutan.\n\n"
              . "Jika menurut Anda ada seseorang yang ingin masuk ke akun tanpa sepengetahuan Anda, silahkan kombinasikan password anda dengan angka dan huruf besar kecil serta gunakan karakter khusus pada <b>Pengaturan Pengguna</b>.\n\n"
              . "Salam,\n"
              . "WikaBot.";  

              sendTelegramBot(getTelegramId(),$text);
          }
            

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

    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
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

        $profil = getProfileByUserId(\Auth::user()->id);

        if(!empty($profil))
        {
          $agent = new Agent();
          $browser = $agent->browser();
          $version_browser = $agent->version($browser);

          $platform = $agent->platform();
          $version_platform = $agent->version($platform);

          $emoticon_smile = "\ud83d\ude0a";
          $text = "Permintaan Keluar Baru. Halo <b>" . $profil->nama . "</b> kami mendeteksi adanya permintaan keluar dari akun Wika Anda.\n\n"
          . "Perangkat : ".$platform." ".$version_platform."\n"
          . "Browser : ".$browser."\n"
          . "IP Address : ".$this->getIp()."\n"
          . "Waktu : ".date('d/M/Y')." pukul ".date('H:i:s')."\n\n"
          . "Jika ini bukan Anda, Silahkan ubah password anda atau hubungi admin yang bersangkutan.\n\n"
          . "Jika menurut Anda ada seseorang yang ingin masuk ke akun tanpa sepengetahuan Anda, silahkan kombinasikan password anda dengan angka dan huruf besar kecil serta gunakan karakter khusus pada <b>Pengaturan Pengguna</b>.\n\n"
          . "Salam,\n"
          . "WikaBot.";

          sendTelegramBot(getTelegramId(),$text);
      }

        $this->guard()->logout();
        
        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/home');
    }
}
