<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Survei
 * 
 * @property int $id
 * @property Carbon|null $tgl_pembuatan
 * @property string|null $keterangan
 * @property int|null $user_input
 * @property int|null $user_update
 * @property string|null $flag_batal
 * @property string|null $flag_po
 * @property float|null $total_harga
 * @property int|null $supplier_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Supplier|null $supplier
 * @property User|null $user
 * @property Collection|DetailSurvei[] $detail_surveis
 *
 * @package App\Models
 */
class Survei extends Model
{
	protected $table = 'survei';

	protected $casts = [
		'user_input' => 'int',
		'user_update' => 'int',
		'total_harga' => 'float',
		'supplier_id' => 'int'
	];

	protected $dates = [
		'tgl_pembuatan'
	];

	protected $fillable = [
		'tgl_pembuatan',
		'keterangan',
		'user_input',
		'user_update',
		'flag_batal',
		'flag_po',
		'total_harga',
		'supplier_id'
	];

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_update');
	}

	public function detail_surveis()
	{
		return $this->hasMany(DetailSurvei::class);
	}
}
