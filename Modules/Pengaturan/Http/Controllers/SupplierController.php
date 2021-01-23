<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Supplier;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:supplier-list|supplier-create|supplier-edit|supplier-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:supplier-create', ['only' => ['create','store']]);
        $this->middleware('permission:supplier-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('pengaturan::supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pengaturan::supplier.form');
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
        return view('pengaturan::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Supplier::find($id);
        return view('pengaturan::supplier.form',compact('data'));
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
        $data = Supplier::find($id);
        $data->delete();
        message($data,'Data berhasil dihapus!','Data gagal dihapus!');

        return redirect()->back();
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

        $dataList = Supplier::select('*')
                    ->where(function($q) use($search){
                        if(!empty($search))
                        {
                            $q->where('kode_supplier','LIKE','%'.$search.'%')
                            ->orWhere('nama_supplier','LIKE','%'.$search.'%');
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->paginate($limit,['*'], 'page', $page);

        $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['kode_supplier'] = $val->kode_supplier;
            $data[$key]['nama_supplier'] = $val->nama_supplier;
            $data[$key]['telepon'] = $val->telepon;
            $data[$key]['mobile'] = $val->mobile;
            $data[$key]['diskon_supplier'] = $val->diskon_supplier;
            if($val->flag_aktif == 'Y')
            {
                $data[$key]['flag_aktif'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-primary'>Aktif</span></div></div>";
            }
            else
            {
                $data[$key]['flag_aktif'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-danger'>Nonaktif</span></div></div>";
            }
            

            $edit=url("supplier/".$val->id)."/edit";
            $delete=url("supplier/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            if(\Auth::user()->can('supplier-edit'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$edit\")' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";
            }
            if(\Auth::user()->can('supplier-delete'))
            {
                $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function sendData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'kode_supplier' => 'required',
            'nama_supplier' => 'required',
            'flag_aktif' => 'required',
        ]);

        if($validation->passes() == false)
        {
            $data = array(
                'status' => false,
                'msg' => $validation->errors()->all(),
            );

            return \Response::json($data);
        }

        $input = $request->all();

        DB::beginTransaction();
        try {
            switch($input['mode'])
            {
                case 'add':
                $data = array(
                    'kode_supplier' => $input['kode_supplier'],
                    'nama_supplier' => $input['nama_supplier'],
                    'telepon' => $input['telepon'],
                    'mobile' => $input['mobile'],
                    'diskon_supplier' => $input['diskon_supplier'],
                    'flag_aktif' => $input['flag_aktif'],
                );

                $act = Supplier::create($data);
                break;
                case 'edit':
                $act = Supplier::find($input['id']);

                $data = array(
                    'kode_supplier' => $input['kode_supplier'],
                    'nama_supplier' => $input['nama_supplier'],
                    'telepon' => $input['telepon'],
                    'mobile' => $input['mobile'],
                    'diskon_supplier' => $input['diskon_supplier'],
                    'flag_aktif' => $input['flag_aktif'],
                );

                $act->update($data);
                break;
            }

            if($act == true)
            {
                $data = array(
                    'status' => true,
                    'msg' => 'Data berhasil disimpan'
                );
            }
            else
            {
                $data = array(
                    'status' => false,
                    'msg' => 'Data gagal disimpan'
                );
            }
        } catch (Exception $e) {
          echo 'Message' .$e->getMessage();
          DB::rollback();
      }
      DB::commit();

      return \Response::json($data);
  }
}
