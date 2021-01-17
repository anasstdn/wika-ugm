<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Profil;
use App\Models\UserProfil;
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
        $cek_data_exists = \DB::select('SELECT * FROM user_profil WHERE user_id ="'.\Auth::user()->id.'" LIMIT 1');
        // dd($cek_data_exists);

        if(isset($cek_data_exists) && !empty($cek_data_exists))
        {
            $data = Profil::find($cek_data_exists[0]->profil_id);

            return view('pengaturan::profil.index',compact('data'));
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
        $id_profil = null;

        $cek_data_exists = \DB::select('SELECT * FROM user_profil WHERE user_id ="'.\Auth::user()->id.'" LIMIT 1');
        // dd($cek_data_exists);

        if(isset($cek_data_exists) && !empty($cek_data_exists))
        {
            $profile = Profil::find($cek_data_exists[0]->profil_id);
            $id_profil = $profile->id;
        }

        if(!isset($profile))
        {
            $id_profil = $id;
        }

        return view('pengaturan::profil.form',compact('id_profil','profile'));
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
        Validator::make($request->all(), [
            'nik' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'status_perkawinan' => 'required',
            'alamat_ktp' => 'required',
            'kota_ktp' => 'required',
            'alamat_domisili' => 'required',
            'kota_domisili' => 'required',
            'no_telp' => 'required',
            'email' => 'required',
        ]);

        $input = $request->all();

        DB::beginTransaction();
        try {

           $flag=isset($input['foto_name'])?$input['foto_name']:null;

           if($request->hasFile('profile-settings-avatar'))
           {
            $extension = $request->file('profile-settings-avatar')->getClientOriginalExtension();
            $dir = 'images/profile/';
            $flag = uniqid() . '_' . time() . '.' . $extension;
            $request->file('profile-settings-avatar')->move($dir, $flag);
        }

        $dataUser  = array(
            'nama' => ucwords(strtolower($input['nama'])),
            'nik' => $input['nik'] ,
            'jenis_kelamin' => $input['jenis_kelamin'] ,
            'agama' => $input['agama'] ,
            'status_perkawinan' => $input['status_perkawinan'] ,
            'alamat_domisili' => $input['alamat_domisili'] ,
            'kota_domisili' => $input['kota_domisili'] ,
            'alamat_ktp' => $input['alamat_ktp'] ,
            'kota_ktp' => $input['kota_ktp'] ,
            'tempat_lahir' => $input['tempat_lahir'] ,
            'tgl_lahir' => date('Y-m-d',strtotime($input['tanggal_lahir'])) ,
            'no_telp' => $input['no_telp'] ,
            'email' => $input['email'] ,
            'foto' => $flag,
          );

        $userProfil = UserProfil::where('user_id',\Auth::user()->id)->first();

        if(empty($userProfil))
        {
            $save_profil = Profil::create($dataUser);
            $insert = UserProfil::create(['user_id' => \Auth::user()->id,'profil_id' => $save_profil->id]);
        }
        else
        {
            $insert = Profil::find($id)->update($dataUser);
        }

            message($insert,'Data berhasil disimpan!','Data gagal disimpan!');
        } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect('/profil');
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
