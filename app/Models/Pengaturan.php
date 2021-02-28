<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pengaturan
 * 
 * @property int $id
 * @property string|null $opsi_key
 * @property string|null $opsi_val
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Pengaturan extends Model
{
	protected $table = 'pengaturan';

	protected $fillable = [
		'opsi_key',
		'opsi_val'
	];
}
