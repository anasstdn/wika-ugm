<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Bapb;
use App\Models\DetailBapb;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDF;

class BeritaAcaraPenerimaanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:bapb-list|bapb-create|bapb-edit|bapb-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:bapb-create', ['only' => ['create','store']]);
        $this->middleware('permission:bapb-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:bapb-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('transaksi::bapb.index');
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

    public function search(Request $request)
    {
        $data = \DB::table('po')
                        ->select(\DB::raw('po.id, CONCAT("Kode PO : ", no_po) as text'))
                        ->where('no_po','LIKE','%'.$request->input('term', '').'%')
                        ->where('flag_batal','=','N')
                        ->where('flag_verif_komersial','=','Y')
                        ->where('flag_verif_pm','=','Y')
                        ->limit(100)
                        ->get();
        
        return ['results' => $data];
    }

    public function loadDataPo(Request $request)
    {
        $id = $request->input('po_id',null);
        $data = \DB::table('po')
                ->join('supplier','supplier.id','=','po.supplier_id')
                ->where('po.id',$id)
                ->first();

        $arr['no_po'] = $data->no_po;
        $arr['tgl_pengajuan_po'] = date('d-m-Y',strtotime($data->tgl_pengajuan_po));
        $arr['user_input'] = getProfileByUserId($data->user_input)->nama;
        $arr['total_harga'] = 'Rp. '.number_format($data->total_harga,2,',','.');
        $arr['kode_supplier'] = $data->kode_supplier;
        $arr['nama_supplier'] = $data->nama_supplier;

        return response()->json($arr);
    }
}
