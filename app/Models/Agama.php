<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Agama
 * 
 * @property int $id
 * @property string|null $agama
 * @property string|null $flag_aktif
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Agama extends Model
{
	protected $table = 'agama';

	protected $fillable = [
		'agama',
		'flag_aktif'
	];
}
