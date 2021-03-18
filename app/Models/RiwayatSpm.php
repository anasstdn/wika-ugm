<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RiwayatSpm
 * 
 * @property int $id
 * @property int|null $spm_id
 * @property int|null $action_id
 * @property int|null $user_input
 * @property Carbon|null $datetime_log
 * @property array|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Action|null $action
 * @property Spm|null $spm
 * @property User|null $user
 *
 * @package App\Models
 */
class RiwayatSpm extends Model
{
	protected $table = 'riwayat_spm';

	protected $casts = [
		'spm_id' => 'int',
		'action_id' => 'int',
		'user_input' => 'int',
		'description' => 'json'
	];

	protected $dates = [
		'datetime_log'
	];

	protected $fillable = [
		'spm_id',
		'action_id',
		'user_input',
		'datetime_log',
		'description'
	];

	public function action()
	{
		return $this->belongsTo(Action::class);
	}

	public function spm()
	{
		return $this->belongsTo(Spm::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_input');
	}
}
