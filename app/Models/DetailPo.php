<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetailPo
 * 
 * @property int $id
 * @property int|null $po_id
 * @property int|null $material_id
 * @property string|null $merek
 * @property float|null $volume
 * @property Carbon|null $tgl_penggunaan
 * @property string|null $keterangan
 * @property float|null $harga_per_unit
 * @property float|null $subtotal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Material|null $material
 * @property Po|null $po
 *
 * @package App\Models
 */
class DetailPo extends Model
{
	protected $table = 'detail_po';

	protected $casts = [
		'po_id' => 'int',
		'material_id' => 'int',
		'volume' => 'float',
		'harga_per_unit' => 'float',
		'subtotal' => 'float'
	];

	protected $dates = [
		'tgl_penggunaan'
	];

	protected $fillable = [
		'po_id',
		'material_id',
		'merek',
		'volume',
		'tgl_penggunaan',
		'keterangan',
		'harga_per_unit',
		'subtotal'
	];

	public function material()
	{
		return $this->belongsTo(Material::class);
	}

	public function po()
	{
		return $this->belongsTo(Po::class);
	}
}
