<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MwListingTypeField extends Model
{
    protected $connection = 'mysql_legacy';
    protected $table = 'mw_listing_type_filelds';
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = ['category_id', 'listing_type'];

    protected $casts = [
        'category_id' => 'int',
        'listing_type' => 'string'
    ];

    protected $fillable = [
        'category_id',
        'listing_type'
    ];

    // Relationship to category if needed
    public function category()
    {
        return $this->belongsTo(MwCategory::class, 'category_id', 'category_id');
    }
}
