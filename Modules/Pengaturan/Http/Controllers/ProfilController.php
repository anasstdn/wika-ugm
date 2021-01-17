<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Profil;
use App\Models\User;
use App\Models\JenisKelamin;
use App\Models\Agama;
use App\Models\StatusPerkawinan;
use DB;
use Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:profil-list|profil-create|profil-edit|profil-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:profil-create', ['only' => ['create','store']]);
        $this->middleware('permission:profil-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:profil-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id_profil = isset(\Auth::user()->user_profil->profil_id)?\Auth::user()->user_profil->profil_id:null;
        $foto_profile=\App\Models\Profil::find($id_profil);

        $cek_data_exists = \DB::select('SELECT * FROM profil WHERE id ="'.$id_profil.'" LIMIT 1');
        // dd($cek_data_exists);

        if(isset($cek_data_exists) && !empty($cek_data_exists))
        {
            return view('pengaturan::profil.index',compact('foto_profile'));
        }
        else
        {
            $id=\Auth::user()->profile_id!==null?\Auth::user()->profile_id:\Auth::user()->id;
            message(false,'','Silahkan lengkapi dahulu profil anda sebelum melanjutkan.');
            return redirect('profil/edit/'.$id);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pengaturan::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pengaturan::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $profile=null;
        // dd($id);
        $id_profil = isset(\Auth::user()->user_profil->profil_id)?\Auth::user()->user_profil->profil_id:null;
        if($id_profil!==null)
        {
            $profile=Profil::leftjoin('pegawai','pegawai.id_profil','=','profil.id')->find($id);
        }
        return view('pengaturan::profil.form');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function loadDataJenisKelamin() {
        $jenis_kelamin = JenisKelamin::select('*')->where('flag_aktif','Y')->get();
        return $jenis_kelamin;
    }

    public function loadDataAgama() {
        $agama = Agama::select('*')->where('flag_aktif','Y')->get();
        return $agama;
    }

    public function loadDataStatusPerkawinan() {
        $data = StatusPerkawinan::select('*')->where('flag_aktif','Y')->get();
        return $data;
    }
}
