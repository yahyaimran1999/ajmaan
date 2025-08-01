<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MwTranslateRelation
 *
 * @property int $translate_id
 * @property int $rec
 * @property int|null $tag_id
 * @property string|null $common_id
 * @property int|null $article_id
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property int|null $country_id
 * @property int|null $state_id
 * @property int|null $community_id
 * @property int|null $sub_community_id
 * @property int|null $amenities_id
 * @property int|null $language_id
 * @property int|null $ad_id
 * @property int|null $advertisemen_id
 * @property int|null $template_id
 * @property int|null $section_id
 * @property int|null $city_id
 * @property int|null $article_category_id
 * @property int|null $popular_id
 * @property int|null $external_id
 * @property int|null $link_id
 * @property int|null $master_id
 * @property int|null $guide_id
 *
 * @property MwCategory|null $mw_category
 * @property MwLanguage|null $mw_language
 * @property MwPlaceAnAd|null $mw_place_an_ad
 * @property MwAdvertisementLayout|null $mw_advertisement_layout
 * @property MwCustomerEmailTemplate|null $mw_customer_email_template
 * @property MwSection|null $mw_section
 * @property MwSubCommunity|null $mw_sub_community
 * @property MwCity|null $mw_city
 * @property MwArticleCategory|null $mw_article_category
 * @property MwPopularCity|null $mw_popular_city
 * @property MwExternalLink|null $mw_external_link
 * @property MwCommonTag|null $mw_common_tag
 * @property MwDynamicLink|null $mw_dynamic_link
 * @property MwMaster|null $mw_master
 * @property MwAreaGuide|null $mw_area_guide
 * @property MwArticle|null $mw_article
 * @property MwTranslate $mw_translate
 * @property MwSubcategory|null $mw_subcategory
 * @property MwCountry|null $mw_country
 * @property MwState|null $mw_state
 * @property MwCommunity|null $mw_community
 * @property MwAmenity|null $mw_amenity
 *
 * @package App\Models
 */
class MwTranslateRelation extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_translate_relation';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'translate_id' => 'int',
		'rec' => 'int',
		'tag_id' => 'int',
		'article_id' => 'int',
		'category_id' => 'int',
		'sub_category_id' => 'int',
		'country_id' => 'int',
		'state_id' => 'int',
		'community_id' => 'int',
		'sub_community_id' => 'int',
		'amenities_id' => 'int',
		'language_id' => 'int',
		'ad_id' => 'int',
		'advertisemen_id' => 'int',
		'template_id' => 'int',
		'section_id' => 'int',
		'city_id' => 'int',
		'article_category_id' => 'int',
		'popular_id' => 'int',
		'external_id' => 'int',
		'link_id' => 'int',
		'master_id' => 'int',
		'guide_id' => 'int'
	];

	protected $fillable = [
		'tag_id',
		'common_id',
		'article_id',
		'category_id',
		'sub_category_id',
		'country_id',
		'state_id',
		'community_id',
		'sub_community_id',
		'amenities_id',
		'language_id',
		'ad_id',
		'advertisemen_id',
		'template_id',
		'section_id',
		'city_id',
		'article_category_id',
		'popular_id',
		'external_id',
		'link_id',
		'master_id',
		'guide_id'
	];

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}

	public function mw_language()
	{
		return $this->belongsTo(MwLanguage::class, 'language_id');
	}

	public function mw_place_an_ad()
	{
		return $this->belongsTo(MwPlaceAnAd::class, 'ad_id');
	}

	public function mw_advertisement_layout()
	{
		return $this->belongsTo(MwAdvertisementLayout::class, 'advertisemen_id');
	}

	public function mw_customer_email_template()
	{
		return $this->belongsTo(MwCustomerEmailTemplate::class, 'template_id');
	}

	public function mw_section()
	{
		return $this->belongsTo(MwSection::class, 'section_id');
	}

	public function mw_sub_community()
	{
		return $this->belongsTo(MwSubCommunity::class, 'sub_community_id');
	}

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'city_id');
	}

	public function mw_article_category()
	{
		return $this->belongsTo(MwArticleCategory::class, 'article_category_id');
	}

	public function mw_popular_city()
	{
		return $this->belongsTo(MwPopularCity::class, 'popular_id');
	}

	public function mw_external_link()
	{
		return $this->belongsTo(MwExternalLink::class, 'external_id');
	}

	public function mw_common_tag()
	{
		return $this->belongsTo(MwCommonTag::class, 'tag_id');
	}

	public function mw_dynamic_link()
	{
		return $this->belongsTo(MwDynamicLink::class, 'link_id');
	}

	public function mw_master()
	{
		return $this->belongsTo(MwMaster::class, 'master_id');
	}

	public function mw_area_guide()
	{
		return $this->belongsTo(MwAreaGuide::class, 'guide_id');
	}

	public function mw_article()
	{
		return $this->belongsTo(MwArticle::class, 'article_id');
	}

	public function mw_translate()
	{
		return $this->belongsTo(MwTranslate::class, 'translate_id');
	}

	public function mw_subcategory()
	{
		return $this->belongsTo(MwSubcategory::class, 'sub_category_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country_id');
	}

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state_id');
	}

	public function mw_community()
	{
		return $this->belongsTo(MwCommunity::class, 'community_id');
	}

	public function mw_amenity()
	{
		return $this->belongsTo(MwAmenity::class, 'amenities_id');
	}
}
