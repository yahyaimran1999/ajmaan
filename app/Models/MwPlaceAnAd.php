<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class MwPlaceAnAd
 *
 * @property int $id
 * @property string|null $ad_uid
 * @property int|null $section_id
 * @property int|null $listing_type
 * @property int|null $category_id
 * @property int|null $sub_category_id
 * @property string $ad_title
 * @property string|null $ad_description
 * @property float $price
 * @property int $model
 * @property int|null $country
 * @property int|null $state
 * @property int|null $city
 * @property int|null $district
 * @property string $mobile_number
 * @property int $bathrooms
 * @property int $bedrooms
 * @property int|null $user_id
 * @property Carbon $date_added
 * @property Carbon $last_updated
 * @property int|null $priority
 * @property string $isTrash
 * @property string $status
 * @property string $slug
 * @property string $image
 * @property string|null $dynamic
 * @property string $location_latitude
 * @property string $location_longitude
 * @property string $featured
 * @property string $promoted
 * @property string $recmnded
 * @property string $area_location
 * @property string $xml_inserted
 * @property string $code
 * @property string $RefNo
 * @property int|null $community_id
 * @property int|null $sub_community_id
 * @property string $property_name
 * @property float|null $builtup_area
 * @property float|null $plot_area
 * @property string $PrimaryUnitView
 * @property string $FloorNo
 * @property Carbon|null $HandoverDate
 * @property string $parking
 * @property string $salesman_email
 * @property Carbon|null $expiry_date
 * @property string $mandate
 * @property int|null $developer_id
 * @property string $currency_abr
 * @property string $area_measurement
 * @property int $RetUnitCategory
 * @property string $RecommendedProperties
 * @property int $PropertyID
 * @property string $ReraStrNo
 * @property float $Rent
 * @property float $RentPerMonth
 * @property string $occupant_status
 * @property string $rent_paid
 * @property string $nearest_metro
 * @property string $nearest_railway
 * @property string|null $construction_status
 * @property string|null $furnished
 * @property string|null $maid_room
 * @property string|null $rera_no
 * @property int|null $year_built
 * @property string|null $transaction_type
 * @property int|null $balconies
 * @property int|null $total_floor
 * @property string $is_new
 * @property string|null $street_address
 * @property int|null $car_parking
 * @property int|null $no_of_units
 * @property int|null $no_of_stories
 * @property int|null $pantry
 * @property int|null $kitchen
 * @property string|null $payment_plan
 * @property string|null $youtube_url
 * @property string|null $offering
 * @property string|null $ad_description_ar
 * @property string|null $ad_title_ar
 * @property string|null $types_pdf
 * @property string|null $floor_pdf
 * @property string|null $payment_pdf
 * @property string|null $broucher
 * @property int|null $c1
 * @property string|null $contact_name
 * @property string|null $contact_email
 * @property string|null $d_name
 * @property string|null $d_description
 * @property string|null $contractor
 * @property string|null $architect
 * @property string|null $l_architect
 * @property string|null $d_logo
 * @property string|null $p_allowed
 * @property float|null $quality
 * @property int|null $city_2
 * @property int|null $city_3
 * @property int|null $city_4
 * @property string|null $p_o_r
 * @property string $channel
 * @property string|null $h_c
 * @property string|null $s_r
 * @property string|null $map_added
 * @property Carbon|null $s_date
 * @property string|null $meta_title
 * @property string|null $meta_keywords
 * @property string|null $meta_description
 * @property string|null $fetch_schools
 * @property string|null $featured_e
 * @property string|null $f_status
 * @property Carbon|null $f_e_d
 * @property string|null $hot2
 * @property Carbon|null $hot_e
 * @property int|null $package_used2
 * @property Carbon|null $draft_date
 * @property Carbon|null $draft_send
 * @property string|null $h_status
 * @property Carbon|null $refresh_date
 *
 * @property MwSection|null $mw_section
 * @property MwCity|null $mw_city
 * @property MwCategory|null $mw_category
 * @property MwDeveloper|null $mw_developer
 * @property MwSubcategory|null $mw_subcategory
 * @property MwDistrict|null $mw_district
 * @property MwListingUser|null $mw_listing_user
 * @property MwCountry|null $mw_country
 * @property MwState|null $mw_state
 * @property MwCommunity|null $mw_community
 * @property MwSubCommunity|null $mw_sub_community
 * @property MwAdAmenity|null $mw_ad_amenity
 * @property Collection|MwAdFaq[] $mw_ad_faqs
 * @property Collection|MwAdFavourite[] $mw_ad_favourites
 * @property Collection|MwAdFloorPlan[] $mw_ad_floor_plans
 * @property Collection|MwAdImage[] $mw_ad_images
 * @property Collection|MwAdNearestSchool[] $mw_ad_nearest_schools
 * @property Collection|MwAdPropertyType[] $mw_ad_property_types
 * @property Collection|MwAdvertisementItem[] $mw_advertisement_items
 * @property Collection|MwContactU[] $mw_contact_us
 * @property Collection|MwLoanApplication[] $mw_loan_applications
 * @property Collection|MwPricePlanOrder[] $mw_price_plan_orders
 * @property Collection|MwReportListing[] $mw_report_listings
 * @property Collection|MwStatisticsPage[] $mw_statistics_pages
 * @property Collection|MwTranslateRelation[] $mw_translate_relations
 * @property Collection|MwUserPackagesUtility[] $mw_user_packages_utilities
 *
 * @package App\Models
 */
