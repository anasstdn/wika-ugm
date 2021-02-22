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

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('transaksi::po.pengadaan.index');
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
            return redirect('/po');
        }

        if($data->flag_po == 'Y')
        {
            message(false,'','Pengajuan PO sudah dibuat');
            return redirect('/po');
        }

        return view('transaksi::po.pengadaan.po-form',compact('data','data_barang'));
    }
}
