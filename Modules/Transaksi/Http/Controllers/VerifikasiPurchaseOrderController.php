<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Po;
use App\Models\DetailPo;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDF;

class VerifikasiPurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:verifikasi-po-list|verifikasi-po-create|verifikasi-po-edit|verifikasi-po-delete', ['only' => ['index','store','getData']]);
        // $this->middleware('permission:verifikasi-po-create', ['only' => ['create','store']]);
        // $this->middleware('permission:verifikasi-po-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:verifikasi-po-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        if(\Auth::user()->can('verifikasi-pm-po-list'))
        {
            return view('transaksi::verifikasi-po.project_manager.index-pm');
        }

        if(\Auth::user()->can('verifikasi-komersial-po-list'))
        {
            return view('transaksi::verifikasi-po.komersial.index-komersial');
        }

        return view('transaksi::index');
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
        if(\Auth::user()->can('verifikasi-pm-po-list'))
        {
            $data = Po::find($id);
            $data_detail = DetailPo::where('po_id',$data->id)->get();

            return view('transaksi::verifikasi-po.project_manager.detail',compact('data','data_detail'));
        }
        if(\Auth::user()->can('verifikasi-komersial-po-list'))
        {
            $data = Po::find($id);
            $data_detail = DetailPo::where('po_id',$data->id)->get();

            return view('transaksi::verifikasi-po.komersial.detail',compact('data','data_detail'));
        }
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

        DB::beginTransaction();
        try {
            if(\Auth::user()->can('verifikasi-pm-po-edit'))
            {
                $po = Po::find($id);

                if($request->input('verifikasi',null) == 'Y')
                {
                    $po->update([
                        'flag_verif_pm' => $request->input('verifikasi',null), 
                        'tgl_verif_pm' => date('Y-m-d H:i:s'), 
                        'catatan_project_manager' => $request->input('catatan_project_manager',null),
                        'user_verif_pm' => \Auth::user()->id
                    ]);

                    $array = array(
                        'status' => true,
                        'print_button' => true,
                        'msg' => 'Verifikasi Berhasil Dilakukan'
                    );
                }
                else
                {
                    $po->update([
                        'flag_verif_pm' => $request->input('verifikasi',null), 
                        'tgl_verif_pm' => date('Y-m-d H:i:s'),
                        'flag_verif_komersial' => 'N', 
                        'tgl_verif_komersial' => date('Y-m-d H:i:s'),  
                        'catatan_project_manager' => $request->input('catatan_project_manager',null),
                        'user_verif_pm' => \Auth::user()->id,
                        'flag_batal' => 'Y',
                    ]);

                    $array = array(
                        'status' => true,
                        'print_button' => false,
                        'msg' => 'Verifikasi Berhasil Dilakukan'
                    );
                }

            // notifikasi_telegram_spm($request->input('id',null));
            }
            elseif(\Auth::user()->can('verifikasi-komersial-po-edit'))
            {
                $po = Po::find($id);

                if($request->input('verifikasi',null) == 'Y')
                {
                    $po->update([
                        'flag_verif_komersial' => $request->input('verifikasi',null), 
                        'tgl_verif_komersial' => date('Y-m-d H:i:s'), 
                        'catatan_komersial' => $request->input('catatan_komersial',null),
                        'user_verif_komersial' => \Auth::user()->id
                    ]);

                    $array = array(
                        'status' => true,
                        'print_button' => true,
                        'msg' => 'Verifikasi Berhasil Dilakukan'
                    );
                }
                else
                {
                    $po->update([
                        'flag_verif_komersial' => $request->input('verifikasi',null), 
                        'tgl_verif_komersial' => date('Y-m-d H:i:s'),
                        'flag_verif_pm' => 'N', 
                        'tgl_verif_pm' => date('Y-m-d H:i:s'),  
                        'catatan_komersial' => $request->input('catatan_komersial',null),
                        'user_verif_komersial' => \Auth::user()->id,
                        'flag_batal' => 'Y',
                    ]);

                    $array = array(
                        'status' => true,
                        'print_button' => false,
                        'msg' => 'Verifikasi Berhasil Dilakukan'
                    );
                }

            // notifikasi_telegram_spm($request->input('id',null));
            }
            else
            {
                $array = array(
                    'status' => false,
                    'print_button' => false,
                    'msg' => 'Anda tidak memiliki hak akses untuk verifikasi ini'
                );
            }
        } catch (Exception $e) {
          echo 'Message' .$e->getMessage();
          DB::rollback();
      }
      DB::commit();
      return response()->json($array);
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
        $material_id = $input['material_id'] == 'null'?null:$input['material_id'];

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Po::select(\DB::raw('po.*'))
                    ->where(function($q) use($input,$material_id){
                        if(isset($input['nama']) && !empty($input['nama']))
                        {
                            $q->where('no_po','LIKE','%'.$input['nama'].'%');
                        }
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('tgl_pengajuan_po','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('tgl_pengajuan_po','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                        if(isset($material_id) && !empty($material_id))
                        {
                            // $q->where('detail_spm.material_id',$material_id);
                        }

                        if(\Auth::user()->can('verifikasi-pm-po-list'))
                        {
                            $q->whereNull('flag_verif_pm');
                        }
                        elseif(\Auth::user()->can('verifikasi-komersial-po-list'))
                        {
                            $q->where('flag_verif_pm','=','Y');
                            $q->whereNull('flag_verif_komersial');
                        }
                    })
                    ->orderby('tgl_pengajuan_po','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['no_po'] = $val->no_po;
            $data[$key]['tgl_pengajuan_po'] = date_indo(date('Y-m-d',strtotime($val->tgl_pengajuan_po)));
            $data[$key]['supplier_id'] = \App\Models\Supplier::find($val->supplier_id)->nama_supplier;
            $data[$key]['nama_pemohon'] = get_spm_data_from_survei($val->survei_id)->nama_pemohon;
            $data[$key]['lokasi'] = get_spm_data_from_survei($val->survei_id)->lokasi;
            $data[$key]['jumlah_pesanan'] = DetailPo::where('po_id','=',$val->id)->count();
            $verifikasi=url("verifikasi-po/".$val->id)."/verifikasi";

            $data[$key]['aksi'] = '';


            if(\Auth::user()->can('verifikasi-pm-po-edit'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$verifikasi' class='btn btn-primary btn-sm' data-original-title='Verifikasi' title='Verifikasi'><i class='fa fa-check' aria-hidden='true'></i> Verifikasi</a></div></div>";
            }

            if(\Auth::user()->can('verifikasi-komersial-po-edit'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$verifikasi' class='btn btn-primary btn-sm' data-original-title='Verifikasi' title='Verifikasi'><i class='fa fa-check' aria-hidden='true'></i> Verifikasi</a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function getDataDiterima(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10; 
        $material_id = $input['material_id'] == 'null'?null:$input['material_id'];

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Po::select(\DB::raw('po.*'))
                    ->where(function($q) use($input,$material_id){
                        if(isset($input['nama']) && !empty($input['nama']))
                        {
                            $q->where('no_po','LIKE','%'.$input['nama'].'%');
                        }
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('tgl_pengajuan_po','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('tgl_pengajuan_po','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                        if(isset($material_id) && !empty($material_id))
                        {
                            // $q->where('detail_spm.material_id',$material_id);
                        }

                        if(\Auth::user()->can('verifikasi-pm-po-list'))
                        {
                            $q->where('flag_verif_pm','=','Y');
                        }
                        elseif(\Auth::user()->can('verifikasi-komersial-po-list'))
                        {
                            $q->where('flag_verif_pm','=','Y');
                            $q->where('flag_verif_komersial','=','Y');
                        }
                    })
                    ->orderby('tgl_pengajuan_po','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['no_po'] = $val->no_po;
            $data[$key]['tgl_pengajuan_po'] = date_indo(date('Y-m-d',strtotime($val->tgl_pengajuan_po)));
            $data[$key]['supplier_id'] = \App\Models\Supplier::find($val->supplier_id)->nama_supplier;
            $data[$key]['nama_pemohon'] = get_spm_data_from_survei($val->survei_id)->nama_pemohon;
            $data[$key]['lokasi'] = get_spm_data_from_survei($val->survei_id)->lokasi;
            $data[$key]['jumlah_pesanan'] = DetailPo::where('po_id','=',$val->id)->count();
            
            $detail=url("verifikasi-po/".$val->id)."/view";
            $print=url("verifikasi-po/".$val->id)."/test-pdf";

            $data[$key]['aksi'] = '';


            if(\Auth::user()->can('verifikasi-pm-po-list'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$detail' class='btn btn-primary btn-sm' data-original-title='Detail' title='Detail'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }

            if(\Auth::user()->can('verifikasi-komersial-po-list'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$detail' class='btn btn-primary btn-sm' data-original-title='Detail' title='Detail'><i class='fa fa-eye' aria-hidden='true'></i></a>";
            }

            if($val->flag_verif_pm == 'Y' && $val->flag_verif_komersial == 'Y')
            {
                $data[$key]['aksi'] .="<a href='$print' class='btn btn-success btn-sm' data-original-title='Print' target='_blank' title='Print'><i class='fa fa-print' aria-hidden='true'></i></a></div></div>";
            }
            else
            {
                $data[$key]['aksi'] .= "</div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function getDataDitolak(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10; 
        $material_id = $input['material_id'] == 'null'?null:$input['material_id'];

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Po::select(\DB::raw('po.*'))
                    ->where(function($q) use($input,$material_id){
                        if(isset($input['nama']) && !empty($input['nama']))
                        {
                            $q->where('no_po','LIKE','%'.$input['nama'].'%');
                        }
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('tgl_pengajuan_po','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('tgl_pengajuan_po','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                        if(isset($material_id) && !empty($material_id))
                        {
                            // $q->where('detail_spm.material_id',$material_id);
                        }

                        if(\Auth::user()->can('verifikasi-pm-po-list'))
                        {
                            $q->where('flag_verif_pm','=','N');
                        }
                        elseif(\Auth::user()->can('verifikasi-komersial-po-list'))
                        {
                            $q->where('flag_verif_pm','=','N');
                            $q->where('flag_verif_komersial','=','N');
                        }
                    })
                    ->orderby('tgl_pengajuan_po','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['no_po'] = $val->no_po;
            $data[$key]['tgl_pengajuan_po'] = date_indo(date('Y-m-d',strtotime($val->tgl_pengajuan_po)));
            $data[$key]['supplier_id'] = \App\Models\Supplier::find($val->supplier_id)->nama_supplier;
            $data[$key]['nama_pemohon'] = get_spm_data_from_survei($val->survei_id)->nama_pemohon;
            $data[$key]['lokasi'] = get_spm_data_from_survei($val->survei_id)->lokasi;
            $data[$key]['jumlah_pesanan'] = DetailPo::where('po_id','=',$val->id)->count();
            
            $detail=url("verifikasi-po/".$val->id)."/view";

            $data[$key]['aksi'] = '';


            if(\Auth::user()->can('verifikasi-pm-po-list'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$detail' class='btn btn-primary btn-sm' data-original-title='Detail' title='Detail'><i class='fa fa-eye' aria-hidden='true'></i></a></div></div>";
            }

            if(\Auth::user()->can('verifikasi-komersial-po-list'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$detail' class='btn btn-primary btn-sm' data-original-title='Detail' title='Detail'><i class='fa fa-eye' aria-hidden='true'></i></a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function verifikasi(Request $request, $id)
    {
        if(\Auth::user()->can('verifikasi-pm-po-edit'))
        {
            $data = Po::find($id);
            $data_detail = DetailPo::where('po_id',$data->id)->get();

            return view('transaksi::verifikasi-po.project_manager.verif',compact('data','data_detail'));
        }
        if(\Auth::user()->can('verifikasi-komersial-po-edit'))
        {
            $data = Po::find($id);
            $data_detail = DetailPo::where('po_id',$data->id)->get();

            return view('transaksi::verifikasi-po.komersial.verif',compact('data','data_detail'));
        }
    }

    public function test_pdf(Request $request, $id)
    {
        if(\Auth::user()->can('verifikasi-komersial-po-list'))
        {
            $data = Po::find($id);
            $data_detail = DetailPo::where('po_id',$data->id)->get();

            $array['data'] = $data;
            $array['data_detail'] = $data_detail;

            $pdf = PDF::loadView('transaksi::verifikasi-po.pdf', $array);
            
            return $pdf->stream("PO_".$data->no_po."_".date('Y_m_d',strtotime($data->tgl_pengajuan_po)).".pdf");
        }

        if(\Auth::user()->can('verifikasi-pm-po-list'))
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
