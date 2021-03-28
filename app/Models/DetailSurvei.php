<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetailSurvei
 * 
 * @property int $id
 * @property int|null $survei_id
 * @property int|null $material_id
 * @property string|null $merek
 * @property float|null $volume
 * @property Carbon|null $tgl_penggunaan
 * @property string|null $keterangan
 * @property float|null $harga_per_unit
 * @property float|null $subtotal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $flag_verif_komersial
 * @property string|null $flag_verif_pm
 * 
 * @property Material|null $material
 * @property Survei|null $survei
 * @property Collection|SpmSurvei[] $spm_surveis
 *
 * @package App\Models
 */
class DetailSurvei extends Model
{
	protected $table = 'detail_survei';

	protected $casts = [
		'survei_id' => 'int',
		'material_id' => 'int',
		'volume' => 'float',
		'harga_per_unit' => 'float',
		'subtotal' => 'float'
	];

	protected $dates = [
		'tgl_penggunaan'
	];

	protected $fillable = [
		'survei_id',
		'material_id',
		'merek',
		'volume',
		'tgl_penggunaan',
		'keterangan',
		'harga_per_unit',
		'subtotal',
		'flag_verif_komersial',
		'flag_verif_pm'
	];

	public function material()
	{
		return $this->belongsTo(Material::class);
	}

	public function survei()
	{
		return $this->belongsTo(Survei::class);
	}

	public function spm_surveis()
	{
		return $this->hasMany(SpmSurvei::class);
	}
}
