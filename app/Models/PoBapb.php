<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PoBapb
 * 
 * @property int $id
 * @property int|null $po_id
 * @property int|null $bapb_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Bapb|null $bapb
 * @property Po|null $po
 *
 * @package App\Models
 */
class PoBapb extends Model
{
	protected $table = 'po_bapb';

	protected $casts = [
		'po_id' => 'int',
		'bapb_id' => 'int'
	];

	protected $fillable = [
		'po_id',
		'bapb_id'
	];

	public function bapb()
	{
		return $this->belongsTo(Bapb::class);
	}

	public function po()
	{
		return $this->belongsTo(Po::class);
	}
}
