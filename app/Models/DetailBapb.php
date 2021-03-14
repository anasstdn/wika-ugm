<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetailBapb
 * 
 * @property int $id
 * @property int|null $bapb_id
 * @property int|null $material_id
 * @property string|null $merek
 * @property float|null $volume
 * @property string|null $kode_sb_daya
 * @property string|null $kode_tahap
 * @property string|null $keterangan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Bapb|null $bapb
 * @property Material|null $material
 * @property Collection|RiwayatPenerimaanBarang[] $riwayat_penerimaan_barangs
 *
 * @package App\Models
 */
class DetailBapb extends Model
{
	protected $table = 'detail_bapb';

	protected $casts = [
		'bapb_id' => 'int',
		'material_id' => 'int',
		'volume' => 'float'
	];

	protected $fillable = [
		'bapb_id',
		'material_id',
		'merek',
		'volume',
		'kode_sb_daya',
		'kode_tahap',
		'keterangan'
	];

	public function bapb()
	{
		return $this->belongsTo(Bapb::class);
	}

	public function material()
	{
		return $this->belongsTo(Material::class);
	}

	public function riwayat_penerimaan_barangs()
	{
		return $this->hasMany(RiwayatPenerimaanBarang::class);
	}
}
