<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusPerkawinan
 * 
 * @property int $id
 * @property string|null $status_perkawinan
 * @property string|null $flag_aktif
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class StatusPerkawinan extends Model
{
	protected $table = 'status_perkawinan';

	protected $fillable = [
		'status_perkawinan',
		'flag_aktif'
	];
}
