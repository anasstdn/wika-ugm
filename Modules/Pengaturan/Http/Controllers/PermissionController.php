<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityTraits;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    
    use ActivityTraits;
    
    function __construct()
    {
        $this->middleware('permission:permissions-list|permissions-create|permissions-edit|permissions-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:permissions-create', ['only' => ['create','store']]);
        $this->middleware('permission:permissions-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permissions-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        return view('pengaturan::permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        return view('pengaturan::permissions.form');
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
        $this->menuAccess(\Auth::user(), get_current_url());
        $data = Permission::find($id);
        return view('pengaturan::permissions.form',compact('data','id'));
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
        $this->menuAccess(\Auth::user(), get_current_url());
        $data = Permission::find($id);
        $this->logDeletedActivity($data, 'Delete data '.$id, url()->current(), base_table('Spatie\Permission\Models\Permission'));

        $data->delete();
        message($data,'Data berhasil dihapus!','Data gagal dihapus!');

        return redirect()->back();
    }

    public function getData(Request $request)
    {
        $this->menuAccess(\Auth::user(), get_current_url());
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

        $dataList = Permission::select('*')
                    ->where(function($q) use($search){
                        if(!empty($search))
                        {
                            $q->where('name','LIKE','%'.$search.'%')
                            ->orWhere('guard_name','LIKE','%'.$search.'%');
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    // ->get();
                    ->paginate($limit,['*'], 'page', $page);

        // $total_all = Permission::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['name'] = $val->name;
            $data[$key]['guard_name'] = $val->guard_name;

            $edit=url("permissions/".$val->id)."/edit";
            $delete=url("permissions/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            if(\Auth::user()->can('permissions-edit'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$edit\")' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";
            }
            if(\Auth::user()->can('permissions-delete'))
            {
                $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>";
            }
            
            $no++;
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function sendData(Request $request)
    {
        $this->menuAccess(\Auth::user(), get_current_url());

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'guard_name' => 'required',
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
                    'name'          =>  $input['name'],
                    'guard_name'    =>  $input['guard_name'],
            // 'kd_koperasi'   =>  $input['koperasi'][0],
                );

                $this->logCreatedActivity(Auth::user(), $data, url()->current(), base_table('Spatie\Permission\Models\Permission'));

                $act = Permission::create($data);
                break;
                case 'edit':
                $act = Permission::find($input['id']);

                $this->logUpdatedActivity(Auth::user(), $act->getAttributes(), $input, url()->current(), base_table('Spatie\Permission\Models\Permission'));

                $act->update($input);
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
