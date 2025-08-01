<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MwOption
 *
 * @property string $category
 * @property string $key
 * @property string $value
 * @property bool $is_serialized
 * @property Carbon $date_added
 * @property Carbon $last_updated
 *
 * @package App\Models
 */
class MwOption extends Model
{
    protected $connection = 'mysql_legacy';
	protected $table = 'mw_option';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'is_serialized' => 'bool',
		'date_added' => 'datetime',
		'last_updated' => 'datetime'
	];

	protected $fillable = [
		'value',
		'is_serialized',
		'date_added',
		'last_updated'
	];

	/**
	 * Get option value by category and key
	 * @param string $category
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function getOptionValue($category, $key, $default = null)
	{
		try {
			$option = self::where('category', $category)
						  ->where('key', $key)
						  ->first();
			
			if ($option) {
				// Handle serialized data
				return $option->is_serialized ? unserialize($option->value) : $option->value;
			}
			
			return $default;
		} catch (\Exception $e) {
			return $default;
		}
	}

	/**
	 * Get multiple options by category
	 * @param string $category
	 * @return array
	 */
	public static function getOptionsByCategory($category)
	{
		try {
			$options = self::where('category', $category)->get();
			$result = [];
			
			foreach ($options as $option) {
				$value = $option->is_serialized ? unserialize($option->value) : $option->value;
				$result[$option->key] = $value;
			}
			
			return $result;
		} catch (\Exception $e) {
			return [];
		}
	}

	/**
	 * Get company information from system.common options
	 * @return array
	 */
	public static function getCompanyInfo()
	{
		try {
			// Get all system.common options
			$systemOptions = self::getOptionsByCategory('system.common');
			
			// Default company info
			$companyInfo = [
				'name' => 'Ajmaan Properties',
				'address' => 'Business Bay, Dubai, UAE',
				'city' => 'Dubai, United Arab Emirates',
				'phone' => '+971-4-XXXXXXX',
				'email' => 'info@ajmaan.com',
				'website' => 'www.ajmaan.com'
			];

			// Map database keys to company info keys
			$keyMapping = [
				'site_name' => 'name',
				'company_name' => 'name',
				'company_address' => 'address',
				'address' => 'address',
				'company_city' => 'city',
				'city' => 'city',
				'company_phone' => 'phone',
				'phone' => 'phone',
				'contact_phone' => 'phone',
				'company_email' => 'email',
				'email' => 'email',
				'contact_email' => 'email',
				'company_website' => 'website',
				'website' => 'website',
				'site_url' => 'website'
			];

			// Override defaults with database values
			foreach ($keyMapping as $dbKey => $infoKey) {
				if (isset($systemOptions[$dbKey]) && !empty($systemOptions[$dbKey])) {
					$companyInfo[$infoKey] = $systemOptions[$dbKey];
				}
			}

			return $companyInfo;
		} catch (\Exception $e) {
			// Return default values if there's an error
			return [
				'name' => 'Ajmaan Properties',
				'address' => 'Business Bay, Dubai, UAE',
				'city' => 'Dubai, United Arab Emirates',
				'phone' => '+971-4-XXXXXXX',
				'email' => 'info@ajmaan.com',
				'website' => 'www.ajmaan.com'
			];
		}
	}

	/**
	 * Generic method to get nested option value using dot notation
	 * Example: getNestedOption('system.common.site_name') 
	 * @param string $path (format: category.key or category.subcategory.key)
	 * @param mixed $default
	 * @return mixed
	 */
	public static function getNestedOption($path, $default = null)
	{
		try {
			$parts = explode('.', $path);
			
			if (count($parts) < 2) {
				return $default;
			}

			// Handle different path formats
			if (count($parts) == 2) {
				// Format: category.key
				return self::getOptionValue($parts[0], $parts[1], $default);
			} elseif (count($parts) == 3) {
				// Format: category.subcategory.key
				$category = $parts[0] . '.' . $parts[1];
				$key = $parts[2];
				return self::getOptionValue($category, $key, $default);
			}
			
			return $default;
		} catch (\Exception $e) {
			return $default;
		}
	}

	/*
	 * Usage Examples:
	 * 
	 * 1. Get single option value:
	 *    MwOption::getOptionValue('system.common', 'site_name', 'Default Site')
	 * 
	 * 2. Get all options in a category:
	 *    MwOption::getOptionsByCategory('system.common')
	 * 
	 * 3. Get company information (uses system.common category):
	 *    MwOption::getCompanyInfo()
	 * 
	 * 4. Get nested option using dot notation:
	 *    MwOption::getNestedOption('system.common.site_name', 'Default')
	 *    MwOption::getNestedOption('system.common.company_email', 'info@example.com')
	 * 
	 * 5. Get specific company info field:
	 *    $siteName = MwOption::getNestedOption('system.common.site_name', 'Ajmaan Properties')
	 *    $companyEmail = MwOption::getNestedOption('system.common.company_email', 'info@ajmaan.com')
	 */
}
