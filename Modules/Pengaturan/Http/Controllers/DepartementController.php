<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Departement;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:departement-list|departement-create|departement-edit|departement-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:departement-create', ['only' => ['create','store']]);
        $this->middleware('permission:departement-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:departement-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('pengaturan::departement.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pengaturan::departement.form');
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
        $data = Departement::find($id);
        return view('pengaturan::departement.form',compact('data','id'));
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
        $data = Departement::find($id);
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

        $dataList = Departement::select('*')
                    ->where(function($q) use($search){
                        if(!empty($search))
                        {
                            $q->where('departement','LIKE','%'.$search.'%');
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    ->paginate($limit,['*'], 'page', $page);

        // $total_all = Departement::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['departement'] = $val->departement;
            if($val->flag_aktif == 'Y')
            {
                $data[$key]['flag_aktif'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-primary'>Aktif</span></div></div>";
            }
            else
            {
                $data[$key]['flag_aktif'] = "<div class='col-md-12'><div class='text-center'><span class='badge badge-danger'>Nonaktif</span></div></div>";
            }
            

            $edit=url("departement/".$val->id)."/edit";
            $delete=url("departement/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            if(\Auth::user()->can('departement-edit'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$edit\")' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";
            }
            if(\Auth::user()->can('departement-delete'))
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
            'departement' => 'required',
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
                    'departement' => $input['departement'],
                    'flag_aktif' => $input['flag_aktif'],
                );

                $act = Departement::create($data);
                break;
                case 'edit':
                $act = Departement::find($input['id']);

                $data = array(
                    'departement' => $input['departement'],
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
