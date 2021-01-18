<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pegawai
 * 
 * @property int $id
 * @property int|null $profil_id
 * @property string|null $nip
 * @property Carbon|null $tgl_bergabung
 * @property Carbon|null $tgl_resign
 * @property string|null $status_resign
 * @property int|null $departement_id
 * @property int|null $jabatan_id
 * @property int|null $user_input
 * @property int|null $user_update
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Departement|null $departement
 * @property Jabatan|null $jabatan
 * @property Profil|null $profil
 * @property User|null $user
 *
 * @package App\Models
 */
class Pegawai extends Model
{
	protected $table = 'pegawai';

	protected $casts = [
		'profil_id' => 'int',
		'departement_id' => 'int',
		'jabatan_id' => 'int',
		'user_input' => 'int',
		'user_update' => 'int'
	];

	protected $dates = [
		'tgl_bergabung',
		'tgl_resign'
	];

	protected $fillable = [
		'profil_id',
		'nip',
		'tgl_bergabung',
		'tgl_resign',
		'status_resign',
		'departement_id',
		'jabatan_id',
		'user_input',
		'user_update'
	];

	public function departement()
	{
		return $this->belongsTo(Departement::class);
	}

	public function jabatan()
	{
		return $this->belongsTo(Jabatan::class);
	}

	public function profil()
	{
		return $this->belongsTo(Profil::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_update');
	}
}
