<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\RiwayatStok;

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
        // dd(redirect('home')->getTargetUrl());
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

    public function materialDropdown()
    {
        $material = Material::where('parent_status','N')->orderby('kode_material','ASC')->get();
        return $material;
    }

    public function getDataStok(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $search = $request->has('search') ? $request->get('search') : null;

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $material_id = $input['material_id'] == 'null'?null:$input['material_id'];

        $dataList = Material::select('*')
                    ->where(function($q) use($material_id){
                        if(!empty($material_id))
                        {
                            $q->where('id',$material_id);
                        }
                    })
                    ->where('parent_status','N')
                    ->where('flag_aktif','Y')
                    ->orderby('kode_material','ASC')
                    ->paginate($limit,['*'], 'page', $page);

        $total_all = Material::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['kode_material'] = $val->kode_material;
            $data[$key]['material'] = $val->material;
            $data[$key]['satuan'] = $val->satuan;

            $get_stok = \DB::table('stok')->where('material_id',$val->id)->first();
            if(isset($get_stok) && !empty($get_stok))
            {
                $data[$key]['qty'] = $get_stok->qty;
            }
            else
            {
                $data[$key]['qty'] = 0;
            }            

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

        public function getDataRiwayatStok(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $search = $request->has('search') ? $request->get('search') : null;

        $material_id = $input['material_id'] == 'null'?null:$input['material_id'];
        $date_start = $input['date_start'];
        $date_end = $input['date_end'];

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = RiwayatStok::select('*')
                    ->where(function($q) use($material_id,$date_start,$date_end){
                        if(isset($material_id) && !empty($material_id))
                        {
                            $q->where('material_id',$material_id);
                        }
                        if(isset($date_start) && !empty($date_start))
                        {
                            $q->whereDate('tanggal_riwayat','>=',date('Y-m-d',strtotime($date_start)));
                        }
                        if(isset($date_end) && !empty($date_end))
                        {
                            $q->whereDate('tanggal_riwayat','<=',date('Y-m-d',strtotime($date_end)));
                        }
                    })
                    ->orderby('tanggal_riwayat','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        $total_all = RiwayatStok::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['material'] = Material::find($val->material_id)->material;
            $data[$key]['tanggal_riwayat'] = date('d M y',strtotime($val->tanggal_riwayat));
            $data[$key]['qty'] = $val->qty;       
            $data[$key]['penambahan'] = $val->penambahan;
            $data[$key]['pengurangan'] = $val->pengurangan;

            $user_profil = \DB::table('user_profil')->where('user_id', $val->user_input)->first();
            if(isset($user_profil) && !empty($user_profil))
            {
                $nama = \DB::table('profil')->find($user_profil->profil_id)->nama;
            }
            else
            {
                $nama = \DB::table('users')->find($val->user_input)->name;
            }

            $data[$key]['user_input'] = $nama;
            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }
}
