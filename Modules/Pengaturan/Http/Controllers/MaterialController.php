<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Material;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:material-list|material-create|material-edit|material-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:material-create', ['only' => ['create','store']]);
        $this->middleware('permission:material-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:material-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('pengaturan::material.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pengaturan::material.form');
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
        return view('pengaturan::material.form');
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

        $dataList = Material::select('*')
                    ->where(function($q) use($search){
                        if(!empty($search))
                        {
                            $q->where('kode_material','LIKE','%'.$search.'%')
                            ->orWhere('material','LIKE','%'.$search.'%')
                            ->orWhere('satuan','LIKE','%'.$search.'%');
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->orderby('kode_material','ASC')
                    ->paginate($limit);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['material'] ="&nbsp";
            for($i = 0; $i < $val->level ; $i++)
            {
                $data[$key]['material'] .= "&emsp;&emsp;";
            }
            $data[$key]['material'] .= $val->kode_material." ".$val->material;
            $data[$key]['spesifikasi'] = $val->spesifikasi;
            $data[$key]['satuan'] = $val->satuan;
            $data[$key]['level'] = $val->level;
            if($val->flag_aktif == 'Y')
            {
                $data[$key]['flag_aktif'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-primary'>Aktif</span></div></div>";
            }
            else
            {
                $data[$key]['flag_aktif'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-danger'>Nonaktif</span></div></div>";
            }
            
            $add=url("material/".$val->id)."/add-child";
            $edit=url("material/".$val->id)."/edit";
            $delete=url("material/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            if(\Auth::user()->can('material-create'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$add\")' class='btn btn-primary btn-sm' data-original-title='Tambah' title='Tambah'><i class='fa fa-plus' aria-hidden='true'></i></a>&nbsp";
            }

            if(\Auth::user()->can('material-edit'))
            {
                $data[$key]['aksi'] .="<a href='#' onclick='show_modal(\"$edit\")' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";
            }
            if(\Auth::user()->can('material-delete'))
            {
                $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    // public function materialSearch(Request $request)
    // {
    //     $data = \DB::table('material')
    //             ->select(\DB::raw('material.id, CONCAT(kode_material,", ",)'));
    // }

    public function sendData(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'kode_material' => 'required',
            'material' => 'required',
            'flag_aktif' => 'required',
            'level' => 'required',
            'parent_status' => 'required',
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
                    'kode_material' => $input['kode_material'],
                    'material' => $input['material'],
                    'spesifikasi' => $input['spesifikasi'],
                    'satuan' => $input['satuan'],
                    'flag_aktif' => $input['flag_aktif'],
                    'level' => $input['level'],
                    'parent_status' => $input['parent_status'],
                    'parent_id' => $input['parent_id'] !== "null" ?$input['parent_id']:null ,
                );

                $act = Material::create($data);
                break;
                case 'edit':
                $act = Material::find($input['id']);

                $data = array(
                    'kode_material' => $input['kode_material'],
                    'material' => $input['material'],
                    'spesifikasi' => $input['spesifikasi'],
                    'satuan' => $input['satuan'],
                    'flag_aktif' => $input['flag_aktif'],
                    'level' => $input['level'],
                    'parent_status' => $input['parent_status'],
                    'parent_id' => $input['parent_id'] !== "null" ?$input['parent_id']:null ,
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

  public function addChild($id)
  {
    $parent_id = $id;
    $data = Material::find($id);

    return view('pengaturan::material.form-child',compact('data','parent_id'));
  }
}
