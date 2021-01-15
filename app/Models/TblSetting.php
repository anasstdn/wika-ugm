<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TblSetting
 * 
 * @property int $id
 * @property int|null $user_input
 * @property string|null $opsi_key
 * @property string|null $opsi_val
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class TblSetting extends Model
{
	protected $table = 'tbl_setting';

	protected $casts = [
		'user_input' => 'int'
	];

	protected $fillable = [
		'user_input',
		'opsi_key',
		'opsi_val'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_input');
	}
}
