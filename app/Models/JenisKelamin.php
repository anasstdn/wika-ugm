<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JenisKelamin
 * 
 * @property int $id
 * @property string|null $jenis_kelamin
 * @property string|null $flag_aktif
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class JenisKelamin extends Model
{
	protected $table = 'jenis_kelamin';

	protected $fillable = [
		'jenis_kelamin',
		'flag_aktif'
	];
}
