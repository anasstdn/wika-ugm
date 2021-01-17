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

?>