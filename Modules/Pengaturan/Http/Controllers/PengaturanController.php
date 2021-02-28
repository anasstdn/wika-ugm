<?php

namespace Modules\Pengaturan\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use DB;
use App\Models\Pengaturan;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = array();
        $opsi_val_arr = get_key_val();
        foreach ($opsi_val_arr as $key => $value){
            $data[$key] = $value;
        }

        return view('pengaturan::pengaturan.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pengaturan::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
        $this->validateUmum($request->all());
        $array = array();
        DB::beginTransaction();
        try {
            $input = $request->all();
            foreach($input['pengaturan'] as $key => $val)
            {
                $key_val    =   key($val);
                $data       =   array(
                    'opsi_key'    =>  $key_val,
                    'opsi_val'    =>  $val[$key_val],
                );    

                $get = Pengaturan::where('opsi_key','=',$key_val)->first();

                if(isset($get) && !empty($get))
                {
                    $act = $get->update($data);
                }
                else
                {
                    $act = Pengaturan::create($data);
                }
            }

            if($act == true)
            {
                $array = array(
                    'status' => true,
                    'msg' => 'Data berhasil disimpan'
                );
            }

            } catch (Exception $e) {
            echo 'Message' .$e->getMessage();
            DB::rollback();
        }
        DB::commit();

        return response()->json($array);
    }

    private function validateUmum($request)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        for($i=0; $i < count($request['pengaturan']); $i++)
        {
            if(key($request['pengaturan'][$i]) == 'nama_perusahaan')
            {
                if($request['pengaturan'][$i]['nama_perusahaan'] == null)
                {
                    $data['inputerror'][] = 'pengaturan['.$i.'][nama_perusahaan]';
                    $data['error_string'][] = 'Silahkan lengkapi data';
                    $data['status'] = FALSE;
                }
                else
                {
                    if(strlen($request['pengaturan'][$i]['nama_perusahaan']) < 1)
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][nama_perusahaan]';
                        $data['error_string'][] = 'Silahkan isi karakter minimal 1';
                        $data['status'] = FALSE;
                    }

                    if(strlen($request['pengaturan'][$i]['nama_perusahaan']) > 100)
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][nama_perusahaan]';
                        $data['error_string'][] = 'Silahkan isi karakter maksimal 1';
                        $data['status'] = FALSE;
                    }
                }   
            }

            if(key($request['pengaturan'][$i]) == 'alamat')
            {
                if($request['pengaturan'][$i]['alamat'] == null)
                {
                    $data['inputerror'][] = 'pengaturan['.$i.'][alamat]';
                    $data['error_string'][] = 'Silahkan lengkapi data';
                    $data['status'] = FALSE;
                }
                else
                {
                    if(strlen($request['pengaturan'][$i]['alamat']) < 1)
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][alamat]';
                        $data['error_string'][] = 'Silahkan isi karakter minimal 1';
                        $data['status'] = FALSE;
                    }
                }   
            }

            if(key($request['pengaturan'][$i]) == 'telepon')
            {
                if($request['pengaturan'][$i]['telepon'] == null)
                {
                    $data['inputerror'][] = 'pengaturan['.$i.'][telepon]';
                    $data['error_string'][] = 'Silahkan lengkapi data';
                    $data['status'] = FALSE;
                }
                else
                {
                    if(strlen($request['pengaturan'][$i]['telepon']) < 8)
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][telepon]';
                        $data['error_string'][] = 'Silahkan isi karakter minimal 8';
                        $data['status'] = FALSE;
                    }

                    if(strlen($request['pengaturan'][$i]['telepon']) > 14)
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][telepon]';
                        $data['error_string'][] = 'Silahkan isi karakter maksimal 14';
                        $data['status'] = FALSE;
                    }
                }   
            }

            if(key($request['pengaturan'][$i]) == 'email')
            {
                if($request['pengaturan'][$i]['email'] == null)
                {
                    $data['inputerror'][] = 'pengaturan['.$i.'][email]';
                    $data['error_string'][] = 'Silahkan lengkapi data';
                    $data['status'] = FALSE;
                }
                else
                {
                    if(strlen($request['pengaturan'][$i]['email']) < 1)
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][email]';
                        $data['error_string'][] = 'Silahkan isi karakter minimal 1';
                        $data['status'] = FALSE;
                    }

                    if(strlen($request['pengaturan'][$i]['email']) > 100)
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][email]';
                        $data['error_string'][] = 'Silahkan isi karakter maksimal 100';
                        $data['status'] = FALSE;
                    }

                    if(!filter_var($request['pengaturan'][$i]['email'], FILTER_VALIDATE_EMAIL))
                    {
                        $data['inputerror'][] = 'pengaturan['.$i.'][email]';
                        $data['error_string'][] = 'Format harus email, contoh wika@mail.com';
                        $data['status'] = FALSE;
                    }
                }   
            }
        }

        if($data['status'] === FALSE)
        {
          echo json_encode($data);
          exit();
      }
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
        return view('pengaturan::edit');
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
}
