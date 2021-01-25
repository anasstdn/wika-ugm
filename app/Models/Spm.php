<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Spm
 * 
 * @property int $id
 * @property string|null $no_spm
 * @property Carbon|null $tgl_spm
 * @property string|null $nama_pemohon
 * @property string|null $lokasi
 * @property string|null $keterangan
 * @property int|null $user_input
 * @property int|null $user_update
 * @property string|null $flag_verif_site_manager
 * @property int|null $user_verif_site_manager
 * @property Carbon|null $tgl_verif_site_manager
 * @property string|null $flag_verif_komersial
 * @property int|null $user_verif_komersial
 * @property Carbon|null $tgl_verif_komersial
 * @property string|null $flag_verif_pm
 * @property int|null $user_verif_pm
 * @property Carbon|null $tgl_verif_pm
 * @property string|null $flag_batal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $catatan_site_manager
 * @property string|null $catatan_project_manager
 * @property string|null $catatan_komersial
 * 
 * @property User|null $user
 * @property Collection|DetailSpm[] $detail_spms
 *
 * @package App\Models
 */
class Spm extends Model
{
	protected $table = 'spm';

	protected $casts = [
		'user_input' => 'int',
		'user_update' => 'int',
		'user_verif_site_manager' => 'int',
		'user_verif_komersial' => 'int',
		'user_verif_pm' => 'int'
	];

	protected $dates = [
		'tgl_spm',
		'tgl_verif_site_manager',
		'tgl_verif_komersial',
		'tgl_verif_pm'
	];

	protected $fillable = [
		'no_spm',
		'tgl_spm',
		'nama_pemohon',
		'lokasi',
		'keterangan',
		'user_input',
		'user_update',
		'flag_verif_site_manager',
		'user_verif_site_manager',
		'tgl_verif_site_manager',
		'flag_verif_komersial',
		'user_verif_komersial',
		'tgl_verif_komersial',
		'flag_verif_pm',
		'user_verif_pm',
		'tgl_verif_pm',
		'flag_batal',
		'catatan_site_manager',
		'catatan_project_manager',
		'catatan_komersial'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_verif_site_manager');
	}

	public function detail_spms()
	{
		return $this->hasMany(DetailSpm::class);
	}
}
