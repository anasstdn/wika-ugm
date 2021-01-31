<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SpmSurvei
 * 
 * @property int $id
 * @property int|null $detail_spm_id
 * @property int|null $detail_survei_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DetailSpm|null $detail_spm
 * @property DetailSurvei|null $detail_survei
 *
 * @package App\Models
 */
class SpmSurvei extends Model
{
	protected $table = 'spm_survei';

	protected $casts = [
		'detail_spm_id' => 'int',
		'detail_survei_id' => 'int'
	];

	protected $fillable = [
		'detail_spm_id',
		'detail_survei_id'
	];

	public function detail_spm()
	{
		return $this->belongsTo(DetailSpm::class);
	}

	public function detail_survei()
	{
		return $this->belongsTo(DetailSurvei::class);
	}
}
