<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Supplier
 * 
 * @property int $id
 * @property string|null $kode_supplier
 * @property string|null $nama_supplier
 * @property string|null $telepon
 * @property string|null $mobile
 * @property float|null $diskon_supplier
 * @property string|null $flag_aktif
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Supplier extends Model
{
	protected $table = 'supplier';

	protected $casts = [
		'diskon_supplier' => 'float'
	];

	protected $fillable = [
		'kode_supplier',
		'nama_supplier',
		'telepon',
		'mobile',
		'diskon_supplier',
		'flag_aktif'
	];
}
