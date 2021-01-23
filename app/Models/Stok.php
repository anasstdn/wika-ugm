<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Stok
 * 
 * @property int $id
 * @property int|null $material_id
 * @property float|null $qty
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Material|null $material
 *
 * @package App\Models
 */
class Stok extends Model
{
	protected $table = 'stok';

	protected $casts = [
		'material_id' => 'int',
		'qty' => 'float'
	];

	protected $fillable = [
		'material_id',
		'qty'
	];

	public function material()
	{
		return $this->belongsTo(Material::class);
	}
}
