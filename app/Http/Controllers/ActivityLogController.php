<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests;
use App\Traits\ActivityTraits;

class ActivityLogController extends Controller
{
    //
    use ActivityTraits;

    function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:agama-list|agama-create|agama-edit|agama-delete', ['only' => ['index','store','getData']]);
        // $this->middleware('permission:agama-create', ['only' => ['create','store']]);
        // $this->middleware('permission:agama-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:agama-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
    	$this->menuAccess(\Auth::user(), get_current_url());
        return view('activity_log.index');
    }

    public function getData(Request $request)
    {
        $input = $request->all();

        $offset = $request->has('offset') ? $request->get('offset') : 0;
        $limit = $request->has('limit') ? $request->get('limit') : 10;
        // $search = $request->has('search') ? $request->get('search') : null;as
        // 
        // $material_id = $input['material_id'] == 'null'?null:$input['material_id'];

        if($offset == 0)
        {
          $page = 1;
        }
        else
        {
          $page = ($offset / $limit) + 1;
        }

        $dataList = Activity::select(\DB::raw('activity_log.*'))
                    ->where(function($q) use($input){
                        if(isset($input['date_start']) && !empty($input['date_start']))
                        {
                            $q->whereDate('created_at','>=',date('Y-m-d',strtotime($input['date_start'])));
                        }
                        if(isset($input['date_end']) && !empty($input['date_end']))
                        {
                            $q->whereDate('created_at','<=',date('Y-m-d',strtotime($input['date_end'])));
                        }
                    })
                    ->orderby('created_at','DESC')
                    ->paginate($limit,['*'], 'page', $page);

        // $dataList = Spm::select(\DB::raw('spm.*'))
        //             ->join('detail_spm','detail_spm.spm_id','=','spm.id')
        //             ->where(function($q) use($input,$material_id){
        //                 if(isset($input['nama']) && !empty($input['nama']))
        //                 {
        //                     $q->where('no_spm','LIKE','%'.$input['nama'].'%')
        //                     ->orWhere('nama_pemohon','LIKE','%'.$input['nama'].'%')
        //                     ->orWhere('lokasi','LIKE','%'.$input['nama'].'%');
        //                 }
        //                 if(isset($input['date_start']) && !empty($input['date_start']))
        //                 {
        //                     $q->whereDate('tgl_spm','>=',date('Y-m-d',strtotime($input['date_start'])));
        //                 }
        //                 if(isset($input['date_end']) && !empty($input['date_end']))
        //                 {
        //                     $q->whereDate('tgl_spm','<=',date('Y-m-d',strtotime($input['date_end'])));
        //                 }
        //                 if(!in_array(\Auth::user()->roles->pluck('id')[0], getConfigValues('ROLE_ADMIN')))
        //                 {
        //                     $q->where('user_input',\Auth::user()->id);
        //                 }
        //                 if(isset($material_id) && !empty($material_id))
        //                 {
        //                     $q->where('detail_spm.material_id',$material_id);
        //                 }
        //             })
        //             ->whereNull('flag_verif_komersial')
        //             // ->offset($offset)
        //             // ->limit($limit)
        //             ->distinct()
        //             ->orderby('tgl_spm','DESC')
        //             ->paginate($limit,['*'], 'page', $page);

        // $total_all = Supplier::get();

        $data = array();

        $no = $offset + 1;
        
        foreach($dataList as $key => $val)
        {
            $jsonData = json_decode($val->properties, true);
            $data[$key]['no'] = $no;
            $data[$key]['created_at'] = date('Y-m-d H:i:s',strtotime($val->created_at));
            $data[$key]['causer_id'] = \App\Models\User::find($val->causer_id)->name;
            $data[$key]['description'] = '<table border="0" style="font-size: 9pt">';
            $data[$key]['description'] .= '<tfoot>';
            $data[$key]['description'] .= '<tr>';
            $data[$key]['description'] .= '<td width="20%"><b>Access Type</b></td><td width="40%">'.$jsonData['attributes']['type'].'</td>';
            $data[$key]['description'] .= '</tr>';
            // if(isset($jsonData['attributes']['description']))
            // {
            //     $data[$key]['description'] .= '<tr>';
            //     $data[$key]['description'] .= '<td width="40%"><b>Description</b></td><td>'.$jsonData['attributes']['description'].'</td>';
            //     $data[$key]['description'] .= '</tr>';
            // }
            if(isset($jsonData['attributes']['menu']))
            {
                $data[$key]['description'] .= '<tr>';
                $data[$key]['description'] .= '<td width="20%"><b>URL</b></td><td width="40%">'.$jsonData['attributes']['menu'].'</td>';
                $data[$key]['description'] .= '</tr>';
            }
            $data[$key]['description'] .= '</tfoot>';
            $data[$key]['description'] .= '</table>';
            // $data[$key]['lokasi'] = $val->lokasi;
            
            $no++;
            
        }
        return response()->json(array('data' => $data,'total' => $dataList->total()));
    }
}
