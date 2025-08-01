<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwUserMainCategory
 *
 * @property int $user_id
 * @property int $category_id
 *
 * @property MwListingUser $mw_listing_user
 * @property MwCategory $mw_category
 *
 * @package App\Models
 */
class MwUserMainCategory extends Model
{
    protected $connection = 'mysql_legacy';
    protected $table = 'mw_user_main_categories';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'category_id'
    ];

    protected $casts = [
        'user_id' => 'int',
        'category_id' => 'int'
    ];

    public function user()
    {
        return $this->belongsTo(MwListingUser::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(MwCategory::class, 'category_id', 'category_id');
    }
}
