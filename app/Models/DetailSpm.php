<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetailSpm
 * 
 * @property int $id
 * @property int|null $spm_id
 * @property int|null $material_id
 * @property float|null $volume
 * @property Carbon|null $tgl_penggunaan
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $flag_verif_site_manager
 * @property string|null $flag_verif_komersial
 * @property string|null $flag_verif_pm
 * 
 * @property Material|null $material
 * @property Spm|null $spm
 * @property Collection|SpmSurvei[] $spm_surveis
 *
 * @package App\Models
 */
class DetailSpm extends Model
{
	protected $table = 'detail_spm';

	protected $casts = [
		'spm_id' => 'int',
		'material_id' => 'int',
		'volume' => 'float'
	];

	protected $dates = [
		'tgl_penggunaan'
	];

	protected $fillable = [
		'spm_id',
		'material_id',
		'volume',
		'tgl_penggunaan',
		'keterangan',
		'flag_verif_site_manager',
		'flag_verif_komersial',
		'flag_verif_pm'
	];

	public function material()
	{
		return $this->belongsTo(Material::class);
	}

	public function spm()
	{
		return $this->belongsTo(Spm::class);
	}

	public function spm_surveis()
	{
		return $this->hasMany(SpmSurvei::class);
	}
}
