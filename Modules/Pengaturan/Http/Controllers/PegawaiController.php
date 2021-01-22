<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Pegawai;
use App\Models\Profil;
use App\Models\Jabatan;
use App\Models\Departement;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pegawai-list|pegawai-create|pegawai-edit|pegawai-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:pegawai-create', ['only' => ['create','store']]);
        $this->middleware('permission:pegawai-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:pegawai-delete', ['only' => ['destroy']]);
    }


    public function index()
    {
        return view('pengaturan::pegawai.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pengaturan::pegawai.form');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
        Validator::make($request->all(), [
            'profil_id' => 'required',
            'nip' => 'required',
            'jabatan_id' => 'required',
            'departement_id' => 'required',
            'tgl_bergabung' => 'required',
            'status_resign' => 'required',
        ]);

        $input = $request->all();

        DB::beginTransaction();
        try {
            $tgl_resign = null;
            if($input['status_resign'] == 'Y')
            {
                $tgl_resign = date('Y-m-d',strtotime($input['tgl_resign']));
            }

            $data = array(
                'profil_id' => $input['profil_id'],
                'nip' => $input['nip'],
                'jabatan_id' => $input['jabatan_id'],
                'departement_id' => $input['departement_id'],
                'tgl_bergabung' => date('Y-m-d',strtotime($input['tgl_bergabung'])),
                'status_resign' => $input['status_resign'],
                'tgl_resign' => $tgl_resign,
                'user_input' => \Auth::user()->id, 
            );

            $insert = Pegawai::create($data);

            message($insert,'Data berhasil disimpan!','Data gagal disimpan!');

        } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect('/pegawai');
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
        $data = Pegawai::find($id);
        return view('pengaturan::pegawai.form',compact('data','id'));
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
            'profil_id' => 'required',
            'nip' => 'required',
            'jabatan_id' => 'required',
            'departement_id' => 'required',
            'tgl_bergabung' => 'required',
            'status_resign' => 'required',
        ]);

        $input = $request->all();

        DB::beginTransaction();
        try {
            $tgl_resign = null;
            if($input['status_resign'] == 'Y')
            {
                $tgl_resign = date('Y-m-d',strtotime($input['tgl_resign']));
            }

            $data = array(
                'profil_id' => $input['profil_id'],
                'nip' => $input['nip'],
                'jabatan_id' => $input['jabatan_id'],
                'departement_id' => $input['departement_id'],
                'tgl_bergabung' => date('Y-m-d',strtotime($input['tgl_bergabung'])),
                'status_resign' => $input['status_resign'],
                'tgl_resign' => $tgl_resign,
                'user_update' => \Auth::user()->id, 
            );

            $insert = Pegawai::find($id)->update($data);

            message($insert,'Data berhasil disimpan!','Data gagal disimpan!');

        } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect('/pegawai');
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

    public function getData(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Pegawai::select(\DB::raw('pegawai.*, profil.nama,jabatan.jabatan,departement.departement'))
                    ->join('profil','pegawai.profil_id','=','profil.id')
                    ->join('jabatan','jabatan.id','=','pegawai.jabatan_id')
                    ->join('departement','departement.id','=','pegawai.departement_id')
                    ->where(function($q) use($input){
                        if(isset($input['nama']) && !empty($input['nama']))
                        {
                            $q->where('nama','LIKE','%'.$input['nama'].'%');
                        }
                        if(isset($input['departement_id']) && !empty($input['departement_id']))
                        {
                            $q->where('pegawai.departement_id','=',$input['departement_id']);
                        }
                        if(isset($input['jabatan_id']) && !empty($input['jabatan_id']))
                        {
                            $q->where('pegawai.jabatan_id','=',$input['jabatan_id']);
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->orderby('profil.nama','ASC')
                    ->paginate($limit,['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['nama'] = $val->nama;
            $data[$key]['nip'] = $val->nip;
            $data[$key]['jabatan'] = $val->jabatan;
            $data[$key]['departement'] = $val->departement;
            if($val->status_resign == 'Y')
            {
                $data[$key]['status_resign'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-danger'>Pegawai Sudah Resign</span></div></div>";
            }
            else
            {
                $data[$key]['status_resign'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-success'>Pegawai Belum Resign</span></div></div>";
            }
            
            $add=url("pegawai/".$val->id)."/create";
            $edit=url("pegawai/".$val->id)."/edit";
            
            $delete=url("pegawai/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            //     $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            // }

            if(\Auth::user()->can('pegawai-edit'))
            {
                $data[$key]['aksi'] .="<a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";
            }

            // if(\Auth::user()->can('material-delete'))
            // {
            //     $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>";
            // }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function profilSearch(Request $request)
    {
        $data = \DB::table('profil')
                        ->select(\DB::raw('profil.id, CONCAT(nama) as text'))
                        ->where('profil.nama','LIKE','%'.$request->input('term', '').'%')
                        ->limit(100)
                        ->get();
        
        return ['results' => $data];
    }

    public function loadProfil(Request $request)
    {
        $profil_id = $request->input('profil_id',null);

        $data = \DB::table('profil')
                ->join('agama','agama.id','=','profil.agama')
                ->join('status_perkawinan','status_perkawinan.id','profil.status_perkawinan')
                ->join('jenis_kelamin','jenis_kelamin.id','=','profil.jenis_kelamin')
                ->where('profil.id',$profil_id)
                ->first();

        $arr['nama'] = $data->nama;
        $arr['nik'] = $data->nik;
        $arr['jenis_kelamin'] = $data->jenis_kelamin;
        $arr['agama'] = $data->agama;
        $arr['status_perkawinan'] = $data->status_perkawinan;
        $arr['tempat_lahir'] = $data->tempat_lahir.', '.date_indo(date('Y-m-d',strtotime($data->tgl_lahir))); 

        return response()->json($arr);
    }

    public function loadDataJabatan(Request $request)
    {
        $data = Jabatan::select('*')->where('flag_aktif','Y')->get();
        return $data;
    }

    public function loadDataDepartement(Request $request)
    {
        $data = Departement::select('*')->where('flag_aktif','Y')->get();
        return $data;
    }
}
