<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Material
 * 
 * @property int $id
 * @property string|null $kode_material
 * @property string|null $material
 * @property string|null $spesifikasi
 * @property string|null $satuan
 * @property string|null $flag_aktif
 * @property int|null $level
 * @property string|null $parent_status
 * @property int|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Material[] $materials
 *
 * @package App\Models
 */
class Material extends Model
{
	protected $table = 'material';

	protected $casts = [
		'level' => 'int',
		'parent_id' => 'int'
	];

	protected $fillable = [
		'kode_material',
		'material',
		'spesifikasi',
		'satuan',
		'flag_aktif',
		'level',
		'parent_status',
		'parent_id'
	];

	public function material()
	{
		return $this->belongsTo(Material::class, 'parent_id');
	}

	public function materials()
	{
		return $this->hasMany(Material::class, 'parent_id');
	}
}
