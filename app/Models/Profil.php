<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Profil
 * 
 * @property int $id
 * @property string|null $nama
 * @property string|null $nik
 * @property int|null $jenis_kelamin
 * @property int|null $agama
 * @property int|null $status_perkawinan
 * @property string|null $alamat_domisili
 * @property string|null $kota_domisili
 * @property string|null $alamat_ktp
 * @property string|null $kota_ktp
 * @property string|null $tempat_lahir
 * @property Carbon|null $tgl_lahir
 * @property string|null $no_telp
 * @property string|null $email
 * @property string|null $foto
 * @property int|null $user_input
 * @property int|null $user_update
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property Collection|UserProfil[] $user_profils
 *
 * @package App\Models
 */
class Profil extends Model
{
	protected $table = 'profil';

	protected $casts = [
		'jenis_kelamin' => 'int',
		'agama' => 'int',
		'status_perkawinan' => 'int',
		'user_input' => 'int',
		'user_update' => 'int'
	];

	protected $dates = [
		'tgl_lahir'
	];

	protected $fillable = [
		'nama',
		'nik',
		'jenis_kelamin',
		'agama',
		'status_perkawinan',
		'alamat_domisili',
		'kota_domisili',
		'alamat_ktp',
		'kota_ktp',
		'tempat_lahir',
		'tgl_lahir',
		'no_telp',
		'email',
		'foto',
		'user_input',
		'user_update'
	];

	public function agama()
	{
		return $this->belongsTo(Agama::class, 'agama');
	}

	public function jenis_kelamin()
	{
		return $this->belongsTo(JenisKelamin::class, 'jenis_kelamin');
	}

	public function status_perkawinan()
	{
		return $this->belongsTo(StatusPerkawinan::class, 'status_perkawinan');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_update');
	}

	public function user_profils()
	{
		return $this->hasMany(UserProfil::class);
	}
}
