<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Survei;
use App\Models\DetailSurvei;
use App\Models\SpmSurvei;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\Po;
use App\Models\DetailPo;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityTraits;
use PDF;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ActivityTraits;

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:po-list|po-create|po-edit|po-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:po-create', ['only' => ['create','store']]);
        $this->middleware('permission:po-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:po-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $material = \DB::table('material')
                    ->where('parent_status','N')
                    ->where('flag_aktif','Y')
                    ->orderby('kode_material','ASC')
                    ->get();

        $supplier = Supplier::where('flag_aktif','Y')->get();

        $this->menuAccess(\Auth::user(), get_current_url());
        return view('transaksi::po.pengadaan.index',compact('material','supplier'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('transaksi::po.pengadaan.form');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $getData = Survei::find($request->input('id',null));

            $data['survei_id'] = $request->input('id',null);
            $data['supplier_id'] = $getData->supplier_id;
            $data['no_po'] = $request->input('no_po',null);
            $data['tgl_pengajuan_po'] = date('Y-m-d',strtotime($request->input('tgl_pengajuan_po',null))).' '.date('H:i:s');
            $data['user_input'] = \Auth::user()->id;
            $data['total_harga'] = $getData->total_harga;

            $this->logCreatedActivity(Auth::user(), $data, url()->current(), base_table('App\Models\Po'));

            $act = Po::create($data);
            $id_po = $act->id;

            foreach($request->input('survei_id',null) as $key => $value)
            {
                $detail_survei = DetailSurvei::find($value);
                $data_detail = array(
                    'po_id' => $id_po,
                    'material_id' => $detail_survei->material_id,
                    'merek' => $detail_survei->merek,
                    'volume' => $detail_survei->volume,
                    'tgl_penggunaan' => $detail_survei->tgl_penggunaan,
                    'keterangan' => $detail_survei->keterangan,
                    'harga_per_unit' => $detail_survei->harga_per_unit,
                    'subtotal' => $detail_survei->subtotal,
                );

                $act_detail = DetailPo::create($data_detail);
            }

            $update = $getData->update(['flag_po' => 'Y']);

            message($update,'Data berhasil disimpan','Data gagal disimpan');
        }
        catch(Exception $e)
        {
            echo 'Message '.$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect('po');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $data = Po::find($id);
        $data_detail = DetailPo::where('po_id',$id)->get();
        $this->menuAccess(\Auth::user(), get_current_url());
        return view('transaksi::po.pengadaan.detail',compact('data','data_detail'));
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
                    ->whereRaw('survei.id NOT IN (SELECT survei_id FROM po)')
                    ->where('flag_po','=','N')
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
            
            $create_po=url("po/".$val->id)."/buat-po";
            $batal=url("po/".$val->id)."/batal";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            //     $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            // }

            if(\Auth::user()->can('po-edit'))
            {
                if($val->flag_po !== 'Y')
                {
                    $data[$key]['aksi'] .="<a href='$create_po' class='btn btn-primary btn-sm' data-original-title='Buat PO' title='Buat PO'><i class='fa fa-edit' aria-hidden='true'></i> Buat PO</a>&nbsp";
                }
            }

            if(\Auth::user()->can('po-delete'))
            {
                $data[$key]['aksi'].="<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Batal PO' title='Batal PO'><i class='fa fa-times' aria-hidden='true'></i> Batalkan PO</a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function getDataLoading(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        // 
        $material_id = $request->input('material_id',null);
        $supplier_id = $request->input('supplier_id',null);

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Po::select(\DB::raw('po.*'))
                    ->where(function($q) use($input,$material_id,$supplier_id){
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('tgl_pengajuan_po','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('tgl_pengajuan_po','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                        // if(!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN')))
                        // {
                        //     $q->where('user_input',\Auth::user()->id);
                        // }
                        if(isset($material_id) && !empty($material_id))
                        {
                            $q->whereRaw('po.id IN (SELECT po_id FROM detail_po WHERE material_id ="'.$material_id.'")');
                        }
                        if(isset($supplier_id) && !empty($supplier_id))
                        {
                            $q->where('po.supplier_id',$supplier_id);
                        }
                        $q->whereNull('flag_verif_komersial');
                        $q->orWhereNull('flag_verif_pm');
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->orderby('tgl_pengajuan_po','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $material = DetailPo::where('po_id',$val->id)->get();

            $data[$key]['no'] = $no;
            $data[$key]['no_po'] = $val->no_po;
            $data[$key]['tgl_pengajuan_po'] = date_indo(date('Y-m-d',strtotime($val->tgl_pengajuan_po)));
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

            if($val->flag_verif_komersial == 'Y')
            {
                $data[$key]['flag_verif_komersial'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-success">Ya</div></div>';
            }
            elseif($val->flag_verif_komersial == 'N')
            {
                $data[$key]['flag_verif_komersial'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-danger">Tidak</div></div>';   
            }
            else
            {
                $data[$key]['flag_verif_komersial'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-secondary">Menunggu Verif</div></div>';   
            }

            if($val->flag_verif_pm == 'Y')
            {
                $data[$key]['flag_verif_pm'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-success">Ya</div></div>';
            }
            elseif($val->flag_verif_pm == 'N')
            {
                $data[$key]['flag_verif_pm'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-danger">Tidak</div></div>';   
            }
            else
            {
                $data[$key]['flag_verif_pm'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-secondary">Menunggu Verif</div></div>';   
            }
            
            $view=url("po/".$val->id)."/view";
            $create_po=url("po/".$val->id)."/buat-po";
            $batal=url("po/".$val->id)."/batal";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";
            // }


            if(\Auth::user()->can('po-delete'))
            {
                if($val->flag_verif_komersial !== null && $val->flag_verif_pm !==null)
                {
                    $data[$key]['aksi'].="</div></div>";
                }
                else
                {
                    $data[$key]['aksi'].="<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Batal PO' title='Batal PO'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
                }
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function getDataVerified(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        // 
        $material_id = $request->input('material_id',null);
        $supplier_id = $request->input('supplier_id',null);

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Po::select(\DB::raw('po.*'))
                    ->where(function($q) use($input,$material_id,$supplier_id){
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('tgl_pengajuan_po','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('tgl_pengajuan_po','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                        // if(!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN')))
                        // {
                        //     $q->where('user_input',\Auth::user()->id);
                        // }
                        if(isset($material_id) && !empty($material_id))
                        {
                            $q->whereRaw('po.id IN (SELECT po_id FROM detail_po WHERE material_id ="'.$material_id.'")');
                        }
                        if(isset($supplier_id) && !empty($supplier_id))
                        {
                            $q->where('po.supplier_id',$supplier_id);
                        }
                        $q->whereNotNull('flag_verif_komersial');
                        $q->whereNotNull('flag_verif_pm');
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->orderby('tgl_pengajuan_po','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $material = DetailPo::where('po_id',$val->id)->get();

            $data[$key]['no'] = $no;
            $data[$key]['no_po'] = $val->no_po;
            $data[$key]['tgl_pengajuan_po'] = date_indo(date('Y-m-d',strtotime($val->tgl_pengajuan_po)));
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

            if($val->flag_verif_komersial == 'Y')
            {
                $data[$key]['flag_verif_komersial'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-success">Ya</div></div>';
            }
            elseif($val->flag_verif_komersial == 'N')
            {
                $data[$key]['flag_verif_komersial'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-danger">Tidak</div></div>';   
            }
            else
            {
                $data[$key]['flag_verif_komersial'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-secondary">Menunggu Verif</div></div>';   
            }

            if($val->flag_verif_pm == 'Y')
            {
                $data[$key]['flag_verif_pm'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-success">Ya</div></div>';
            }
            elseif($val->flag_verif_pm == 'N')
            {
                $data[$key]['flag_verif_pm'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-danger">Tidak</div></div>';   
            }
            else
            {
                $data[$key]['flag_verif_pm'] = '<div class="col-md-12"><div class="text-center"><span class="badge badge-secondary">Menunggu Verif</div></div>';   
            }
            
            $view=url("po/".$val->id)."/view";
            $create_po=url("po/".$val->id)."/buat-po";
            $batal=url("po/".$val->id)."/batal";
            $print=url("po/".$val->id)."/test-pdf";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";
            // }


            if(\Auth::user()->can('po-delete'))
            {
                if($val->flag_verif_komersial !== null && $val->flag_verif_pm !==null)
                {
                    if($val->flag_verif_pm == 'Y' && $val->flag_verif_komersial == 'Y')
                    {
                        $data[$key]['aksi'] .="<a href='$print' class='btn btn-danger btn-sm' data-original-title='Print' target='_blank' title='Print'><i class='fa fa-print' aria-hidden='true'></i> Print PDF</a></div></div>";
                    }
                    else
                    {
                        $data[$key]['aksi'].="</div></div>";
                    }
                }
                else
                {
                    $data[$key]['aksi'].="<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Batal PO' title='Batal PO'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
                }
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function loadTable(Request $request)
    {
        if($request->ajax())
        {

            $data = Survei::select('survei.*')
                    ->where('flag_po','N')
                    ->where('flag_batal','N')
                    ->orderBy('tgl_pembuatan','DESC')
                    ->limit(100)
                    ->paginate(25);

            return view('transaksi::po.pengadaan.form-data',compact('data'))->render();
        }
    }

    public function buatPO(Request $request, $id)
    {
        $data = Survei::find($id);
        $data_barang = DetailSurvei::where('survei_id','=',$id)->get();

        if($data->flag_batal == 'Y')
        {
            message(false,'','Survei barang sudah dibatalkan');
            return redirect()->back();
        }

        if($data->flag_po == 'Y')
        {
            message(false,'','Pengajuan PO sudah dibuat');
            return redirect()->back();
        }

        $this->menuAccess(\Auth::user(), get_current_url());
        return view('transaksi::po.pengadaan.po-form',compact('data','data_barang'));
    }

    public function batal(Request $request, $id)
    {
       DB::beginTransaction();
       try{

        $getPo = Po::find($id);
        $getSurvei = Survei::find($getPo->survei_id);

        $this->logUpdatedActivity(Auth::user(), $getSurvei->getAttributes(), ['flag_batal' => 'Y','user_update' => \Auth::user()->id], url()->current(), base_table('App\Models\Survei'));
        
        $update_survei = $getSurvei->update(['flag_batal' => 'Y','user_update' => \Auth::user()->id]);

        $this->logUpdatedActivity(Auth::user(), $getPo->getAttributes(), [
            'flag_batal' => 'Y',
            'flag_verif_komersial' => 'N',
            'flag_verif_pm' => 'N',
            'tgl_verif_komersial' => date('Y-m-d'),
            'tgl_verif_pm' => date('Y-m-d'),
            'user_update' => \Auth::user()->id,
        ], url()->current(), base_table('App\Models\Po'));

        $update_po = $getPo->update([
            'flag_batal' => 'Y',
            'flag_verif_komersial' => 'N',
            'flag_verif_pm' => 'N',
            'tgl_verif_komersial' => date('Y-m-d'),
            'tgl_verif_pm' => date('Y-m-d'),
            'user_update' => \Auth::user()->id,
        ]);

        message($update_po,'PO berhasil dibatalkan','PO gagal dibatalkan');
    }
    catch(Exception $e)
    {
        echo 'Message '.$e->getMessage();
        DB::rollback();
    }
    DB::commit();
    return redirect()->back();
}

public function test_pdf(Request $request, $id)
{
    if(\Auth::user()->can('po-list'))
    {
        $data = Po::find($id);
        $data_detail = DetailPo::where('po_id',$data->id)->get();

        $array['data'] = $data;
        $array['data_detail'] = $data_detail;

        $pdf = PDF::loadView('transaksi::verifikasi-po.pdf', $array);

        return $pdf->stream("PO_".$data->no_po."_".date('Y_m_d',strtotime($data->tgl_pengajuan_po)).".pdf");
    }

    message(false,'','Anda tidak dapat mengakses halaman ini');
    return redirect()->back();
}

}
