<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Action
 * 
 * @property int $id
 * @property string|null $action
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|RiwayatSpm[] $riwayat_spms
 *
 * @package App\Models
 */
class Action extends Model
{
	protected $table = 'action';

	protected $fillable = [
		'action',
		'description'
	];

	public function riwayat_spms()
	{
		return $this->hasMany(RiwayatSpm::class);
	}
}
