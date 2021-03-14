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

        $detail_data = \DB::table('detail_po')
                        ->select(\DB::raw('detail_po.*, material.material, material.spesifikasi, material.satuan'))
                        ->join('material','material.id','=','detail_po.material_id')
                        ->where('detail_po.po_id','=',$id)
                        ->get();

        // $arr['no_po'] = $data->no_po;
        // $arr['tgl_pengajuan_po'] = date('d-m-Y',strtotime($data->tgl_pengajuan_po));
        // $arr['user_input'] = getProfileByUserId($data->user_input)->nama;
        // $arr['total_harga'] = 'Rp. '.number_format($data->total_harga,2,',','.');
        // $arr['kode_supplier'] = $data->kode_supplier;
        // $arr['nama_supplier'] = $data->nama_supplier;
        $body = '<div class="form-row">
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Nomor Purchase Order</label>
                <input type="text" class="form-control" name="no_po" id="no_po" value="'.$data->no_po.'" readonly>
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Tanggal Pengajuan PO</label>
                <input type="text" class="form-control" name="tgl_pengajuan_po" id="tgl_pengajuan_po" value="'.$data->tgl_pengajuan_po.'" readonly>
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">User Pembuat PO</label>
                <input type="text" class="form-control" name="user_input" id="user_input" value="'.getProfileByUserId($data->user_input)->nama.'" readonly>
                </div>
                </div>
                <div class="form-row">
                <div class="form-group col-2">
                <label for="wizard-progress-nama-depan">Kode Supplier</label>
                <input type="text" class="form-control" name="kode_supplier" id="kode_supplier" value="'.$data->kode_supplier.'" readonly>
                </div>
                <div class="form-group col-3">
                <label for="wizard-progress-nama-depan">Nama Supplier</label>
                <input type="text" class="form-control" name="nama_supplier" id="nama_supplier" value="'.$data->nama_supplier.'" readonly>
                </div>
                <div class="form-group col-3">
                <label for="wizard-progress-nama-depan">Total Harga</label>
                <input type="text" class="form-control" name="total_harga" id="total_harga" value="Rp. '.number_format($data->total_harga,2,',','.').'" readonly>
                </div>
                </div>
                <hr/>
                ';

                if(isset($detail_data) && !$detail_data->isEmpty())
                {
                    $body .= '
                    <div class="table-responsive">
                    <table class="table table-sm" width="100%">
                    <thead>
                    <tr>
                    <th>No</th>
                    <th>Jenis Material</th>
                    <th>Spesifikasi</th>
                    <th>Satuan</th>
                    <th>QTY Pesan</th>
                    <th>QTY Sisa</th>
                    <th>QTY Terima</th>
                    <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    ';

                    foreach($detail_data as $key => $val)
                    {
                        $body .= '
                        <tr>
                        <td>'.($key+1).'
                        <input type="hidden" name="detail_po_id[]" id="detail_po_id_'.$key.'" value="'.$val->id.'"
                        </td>
                        <td>'.$val->material.'</td>
                        <td>'.$val->spesifikasi.'</td>
                        <td>'.$val->satuan.'</td>
                        <td>'.$val->volume.'</td>
                        <td></td>
                        <td><input type="number" class="form-control form-control-sm col-6" id="qty_'.$key.'" name="qty[]" value="0" step="0.01" min="0"></td>
                        <td></td>
                        </tr>
                        ';
                    }

                    $body .= '
                    </tbody>
                    </table>
                    </div>
                    ';
                }

                $body .= '
                <br/><hr/>

                <div class="form-row">
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">No BAPB</label>
                <input type="text" class="form-control" name="no_bapb" id="no_bapb" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Tanggal BAPB</label>
                <input type="text" class="form-control" name="tgl_bapb" id="tgl_bapb" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Leveransir</label>
                <input type="text" class="form-control" name="leveransir" id="leveransir" value="">
                </div>
                </div>

                <div class="form-row">
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">No Surat Jalan</label>
                <input type="text" class="form-control" name="no_surat_jalan" id="no_surat_jalan" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Tanggal Surat Jalan</label>
                <input type="text" class="form-control" name="tgl_surat_jalan" id="tgl_surat_jalan" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">No Polisi</label>
                <input type="text" class="form-control" name="no_polisi" id="no_polisi" value="">
                </div>
                </div>

                <div class="form-row">
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Jenis Kendaraan</label>
                <select class="form-control" name="jenis_kendaraan" id="jenis_kendaraan">
                <option value="">- Silahkan Pilih -</option>
                <option value="MOBIL PICK UP">MOBIL PICK UP</option>
                <option value="MOBIL BOX">MOBIL BOX</option>
                <option value="TRUK BAK SEDANG">TRUK BAK SEDANG</option>
                <option value="TRUK BOX SEDANG">TRUK BOX SEDANG</option>
                <option value="TRUK BAK FUSO">TRUK BAK FUSO</option>
                <option value="TRUK BOX FUSO">TRUK BOX FUSO</option>
                </select>
                </div>
                <div class="form-group col-6">
                <label for="wizard-progress-nama-depan">Keterangan (Tidak Wajib Diisi)</label>
                <textarea class="form-control" name="keterangan" id="keterangan" rows="5"></textarea>
                </div>
                </div>
                <hr/>

                <div class="form-row">
                <div class="form-group col-12 text-right">
                <button type="submit" id="simpan" class="btn btn-alt-primary"><i class="fa fa-check mr-5"></i> Proses & Simpan
                </button>
                </div>
                </div>
                <hr/>
                ';

                $body .= '
                <div class="form-row">
                <div class="form-group col-12">
                <h5>Daftar Faktur BAPB</h5>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter table-sm" id="table">
                <thead>
                <tr>
                <th>No</th>
                <th>Nomor SPM</th>
                <th>Tanggal Pengajuan</th>
                <th>Pemohon</th>
                <th>Lokasi</th>
                <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                </tr>
                </tbody>
                </table>
                </div>
                </div>
                </div>
                ';

        return $body;
        exit;
        // return response()->json($arr);
    }

    public function simpanData(Request $request)
    {
        dd($request->all());
    }
}
