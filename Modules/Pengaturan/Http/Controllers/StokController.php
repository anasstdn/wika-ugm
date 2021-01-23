<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Material;
use App\Models\Stok;
use App\Models\RiwayatStok;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:stok-list|stok-create|stok-edit|stok-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:stok-create', ['only' => ['create','store']]);
        $this->middleware('permission:stok-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:stok-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('pengaturan::stok.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data_material = \DB::table('material')
                        ->where('parent_status','=','N')
                        ->where('flag_aktif','Y')
                        ->orderby('kode_material','ASC')
                        ->get();

        $data['data_material'] = $data_material;

        return view('pengaturan::stok.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        
      $input = $request->all();

        DB::beginTransaction();
        try {

          foreach($input['material_id'] as $key => $val)
          {
            $data_stok = array(
              'material_id' => $val,
              'qty' => $input['qty'][$key],
            );

            $get_stok = Stok::where('material_id','=',$val)->first();

            if(isset($get_stok) && !empty($get_stok))
            {
              $insert_stok = $get_stok->update($data_stok);
            }
            else
            {
              $insert_stok = Stok::create($data_stok);
            }

            $riwayat_stok = array(
              'material_id' => $val,
              'tanggal_riwayat' => date('Y-m-d H:i:s'),
              'qty' => $input['qty'][$key],
              'penambahan' => 0,
              'pengurangan' => 0,
              'user_input' => \Auth::user()->id,
              'keterangan' => 'Inisialisasi Stok Gudang Material '.Material::find($val)->material.'',
            );

            $insert_riwayat_stok = RiwayatStok::create($riwayat_stok);

            message($insert_riwayat_stok,'Data Berhasil Diupdate!','Data Gagal Diupdate!');
          }

           } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pengaturan::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
      $data_material = \DB::table('material')
                        ->where('parent_status','=','N')
                        ->where('flag_aktif','Y')
                        ->where('id',$id)
                        ->orderby('kode_material','ASC')
                        ->get();

        $data['data_material'] = $data_material;

        return view('pengaturan::stok.form', $data);
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
        $search = $request->has('search') ? $request->get('search') : null;

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Material::select('*')
                    ->where(function($q) use($search){
                        if(!empty($search))
                        {
                            $q->where('kode_material','LIKE','%'.$search.'%')
                            ->orWhere('material','LIKE','%'.$search.'%')
                            ->orWhere('satuan','LIKE','%'.$search.'%');
                        }
                    })
                    ->where('parent_status','N')
                    ->where('flag_aktif','Y')
                    ->orderby('kode_material','ASC')
                    ->paginate($limit,['*'], 'page', $page);

        $total_all = Material::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['kode_material'] = $val->kode_material;
            $data[$key]['material'] = $val->material;
            $data[$key]['satuan'] = $val->satuan;

            $get_stok = \DB::table('stok')->where('material_id',$val->id)->first();
            if(isset($get_stok) && !empty($get_stok))
            {
                $data[$key]['qty'] = $get_stok->qty;
            }
            else
            {
                $data[$key]['qty'] = 0;
            }            

            $edit=url("stok/".$val->id)."/edit";
            // $delete=url("stok/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            if(\Auth::user()->can('supplier-edit'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";
            }
            // if(\Auth::user()->can('supplier-delete'))
            // {
            //     $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>";
            // }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }
}
