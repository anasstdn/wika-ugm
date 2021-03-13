<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RiwayatPenerimaanBarang
 * 
 * @property int $id
 * @property int|null $bapb_id
 * @property int|null $riwayat_stok_id
 * @property int|null $detail_bapb_id
 * @property int|null $user_input
 * @property int|null $user_update
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Bapb|null $bapb
 * @property DetailBapb|null $detail_bapb
 * @property RiwayatStok|null $riwayat_stok
 * @property User|null $user
 *
 * @package App\Models
 */
class RiwayatPenerimaanBarang extends Model
{
	protected $table = 'riwayat_penerimaan_barang';

	protected $casts = [
		'bapb_id' => 'int',
		'riwayat_stok_id' => 'int',
		'detail_bapb_id' => 'int',
		'user_input' => 'int',
		'user_update' => 'int'
	];

	protected $fillable = [
		'bapb_id',
		'riwayat_stok_id',
		'detail_bapb_id',
		'user_input',
		'user_update'
	];

	public function bapb()
	{
		return $this->belongsTo(Bapb::class);
	}

	public function detail_bapb()
	{
		return $this->belongsTo(DetailBapb::class);
	}

	public function riwayat_stok()
	{
		return $this->belongsTo(RiwayatStok::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_update');
	}
}
