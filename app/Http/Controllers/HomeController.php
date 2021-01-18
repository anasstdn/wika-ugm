<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $config_admin = getConfigValues('ROLE_ADMIN');

        if(isset(\Auth::user()->roles[0]->id) && !in_array(\Auth::user()->roles[0]->id, $config_admin))
        {
            $cek_data_exists = \DB::select('SELECT * FROM user_profil WHERE user_id ="'.\Auth::user()->id.'" LIMIT 1');

            if(isset($cek_data_exists) && !empty($cek_data_exists))
            {
                return view('home');
            }
            else
            {
                $id=\Auth::user()->id;
                message(false,'','Silahkan lengkapi dahulu profil anda sebelum melanjutkan.');
                return redirect('profil/edit/'.$id);
            }
        }
        return view('home');
    }
}
