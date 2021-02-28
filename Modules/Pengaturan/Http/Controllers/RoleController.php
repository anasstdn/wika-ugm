<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ActivityTraits;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ActivityTraits;

    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        return view('pengaturan::roles.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        $permission = Permission::get();
        return view('pengaturan::roles.form',compact('permission'));
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
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();
        return view('pengaturan::roles.form',compact('role','permission','rolePermissions','id'));
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
        $this->menuAccess(\Auth::user(), get_current_url());
        Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required',
        ]);
        
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        
        $role->syncPermissions($request->input('permission'));
        
        message($role,'Data berhasil disimpan!','Data gagal disimpan!');
        return redirect()->back();
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
        $data = DB::table("roles")->where('id',$id)->delete();
        
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

        $dataList = Role::select('*')
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

        $total_all = Role::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['name'] = $val->name;
            $data[$key]['guard_name'] = $val->guard_name;

            $edit=url("roles/".$val->id)."/edit";
            $delete=url("roles/".$val->id)."/delete";

            $data[$key]['aksi'] = '';

            if(\Auth::user()->can('role-edit'))
            {
                $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='$edit' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";
            }
            if(\Auth::user()->can('role-delete'))
            {
                $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>";
            }

            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }
}
