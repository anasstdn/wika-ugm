<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Jabatan
 * 
 * @property int $id
 * @property string|null $jabatan
 * @property string|null $flag_aktif
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Jabatan extends Model
{
	protected $table = 'jabatan';

	protected $fillable = [
		'jabatan',
		'flag_aktif'
	];
}
