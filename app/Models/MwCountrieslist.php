<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwCountrieslist
 *
 * @property int $id
 * @property string $name
 *
 * @package App\Models
 */
class MwCountrieslist extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_countrieslist';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];
}
