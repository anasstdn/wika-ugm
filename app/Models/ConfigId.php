<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cache;
use App\Traits\CacheUpdater;

/**
 * Class ConfigId
 * 
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class ConfigId extends Model
{
	use CacheUpdater;
	protected $table = 'config_ids';

	protected $fillable = [
		'config_name',
		'table_source',
		'config_value',
		'description'
	];

	public static function getValues($configName){
		$configs = Cache::remember('config_ids_'.$configName,120, function() use($configName)
		{
			$temp = ConfigId::select('config_value')->where('config_name',$configName)->first();
			if($temp==null)return null;
			return explode(',',$temp->config_value);
		});

		return $configs;
	}

	public static function getDate($configName)
	{
		$configs = Cache::remember('config_ids_'.$configName,120, function() use($configName)
		{
			$temp = ConfigId::select('config_value')->where('config_name',$configName)->first();
			if($temp==null)return null;
			return explode(',',$temp->config_value);
		});

		return $configs;
	}

	public static function getValfromDB($configName)
	{
		$configs = Cache::remember('config_ids_'.$configName,120, function() use($configName)
		{
			$temp = ConfigId::select('*')->where('config_name',$configName)->first();

			if($temp==null)
				{return null;}
			else
			{
				$data['table_source']=explode(',',$temp->table_source);
				$data['config_value']=explode(',',$temp->config_value);
				return $data;
			}

		});

		return $configs;
	}

	private function updateCache(){
		Cache::forget('config_ids_'.$this->config_name);

		return self::getValues($this->config_name);
	}

	private function updateCache1(){
		Cache::forget('config_ids_'.$this->config_name);

		return self::getDate($this->config_name);
	}

	private function updateCache2(){
		Cache::forget('config_ids_'.$this->config_name);

		return self::getValfromDB($this->config_name);
	}
}
