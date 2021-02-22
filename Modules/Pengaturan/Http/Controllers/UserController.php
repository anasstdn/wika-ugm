<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Arr;
use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Response;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Schema;
use Illuminate\Support\Facades\Auth;
use App\Traits\ActivityTraits;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    use ActivityTraits;
    
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store','getData']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        return view('pengaturan::user.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $roles = Role::whereNotIn('id',getConfigValues('ROLE_DEVELOPER'))->pluck('name','name')->all();
        $this->menuAccess(\Auth::user(), get_current_url());
        return view('pengaturan::user.form',compact('roles'));
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
        $data = User::find($id);
        $roles = Role::whereNotIn('id',getConfigValues('ROLE_DEVELOPER'))->pluck('name','name')->all();
        $userRole = $data->roles->pluck('name','name')->all();
        $this->menuAccess(\Auth::user(), get_current_url());
        return view('pengaturan::user.form',compact('roles','data','userRole','id'));
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
        $this->menuAccess(\Auth::user(), get_current_url());
        $data = User::find($id);

        $this->logDeletedActivity($data, 'Delete data '.$id, url()->current(), base_table('App\User'));

        $data->delete();
        message($data,'Data berhasil dihapus!','Data gagal dihapus!');

        return redirect()->back();
    }

    public function getData(Request $request)
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        $config = getConfigValues('ROLE_DEVELOPER');
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        $search = $request->has('search') ? $request->get('search') : null;

        $columns = Schema::getColumnListing('users');

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = User::select(\DB::raw('users.*'))
                    ->with('roles')
                    ->where(function($q) use($search){
                        if(!empty($search))
                        {
                            $q->where('name','LIKE','%'.$search.'%')
                            ->orWhere('username','LIKE','%'.$search.'%')
                            ->orWhere('email','LIKE','%'.$search.'%');
                        }
                    })
                    // ->offset($offset)
                    // ->limit($limit)
                    // ->get();
                    ->paginate($limit,['*'], 'page', $page);

        $total_all = User::get();

        $data = array();

        $no = $offset + 1;

        foreach($dataList as $key => $val)
        {
            $data[$key]['no'] = $no;
            $data[$key]['name'] = $val->name;
            $data[$key]['username'] = $val->username;
            $data[$key]['email'] = $val->email;
            if(isset($val->roles->first()->name))
            {
                if(in_array($val->roles->first()->id,getConfigValues('ROLE_ADMIN')))
                {
                    $data[$key]['roles'] = '<span class="badge badge-primary">'.$val->roles->first()->name.'</span>';
                }
                else
                {
                    $data[$key]['roles'] = '<span class="badge badge-success">'.$val->roles->first()->name.'</span>';
                }
                
            }
            if(isset($val->status_aktif))
            {
                $nonaktif = url("user/".$val->id)."/nonaktifkan";
                $aktif = url("user/".$val->id)."/aktifkan";
                if($val->status_aktif==1)
                {
                    $data[$key]['status_aktif'] = "<div class='col-md-12'><div class='text-center'><a class='btn btn-success btn-sm' href='$nonaktif' style='color:white;font-family:Arial' title='Nonaktifkan User' onclick='clicked(event)'>Aktif</a></div></div>";
                }
                else
                {
                    $data[$key]['status_aktif'] = "<div class='col-md-12'><div class='text-center'><a class='btn btn-danger btn-sm' href='$aktif'  style='color:white;font-family:Arial' title='Aktifkan User' onclick='clicked(event)'>Nonaktif</a></div></div>";
                }
            }

            $edit=url("user/".$val->id)."/edit";
            $delete=url("user/".$val->id)."/delete";
            $reset=url("user/".$val->id)."/reset";
            $data[$key]['aksi'] = '';
            if (!in_array($val->roles->pluck('id')[0], $config)) {
                
                if(\Auth::user()->can('user-edit'))
                {
                    $data[$key]['aksi'] .="<div class='col-md-12'><div class='text-center'><a href='#' onclick='show_modal(\"$edit\")' class='btn btn-primary btn-sm' data-original-title='Edit' title='Edit'><i class='fa fa-edit' aria-hidden='true'></i></a>&nbsp";

                    $data[$key]['aksi'].="<a href='$reset' onclick='clicked(event)' class='btn btn-info btn-sm reset-password' data-original-title='Reset Password' title='Reset Password'><i class='fa fa-key' aria-hidden='true'></i></a>&nbsp";
                }
                $data[$key]['aksi'].="<a href='$delete' onclick='clicked(event)' class='btn btn-danger btn-sm' data-original-title='Hapus' title='Hapus'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>";
            }

            $no++;
            
        }

        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }

    public function sendData(Request $request)
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        $input = $request->all();
        DB::beginTransaction();
        try {
            switch($input['mode'])
            {
                case 'add':
                $validation = Validator::make($request->all(), [
                    'name' => 'required',
                    'username' => 'required|unique:users',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|same:confirm-password',
                    'roles' => 'required',
                ]);

                if($validation->passes() == false)
                {
                    $data = array(
                        'status' => false,
                        'msg' => $validation->errors()->all(),
                    );

                    return \Response::json($data);
                }

                $input['password'] = Hash::make($input['password']);

                $data = array(
                    'name'          =>  $input['name'],
                    'username'      =>  $input['username'],
                    'email'         =>  $input['email'],
                    'password'      =>  $input['password'],
            // 'kd_koperasi'   =>  $input['koperasi'][0],
                );
                // dd($data);
                $this->logCreatedActivity(Auth::user(), $data, url()->current(), base_table('App\User'));

                $act = User::create($data);
                $act->assignRole($request->input('roles'));

                break;
                case 'edit':

                $validation = Validator::make($request->all(), [
                    'name' => 'required',
                    'username' => 'required',
                    'email' => 'required',
                    // 'password' => 'required|same:confirm-password',
                    'roles' => 'required',
                ]);

                if($validation->passes() == false)
                {
                    $data = array(
                        'status' => false,
                        'msg' => $validation->errors()->all(),
                    );

                    return \Response::json($data);
                }

                if(!empty($input['password'])){ 
                  $input['password'] = Hash::make($input['password']);
              }else{
                  $input = Arr::except($input,array('password'));    
              }

              $act = User::find($input['id']);

              $this->logUpdatedActivity(Auth::user(), $act->getAttributes(), Arr::except($input,array('roles')), url()->current(), base_table('App\User'));
              
              $act->update($input);

              DB::table('model_has_roles')->where('model_id',$input['id'])->delete();
              $act->assignRole($request->input('roles'));

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

  public function checkUsername(Request $request)
  {
    $this->menuAccess(\Auth::user(), get_current_url());
    $all_data = $request->all();
        // dd($all_data);
    switch($all_data['mode'])
    {
      case 'add':
      $query="SELECT * FROM users WHERE username LIKE '%".trim($all_data['username'])."%' LIMIT 1"; 
      break;
      case 'edit':
      $query="SELECT * FROM users WHERE username LIKE '%".trim($all_data['username'])."%' AND id <> '".$all_data['id']."' LIMIT 1"; 
      break;
  }
  $cek=DB::select($query);
  if($cek==true) 
  {
    return Response::json(array('msg' => 'true'));
}
return Response::json(array('msg' => 'false'));  
}

public function checkEmail(Request $request)
{
    $this->menuAccess(\Auth::user(), get_current_url());
    $all_data = $request->all();
    switch($all_data['mode'])
    {
      case 'add':
      $query="SELECT * FROM users WHERE email LIKE '%".trim($all_data['email'])."%' LIMIT 1"; 
      break;
      case 'edit':
      $query="SELECT * FROM users WHERE email LIKE '%".trim($all_data['email'])."%' AND id <> '".$all_data['id']."' LIMIT 1"; 
      break;
  }
  $cek=DB::select($query);
  if($cek==true) 
  {
    return Response::json(array('msg' => 'true'));
}
return Response::json(array('msg' => 'false'));  
}

 public function reset(Request $request, $kode)
    {
      $this->menuAccess(\Auth::user(), get_current_url());
      $user=User::find($kode);
      $act=false;
      try {
         $dat=array(
            'password'=>bcrypt('password'),
        );
         $reset=$user->update($dat);
         message($reset,'Data berhasil disimpan!','Data gagal disimpan!');
     } catch (\Exception $e) {
         $dat=array(
            'password'=>bcrypt('password'),
        );
         $reset=$user->update($dat);
         message($reset,'Data berhasil disimpan!','Data gagal disimpan!');
     }

     return redirect()->back();
 }

 public function aktifkan(Request $request, $kode)
    {
        // dd($kode);
        $this->menuAccess(\Auth::user(), get_current_url());
        $user = User::find($kode);
        $data = array(
            'status_aktif' => 1,
        );
        // $this->logUpdatedActivity(Auth::user(), $user->getAttributes(), $data, 'ACL Users', 'users');
        $status = $user->update($data);
        message($status, 'User Berhasil Diaktifkan Kembali', 'User Gagal Diaktifkan Kembali');
        return redirect()->back();
    }

    public function nonaktifkan(Request $request, $kode)
    {
        $this->menuAccess(\Auth::user(), get_current_url());
        $user = User::find($kode);

        $data = array(
            'status_aktif' => 0,
        );
        // $this->logUpdatedActivity(Auth::user(), $user->getAttributes(), $data, 'ACL Users', 'users');
        $status = $user->update($data);
        message($status, 'User Berhasil Dinonaktifkan', 'User Gagal Dinonaktifkan');
        return redirect()->back();
    }
}
