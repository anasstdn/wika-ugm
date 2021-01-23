<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RiwayatStok
 * 
 * @property int $id
 * @property int|null $material_id
 * @property Carbon|null $tanggal_riwayat
 * @property float|null $qty
 * @property float|null $penambahan
 * @property float|null $pengurangan
 * @property int|null $user_input
 * @property int|null $user_update
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Material|null $material
 * @property User|null $user
 *
 * @package App\Models
 */
class RiwayatStok extends Model
{
	protected $table = 'riwayat_stok';

	protected $casts = [
		'material_id' => 'int',
		'qty' => 'float',
		'penambahan' => 'float',
		'pengurangan' => 'float',
		'user_input' => 'int',
		'user_update' => 'int'
	];

	protected $dates = [
		'tanggal_riwayat'
	];

	protected $fillable = [
		'material_id',
		'tanggal_riwayat',
		'qty',
		'penambahan',
		'pengurangan',
		'user_input',
		'user_update',
		'keterangan'
	];

	public function material()
	{
		return $this->belongsTo(Material::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_update');
	}
}
