<?php
function message($isSuccess,$successMessage="Data has been saved",$failedMessage="Failed to save data")
{
    if($isSuccess){
        Session::flash('message',$successMessage);
    } else {
        Session::flash('message',$failedMessage);
    }

    Session::flash('messageType',$isSuccess ? 'sukses' : 'gagal');
}

function getConfigValues($configName){
    return \App\Models\ConfigId::getValues($configName);
}

function date_indo($tgl)
{
    $ubah = gmdate($tgl, time()+60*60*8);
    $pecah = explode("-",$ubah);
    $tanggal = $pecah[2];
    $bulan = bulan($pecah[1]);
    $tahun = $pecah[0];
    return $tanggal.' '.$bulan.' '.$tahun;
}

function current_datetime()
{
    return date('Y-m-d H:i:s');
}

function nama_hari($tgl)
{
    $hari=date('D',strtotime($tgl));
    switch($hari){
        case 'Sun':
        $hari_ini = "Minggu";
        break;
        
        case 'Mon':         
        $hari_ini = "Senin";
        break;
        
        case 'Tue':
        $hari_ini = "Selasa";
        break;
        
        case 'Wed':
        $hari_ini = "Rabu";
        break;
        
        case 'Thu':
        $hari_ini = "Kamis";
        break;
        
        case 'Fri':
        $hari_ini = "Jumat";
        break;
        
        case 'Sat':
        $hari_ini = "Sabtu";
        break;
        
        default:
        $hari_ini = "Tidak di ketahui";     
        break;
    }
    return $hari_ini;
}

function bulan($bln)
{
    switch ($bln)
    {
        case 1:
        return "Januari";
        break;
        case 2:
        return "Februari";
        break;
        case 3:
        return "Maret";
        break;
        case 4:
        return "April";
        break;
        case 5:
        return "Mei";
        break;
        case 6:
        return "Juni";
        break;
        case 7:
        return "Juli";
        break;
        case 8:
        return "Agustus";
        break;
        case 9:
        return "September";
        break;
        case 10:
        return "Oktober";
        break;
        case 11:
        return "November";
        break;
        case 12:
        return "Desember";
        break;
    }
}

function dashboard_pelaksana()
{
    $total_all_spm = \DB::table('spm')
                    ->where('user_input',\Auth::user()->id)
                    ->count();

    $total_belum_diverif = \DB::table('spm')
                            ->whereNull('flag_verif_komersial')
                            ->where('user_input',\Auth::user()->id)
                            ->count();

    $total_verif_diterima = \DB::table('spm')
                            ->where('flag_verif_komersial','=','Y')
                            ->where('user_input',\Auth::user()->id)
                            ->count();

    $total_verif_ditolak = \DB::table('spm')
                            ->where('flag_verif_komersial','=','N')
                            ->where('user_input',\Auth::user()->id)
                            ->count();

    $data['total_all_spm'] = $total_all_spm;
    $data['total_belum_diverif'] = $total_belum_diverif;
    $data['total_verif_diterima'] = $total_verif_diterima;
    $data['total_verif_ditolak'] = $total_verif_ditolak;

    return $data;
}

function dashboard_site_manager()
{
    $total_all_spm = \DB::table('spm')->count();

    $total_belum_diverif = \DB::table('spm')
                            ->whereNull('flag_verif_site_manager')
                            ->count();

    $total_verif_diterima = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','Y')
                            // ->where('user_verif_site_manager','=',\Auth::user()->id)
                            ->count();

    $total_verif_ditolak = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','N')
                            // ->where('user_verif_site_manager','=',\Auth::user()->id)
                            ->count();

    $data['total_all_spm'] = $total_all_spm;
    $data['total_belum_diverif'] = $total_belum_diverif;
    $data['total_verif_diterima'] = $total_verif_diterima;
    $data['total_verif_ditolak'] = $total_verif_ditolak;

    return $data;
}

function dashboard_project_manager()
{
    $total_all_spm = \DB::table('spm')->count();

    $total_belum_diverif = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','Y')
                            ->whereNull('flag_verif_pm')
                            ->count();

    $total_verif_diterima = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','Y')
                            ->where('flag_verif_pm','=','Y')
                            ->count();

    $total_verif_ditolak = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','N')
                            ->where('flag_verif_pm','=','N')
                            ->count();

    $data['total_all_spm'] = $total_all_spm;
    $data['total_belum_diverif'] = $total_belum_diverif;
    $data['total_verif_diterima'] = $total_verif_diterima;
    $data['total_verif_ditolak'] = $total_verif_ditolak;

    return $data;
}

function dashboard_komersial()
{
    $total_all_spm = \DB::table('spm')->count();

    $total_belum_diverif = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','Y')
                            ->where('flag_verif_pm','=','Y')
                            ->whereNull('flag_verif_komersial')
                            ->count();

    $total_verif_diterima = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','Y')
                            ->where('flag_verif_pm','=','Y')
                            ->where('flag_verif_komersial','=','Y')
                            ->count();

    $total_verif_ditolak = \DB::table('spm')
                            ->where('flag_verif_site_manager','=','N')
                            ->where('flag_verif_pm','=','N')
                            ->where('flag_verif_komersial','=','N')
                            ->count();

    $data['total_all_spm'] = $total_all_spm;
    $data['total_belum_diverif'] = $total_belum_diverif;
    $data['total_verif_diterima'] = $total_verif_diterima;
    $data['total_verif_ditolak'] = $total_verif_ditolak;

    return $data;
}

function get_jumlah_current_stok($material_id)
{
    $stok = \DB::table('stok')->where('material_id',$material_id)->first();

    return isset($stok) && !empty($stok)?$stok->qty:0;
}

function get_current_url()
{
    return url()->previous().' => '.url()->current();
}

function base_table($model)
{
    $modelTable = str_replace('\\', '', Str::snake(Str::plural(class_basename($model))));

    return $modelTable;
}

function sendTelegramBot($telegram_id, $message_text, $secret_token = '1587425011:AAHbQrOgc9K_bSpy8PpaBHglya8OebkqqL0')
{
    $website="https://api.telegram.org/bot".$secret_token;
    $params=[
        'chat_id' => $telegram_id,
        'text' => $message_text,
        'parse_mode' => 'HTML'
    ];
    $ch = curl_init($website . '/sendMessage');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function get_key_val()
{
    $out    =   array();
    $data   = \DB::table('pengaturan')->select(\DB::raw('opsi_key,opsi_val'))->get();
    if(isset($data) && count($data) > 0)
    {
        foreach ($data as $key => $value) {
            $out[$value->opsi_key]  =   $value->opsi_val;
        }
        return $out;
    }
    else
    {
        return array();
    }
}

?>