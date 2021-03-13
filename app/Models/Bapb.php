<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bapb
 * 
 * @property int $id
 * @property string|null $no_bapb
 * @property Carbon|null $tgl_bapb
 * @property string|null $leveransir
 * @property string|null $no_surat_jalan
 * @property Carbon|null $tgl_surat_jalan
 * @property string|null $no_polisi
 * @property string|null $jenis_kendaraan
 * @property int|null $user_input
 * @property int|null $user_update
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Collection|Po[] $pos
 * @property Collection|RiwayatPenerimaanBarang[] $riwayat_penerimaan_barangs
 *
 * @package App\Models
 */
class Bapb extends Model
{
	protected $table = 'bapb';

	protected $casts = [
		'user_input' => 'int',
		'user_update' => 'int'
	];

	protected $dates = [
		'tgl_bapb',
		'tgl_surat_jalan'
	];

	protected $fillable = [
		'no_bapb',
		'tgl_bapb',
		'leveransir',
		'no_surat_jalan',
		'tgl_surat_jalan',
		'no_polisi',
		'jenis_kendaraan',
		'user_input',
		'user_update',
		'keterangan'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_update');
	}

	public function pos()
	{
		return $this->belongsToMany(Po::class, 'po_bapb')
					->withPivot('id')
					->withTimestamps();
	}

	public function riwayat_penerimaan_barangs()
	{
		return $this->hasMany(RiwayatPenerimaanBarang::class);
	}
}
