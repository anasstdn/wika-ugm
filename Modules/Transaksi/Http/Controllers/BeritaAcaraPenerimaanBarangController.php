<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\DetailPo;
use App\Models\Bapb;
use App\Models\DetailBapb;
use App\Models\PoBapb;
use App\Models\Stok;
use App\Models\RiwayatStok;
use App\Models\RiwayatPenerimaanBarang;
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
        $body = '
                <input type="hidden" name="po_id" id="po_id" value="'.$id.'">
                <div class="form-row">
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
                        $riwayat_penerimaan_barang = RiwayatPenerimaanBarang::select(\DB::raw('SUM(riwayat_stok.penambahan) as total'))->join('riwayat_stok','riwayat_stok.id','=','riwayat_penerimaan_barang.riwayat_stok_id')->where('detail_po_id','=',$val->id)->first();

                        $sisa = $val->volume - $riwayat_penerimaan_barang->total;

                        if($sisa > 0)
                        {
                            $text = '<span class="badge badge-danger">Order Belum Selesai Dikirim</span>';
                        }
                        else
                        {
                            $text = '<span class="badge badge-success">Order Selesai Dikirim</span>';
                        }

                        $body .= '
                        <tr>
                        <td>'.($key+1).'
                        <input type="hidden" name="detail_po_id[]" id="detail_po_id_'.$key.'" value="'.$val->id.'"
                        </td>
                        <td>'.$val->material.'</td>
                        <td>'.$val->spesifikasi.'</td>
                        <td>'.$val->satuan.'</td>
                        <td>'.$val->volume.'</td>
                        <td>'.$sisa.'</td>
                        <td><input type="number" class="form-control form-control-sm col-6" id="qty_'.$key.'" name="qty[]" value="0" step="0.01" min="0"></td>
                        <td>'.$text.'</td>
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
                <input type="text" class="form-control confirm" name="no_bapb" id="no_bapb" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Tanggal BAPB</label>
                <input type="text" class="form-control confirm datepicker" name="tgl_bapb" id="tgl_bapb" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Leveransir (Tidak Wajib Diisi)</label>
                <input type="text" class="form-control confirm" name="leveransir" id="leveransir" value="">
                </div>
                </div>

                <div class="form-row">
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">No Surat Jalan</label>
                <input type="text" class="form-control confirm" name="no_surat_jalan" id="no_surat_jalan" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Tanggal Surat Jalan</label>
                <input type="text" class="form-control confirm datepicker" name="tgl_surat_jalan" id="tgl_surat_jalan" value="">
                </div>
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">No Polisi</label>
                <input type="text" class="form-control confirm" name="no_polisi" id="no_polisi" value="">
                </div>
                </div>

                <div class="form-row">
                <div class="form-group col-4">
                <label for="wizard-progress-nama-depan">Jenis Kendaraan</label>
                <select class="form-control confirm" name="jenis_kendaraan" id="jenis_kendaraan">
                <option value="">- Silahkan Pilih -</option>
                <option value="MOTOR PICK UP">MOTOR PICK UP</option>
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
                <textarea class="form-control confirm" name="keterangan" id="keterangan" rows="5"></textarea>
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

                $bapb = Bapb::select('bapb.*')
                        ->join('po_bapb','bapb.id','=','po_bapb.bapb_id')
                        ->where('po_bapb.po_id','=',$id)
                        ->get();

                $body .= '
                <div class="form-row">
                <div class="form-group col-12">
                <h5>Daftar Faktur BAPB</h5>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter table-sm" id="table">
                <thead>
                <tr>
                <th>No</th>
                <th>Nomor BAPB</th>
                <th>Tanggal BAPB</th>
                <th>Leveransir</th>
                <th>No Polisi</th>
                <th>Jenis Kendaraan</th>
                <th>User</th>
                <th>Cetak Faktur</th>
                </tr>
                </thead>
                <tbody>';

                if(isset($bapb) && !$bapb->isEmpty())
                {
                    foreach($bapb as $key => $val)
                    {
                        $body .=
                        '<tr>
                        <td>'.($key+1).'</td>
                        <td>'.$val->no_bapb.'</td>
                        <td>'.$val->tgl_bapb.'</td>
                        <td>'.$val->leveransir.'</td>
                        <td>'.$val->no_polisi.'</td>
                        <td>'.$val->jenis_kendaraan.'</td>
                        <td>'.getProfileByUserId($val->user_input)->nama.'</td>
                        <td><a href="'.url('bapb/cetak/'.$val->id).'" class="btn btn-alt-info" target="_blank"><i class="fa fa-print mr-5"></i> Cetak</a></td>
                        </tr>';
                    }
                }
                
                $body .= '
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

    public function cetakFaktur(Request $request, $id)
    {
        $data = Bapb::find($id);

        $data_detail = DetailBapb::where('bapb_id','=',$id)->get();

        $array['data'] = $data;
        $array['data_detail'] = $data_detail;

        $pdf = PDF::loadView('transaksi::bapb.pdf', $array);

        return $pdf->stream("BAPB_".$data->no_bapb."_".date('Y_m_d',strtotime($data->tgl_bapb)).".pdf");
    }

    public function simpanData(Request $request)
    {
        Validator::make($request->all(), [
            'no_bapb' => 'required',
            'tgl_bapb' => 'required',
            'no_surat_jalan' => 'required',
            'tgl_surat_jalan' => 'required',
            'no_polisi' => 'required',
            'jenis_kendaraan' => 'required',
        ]);

        $allZeroes = count( $request->input('qty') ) == count( array_keys( $request->input('qty'), '0', true ) );

        if($allZeroes == true)
        {
            $data = array(
                'status' => false,
                'msg' => 'Mohon isi salah satu QTY Terima minimal 1'
            );
        }
        else
        {
            DB::beginTransaction();
            try
            {
                $data_bapb = array(
                    'no_bapb' => $request->input('no_bapb',null),
                    'tgl_bapb' => date('Y-m-d',strtotime($request->input('tgl_bapb',null))).' '.date('H:i:s'),
                    'leveransir' => $request->input('leveransir',null),
                    'no_surat_jalan' => $request->input('no_surat_jalan',null),
                    'tgl_surat_jalan' => date('Y-m-d',strtotime($request->input('tgl_surat_jalan',null))).' '.date('H:i:s'),
                    'no_polisi' => $request->input('no_polisi',null),
                    'jenis_kendaraan' => $request->input('jenis_kendaraan',null),
                    'user_input' => \Auth::user()->id,
                    'keterangan' => $request->input('keterangan',null),
                );

                $insert_bapb = Bapb::create($data_bapb);
                $id_bapb = $insert_bapb->id;

                $insert_po_bapb = PoBapb::create(['po_id' => $request->input('po_id',null), 'bapb_id' => $id_bapb]);

                foreach($request->input('detail_po_id',null) as $key => $val)
                {
                    $data_detail_po = DetailPo::find($val);

                    if($request->input('qty',null)[$key] > 0)
                    {
                        $data_detail_bapb = array(
                            'bapb_id' => $id_bapb,
                            'material_id' => $data_detail_po->material_id,
                            'merek' => $data_detail_po->merek,
                            'volume' => $request->input('qty',null)[$key],
                        );

                        $insert_detail_bapb = DetailBapb::create($data_detail_bapb);
                        $id_detail_bapb = $insert_detail_bapb->id;

                        $stok = Stok::where('material_id','=',$data_detail_po->material_id)->first();
                        $get_qty_stok = $stok->qty;
                        $total_qty_stok = $get_qty_stok + $request->input('qty',null)[$key];
                        $stok->update(['qty' => $total_qty_stok]);

                        $data_riwayat_stok = array(
                            'material_id' => $data_detail_po->material_id,
                            'tanggal_riwayat' => date('Y-m-d H:i:s'),
                            'qty' => $total_qty_stok,
                            'penambahan' => $request->input('qty',null)[$key],
                            'pengurangan' => 0,
                            'user_input' => \Auth::user()->id,
                            'keterangan' => 'BAPB No '.$request->input('no_bapb',null).' Material '.\App\Models\Material::find($data_detail_po->material_id)->material.'tanggal '.date('Y-m-d H:i:s')
                        );

                        $insert_riwayat_stok = RiwayatStok::create($data_riwayat_stok);
                        $id_riwayat_stok = $insert_riwayat_stok->id;


                        $data_riwayat_penerimaan_barang = RiwayatPenerimaanBarang::create([
                            'riwayat_stok_id' => $id_riwayat_stok,
                            'detail_bapb_id' => $id_detail_bapb,
                            'detail_po_id' => $val
                        ]);
                    }
                }

                $data = array(
                    'status' => true,
                    'msg' => 'Data berhasil disimpan',
                    'po_id' => $request->input('po_id',null)
                );
            }
            catch(Exception $e)
            {
                echo 'Message '.$e->getMessage();
                DB::rollback();
            }
            DB::commit();
        }

        return response()->json($data);
    }
}
