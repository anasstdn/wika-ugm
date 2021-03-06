<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Po
 * 
 * @property int $id
 * @property int|null $survei_id
 * @property int|null $supplier_id
 * @property string|null $no_po
 * @property Carbon|null $tgl_pengajuan_po
 * @property int|null $user_input
 * @property int|null $user_update
 * @property string|null $flag_batal
 * @property string|null $flag_verif_komersial
 * @property int|null $user_verif_komersial
 * @property Carbon|null $tgl_verif_komersial
 * @property string|null $flag_verif_pm
 * @property int|null $user_verif_pm
 * @property Carbon|null $tgl_verif_pm
 * @property float|null $total_harga
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $catatan_project_manager
 * @property string|null $catatan_komersial
 * 
 * @property Supplier|null $supplier
 * @property Survei|null $survei
 * @property User|null $user
 * @property Collection|DetailPo[] $detail_pos
 *
 * @package App\Models
 */
class Po extends Model
{
	protected $table = 'po';

	protected $casts = [
		'survei_id' => 'int',
		'supplier_id' => 'int',
		'user_input' => 'int',
		'user_update' => 'int',
		'user_verif_komersial' => 'int',
		'user_verif_pm' => 'int',
		'total_harga' => 'float'
	];

	protected $dates = [
		'tgl_pengajuan_po',
		'tgl_verif_komersial',
		'tgl_verif_pm'
	];

	protected $fillable = [
		'survei_id',
		'supplier_id',
		'no_po',
		'tgl_pengajuan_po',
		'user_input',
		'user_update',
		'flag_batal',
		'flag_verif_komersial',
		'user_verif_komersial',
		'tgl_verif_komersial',
		'flag_verif_pm',
		'user_verif_pm',
		'tgl_verif_pm',
		'total_harga',
		'catatan_project_manager',
		'catatan_komersial'
	];

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

	public function survei()
	{
		return $this->belongsTo(Survei::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_verif_pm');
	}

	public function detail_pos()
	{
		return $this->hasMany(DetailPo::class);
	}
}
