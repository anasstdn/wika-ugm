<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Spm;
use App\Models\DetailSpm;
use App\Models\Survei;
use App\Models\DetailSurvei;
use App\Models\SpmSurvei;
use App\Models\Supplier;
use App\Models\Material;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SurveiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:survei-list|survei-create|survei-edit|survei-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:survei-create', ['only' => ['create','store']]);
        $this->middleware('permission:survei-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:survei-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $material = \DB::table('material')
                    ->where('parent_status','N')
                    ->where('flag_aktif','Y')
                    ->orderby('kode_material','ASC')
                    ->get();

        $supplier = Supplier::where('flag_aktif','Y')->get();

        return view('transaksi::survei.index',compact('material','supplier'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('transaksi::survei.form');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            if($request->input('check',null) !== null && count($request->input('check',null)) > 0)
            {
                $data = array(
                    'tgl_pembuatan' => date('Y-m-d'),
                    'user_input' => \Auth::user()->id,
                    'total_harga' => intval(0),
                );

                $act = Survei::create($data);
                $survei_id = $act->id;

                foreach($request->input('check',null) as $key => $val)
                {
                    $detail_spm = DetailSpm::find($val);

                    $data_detail = array(
                        'survei_id' => $survei_id,
                        'material_id' => $detail_spm->material_id,
                        'volume' => $detail_spm->volume,
                        'tgl_penggunaan' => $detail_spm->tgl_penggunaan,
                    );

                    $insert_survei = DetailSurvei::create($data_detail);

                    $insert_spm_survei = SpmSurvei::create(['detail_spm_id' => $val, 'detail_survei_id' => $insert_survei->id]);
                }

                message($insert_spm_survei,'Data berhasil dibuat','Data gagal dibuat');
            }
        } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect('/survei/'.$survei_id.'/form-survei');
    }

    public function formSurvei(Request $request, $id)
    {
        $data = Survei::find($id);
        $data_barang = DetailSurvei::where('survei_id',$id)->get();
        $supplier = Supplier::get();

        return view('transaksi::survei.survei',compact('data','data_barang','supplier'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = Survei::find($id);
        $data_barang = DetailSurvei::where('survei_id',$id)->get();

        return view('transaksi::survei.detail',compact('data','data_barang'));
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
        DB::beginTransaction();
        try {

            $survei = Survei::find($id);

            $update = $survei->update(['user_update' => \Auth::user()->id, 'supplier_id' => $request->input('supplier_id',null),'total_harga' => $request->input('grand_total',null)]);

            foreach($request->input('survei_id',null) as $key => $val)
            {
                $data = array(
                    'merek' => $request->input('merek',null)[$key],
                    'harga_per_unit' => str_replace(".","",$request->input('total',null)[$key]),
                    'subtotal' => str_replace(".","",$request->input('subtotal',null)[$key]),
                );

                $update_detail = DetailSurvei::find($val)->update($data);
            }

            message($update_detail, 'Data berhasil disimpan','Data gagal disimpan');

            } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect('/survei');
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

    public function loadTable(Request $request)
    {
         if ($request->ajax()) 
         {
            $data = DetailSpm::select(\DB::raw('detail_spm.id, spm.no_spm, spm.tgl_spm, detail_spm.tgl_penggunaan, material.kode_material, material.material, detail_spm.volume, detail_spm.keterangan, material.satuan'))
                                ->leftjoin('spm','spm.id','=','detail_spm.spm_id')
                                ->leftjoin('material','material.id','=','detail_spm.material_id')
                                ->where('spm.flag_verif_site_manager','=','Y')
                                ->where('spm.flag_verif_pm','=','Y')
                                ->where('spm.flag_verif_komersial','=','Y')
                                ->whereRaw('detail_spm.id NOT IN (SELECT detail_spm_id FROM spm_survei)')
                                ->orderByRaw('spm.tgl_spm DESC, material.kode_material ASC')
                                ->limit(100)
                                ->paginate(25);

            return view('transaksi::survei.form-data',compact('data'))->render();
         }
    }

    public function getData(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        // 
        $material_id = $input['material_id'] == 'null'?null:$input['material_id'];
        $supplier_id = $input['supplier_id'] == 'null'?null:$input['supplier_id'];

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Survei::select(\DB::raw('survei.*'))
                    ->where(function($q) use($input,$material_id,$supplier_id){
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('tgl_pembuatan','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('tgl_pembuatan','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                        // if(!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN')))
                        // {
                        //     $q->where('user_input',\Auth::user()->id);
                        // }
                        if(isset($material_id) && !empty($material_id))
                        {
                            $q->whereRaw('survei.id IN (SELECT survei_id FROM detail_survei WHERE material_id ="'.$material_id.'")');
                        }
                        if(isset($supplier_id) && !empty($supplier_id))
                        {
                            $q->where('survei.supplier_id',$supplier_id);
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->orderby('tgl_pembuatan','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $material = DetailSurvei::where('survei_id',$val->id)->get();

            $data[$key]['no'] = $no;
            $data[$key]['tgl_pembuatan'] = date_indo(date('Y-m-d',strtotime($val->tgl_pembuatan)));
            $data[$key]['supplier'] = $val->supplier->nama_supplier;
            $data[$key]['jumlah_material'] = count($material);
            if($val->flag_batal == 'Y')
            {
                $data[$key]['flag_batal'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-primary">Ya</div></div>';
            }
            else
            {
                $data[$key]['flag_batal'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-info">Tidak</div></div>';   
            }

            if($val->flag_po == 'Y')
            {
                $data[$key]['flag_po'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-success">Ya</div></div>';
            }
            else
            {
                $data[$key]['flag_po'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-danger">Tidak</div></div>';   
            }
            
            $view=url("survei/".$val->id)."/view";
            $edit=url("survei/".$val->id)."/edit";
            $batal=url("survei/".$val->id)."/batal";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            //     $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            // }
            $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-eye' aria-hidden='true'></i> Detail</a>&nbsp";

            if(\Auth::user()->can('survei-edit'))
            {
                if($val->flag_po !== 'Y')
                {
                    $data[$key]['aksi'] .="<a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i> Edit</a>&nbsp";
                }
            }

            if(\Auth::user()->can('survei-delete'))
            {
                // $data[$key]['aksi'].="<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }
}