class MwPlaceAnAd extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_place_an_ad';
	public $timestamps = false;

	protected $casts = [
		'section_id' => 'int',
		'listing_type' => 'int',
		'category_id' => 'int',
		'sub_category_id' => 'int',
		'price' => 'float',
		'model' => 'int',
		'country' => 'int',
		'state' => 'int',
		'city' => 'int',
		'district' => 'int',
		'bathrooms' => 'int',
		'bedrooms' => 'int',
		'user_id' => 'int',
		'date_added' => 'datetime',
		'last_updated' => 'datetime',
		'priority' => 'int',
		'community_id' => 'int',
		'sub_community_id' => 'int',
		'builtup_area' => 'float',
		'plot_area' => 'float',
		'HandoverDate' => 'datetime',
		'expiry_date' => 'datetime',
		'developer_id' => 'int',
		'RetUnitCategory' => 'int',
		'PropertyID' => 'int',
		'Rent' => 'float',
		'RentPerMonth' => 'float',
		'year_built' => 'int',
		'balconies' => 'int',
		'total_floor' => 'int',
		'car_parking' => 'int',
		'no_of_units' => 'int',
		'no_of_stories' => 'int',
		'pantry' => 'int',
		'kitchen' => 'int',
		'c1' => 'int',
		'quality' => 'float',
		'city_2' => 'int',
		'city_3' => 'int',
		'city_4' => 'int',
		's_date' => 'datetime',
		'f_e_d' => 'datetime',
		'hot_e' => 'datetime',
		'package_used2' => 'int',
		'draft_date' => 'datetime',
		'draft_send' => 'datetime',
		'refresh_date' => 'datetime'
	];

	protected $fillable = [
		'ad_uid',
		'section_id',
		'listing_type',
		'category_id',
		'sub_category_id',
		'ad_title',
		'ad_description',
		'price',
		'model',
		'country',
		'state',
		'city',
		'district',
		'mobile_number',
		'bathrooms',
		'bedrooms',
		'user_id',
		'date_added',
		'last_updated',
		'priority',
		'isTrash',
		'status',
		'slug',
		'image',
		'dynamic',
		'location_latitude',
		'location_longitude',
		'featured',
		'promoted',
		'recmnded',
		'area_location',
		'xml_inserted',
		'code',
		'RefNo',
		'community_id',
		'sub_community_id',
		'property_name',
		'builtup_area',
		'plot_area',
		'PrimaryUnitView',
		'FloorNo',
		'HandoverDate',
		'parking',
		'salesman_email',
		'expiry_date',
		'mandate',
		'developer_id',
		'currency_abr',
		'area_measurement',
		'RetUnitCategory',
		'RecommendedProperties',
		'PropertyID',
		'ReraStrNo',
		'Rent',
		'RentPerMonth',
		'occupant_status',
		'rent_paid',
		'nearest_metro',
		'nearest_railway',
		'construction_status',
		'furnished',
		'maid_room',
		'rera_no',
		'year_built',
		'transaction_type',
		'balconies',
		'total_floor',
		'is_new',
		'street_address',
		'car_parking',
		'no_of_units',
		'no_of_stories',
		'pantry',
		'kitchen',
		'payment_plan',
		'youtube_url',
		'offering',
		'ad_description_ar',
		'ad_title_ar',
		'types_pdf',
		'floor_pdf',
		'payment_pdf',
		'broucher',
		'c1',
		'contact_name',
		'contact_email',
		'd_name',
		'd_description',
		'contractor',
		'architect',
		'l_architect',
		'd_logo',
		'p_allowed',
		'quality',
		'city_2',
		'city_3',
		'city_4',
		'p_o_r',
		'channel',
		'h_c',
		's_r',
		'map_added',
		's_date',
		'meta_title',
		'meta_keywords',
		'meta_description',
		'fetch_schools',
		'featured_e',
		'f_status',
		'f_e_d',
		'hot2',
		'hot_e',
		'package_used2',
		'draft_date',
		'draft_send',
		'h_status',
		'refresh_date'
	];

	public function mw_section()
	{
		return $this->belongsTo(MwSection::class, 'section_id');
	}

	public function mw_city()
	{
		return $this->belongsTo(MwCity::class, 'city');
	}

	public function mw_category()
	{
		return $this->belongsTo(MwCategory::class, 'category_id');
	}

	public function mw_developer()
	{
		return $this->belongsTo(MwDeveloper::class, 'developer_id');
	}

	public function mw_subcategory()
	{
		return $this->belongsTo(MwSubcategory::class, 'sub_category_id');
	}

	public function mw_district()
	{
		return $this->belongsTo(MwDistrict::class, 'district');
	}

	public function mw_listing_user()
	{
		return $this->belongsTo(MwListingUser::class, 'user_id');
	}

	public function mw_country()
	{
		return $this->belongsTo(MwCountry::class, 'country');
	}

	public function mw_state()
	{
		return $this->belongsTo(MwState::class, 'state');
	}

	public function mw_community()
	{
		return $this->belongsTo(MwCommunity::class, 'community_id');
	}

	public function mw_sub_community()
	{
		return $this->belongsTo(MwSubCommunity::class, 'sub_community_id');
	}

	public function mw_ad_amenity()
	{
		return $this->hasMany(MwAdAmenity::class, 'ad_id');
	}

	public function mw_ad_faqs()
	{
		return $this->hasMany(MwAdFaq::class, 'ad_id');
	}

	public function mw_ad_favourites()
	{
		return $this->hasMany(MwAdFavourite::class, 'ad_id');
	}

	public function mw_ad_floor_plans()
	{
		return $this->hasMany(MwAdFloorPlan::class, 'ad_id');
	}

	public function mw_ad_images()
	{
		return $this->hasMany(MwAdImage::class, 'ad_id');
	}

	public function mw_ad_nearest_schools()
	{
		return $this->hasMany(MwAdNearestSchool::class, 'ad_id');
	}

	public function mw_ad_property_types()
	{
		return $this->hasMany(MwAdPropertyType::class, 'ad_id');
	}

	public function mw_advertisement_items()
	{
		return $this->hasMany(MwAdvertisementItem::class, 'ad_id');
	}

	public function mw_contact_us()
	{
		return $this->hasMany(MwContactU::class, 'ad_id');
	}

	public function mw_loan_applications()
	{
		return $this->hasMany(MwLoanApplication::class, 'ad_id');
	}

	public function mw_price_plan_orders()
	{
		return $this->hasMany(MwPricePlanOrder::class, 'ad_id');
	}

	public function mw_report_listings()
	{
		return $this->hasMany(MwReportListing::class, 'ad_id');
	}

	public function mw_statistics_pages()
	{
		return $this->hasMany(MwStatisticsPage::class, 'pid');
	}

	public function mw_statistics()
	{
		return $this->hasMany(MwStatistic::class, 'id', 'id');
	}

	public function mw_translate_relations()
	{
		return $this->hasMany(MwTranslateRelation::class, 'ad_id');
	}

	public function mw_user_packages_utilities()
	{
		return $this->hasMany(MwUserPackagesUtility::class, 'ad_id');
	}

    /**
     * Get property counts by status and type for a specific user
     *
     * @param int $userId
     * @return array
     */
    public static function getCounter($userId)
    {
        try {
           
            $approved = self::where('user_id', $userId)
                ->where('status', 'A')
                ->where('isTrash', '0')
                ->count();
                
            
            $sale = self::where('user_id', $userId)
                ->where('status', 'A')
                ->where('isTrash', '0')
                ->where('section_id', 1) 
                ->count();
                

            $rent = self::where('user_id', $userId)
                ->where('status', 'A')
                ->where('isTrash', '0')
                ->where('section_id', 2) 
                ->count();
                
            return [
                'approved' => $approved,
                'sale' => $sale,
                'rent' => $rent
            ];
        } catch (\Exception $e) {
            Log::error('Error in getCounter method: ' . $e->getMessage());
            return [
                'approved' => 0,
                'sale' => 0,
                'rent' => 0
            ];
        }
    }

	
}
