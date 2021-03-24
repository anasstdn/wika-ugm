<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Spm;
use App\Models\DetailSpm;
use App\Models\RiwayatSpm;
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
        $this->middleware('permission:spm-list|spm-create|spm-edit|spm-delete', ['only' => ['index', 'store', 'getData']]);
        $this->middleware('permission:spm-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:spm-edit', ['only' => ['edit', 'update']]);
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
        $material = \DB::table('material')
            ->where('parent_status', 'N')
            ->where('flag_aktif', 'Y')
            ->orderby('kode_material', 'ASC')
            ->get();

        $no_spm = get_number_surat_pengajuan('spm');

        return view('transaksi::spm.form', compact('material', 'no_spm'));
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
                'tgl_spm' => date('Y-m-d', strtotime($input['tgl_spm'])),
                'nama_pemohon' => $input['nama_pemohon'],
                'lokasi' => $input['lokasi'],
                'keterangan' => $input['keterangan_spm'],
                'user_input' => \Auth::user()->id
            );

            $insert_spm = Spm::create($data_spm);
            $id_spm = $insert_spm->id;

            foreach ($input['material_id'] as $key => $val) {
                $data_detail_spm = array(
                    'spm_id' => $id_spm,
                    'material_id' => $val,
                    'volume' => $input['volume'][$key],
                    'tgl_penggunaan' => $input['tgl_penggunaan'][$key],
                    'keterangan' => $input['keterangan'][$key]
                );

                $insert_detail_spm = DetailSpm::create($data_detail_spm);
            }

            $data_riwayat_spm = array(
                'spm_id' => $id_spm,
                'action_id' => getConfigValues('ACTION_CREATE')[0],
                'user_input' => \Auth::user()->id,
                'datetime_log' => current_datetime(),
                'description' => array(
                    'action' => 'User ' . getProfileByUserId(\Auth::user()->id)->nama . ' telah membuat SPM dengan nomor ' . $input['no_spm']
                ),
            );

            $insert_riwayat_spm = RiwayatSpm::create($data_riwayat_spm);

            message($insert_detail_spm, 'Data berhasil disimpan!', 'Data gagal disimpan!');
        } catch (Exception $e) {
            echo 'Message' . $e->getMessage();
            DB::rollback();
        }
        DB::commit();

        if ($insert_detail_spm == true) {
            notifikasi_telegram_spm($id_spm);
        }

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
        $data_detail = DetailSpm::where('spm_id', $data->id)->get();
        $riwayat_spm = RiwayatSpm::where('spm_id', '=', $data->id)->orderby('updated_at', 'DESC')->get();

        return view('transaksi::spm.detail', compact('data', 'data_detail', 'riwayat_spm'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Spm::find($id);
        $data_detail = DetailSpm::where('spm_id', $data->id)->get();

        $material = \DB::table('material')
            ->where('parent_status', 'N')
            ->where('flag_aktif', 'Y')
            ->orderby('kode_material', 'ASC')
            ->get();

        return view('transaksi::spm.form', compact('data', 'data_detail', 'material'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
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
            $spm = Spm::where('id', '=', $id)->first();

            $data_spm = array(
                'no_spm' => $input['no_spm'],
                'tgl_spm' => date('Y-m-d', strtotime($input['tgl_spm'])),
                'nama_pemohon' => $input['nama_pemohon'],
                'lokasi' => $input['lokasi'],
                'keterangan' => $input['keterangan_spm'],
                'user_update' => \Auth::user()->id
            );
            $insert_spm = $spm->update($data_spm);
            if (count($spm->getChanges()) > 0) {
                $list_update = implode(', ', array_map(
                    function ($a, $b) {
                        if ($a == 'user_update') {
                            return "$a = " . getProfileByUserId($b)->nama . "";
                        } else {
                            return "$a = $b";
                        }
                    },
                    array_keys($spm->getChanges()),
                    array_values($spm->getChanges())
                ));

                $data_riwayat_spm = array(
                    'spm_id' => $id,
                    'action_id' => getConfigValues('ACTION_UPDATE')[0],
                    'user_input' => \Auth::user()->id,
                    'datetime_log' => current_datetime(),
                    'description' => array(
                        'action' => 'User ' . getProfileByUserId(\Auth::user()->id)->nama . ' melakukan edit data ' . $list_update
                    ),
                );

                $insert_riwayat_spm = RiwayatSpm::create($data_riwayat_spm);
            }
            $id_spm = $id;

            foreach ($input['material_id'] as $key => $val) {
                $data_detail_spm = array(
                    'spm_id' => $id_spm,
                    'material_id' => $val,
                    'volume' => $input['volume'][$key],
                    'tgl_penggunaan' => $input['tgl_penggunaan'][$key],
                    'keterangan' => $input['keterangan'][$key]
                );

                if (isset($input['id_detail_spm'][$key])) {
                    $insert_detail_spm = DetailSpm::find($input['id_detail_spm'][$key])->update($data_detail_spm);
                    $change_detail_spm = DetailSpm::where('id', '=', $insert_detail_spm)->first()->getChanges();
                } else {
                    $insert_detail_spm = DetailSpm::create($data_detail_spm);
                    $id_detail_spm = $insert_detail_spm->id;
                }



                if (isset($change_detail_spm) && count($change_detail_spm) > 0) {
                    dd('aaaa');
                }
            }

            message($insert_detail_spm, 'Data berhasil disimpan!', 'Data gagal disimpan!');
        } catch (Exception $e) {
            echo 'Message' . $e->getMessage();
            DB::rollback();
        }
        DB::commit();

        if ($insert_detail_spm == true) {
            notifikasi_telegram_spm($id_spm);
        }

        return redirect('/spm');
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

    public function batal($id)
    {
        $spm = \DB::table('spm')->where('id', $id);
        $detail_spm = DetailSpm::where('spm_id','=',$id)->get();

        foreach($detail_spm as $key => $val){
            $val->flag_verif_site_manager = 'N';
            $val->flag_verif_komersial = 'N';
            $val->flag_verif_pm = 'N';
            $val->keterangan = 'Pengajuan dibatalkan oleh ' . getProfileByUserId(\Auth::user()->id)->nama;
            $val->save();
        }

        $spm->update([
            'flag_verif_site_manager' => 'N',
            'tgl_verif_site_manager' => date('Y-m-d'),
            'flag_verif_pm' => 'N',
            'tgl_verif_pm' => date('Y-m-d'),
            'flag_verif_komersial' => 'N',
            'tgl_verif_komersial' => date('Y-m-d'),
            'flag_batal' => 'Y',
            'keterangan' => 'Pengajuan dibatalkan oleh ' . getProfileByUserId(\Auth::user()->id)->nama
        ]);

        $data_riwayat_spm = array(
            'spm_id' => $id,
            'action_id' => getConfigValues('ACTION_UPDATE')[0],
            'user_input' => \Auth::user()->id,
            'datetime_log' => current_datetime(),
            'description' => array(
                'action' => 'User ' . getProfileByUserId(\Auth::user()->id)->nama . ' melakukan pembatalan SPM nomor ' . $spm->first()->no_spm
            ),
        );

        $insert_riwayat_spm = RiwayatSpm::create($data_riwayat_spm);

        if ($id !== null) {
            notifikasi_telegram_spm($id);
        }

        message(true, 'Pengajuan Berhasil Dibatalkan', '');

        return redirect()->back();
    }

    public function searchMaterial(Request $request)
    {
        $data = \DB::table('material')
            ->select(\DB::raw('material.id, CONCAT(kode_material," - ",material) as text'))
            ->where('material', 'LIKE', '%' . $request->input('term', '') . '%')
            ->where('flag_aktif', 'Y')
            ->where('parent_status', 'N')
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
        $material_id = $input['material_id'] == 'null' ? null : $input['material_id'];

        if ($offset == 0) {
            $page = 1;
        } else {
            $page = ($offset / $limit) + 1;
        }

        $dataList = Spm::select(\DB::raw('spm.*'))
            ->join('detail_spm', 'detail_spm.spm_id', '=', 'spm.id')
            ->where(function ($q) use ($input, $material_id) {
                if (isset($input['nama']) && !empty($input['nama'])) {
                    $q->where('no_spm', 'LIKE', '%' . $input['nama'] . '%')
                        ->orWhere('nama_pemohon', 'LIKE', '%' . $input['nama'] . '%')
                        ->orWhere('lokasi', 'LIKE', '%' . $input['nama'] . '%');
                }
                if (isset($input['date_start']) && !empty($input['date_start'])) {
                    $q->whereDate('tgl_spm', '>=', date('Y-m-d', strtotime($input['date_start'])));
                }
                if (isset($input['date_end']) && !empty($input['date_end'])) {
                    $q->whereDate('tgl_spm', '<=', date('Y-m-d', strtotime($input['date_end'])));
                }
                if (!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN'))) {
                    $q->where('user_input', \Auth::user()->id);
                }
                if (isset($material_id) && !empty($material_id)) {
                    $q->where('detail_spm.material_id', $material_id);
                }
            })
            ->whereNull('spm.flag_verif_komersial')
            // ->offset($offset)
            // ->limit($limit)
            ->distinct()
            ->orderby('tgl_spm', 'DESC')
            ->paginate($limit, ['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;

        foreach ($dataList as $key => $val) {
            $data[$key]['no'] = $no;
            $data[$key]['no_spm'] = $val->no_spm;
            $data[$key]['tgl_spm'] = date_indo(date('Y-m-d', strtotime($val->tgl_spm)));
            $data[$key]['nama_pemohon'] = $val->nama_pemohon;
            $data[$key]['lokasi'] = $val->lokasi;
            $total_barang = DetailSpm::where('spm_id','=',$val->id)->count();
            $data[$key]['total_barang'] = '<div class="row"><div class="col-4"><label>Total</label></div><div class="col-1">:</div><div class="col-2">'.$total_barang.'</div></div>';

            $view = url("spm/" . $val->id) . "/view";
            $edit = url("spm/" . $val->id) . "/edit";
            $batal = url("spm/" . $val->id) . "/batal";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            //     $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            // }
            $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";

            if (\Auth::user()->can('spm-edit')) {
                $data[$key]['aksi'] .= "<a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i> Edit</a>&nbsp";
            }

            if (\Auth::user()->can('spm-delete')) {
                $data[$key]['aksi'] .= "<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
            }

            $no++;
        }
        return response()->json(array('data' => $data, 'total' => $dataList->count()));
    }

    public function getDataDiterima(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        // 
        $material_id = $input['material_id'] == 'null' ? null : $input['material_id'];

        if ($offset == 0) {
            $page = 1;
        } else {
            $page = ($offset / $limit) + 1;
        }

        $dataList = Spm::select(\DB::raw('spm.*'))
            ->join('detail_spm', 'detail_spm.spm_id', '=', 'spm.id')
            ->where(function ($q) use ($input, $material_id) {
                if (isset($input['nama']) && !empty($input['nama'])) {
                    $q->where('no_spm', 'LIKE', '%' . $input['nama'] . '%')
                        ->orWhere('nama_pemohon', 'LIKE', '%' . $input['nama'] . '%')
                        ->orWhere('lokasi', 'LIKE', '%' . $input['nama'] . '%');
                }
                if (isset($input['date_start']) && !empty($input['date_start'])) {
                    $q->whereDate('tgl_spm', '>=', date('Y-m-d', strtotime($input['date_start'])));
                }
                if (isset($input['date_end']) && !empty($input['date_end'])) {
                    $q->whereDate('tgl_spm', '<=', date('Y-m-d', strtotime($input['date_end'])));
                }
                if (!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN'))) {
                    $q->where('user_input', \Auth::user()->id);
                }
                if (isset($material_id) && !empty($material_id)) {
                    $q->where('detail_spm.material_id', $material_id);
                }
            })
            ->where('spm.flag_verif_komersial', '=', 'Y')
            // ->offset($offset)
            // ->limit($limit)
            ->distinct()
            ->orderby('tgl_spm', 'DESC')
            ->paginate($limit, ['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;

        foreach ($dataList as $key => $val) {
            $data[$key]['no'] = $no;
            $data[$key]['no_spm'] = $val->no_spm;
            $data[$key]['tgl_spm'] = date_indo(date('Y-m-d', strtotime($val->tgl_spm)));
            $data[$key]['nama_pemohon'] = $val->nama_pemohon;
            $data[$key]['lokasi'] = $val->lokasi;

            $view = url("spm/" . $val->id) . "/view";
            $edit = url("spm/" . $val->id) . "/edit";
            $batal = url("spm/" . $val->id) . "/batal";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            //     $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            // }
            $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";


            // if(\Auth::user()->can('spm-delete'))
            // {
            //     $data[$key]['aksi'].="<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
            // }

            $no++;
        }
        return response()->json(array('data' => $data, 'total' => $dataList->count()));
    }

    public function getDataDitolak(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;
        // 
        $material_id = $input['material_id'] == 'null' ? null : $input['material_id'];

        if ($offset == 0) {
            $page = 1;
        } else {
            $page = ($offset / $limit) + 1;
        }

        $dataList = Spm::select(\DB::raw('spm.*'))
            ->join('detail_spm', 'detail_spm.spm_id', '=', 'spm.id')
            ->where(function ($q) use ($input, $material_id) {
                if (isset($input['nama']) && !empty($input['nama'])) {
                    $q->where('no_spm', 'LIKE', '%' . $input['nama'] . '%')
                        ->orWhere('nama_pemohon', 'LIKE', '%' . $input['nama'] . '%')
                        ->orWhere('lokasi', 'LIKE', '%' . $input['nama'] . '%');
                }
                if (isset($input['date_start']) && !empty($input['date_start'])) {
                    $q->whereDate('tgl_spm', '>=', date('Y-m-d', strtotime($input['date_start'])));
                }
                if (isset($input['date_end']) && !empty($input['date_end'])) {
                    $q->whereDate('tgl_spm', '<=', date('Y-m-d', strtotime($input['date_end'])));
                }
                if (!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN'))) {
                    $q->where('user_input', \Auth::user()->id);
                }
                if (isset($material_id) && !empty($material_id)) {
                    $q->where('detail_spm.material_id', $material_id);
                }
            })
            ->where('spm.flag_verif_komersial', '=', 'N')
            // ->offset($offset)
            // ->limit($limit)
            ->distinct()
            ->orderby('tgl_spm', 'DESC')
            ->paginate($limit, ['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;

        foreach ($dataList as $key => $val) {
            $data[$key]['no'] = $no;
            $data[$key]['no_spm'] = $val->no_spm;
            $data[$key]['tgl_spm'] = date_indo(date('Y-m-d', strtotime($val->tgl_spm)));
            $data[$key]['nama_pemohon'] = $val->nama_pemohon;
            $data[$key]['lokasi'] = $val->lokasi;

            $view = url("spm/" . $val->id) . "/view";
            $edit = url("spm/" . $val->id) . "/edit";
            $batal = url("spm/" . $val->id) . "/batal";

            $data[$key]['aksi'] = '';

            // if(\Auth::user()->can('pegawai-create'))
            // {
            //     $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            // }
            $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";

            // if(\Auth::user()->can('spm-edit'))
            // {
            //     $data[$key]['aksi'] .="<a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i> Edit</a>&nbsp";
            // }

            // if(\Auth::user()->can('spm-delete'))
            // {
            //     $data[$key]['aksi'].="<a href='$batal' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
            // }

            $no++;
        }
        return response()->json(array('data' => $data, 'total' => $dataList->count()));
    }

    public function loadDataMaterial(Request $request)
    {
        $id = $request->input('material_id', null);
        $data = \DB::table('material')->where('id', $id)->first();

        $arr['satuan'] = $data->satuan;
        $arr['spesifikasi'] = $data->spesifikasi;

        return response()->json($arr);
    }

    public function deleteDetailSpm(Request $request)
    {
        $input = $request->all();

        DB::beginTransaction();
        try {

            $delete = \DB::table('detail_spm')->where('id', $input['id'])->delete();

            if ($delete) {
                $arr['status'] = true;
                $arr['msg'] = 'Data Berhasil Dihapus';
            } else {
                $arr['status'] = false;
                $arr['msg'] = 'Data Gagal Dihapus';
            }
        } catch (Exception $e) {
            echo 'Message' . $e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return response()->json($arr);
    }
}
