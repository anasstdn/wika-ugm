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

    $total_all_po = \DB::table('po')->count();

    $total_po_belum_verif = \DB::table('po')
                            ->where('flag_batal','=','N')
                            ->whereNull('flag_verif_komersial')
                            ->count();

    $total_po_verif_diterima = \DB::table('po')
                            ->where('flag_batal','=','N')
                            ->where('flag_verif_komersial','=','Y')
                            ->count();

    $total_po_verif_ditolak = \DB::table('po')
                            ->where('flag_batal','=','Y')
                            ->where('flag_verif_komersial','=','N')
                            ->where('flag_verif_pm','=','N')
                            ->count();

    $data['total_all_po'] = $total_all_po;
    $data['total_po_belum_verif'] = $total_po_belum_verif;
    $data['total_po_verif_diterima'] = $total_po_verif_diterima;
    $data['total_po_verif_ditolak'] = $total_po_verif_ditolak;

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

function sendTelegramBot($telegram_id, $message_text)
{
    $secret_token = isset(get_key_val()['telegram_api'])?get_key_val()['telegram_api']:'';
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

function getTelegramId()
{
    $user_profil = \App\Models\UserProfil::join('profil','profil.id','=','user_profil.profil_id')
                    ->where('user_id',\Auth::user()->id)->first(['profil.telegram_id']);

    if(!empty($user_profil))
    {
        return $user_profil->telegram_id;
    }
    else
    {
        return null;
    }
}

function getTelegramByRoles($config_id)
{
    $user_profil = \App\User::join('user_profil','users.id','=','user_profil.user_id')
                    ->join('profil','profil.id','=','user_profil.profil_id') 
                    ->whereHas('roles', function($q) use($config_id){
                        $q->whereIn('id',$config_id);
                    })->get(['profil.*']);

    if(isset($user_profil) && !$user_profil->isEmpty())
    {
        return $user_profil;
    }
    else
    {
        return null;
    }
}

function getProfileByUserId($id)
{
     $user_profil = \App\Models\UserProfil::select('profil.*')
                    ->join('profil','profil.id','=','user_profil.profil_id')
                    ->where('user_id',$id)->first();

    if(!empty($user_profil))
    {
        return $user_profil;
    }
    else
    {
        return null;
    }
}

function notifikasi_telegram_spm($id_spm)
{
	$spm = \App\Models\Spm::find($id_spm);

	if($spm->flag_batal == 'N')
	{
		if($spm->flag_verif_site_manager == null && $spm->flag_verif_komersial == null && $spm->flag_verif_pm == null)
		{
        // == SEND TELEGRAM NOTIFICATION TO ALL SITE MANAGER== //
			$profil = getTelegramByRoles(getConfigValues('ROLE_SITE_MANAGER'));
			if(isset($profil) && count($profil) > 0)
			{
				foreach($profil as $key => $val)
				{
					if($val->telegram_id !== null)
					{
						$text_site_manager = "Permintaan Verifikasi SPM. Halo <b>" . $val->nama . "</b> anda menerima pemberitahuan permintaan Verifikasi SPM dengan detail sebagai berikut :.\n\n"
						. "No SPM : ".$spm->no_spm."\n"
						. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
						. "Nama Pemohon : ".$spm->nama_pemohon."\n"
						. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
						. "Keterangan : ".$spm->keterangan."\n\n"
						. "Silahkan anda untuk menindak lanjuti permintaan ini dengan mengakses menu <b>SPM -> Verifikasi SPM</b>.\n\n"
						. "Terima kasih. Salam,\n"
						. "WikaBot.";

						sendTelegramBot($val->telegram_id,$text_site_manager);
					}
				}
			}
       	 // == END == //

        	// == SEND TELEGRAM NOTIFICATION TO USER PEMBUAT==//
			$text_pelaksana = "Pesan Notifikasi Pengajuan SPM. Halo <b>" . getProfileByUserId($spm->user_input)->nama . "</b> anda telah mengajukan SPM dengan detail sebagai berikut :.\n\n"
			. "No SPM : ".$spm->no_spm."\n"
			. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
			. "Nama Pemohon : ".$spm->nama_pemohon."\n"
			. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
			. "Keterangan : ".$spm->keterangan."\n\n"
			. "Silahkan anda untuk menunggu verifikasi selanjutnya.\n\n"
			. "Terima kasih. Salam,\n"
			. "WikaBot.";

			if(!empty(getProfileByUserId($spm->user_input)->telegram_id))
			{
                sendTelegramBot(getProfileByUserId($spm->user_input)->telegram_id,$text_pelaksana);
			}
        // == END ==//
		}
		elseif($spm->flag_verif_site_manager !== null && $spm->flag_verif_komersial == null && $spm->flag_verif_pm == null)
		{
			// == SEND TELEGRAM NOTIFICATION TO ALL PROJECT MANAGER== //
			$profil = getTelegramByRoles(getConfigValues('ROLE_PROJECT_MANAGER'));
			if(isset($profil) && count($profil) > 0)
			{
				foreach($profil as $key => $val)
				{
					if($val->telegram_id !== null)
					{
						$text_site_manager = "Permintaan Verifikasi SPM. Halo <b>" . $val->nama . "</b> anda menerima pemberitahuan permintaan Verifikasi SPM dengan detail sebagai berikut :.\n\n"
						. "No SPM : ".$spm->no_spm."\n"
						. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
						. "Nama Pemohon : ".$spm->nama_pemohon."\n"
						. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
						. "Keterangan : ".$spm->keterangan."\n\n"
						. "Silahkan anda untuk menindak lanjuti permintaan ini dengan mengakses menu <b>SPM -> Verifikasi SPM</b>.\n\n"
						. "Terima kasih. Salam,\n"
						. "WikaBot.";

						sendTelegramBot($val->telegram_id,$text_site_manager);
					}
				}
			}
       	 // == END == //

			if($spm->flag_verif_site_manager == 'Y')
			{
				$verif_sm = 'Diterima'; 
			}
			elseif($spm->flag_verif_site_manager == 'N')
			{
				$verif_sm = 'Ditolak'; 
			}
			else
			{
				$verif_sm = 'Menunggu Verifikasi'; 
			}

       	 // == SEND TELEGRAM NOTIFICATION TO USER PEMBUAT==//
			$text_pelaksana = "Pesan Notifikasi Pengajuan SPM. Halo <b>" . getProfileByUserId($spm->user_input)->nama . "</b> berikut adalah informasi SPM dengan detail sebagai berikut :.\n\n"
			. "No SPM : ".$spm->no_spm."\n"
			. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
			. "Nama Pemohon : ".$spm->nama_pemohon."\n"
			. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
			. "Keterangan : ".$spm->keterangan."\n"
			. "Verifikasi Site Manager : ".$verif_sm."\n\n"
			. "Silahkan anda untuk menunggu verifikasi selanjutnya.\n\n"
			. "Terima kasih. Salam,\n"
			. "WikaBot.";

			if(!empty(getProfileByUserId($spm->user_input)->telegram_id))
			{
				sendTelegramBot(getProfileByUserId($spm->user_input)->telegram_id,$text_pelaksana);
			}
        // == END ==//
		}
		elseif($spm->flag_verif_site_manager !== null && $spm->flag_verif_komersial == null && $spm->flag_verif_pm !== null)
		{
			// == SEND TELEGRAM NOTIFICATION TO ALL PROJECT MANAGER== //
			$profil = getTelegramByRoles(getConfigValues('ROLE_KOMERSIAL'));
			if(isset($profil) && count($profil) > 0)
			{
				foreach($profil as $key => $val)
				{
					if($val->telegram_id !== null)
					{
						$text_site_manager = "Permintaan Verifikasi SPM. Halo <b>" . $val->nama . "</b> anda menerima pemberitahuan permintaan Verifikasi SPM dengan detail sebagai berikut :.\n\n"
						. "No SPM : ".$spm->no_spm."\n"
						. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
						. "Nama Pemohon : ".$spm->nama_pemohon."\n"
						. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
						. "Keterangan : ".$spm->keterangan."\n\n"
						. "Silahkan anda untuk menindak lanjuti permintaan ini dengan mengakses menu <b>SPM -> Verifikasi SPM</b>.\n\n"
						. "Terima kasih. Salam,\n"
						. "WikaBot.";

						sendTelegramBot($val->telegram_id,$text_site_manager);
					}
				}
			}
       	 // == END == //

			if($spm->flag_verif_site_manager == 'Y')
			{
				$verif_sm = 'Diterima'; 
			}
			elseif($spm->flag_verif_site_manager == 'N')
			{
				$verif_sm = 'Ditolak'; 
			}
			else
			{
				$verif_sm = 'Menunggu Verifikasi'; 
			}

			if($spm->flag_verif_pm == 'Y')
			{
				$verif_pm = 'Diterima'; 
			}
			elseif($spm->flag_verif_pm == 'N')
			{
				$verif_pm = 'Ditolak'; 
			}
			else
			{
				$verif_pm = 'Menunggu Verifikasi'; 
			}

       	 // == SEND TELEGRAM NOTIFICATION TO USER PEMBUAT==//
			$text_pelaksana = "Pesan Notifikasi Pengajuan SPM. Halo <b>" . getProfileByUserId($spm->user_input)->nama . "</b> berikut adalah informasi SPM dengan detail sebagai berikut :.\n\n"
			. "No SPM : ".$spm->no_spm."\n"
			. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
			. "Nama Pemohon : ".$spm->nama_pemohon."\n"
			. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
			. "Keterangan : ".$spm->keterangan."\n"
			. "Verifikasi Site Manager : ".$verif_sm."\n"
			. "Verifikasi Project Manager : ".$verif_pm."\n\n"
			. "Silahkan anda untuk menunggu verifikasi selanjutnya.\n\n"
			. "Terima kasih. Salam,\n"
			. "WikaBot.";

			if(!empty(getProfileByUserId($spm->user_input)->telegram_id))
			{
				sendTelegramBot(getProfileByUserId($spm->user_input)->telegram_id,$text_pelaksana);
			}
        // == END ==//
		}
		elseif($spm->flag_verif_site_manager !== null && $spm->flag_verif_komersial !== null && $spm->flag_verif_pm !== null)
		{
			// == SEND TELEGRAM NOTIFICATION TO ALL PROJECT MANAGER== //
			$profil = getTelegramByRoles(getConfigValues('ROLE_PENGADAAN'));
			if(isset($profil) && count($profil) > 0)
			{
				foreach($profil as $key => $val)
				{
					if($val->telegram_id !== null)
					{
						$text_pengadaan = "Permintaan Survei Barang. Halo <b>" . $val->nama . "</b> anda menerima pemberitahuan permintaan Survei Barang dengan detail SPM sebagai berikut :.\n\n"
						. "No SPM : ".$spm->no_spm."\n"
						. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
						. "Nama Pemohon : ".$spm->nama_pemohon."\n"
						. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
						. "Keterangan : ".$spm->keterangan."\n\n"
						. "Silahkan anda untuk menindak lanjuti permintaan ini dengan mengakses menu <b>Purchase Order -> Survei Barang -> Tambah Survei Baru</b>.\n\n"
						. "Terima kasih. Salam,\n"
						. "WikaBot.";

						sendTelegramBot($val->telegram_id,$text_pengadaan);
					}
				}
			}
       	 // == END == //
       	 if($spm->flag_verif_site_manager == 'Y')
			{
				$verif_sm = 'Diterima'; 
			}
			elseif($spm->flag_verif_site_manager == 'N')
			{
				$verif_sm = 'Ditolak'; 
			}
			else
			{
				$verif_sm = 'Menunggu Verifikasi'; 
			}

			if($spm->flag_verif_pm == 'Y')
			{
				$verif_pm = 'Diterima'; 
			}
			elseif($spm->flag_verif_pm == 'N')
			{
				$verif_pm = 'Ditolak'; 
			}
			else
			{
				$verif_pm = 'Menunggu Verifikasi'; 
			}

			if($spm->flag_verif_komersial == 'Y')
			{
				$verif_komersial = 'Diterima'; 
			}
			elseif($spm->flag_verif_komersial == 'N')
			{
				$verif_komersial = 'Ditolak'; 
			}
			else
			{
				$verif_komersial = 'Menunggu Verifikasi'; 
			}

       	 // == SEND TELEGRAM NOTIFICATION TO USER PEMBUAT==//
			$text_pelaksana = "Pesan Notifikasi Pengajuan SPM. Halo <b>" . getProfileByUserId($spm->user_input)->nama . "</b> berikut adalah informasi SPM dengan detail sebagai berikut :.\n\n"
			. "No SPM : ".$spm->no_spm."\n"
			. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
			. "Nama Pemohon : ".$spm->nama_pemohon."\n"
			. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
			. "Keterangan : ".$spm->keterangan."\n"
			. "Verifikasi Site Manager : ".$verif_sm."\n"
			. "Verifikasi Project Manager : ".$verif_pm."\n"
			. "Verifikasi Project Komersial : ".$verif_komersial."\n\n"
			. "Pengajuan SPM anda akan dilanjutkan ke Survei Barang di Pengadaan.\n\n"
			. "Terima kasih. Salam,\n"
			. "WikaBot.";

			if(!empty(getProfileByUserId($spm->user_input)->telegram_id))
			{
				sendTelegramBot(getProfileByUserId($spm->user_input)->telegram_id,$text_pelaksana);
			}
        // == END ==//	
		}
	}
	else
	{
		 // == SEND TELEGRAM NOTIFICATION TO USER PEMBUAT==//
		$text_pelaksana = "Pesan Notifikasi Pengajuan SPM. Halo <b>" . getProfileByUserId($spm->user_input)->nama . "</b> berikut adalah informasi SPM dengan detail sebagai berikut :.\n\n"
		. "No SPM : ".$spm->no_spm."\n"
		. "Tanggal : ".date('d/m/Y',strtotime($spm->tgl_spm))."\n"
		. "Nama Pemohon : ".$spm->nama_pemohon."\n"
		. "Nama Pelaksana : ".getProfileByUserId($spm->user_input)->nama."\n"
		. "Keterangan : ".$spm->keterangan."\n"
		. "Pengajuan SPM anda berhasil dibatalkan.\n\n"
		. "Terima kasih. Salam,\n"
		. "WikaBot.";

		if(!empty(getProfileByUserId($spm->user_input)->telegram_id))
		{
			sendTelegramBot(getProfileByUserId($spm->user_input)->telegram_id,$text_pelaksana);
		}
	}

	return null;
}

?>