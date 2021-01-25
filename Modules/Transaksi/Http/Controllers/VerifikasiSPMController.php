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

class VerifikasiSPMController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:verifikasi-spm-list|verifikasi-spm-create|verifikasi-spm-edit|verifikasi-spm-delete', ['only' => ['index','store','getData']]);
        // $this->middleware('permission:verifikasi-spm-create', ['only' => ['create','store']]);
        // $this->middleware('permission:verifikasi-spm-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:verifikasi-spm-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        if(\Auth::user()->can('verifikasi-site-manager-spm-list'))
        {
            return view('transaksi::verifikasi-spm.index-site-manager');
        }

        if(\Auth::user()->can('verifikasi-pm-spm-list'))
        {
            return view('transaksi::verifikasi-spm.index-pm');
        }

        if(\Auth::user()->can('verifikasi-komersial-spm-list'))
        {
            return view('transaksi::verifikasi-spm.index-komersial');
        }

        return view('transaksi::verifikasi-spm.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('transaksi::create');
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
        return view('transaksi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('transaksi::edit');
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

    public function getData(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        // 
        $material_id = $input['material_id'] == 'null'?null:$input['material_id'];

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Spm::select(\DB::raw('spm.*'))
                    ->join('detail_spm','detail_spm.spm_id','=','spm.id')
                    ->where(function($q) use($input,$material_id){
                        if(isset($input['nama']) && !empty($input['nama']))
                        {
                            $q->where('no_spm','LIKE','%'.$input['nama'].'%')
                            ->orWhere('nama_pemohon','LIKE','%'.$input['nama'].'%')
                            ->orWhere('lokasi','LIKE','%'.$input['nama'].'%');
                        }
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('tgl_spm','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('tgl_spm','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                        if(!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN')))
                        {
                            $q->where('user_input',\Auth::user()->id);
                        }
                        if(isset($material_id) && !empty($material_id))
                        {
                            $q->where('detail_spm.material_id',$material_id);
                        }
                    })
                    ->whereNull('flag_verif_komersial')
                    // ->offset($offset)
                    // ->limit($limit)
                    ->distinct()
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
            $batal=url("spm/".$val->id)."/batal";

            $data[$key]['aksi'] = '';

            $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";

            if(\Auth::user()->can('spm-edit'))
            {
                $data[$key]['aksi'] .="<a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i> Edit</a>&nbsp";
            }

            if(\Auth::user()->can('spm-delete'))
            {
                $data[$key]['aksi'].="<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }
}
