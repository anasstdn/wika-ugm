<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Spm;
use App\Models\DetailSpm;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SPMController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:spm-list|spm-create|spm-edit|spm-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:spm-create', ['only' => ['create','store']]);
        $this->middleware('permission:spm-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:spm-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('transaksi::spm.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('transaksi::spm.form');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'no_spm' => 'required',
            'tgl_spm' => 'required',
            'nama_pemohon' => 'required',
            'lokasi' => 'required',
        ]);

        $input = $request->all();

        DB::beginTransaction();
        try {
            $data_spm = array(
                'no_spm' => $input['no_spm'],
                'tgl_spm' => date('Y-m-d',strtotime($input['tgl_spm'])),
                'nama_pemohon' => $input['nama_pemohon'],
                'lokasi' => $input['lokasi'],
                'keterangan' => $input['keterangan_spm'],
                'user_input' => \Auth::user()->id
            );

            $insert_spm = Spm::create($data_spm);
            $id_spm = $insert_spm->id;

            foreach($input['material_id'] as $key => $val)
            {
                $data_detail_spm = array(
                    'spm_id' => $id_spm,
                    'material_id' => $val,
                    'volume' => $input['volume'][$key],
                    'keterangan' => $input['keterangan'][$key]
                );

                $insert_detail_spm = DetailSpm::create($data_detail_spm);
            }

            message($insert_detail_spm,'Data berhasil disimpan!','Data gagal disimpan!');

        } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect('/spm');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = Spm::find($id);
        $data_detail = DetailSpm::where('spm_id',$data->id)->get();

        return view('transaksi::spm.detail',compact('data','data_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('transaksi::spm.form');
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

    public function searchMaterial(Request $request)
    {
        $data = \DB::table('material')
                        ->select(\DB::raw('material.id, CONCAT(kode_material," - ",material) as text'))
                        ->where('material','LIKE','%'.$request->input('term', '').'%')
                        ->where('flag_aktif','Y')
                        ->where('parent_status','N')
                        ->limit(100)
                        ->get();
        
        return ['results' => $data];
    }

    public function getData(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        // 
        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Spm::select(\DB::raw('spm.*'))
                    ->where(function($q) use($input){
                        if(isset($input['nama']) && !empty($input['nama']))
                        {
                            $q->where('no_spm','LIKE','%'.$input['nama'].'%')
                            ->orWhere('nama_pemohon','LIKE','%'.$input['nama'].'%')
                            ->orWhere('lokasi','LIKE','%'.$input['nama'].'%');
                        }
                        if(isset($input['tgl_spm']) && !empty($input['tgl_spm']))
                        {
                            $q->whereDate('tgl_spm','=',date('Y-m-d',strtotime($input['tgl_spm'])));
                        }
                        if(!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN')))
                        {
                            $q->where('user_input',\Auth::user()->id);
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->orderby('tgl_spm','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['no_spm'] = $val->no_spm;
            $data[$key]['tgl_spm'] = date_indo(date('Y-m-d',strtotime($val->tgl_spm)));
            $data[$key]['nama_pemohon'] = $val->nama_pemohon;
            $data[$key]['lokasi'] = $val->lokasi;
            
            $view=url("spm/".$val->id)."/view";
            $edit=url("spm/".$val->id)."/edit";
            $delete=url("spm/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            //     $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            // }
            $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";

            if(\Auth::user()->can('spm-edit'))
            {
                $data[$key]['aksi'] .="<a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i> Edit</a>&nbsp";
            }

            if(\Auth::user()->can('spm-delete'))
            {
                $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }
}
