<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Spm;
use App\Models\DetailSpm;
use App\Models\RiwayatSpm;
use App\Models\Material;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PDF;

class VerifikasiSPMController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:verifikasi-spm-list|verifikasi-spm-create|verifikasi-spm-edit|verifikasi-spm-delete', ['only' => ['index', 'store', 'getData']]);
        // $this->middleware('permission:verifikasi-spm-create', ['only' => ['create','store']]);
        // $this->middleware('permission:verifikasi-spm-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:verifikasi-spm-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        if (\Auth::user()->can('verifikasi-site-manager-spm-list')) {
            return view('transaksi::verifikasi-spm.site_manager.index-site-manager');
        }

        if (\Auth::user()->can('verifikasi-pm-spm-list')) {
            return view('transaksi::verifikasi-spm.project_manager.index-pm');
        }

        if (\Auth::user()->can('verifikasi-komersial-spm-list')) {
            return view('transaksi::verifikasi-spm.komersial.index-komersial');
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
        $data = Spm::find($id);
        $data_detail = DetailSpm::where('spm_id', $data->id)->get();
        $riwayat_spm = RiwayatSpm::where('spm_id', '=', $data->id)->orderby('updated_at', 'DESC')->get();

        return view('transaksi::verifikasi-spm.detail', compact('data', 'data_detail', 'riwayat_spm'));
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

        if (\Auth::user()->can('verifikasi-site-manager-spm-edit')) {
            DB::beginTransaction();
            try {
                $spm = Spm::find($id);
                foreach ($request->input('detail_id', null) as $key => $val) {
                    $detail_spm = DetailSpm::find($val);
                    $data_detail_spm = array(
                        'volume' => $request->input('volume', null)[$key],
                        'flag_verif_site_manager' => isset($request->input('verified')[$key]) ? 'Y' : 'N'
                    );

                    $detail_spm->update($data_detail_spm);

                    if (count($detail_spm->getChanges()) > 0) {
                        $list_update = implode(', ', array_map(
                            function ($a, $b, $c) {
                                switch ($a) {
                                    // case 'user_update':
                                    //     return "$a = " . getProfileByUserId($b)->nama . "";
                                    //     break;
                                    case 'flag_verif_site_manager':
                                        if ($b == 'Y') {
                                            return "Verifikasi Site Manager = Diterima";
                                        } else {
                                            return "Verifikasi Site Manager = Ditolak";
                                        }
                                        break;
                                    case 'updated_at':
                                        return "Diubah pada tanggal " . date('d/m/Y H:i:s', strtotime($b));
                                        break;
                                    case 'volume':
                                        return "Volume barang " . Material::find($c->material_id)->material . " diubah menjadi " . $b;
                                    default:
                                        return "$a = $b";
                                }
                            },
                            array_keys($detail_spm->getChanges()),
                            array_values($detail_spm->getChanges()),
                            array($detail_spm),
                        ));

                        $data_riwayat_spm = array(
                            'spm_id' => $id,
                            'action_id' => getConfigValues('ACTION_UPDATE')[0],
                            'user_input' => \Auth::user()->id,
                            'datetime_log' => current_datetime(),
                            'description' => array(
                                'action' => 'User ' . getProfileByUserId(\Auth::user()->id)->nama . ' melakukan perubahan data dengan detail : ' . $list_update
                            ),
                        );

                        $insert_riwayat_spm = RiwayatSpm::create($data_riwayat_spm);
                    }
                }

                if ($request->input('verifikasi', null) == 'Y') {
                    $spm->update([
                        'flag_verif_site_manager' => $request->input('verifikasi', null),
                        'tgl_verif_site_manager' => date('Y-m-d H:i:s'),
                        'catatan_site_manager' => $request->input('catatan_site_manager', null),
                        'user_verif_site_manager' => \Auth::user()->id
                    ]);
                } else {
                    $spm->update([
                        'flag_verif_site_manager' => $request->input('verifikasi', null),
                        'tgl_verif_site_manager' => date('Y-m-d H:i:s'),
                        'flag_verif_komersial' => 'N',
                        'tgl_verif_komersial' => date('Y-m-d H:i:s'),
                        'flag_verif_pm' => 'N',
                        'tgl_verif_pm' => date('Y-m-d H:i:s'),
                        'catatan_site_manager' => $request->input('catatan_site_manager', null),
                        'user_verif_site_manager' => \Auth::user()->id
                    ]);
                }

                if (count($spm->getChanges()) > 0) {
                    $list_update = implode(', ', array_map(
                        function ($a, $b, $c) {
                            switch ($a) {
                                case 'user_verif_site_manager':
                                    return "Diverif oleh " . getProfileByUserId($b)->nama . "";
                                    break;
                                case 'flag_verif_site_manager':
                                    if ($b == 'Y') {
                                        return "Verifikasi Site Manager = Diterima";
                                    } else {
                                        return "Verifikasi Site Manager = Ditolak";
                                    }
                                    break;
                                case 'tgl_verif_site_manager':
                                    return "Diverif pada tanggal " . date('d/m/Y H:i:s', strtotime($b));
                                    break;
                                case 'catatan_site_manager':
                                    return "Catatan Site Manager = " . $b;
                                    break;
                                // case 'updated_at':
                                //     return "Diubah pada tanggal " . date('d/m/Y H:i:s', strtotime($b));
                                //     break;
                                // default:
                                //     return "$a = $b";
                            }
                        },
                        array_keys($spm->getChanges()),
                        array_values($spm->getChanges()),
                        array($spm),
                    ));

                    $data_riwayat_spm = array(
                        'spm_id' => $id,
                        'action_id' => getConfigValues('ACTION_UPDATE')[0],
                        'user_input' => \Auth::user()->id,
                        'datetime_log' => current_datetime(),
                        'description' => array(
                            'action' => 'User ' . getProfileByUserId(\Auth::user()->id)->nama . ' melakukan perubahan data dengan detail : ' . $list_update
                        ),
                    );

                    $insert_riwayat_spm = RiwayatSpm::create($data_riwayat_spm);
                }

                notifikasi_telegram_spm($id);
            } catch (Exception $e) {
                echo 'Message' . $e->getMessage();
                DB::rollback();
            }
            DB::commit();

            message(true, 'Verifikasi berhasil', 'Verifikasi gagal');
            return redirect('verifikasi-spm');
        }

        if (\Auth::user()->can('verifikasi-pm-spm-edit')) {
            $spm = Spm::find($id);

            if ($request->input('verifikasi', null) == 'Y') {
                $spm->update(['flag_verif_pm' => $request->input('verifikasi', null), 'tgl_verif_pm' => date('Y-m-d H:i:s'), 'catatan_project_manager' => $request->input('catatan_project_manager', null), 'user_verif_pm' => \Auth::user()->id]);
            } else {
                $spm->update([
                    'flag_verif_pm' => $request->input('verifikasi', null),
                    'tgl_verif_pm' => date('Y-m-d H:i:s'),
                    'flag_verif_komersial' => 'N',
                    'tgl_verif_komersial' => date('Y-m-d H:i:s'),
                    'flag_verif_site_manager' => 'N',
                    'tgl_verif_site_manager' => date('Y-m-d H:i:s'),
                    'catatan_project_manager' => $request->input('catatan_project_manager', null),
                    'user_verif_pm' => \Auth::user()->id
                ]);
            }

            notifikasi_telegram_spm($id);

            message(true, 'Verifikasi berhasil', 'Verifikasi gagal');
            return redirect('verifikasi-spm');
        }
    }

    public function verifikasiKomersil(Request $request)
    {
        if (\Auth::user()->can('verifikasi-komersial-spm-edit')) {
            $spm = Spm::find($request->input('id', null));

            if ($request->input('verifikasi', null) == 'Y') {
                $spm->update(['flag_verif_komersial' => $request->input('verifikasi', null), 'tgl_verif_komersial' => date('Y-m-d H:i:s'), 'catatan_komersial' => $request->input('catatan_komersial', null), 'user_verif_komersial' => \Auth::user()->id]);

                $array = array(
                    'status' => true,
                    'print_button' => true,
                    'msg' => 'Verifikasi Berhasil Dilakukan'
                );
            } else {
                $spm->update([
                    'flag_verif_komersial' => $request->input('verifikasi', null),
                    'tgl_verif_komersial' => date('Y-m-d H:i:s'),
                    'flag_verif_pm' => 'N',
                    'tgl_verif_pm' => date('Y-m-d H:i:s'),
                    'flag_verif_site_manager' => 'N',
                    'tgl_verif_site_manager' => date('Y-m-d H:i:s'),
                    'catatan_komersial' => $request->input('catatan_project_manager', null),
                    'user_verif_komersial' => \Auth::user()->id
                ]);

                $array = array(
                    'status' => true,
                    'print_button' => false,
                    'msg' => 'Verifikasi Berhasil Dilakukan'
                );
            }

            notifikasi_telegram_spm($request->input('id', null));
        } else {
            $array = array(
                'status' => false,
                'print_button' => false,
                'msg' => 'Anda tidak memiliki hak akses untuk verifikasi ini'
            );
        }
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
                if (isset($material_id) && !empty($material_id)) {
                    $q->where('detail_spm.material_id', $material_id);
                }

                if (\Auth::user()->can('verifikasi-site-manager-spm-list')) {
                    $q->whereNull('spm.flag_verif_site_manager');
                } elseif (\Auth::user()->can('verifikasi-komersial-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'Y');
                    $q->whereNull('spm.flag_verif_pm');
                } elseif (\Auth::user()->can('verifikasi-pm-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'Y');
                    $q->where('spm.flag_verif_pm', '=', 'Y');
                    $q->whereNull('spm.flag_verif_komersial');
                }
            })
            ->distinct()
            ->orderby('tgl_spm', 'DESC')
            ->paginate($limit, ['*'], 'page', $page);

        $data = array();

        $no = $offset + 1;

        foreach ($dataList as $key => $val) {
            $data[$key]['no'] = $no;
            $data[$key]['no_spm'] = $val->no_spm;
            $data[$key]['tgl_spm'] = date_indo(date('Y-m-d', strtotime($val->tgl_spm)));
            $data[$key]['nama_pemohon'] = $val->nama_pemohon;
            $data[$key]['lokasi'] = $val->lokasi;

            $verifikasi = url("verifikasi-spm/" . $val->id) . "/verifikasi";

            $data[$key]['aksi'] = '';

            if (\Auth::user()->can('verifikasi-site-manager-spm-edit')) {
                $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$verifikasi' class='btn btn-primary btn-sm' data-original-title='Verifikasi' title='Verifikasi'><i class='fa fa-check' aria-hidden='true'></i> Verifikasi</a></div></div>";
            }

            if (\Auth::user()->can('verifikasi-pm-spm-edit')) {
                $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$verifikasi' class='btn btn-primary btn-sm' data-original-title='Verifikasi' title='Verifikasi'><i class='fa fa-check' aria-hidden='true'></i> Verifikasi</a></div></div>";
            }

            if (\Auth::user()->can('verifikasi-komersial-spm-edit')) {
                $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$verifikasi' class='btn btn-primary btn-sm' data-original-title='Verifikasi' title='Verifikasi'><i class='fa fa-check' aria-hidden='true'></i> Verifikasi</a></div></div>";
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
                if (isset($material_id) && !empty($material_id)) {
                    $q->where('detail_spm.material_id', $material_id);
                }

                if (\Auth::user()->can('verifikasi-site-manager-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'Y');
                } elseif (\Auth::user()->can('verifikasi-komersial-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'Y');
                    $q->where('spm.flag_verif_pm', '=', 'Y');
                } elseif (\Auth::user()->can('verifikasi-pm-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'Y');
                    $q->where('spm.flag_verif_pm', '=', 'Y');
                    $q->where('spm.flag_verif_komersial', '=', 'Y');
                }
            })
            ->distinct()
            ->orderby('tgl_spm', 'DESC')
            ->paginate($limit, ['*'], 'page', $page);

        $data = array();

        $no = $offset + 1;

        foreach ($dataList as $key => $val) {
            $data[$key]['no'] = $no;
            $data[$key]['no_spm'] = $val->no_spm;
            $data[$key]['tgl_spm'] = date_indo(date('Y-m-d', strtotime($val->tgl_spm)));
            $data[$key]['nama_pemohon'] = $val->nama_pemohon;
            $data[$key]['lokasi'] = $val->lokasi;

            $view = url("verifikasi-spm/" . $val->id) . "/view";
            $batal = url("verifikasi-spm/" . $val->id) . "/batal";
            $print = url("verifikasi-spm/" . $val->id) . "/test-pdf";

            $data[$key]['aksi'] = '';

            $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";

            if (\Auth::user()->can('verifikasi-site-manager-spm-delete')) {
                $data[$key]['aksi'] .= "<a href='#' onclick='show_modal(\"$batal\")' class='btn btn-danger btn-sm' data-original-title='Batal' title='Batal'><i class='fa fa-times' aria-hidden='true'></i> Batal</a></div></div>";
            }

            if (\Auth::user()->can('verifikasi-komersial-spm-list')) {
                if ($val->flag_verif_pm == 'Y' && $val->flag_verif_komersial == 'Y' && $val->flag_verif_site_manager) {
                    $data[$key]['aksi'] .= "<a href='$print' target='_blank' class='btn btn-info btn-sm' data-original-title='Print' title='Print'><i class='fa fa-print' aria-hidden='true'></i> Print PDF</a></div></div>";
                }
            }

            $no++;
        }
        return response()->json(array('data' => $data, 'total' => $dataList->count()));
    }

    public function getDataDitolak(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
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
                if (isset($material_id) && !empty($material_id)) {
                    $q->where('detail_spm.material_id', $material_id);
                }

                if (\Auth::user()->can('verifikasi-site-manager-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'N');
                } elseif (\Auth::user()->can('verifikasi-komersial-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'N');
                    $q->where('spm.flag_verif_pm', '=', 'N');
                } elseif (\Auth::user()->can('verifikasi-pm-spm-list')) {
                    $q->where('spm.flag_verif_site_manager', '=', 'N');
                    $q->where('spm.flag_verif_pm', '=', 'N');
                    $q->where('spm.flag_verif_komersial', '=', 'N');
                }
            })
            ->distinct()
            ->orderby('tgl_spm', 'DESC')
            ->paginate($limit, ['*'], 'page', $page);

        $data = array();

        $no = $offset + 1;

        foreach ($dataList as $key => $val) {
            $data[$key]['no'] = $no;
            $data[$key]['no_spm'] = $val->no_spm;
            $data[$key]['tgl_spm'] = date_indo(date('Y-m-d', strtotime($val->tgl_spm)));
            $data[$key]['nama_pemohon'] = $val->nama_pemohon;
            $data[$key]['lokasi'] = $val->lokasi;

            $view = url("verifikasi-spm/" . $val->id) . "/view";
            $batal = url("verifikasi-spm/" . $val->id) . "/verifikasi";

            $data[$key]['aksi'] = '';

            $data[$key]['aksi'] .= "<div class='col-md-12'><div class='text-center'><a href='$view' class='btn btn-success btn-sm' data-original-title='View' title='View'><i class='fa fa-edit' aria-hidden='true'></i> Detail</a>&nbsp";

            $no++;
        }
        return response()->json(array('data' => $data, 'total' => $dataList->count()));
    }

    public function verifikasi(Request $request, $id)
    {
        if (\Auth::user()->can('verifikasi-site-manager-spm-edit')) {
            $data = Spm::find($id);
            $data_detail = DetailSpm::where('spm_id', $data->id)->get();
            $riwayat_spm = RiwayatSpm::where('spm_id', '=', $data->id)->orderby('updated_at', 'DESC')->get();

            return view('transaksi::verifikasi-spm.site_manager.verif', compact('data', 'data_detail', 'riwayat_spm'));
        }
        if (\Auth::user()->can('verifikasi-pm-spm-edit')) {
            $data = Spm::find($id);
            $data_detail = DetailSpm::where('spm_id', $data->id)->get();
            $riwayat_spm = RiwayatSpm::where('spm_id', '=', $data->id)->orderby('updated_at', 'DESC')->get();

            return view('transaksi::verifikasi-spm.project_manager.verif', compact('data', 'data_detail','riwayat_spm'));
        }
        if (\Auth::user()->can('verifikasi-komersial-spm-edit')) {
            $data = Spm::find($id);
            $data_detail = DetailSpm::where('spm_id', $data->id)->get();
            $riwayat_spm = RiwayatSpm::where('spm_id', '=', $data->id)->orderby('updated_at', 'DESC')->get();

            return view('transaksi::verifikasi-spm.komersial.verif', compact('data', 'data_detail','riwayat_spm'));
        }
    }

    public function test_pdf(Request $request, $id)
    {
        if (\Auth::user()->can('verifikasi-komersial-spm-list')) {
            $data = Spm::find($id);
            $data_detail = DetailSpm::where('spm_id', $data->id)->get();

            $array['data'] = $data;
            $array['data_detail'] = $data_detail;

            $pdf = PDF::loadView('transaksi::verifikasi-spm.site_manager.pdf', $array);

            return $pdf->stream("SPM_" . $data->no_spm . "_" . date('Y_m_d', strtotime($data->tgl_spm)) . ".pdf");
        }

        message(false, '', 'Anda tidak dapat mengakses halaman ini');
        return redirect()->back();
    }

    public function batal(Request $request, $id)
    {
        if (\Auth::user()->can('verifikasi-site-manager-spm-delete')) {
            $data = Spm::find($id);
            $data_detail = DetailSpm::where('spm_id', $id)->get();

            return view('transaksi::verifikasi-spm.site_manager.form', compact('data', 'data_detail'));
        }
    }

    public function sendData(Request $request)
    {
        DB::beginTransaction();
        try {
            if (\Auth::user()->can('verifikasi-site-manager-spm-delete')) {
                $data = array(
                    'flag_verif_site_manager' => 'N',
                    'tgl_verif_site_manager' => date('Y-m-d H:i:s'),
                    'flag_verif_komersial' => 'N',
                    'tgl_verif_komersial' => date('Y-m-d H:i:s'),
                    'flag_verif_pm' => 'N',
                    'tgl_verif_pm' => date('Y-m-d H:i:s'),
                    'catatan_site_manager' => $request->input('alasan_pembatalan', null),
                    'user_verif_site_manager' => \Auth::user()->id,
                    'flag_batal' => 'Y',
                );

                $act = Spm::find($request->input('id', null))->update($data);

                if ($request->input('id', null) !== null) {
                    notifikasi_telegram_spm($request->input('id', null));
                }

                if ($act == true) {
                    $data = array(
                        'status' => true,
                        'msg' => 'Data berhasil disimpan'
                    );
                } else {
                    $data = array(
                        'status' => false,
                        'msg' => 'Data gagal disimpan'
                    );
                }
            }
        } catch (Exception $e) {
            echo 'Message' . $e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return \Response::json($data);
    }
}
