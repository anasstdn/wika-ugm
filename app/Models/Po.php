<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Po
 * 
 * @property int $id
 * @property int|null $spm_id
 * @property string|null $no_po
 * @property Carbon|null $tgl_pengajuan_po
 * @property string|null $nama_pemohon
 * @property string|null $lokasi
 * @property string|null $keterangan
 * @property int|null $user_input
 * @property int|null $user_update
 * @property string|null $flag_batal
 * @property string|null $flag_verif_komersial
 * @property int|null $user_verif_komersial
 * @property Carbon|null $tgl_verif_komersial
 * @property string|null $flag_verif_pm
 * @property int|null $user_verif_pm
 * @property Carbon|null $tgl_verif_pm
 * @property float|null $total_harga
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Spm|null $spm
 * @property User|null $user
 * @property Collection|DetailPo[] $detail_pos
 *
 * @package App\Models
 */
class Po extends Model
{
	protected $table = 'po';

	protected $casts = [
		'spm_id' => 'int',
		'user_input' => 'int',
		'user_update' => 'int',
		'user_verif_komersial' => 'int',
		'user_verif_pm' => 'int',
		'total_harga' => 'float'
	];

	protected $dates = [
		'tgl_pengajuan_po',
		'tgl_verif_komersial',
		'tgl_verif_pm'
	];

	protected $fillable = [
		'spm_id',
		'no_po',
		'tgl_pengajuan_po',
		'nama_pemohon',
		'lokasi',
		'keterangan',
		'user_input',
		'user_update',
		'flag_batal',
		'flag_verif_komersial',
		'user_verif_komersial',
		'tgl_verif_komersial',
		'flag_verif_pm',
		'user_verif_pm',
		'tgl_verif_pm',
		'total_harga'
	];

	public function spm()
	{
		return $this->belongsTo(Spm::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_verif_pm');
	}

	public function detail_pos()
	{
		return $this->hasMany(DetailPo::class);
	}
}
