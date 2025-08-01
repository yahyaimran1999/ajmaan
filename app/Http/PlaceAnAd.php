<?php

/**
 * This is the model class for table "mw_place_an_ad".
 *
 * The followings are the available columns in table 'mw_place_an_ad':
 * @property integer $id
 * @property integer $section_id
 * @property integer $category_id
 * @property integer $sub_category_id
 * @property string $ad_title
 * @property string $ad_description
 * @property integer $engine_size
 * @property string $killometer
 * @property integer $model
 * @property string $price
 * @property string $year
 * @property integer $city
 * @property integer $neighbourhood
 * @property string $mobile_number
 * @property integer $employment_type
 * @property string $compensation
 * @property integer $education_level
 * @property integer $experience_level
 * @property string $skills
 * @property string $area
 * @property integer $bathrooms
 * @property integer $bedrooms
 * @property integer $user_id
 * @property string $added_date
 * @property string $modified_date
 * @property integer $priority
 * @property string $isTrash
 * @property string $status
 * @property string $slug
 */
class PlaceAnAd extends ActiveRecord
{
	public $amenities;
	public $location;
	public $location_longitude;
	public $location_latitude;
	public $values;
	public $keyword;
	public $name;
	public $email;
	public $user_name;
	public $floor_plan;
	public $random;
	public $company_name;
	public $contact_email;
	public $no_of_units;
	public $no_of_stories;
	public $puser_id;
	public $rera;
	public $total_reviews;
	public $add_property_types;
	public $faq;
	public $nearest_schools;
	public $pro_type_images;
	const FEATURED_CONDITION = " t.isTrash='0' and featured='Y'  and t.status='A'   ";
	const LATEST_CONDITION   = " t.isTrash='0'  and t.status='A'  ";
	const FEATURED_ORDER     = " featured='Y' desc,t.id desc ";
	const LATEST_ORDER       = " t.id desc";
	const SALE_ID      	  = 1;
	const RENT_ID       	  = 2;
	const NEW_ID       	  = 3;
	const APARTMENT_FOR_SALE = 30;
	const APARTMENT_FOR_RENT = 36;
	const WAREHOUSE_ID       = 96;
	const VILLA_FOR_SALE 	  = 31;
	const VILLA_FOR_RENT     = 38;
	const OFFICE_FOR_SALE 	  = 33;
	const OFFICE_FOR_RENT    = 39;
	const COMMON_CONDITION   = " t.isTrash='0' and t.status='A'  ";
	const COMMON_ORDER       = " featured='Y' desc,t.id desc ";
	const BEDROOM_PLUS       = "10";
	const BATHROOM_PLUS      = "3";
	const BUILDING_ID        = "101";
	const VILLA_ID        = "31";
	const AJMAN_ID        = "55371";

	public $_notMadatory;
	public $dynamicArray;
	public $poplar_area;
	public $poplar_area_title;
	public $_not_development;
	public $order_by;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mw_place_an_ad';
	} 
	public $_notMadatory1;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules1 =  array();
		$tags = Yii::app()->tags;
		if ($this->dynamic) {

			$rules1[] =   array(array_diff((array)$this->dynamicArray, (array) $this->_notMadatory), 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'));
			$rules1[] =   array((array) $this->_notMadatory, 'safe');
		}
		if (defined('NEW_FORM')) {
			 
			$rules1[] =   array($this->dynamicArray1(), 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'));
			$rules1[] =   array((array) $this->_notMadatory1, 'safe');
			$rules1[] =   array(['location_latitude'], 'required',  'message' => 'Location ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'));
			//$rules1[] =   array(['image'], 'required',  'message' => 'Location ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'));
		
		}
		$rules  =  array(
			array('section_id,listing_type, category_id,user_id ,image,ad_title,ad_description,mobile_number,city', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.')),
			array('section_id, category_id, sub_category_id, country, state, city, district,   user_id, priority,    community_id, sub_community_id,RetUnitCategory', 'numerical', 'integerOnly' => true,  'message' => '{attribute} ' . $tags->getTag('must-be-a-number', 'must be a number.')),
			array('ad_title,ad_title_ar, slug,   area_location, property_name, PrimaryUnitView,      FloorNo,             mandate', 'length', 'max' => 250),
			array(' currency_abr, area_measurement', 'length', 'max' => 10),
			array('price', 'length', 'max' => 14),
			array('ad_uid', 'unique'),
			array('price,RentPerMonth,Rent', 'length', 'max' => 14),
			array('mobile_number', 'length', 'max' => 15),
			array('isTrash,  dynamic,    featured, xml_inserted,recmnded,promoted,is_new', 'length', 'max' => 1),
			array('location_latitude, location_longitude, salesman_email,meta_title', 'length', 'max' => 150),
			array('meta_keywords,meta_description', 'length', 'max' => 250),
			//array('xml_type', 'length', 'max'=>2),
			//array('xml_reference', 'length', 'max'=>25),
			array('code, RefNo', 'length', 'max' => 20),
			array('add_property_types', 'validateAddProperty'),
			array('price', 'validatePrice'),
			array('plot_area, builtup_area', 'numerical'),
			array('plot_area, builtup_area', 'length', 'max' => 10),
			array('parking', 'length', 'max' => 5),
			array('no_of_units,no_of_stories', 'numerical', 'integerOnly' => true,  'message' => '{attribute} ' . $tags->getTag('must-be-a-number', 'must be a number.')),
			array('no_of_units,no_of_stories', 'length', 'max' => 4),
			array('city,active_customers,car_parking,street_address,pantry,kitchen,payment_plan,youtube_url,offering,ref_id,types_pdf,floor_pdf,payment_pdf,broucher,faq,nearest_schools,c1,contact_name,contact_email,l_architect,architect,d_logo,d_description,d_name,architect,contractor,p_o_r', 'safe'),
			array('youtube_url', 'validateYoutubeUrl'),
			array('contact_email', 'email'),
			array('_notMadatory1', 'safe'),
			array('faq', 'validateAddFaq2'),
			array('nearest_schools', 'validateSchool'),
			array('furnished,maid_room', 'length', 'max' => 5),
			array('rera_no', 'length', 'max' => 50),
			array('bedrooms,bathrooms', 'numerical', 'integerOnly' => true),
			array('minPrice,maxPrice,type_of,cat', 'numerical',  'message' => '{attribute} ' . $tags->getTag('must-be-a-number', 'must be a number.')),
			array('price', 'numerical',  'message' => '{attribute} ' . $tags->getTag('must-be-a-number', 'must be a number.')),
			array('random,amenities,construction_status,poplar_area,developer_id,p_allowed,channel,h_c,s_r,s_date,s_r1,s_r2,city_id2,promoted_list', 'safe'),
			array('dynamicArray', 'unsafe'),
			array('nearest_metro,nearest_railway,category_name,community_name,country_name,user_name,keyword,maxSqft,minSqft,sort,year_built,floor_plan,user_trash,ad_description_ar,ad_title_ar,city_2,city_3,city_4,order_by,pro_type_images', 'safe'),
			array('modified_date,featured, xml_listing_date, xml_update_date, expiry_date,property_overview,LocalAreaAmenitiesDesc,RecommendedProperties,PropertyID,status,rent_paid,name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, section_id, category_id,city_id2, sub_category_id, ad_title, ad_description, price, country, state, city, district, mobile_number, bathrooms, bedrooms, user_id, added_date, modified_date, priority, isTrash, status,occupant_status, slug, image, dynamic, dynamicArray, location_latitude, location_longitude, featured, area_location, xml_inserted, xml_pk, xml_type, xml_reference, xml_listing_date, xml_update_date, code, RefNo, community_id, sub_community_id, property_name, builtup_area, PrimaryUnitView,     FloorNo, HandoverDate,     parking,   salesman_email, expiry_date,       mandate, currency_abr, area_measurement, PDFBrochureLink,property_overview,ReraStrNo', 'safe', 'on' => 'search'),
		);
		return array_merge($rules1, $rules);
	}
	public function validateYoutubeUrl($attribute, $params)
	{
		if (!empty($this->youtube_url)) {
			$pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)([A-Za-z0-9_-]{11})(\?.*)?$/';

			// Use filter_var to check if the URL is valid
			if (filter_var($this->youtube_url, FILTER_VALIDATE_URL) && preg_match($pattern, $this->youtube_url)) {
				 
			}else{
			$this->addError('youtube_url',  'Enter valid youtube URL' );
			}
		}
	}
	public function dynamicArray1(){
		if(defined('DYNAMIC_ARRAY')){
			return DYNAMIC_ARRAY; 
		}
		return []; 
	}
	public $s_r1;
	public $s_r2;
	public function validateAddFaq2($attribute, $params)
	{
		$post =  Yii::App()->request->getPost('faq', array());
		$errorFound = false;
		if (!empty($post)) {


			for ($i = 0; $i < sizeOf($post['title']); $i++) {
				if (empty($post['title'][$i])) {
					$errorFound = true;
				}
			};
			if ($errorFound) {
				$this->addError($attribute,  Yii::t('app', 'Please fill all row values.', array('{attribute}' => $this->getAttributeLabel($attribute))));
			}
		}
	}
	public function validateSchool($attribute, $params)
	{
		$post =  Yii::App()->request->getPost('nearest_schools', array());
		$errorFound = false;
		if (!empty($post)) {  
			for ($i = 0; $i < sizeOf($post['title']); $i++) {
				if (empty($post['title'][$i])) {
					$errorFound = true;
				}
			};
			if ($errorFound) {
				$this->addError($attribute,  Yii::t('app', 'Please fill all row values.', array('{attribute}' => $this->getAttributeLabel($attribute))));
			}
		}
	}
	public function getReferenceNumberTitleLink()
	{
		return CHtml::link($this->ReferenceNumberTitle, $this->PreviewUrlTrash, ['target' => '_blank']);
	}
	public function getReferenceNumberTitle()
	{
		if (empty($this->id)) {
			$criteria = new CDbCriteria;
			$criteria->select = 'max(id) as id ';
			$newId = PlaceAnAd::find($criteria);
			$newId = $newId->id + 1;
			$val = 'AP-' . str_pad($newId, 5, "0", STR_PAD_LEFT);
			return $val;
		}
		$val = 'AP-' . str_pad($this->id, 5, "0", STR_PAD_LEFT);
		return $val;
	}
	public function getPrimaryField()
	{
		return 'ad_id';
	}
	public function getfullName()
	{
		return $this->ad_title;
	}
	public function   is_rtl($string)
	{
		$rtl_chars_pattern = '/[\x{0590}-\x{05ff}\x{0600}-\x{06ff}]/u';
		return preg_match($rtl_chars_pattern, $string);
	}
	public function getAdDescription()
	{
		if (defined('LANGUAGE')) {
			$lan = LANGUAGE;
		} else {
			$lan = 'en';
		}
		switch ($lan) {
			case 'en':
				return $this->ad_description;
				break;
			case 'ar':
				return    !empty($this->ad_description_ar) ? $this->ad_description_ar : $this->ad_description;
				break;
			default:
				return $this->ad_description;
				break;
		}
	}

	public function dynamicFields()
	{

		return array(
			'builtup_area' => $this->getAttributeLabel('builtup_area'),
			//'plot_area' => $this->getAttributeLabel('plot_area'),
			'bathrooms' => $this->getAttributeLabel('bathrooms'),
			'bedrooms' => $this->getAttributeLabel('bedrooms'),
			//'price'=>  $this->getAttributeLabel('price'),
			'FloorNo' =>  $this->getAttributeLabel('FloorNo'),
			'total_floor' =>  $this->getAttributeLabel('total_floor'),
			'balconies' =>  $this->getAttributeLabel('balconies'),
			'parking' =>  $this->getAttributeLabel('parking'),
			'PrimaryUnitView' =>  $this->getAttributeLabel('PrimaryUnitView'),
			'construction_status' =>  $this->getAttributeLabel('construction_status'),
			'transaction_type' =>  $this->getAttributeLabel('transaction_type'),
			'furnished' =>  $this->getAttributeLabel('furnished'),
			'maid_room' =>  $this->getAttributeLabel('maid_room'),
			'year_built' =>  $this->getAttributeLabel('year_built'),
			'floor_plan' =>  $this->getAttributeLabel('floor_plan'),
			//'rera_no'=>  $this->getAttributeLabel('rera_no'),
			'expiry_date' =>  $this->getAttributeLabel('expiry_date'),
			'mandate' =>  $this->getAttributeLabel('mandate'),
			'car_parking' =>  $this->getAttributeLabel('car_parking'),
			'pantry' =>  $this->getAttributeLabel('pantry'),
			'kitchen' =>  $this->getAttributeLabel('kitchen'),
			'no_of_units' => $this->getAttributeLabel('no_of_units'),
			'no_of_stories' => $this->getAttributeLabel('no_of_stories'),
		);
	}
	public function getExcludeArray($fields)
	{
		$ar = $this->dynamicFields();
		if (empty($fields)) {
			return array_keys($ar);
		} else {
			foreach ($fields as $k => $v) {

				if (array_key_exists($k, $ar)) {

					unset($ar[$k]);
				}
			}
			return array_keys($ar);
		}
	}
	public function getExcludeArrayFormArray($fields)
	{
		$ar = $this->dynamicFields();
		if (empty($fields)) {
			return $ar;
		} else {
			foreach ($fields as $k => $v) {

				if (array_key_exists($k, $ar)) {

					unset($ar[$k]);
				}
			}
			return  $ar;
		}
	}
	public function checkFieldsShow1($field)
	{
		return true;
	}
	public function checkFieldsShow($field)
	{

		if (in_array($field, (array)$this->dynamicArray)) {
			return true;
		}
		return false;
	}
	public function notMadatory()
	{
		return array();
		return array(
			'PrimaryUnitView' => 'PrimaryUnitView',
			'community_id' => 'community_id',
			'bathrooms' => 'bathrooms',
			'bedrooms' => 'bedrooms',
			'parking' => 'parking',
			'FloorNo' => 'FloorNo',
			'property_name' => 'property_name',
			'occupant_status' => 'occupant_status',
		);
	}

	public function dynamicFieldsForPropertyForsale()
	{
		return array(
			'section_id' => 'Section',
			'category_id' => 'Category',
			'sub_category_id' => 'Sub Category',
			'body_type' => 'Body Ttype',
			'bodycondition' => 'Body Condition',
			'bedrooms' => 'Bedrooms',
			'compensation' => 'Compensation',
			'current_occupation' => 'Current Occupation',
			'color' => 'Color',
			'cylinders' => 'Cylinders',
			'door' => 'Door',
			'education_level' => 'Education Level',
			'experience_level' => 'Experience Level',
			'employment_type' => 'Employment Type',
			'fuel_type' => 'Fuel Type',
			'height' => 'Height',
			'killometer' => 'Killometer',
			'marital_status' => 'Marital Status',
			'mechanicalcondition' => 'Mechanical Condition',
			'model' => 'Model',
			'mother_tongue' => 'Mother Tongue',
			//'price'=>'Price',
			'religion_id' => 'Religion',
			'skills' => 'Skills',
			'year' => 'Year',
			'warranty' => 'Warranty',




		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'adAmenities' => array(self::HAS_MANY, 'AdAmenities', 'ad_id'),
			'faqList' => array(self::HAS_MANY, 'AdFaq', 'ad_id'),
			'schoolList' => array(self::HAS_MANY, 'AdNearestSchool', 'ad_id'),
			'adImages' => array(self::HAS_MANY, 'AdImage', 'ad_id', 'on' => "adImages.isTrash='0'"),
			'adFloorPlans' => array(self::HAS_MANY, 'AdFloorPlan', 'ad_id'),
			'c1t' => array(self::BELONGS_TO, 'Master', 'c1'),
			'adImagesAll' => array(self::HAS_MANY, 'AdImage', 'ad_id'),
			'pTypes' => array(self::HAS_MANY, 'AdPropertyTypes', 'ad_id'),
			'singleAdImage' => array(self::HAS_ONE, 'AdImage', 'ad_id', 'on' => "singleAdImage.isTrash='0' and singleAdImage.status='A'", "order" => "singleAdImage.priority"),
			'adImagesOnView' => array(self::HAS_MANY, 'AdImage', 'ad_id', 'on' => "adImagesOnView.isTrash='0' and adImagesOnView.status='A'", "order" => "adImagesOnView.priority"),
			'adImagesOnView2' => array(self::HAS_MANY, 'AdImage', 'ad_id', 'on' => "adImagesOnView2.isTrash='0'", "order" => "adImagesOnView2.status ='A' desc,adImagesOnView2.status='A' desc,adImagesOnView2.priority"),
			'adImagesOnView3' => array(self::HAS_MANY, 'AdImage', 'ad_id', 'condition' => "adImagesOnView3.isTrash='0' and adImagesOnView3.status='I' "),
			'adImagesOnView4' => array(self::HAS_MANY, 'AdImage', 'ad_id', 'condition' => "adImagesOnView4.isTrash='0'   "),
			//           'adImagesOnView3' => array(self::HAS_MANY, 'AdImage', 'ad_id','on'=>"adImages.isTrash='0'"),
			'subCategory' => array(self::BELONGS_TO, 'Subcategory', 'sub_category_id'),
			'section' => array(self::BELONGS_TO, 'Section', 'section_id'),
			'ADIMAGE' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'category' => array(self::BELONGS_TO, 'Category', 'category_id'),
			'category2' => array(self::BELONGS_TO, 'MainCategory', 'listing_type'),
			'listT' => array(self::BELONGS_TO, 'Category', 'listing_type'),
			'subcommunity' => array(self::BELONGS_TO, 'SubCommunity', 'sub_community_id'),
			'stateLocation' => array(self::BELONGS_TO, 'States', 'state'),
			'country0' => array(self::BELONGS_TO, 'Countries', 'country'),
			'state0' => array(self::BELONGS_TO, 'States', 'state'),
			'city0' => array(self::BELONGS_TO, 'City', 'city'),
			'district0' => array(self::BELONGS_TO, 'District', 'district'),
			'Marital' => array(self::BELONGS_TO, 'MaritalStatus', 'marital_status'),
			'Religion' => array(self::BELONGS_TO, 'Religion', 'religion_id'),
			'EngineSize' => array(self::BELONGS_TO, 'EngineSize', 'engine_size'),
			'Model' => array(self::BELONGS_TO, 'VehicleModel', 'model'),
			'EmploymentType' => array(self::BELONGS_TO, 'EmploymentType', 'employment_type'),
			'EducationLevel' => array(self::BELONGS_TO, 'EducationLevel', 'education_level'),
			'Occupation' => array(self::BELONGS_TO, 'Occupation', 'current_occupation'),
			'Experience' => array(self::BELONGS_TO, 'Experience', 'experience_level'),
			'Colors' => array(self::BELONGS_TO, 'Color', 'color'),
			'Customer' => array(self::BELONGS_TO, 'ListingUsers', 'user_id'),
			'Doors' => array(self::BELONGS_TO, 'Door', 'door'),
			'Bodyconditions' => array(self::BELONGS_TO, 'Bodycondition', 'bodycondition'),
			'Mechanicalconditions' => array(self::BELONGS_TO, 'Mechanicalcondition', 'mechanicalcondition'),
			'FuelTypes' => array(self::BELONGS_TO, 'FuelType', 'fuel_type'),
			'BodyTypes' => array(self::BELONGS_TO, 'BodyType', 'body_type'),
		);
	}
	public function getPunit()
	{
		if ($this->section_id == '1') {
			return 'AED' . (int) ($this->price / $this->builtup_area);
		}
	}
	public function getPets()
	{
		if ($this->section_id == '2' and $this->listing_type == '118') {
			switch ($this->p_allowed) {
				case '1':
					return 'Yes';
					break;
				case '0':
					return 'No';
					break;
				default:
					return 'Not Mentioned';
					break;
			}
		}
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		static $_ars;
		if ($_ars != null) {
			return $_ars;
		}
		$label1 =  array();
		$tags = $this->mTag();

		$label2 =  array(
			'p_o_r' => $tags->getTag('ask_for_price', 'Ask for Price'),
			'punit' => $tags->getTag('price/sq._ft.', 'Price/Sq. Ft.'),
			'p_allowed' => $tags->getTag('pets_allowed', 'Pets Allowed'),
			'id' => 'ID',
			'l_architect' => $tags->getTag('landscape_architect', 'Landscape Architect'),
			'architect' => $tags->getTag('architect', 'Architect'),
			'd_name' => $tags->getTag('developer_name', 'Developer Name'),
			'd_logo' => $tags->getTag('developer_logo', 'Developer Logo'),
			'd_description' => $tags->getTag('developer_description', 'Developer Description'),
			'c1' => $tags->getTag('completion_year', 'Completion Year'),
			'payment_pdf' => $tags->getTag('upload_payment_plan_pdf', 'Upload payment plan pdf'),
			'floor_pdf' => $tags->getTag('upload_floor_plan_pdf', 'Upload floor plan pdf'),
			'broucher' => $tags->getTag('brochure', 'Brochure'),
			'types_pdf' => $tags->getTag('upload_property_types_pdf', 'Upload property types pdf'),
			'section_id' => $tags->getTag('section', 'Section'),
			'reference' => $tags->getTag('reference', 'Reference'),
			'car_parking' => $tags->getTag('car_parking', 'Car Parking'),
			'transaction_type' => $tags->getTag('transaction-type', 'Transaction Type'),
			'furnished' => $tags->getTag('furnished', 'Furnished'),
			'maid_room' => $tags->getTag('maid-room', 'Maid Room'),
			'year_built' => $tags->getTag('year-built', 'Year Built'),
			'rent_pad' => $tags->getTag('rent_paid_on', 'Rent Paid On'),
			'category_id' => $tags->getTag('property_type', 'Property Type'),
			'sub_category_id' => $tags->getTag('sub_category', 'Sub Category'),
			'ad_title' => $this->titleLabel,
			'ad_title_ar' => $this->titleLabelAr,
			'ad_description' => $this->DescriptionLabel,
			'ad_description_ar' => $this->DescriptionLabelAr,
			'statistics' => $tags->getTag('statistics', 'Statistics'),
			's_r1' => 'Sold',
			's_r2' => 'Rented',
			//'engine_size' => 'Engine Size',
			//'killometer' => 'Killometer',
			//  'model' => 'Model',
			//  'price' => 'Price',
			'year' =>  $tags->getTag('year', 'Year'),
			'country' => $tags->getTag('country', 'Country'),
			'state' => $tags->getTag('state', 'State'),
			'mobile_number' => $tags->getTag('phone_number', 'Phone Number'),
			'amenities' => $tags->getTag('extra_features', 'Extra Features'),

			'area' => $tags->getTag('area_(sqft)', 'Area (sqft)'),
			'bathrooms' => $tags->getTag('bathrooms', 'Bathrooms'),
			'bedrooms' => $tags->getTag('bedrooms', 'Bedrooms'),

			'user_id' => 'Customer',
			'added_date' => 'Added Date',
			'date_added' => $tags->getTag('date', 'Date'),
			'status' => $tags->getTag('status', 'Status'),
			'modified_date' => 'Modified Date',
			'expiry_date' => $tags->getTag('available_on', 'Available on'),
			'mandate' =>  $tags->getTag('developer', 'Developer'),
			'developer_id' =>  $tags->getTag('developer', 'Developer'),

			'priority' => 'Priority',
			'isTrash' => 'Is Trash',
			//'status' => 'Status',
			'slug' => 'Slug',
			'area_location' => 'Location',
			'community_id' => $tags->getTag('community', 'Community'),
			'sub_community_id' => $tags->getTag('sub-community', 'Sub Community'),
			'builtup_area_sqft' => $tags->getTag('size_(_sq.ft._)', 'Size ( Sq.Ft. )'),
			'nearest_metro' => $tags->getTag('nearest-metro', 'Nearest metro stations'),
			'nearest_railway' => $tags->getTag('nearest-school', 'Nearest school'),
			'builtup_area' => $tags->getTag('size', 'Size'),
			'plot_area' => $tags->getTag('plot-area', 'Plot area in  Sq. Ft.'),
			//	'FloorNo'=>$tags->getTag('floor-no','Floor No.'),
			'parking' => $tags->getTag('parking-available', 'Parking Available?'),
			'bathroom' => $tags->getTag('no._of_bathroom.', 'No. of Bathroom.'),
			'bedroom' =>  $tags->getTag('no._of_bedroom.', 'No. of Bedroom.'),
			'rera_no' => 'RERA Permit Number',
			'PrimaryUnitView' => $tags->getTag('property_views', 'Property Views'),
			'construction_status' => $tags->getTag('status', 'Status'),
			'image' => $tags->getTag('property-images', 'Property Images'),
			'floor_plan' => $tags->getTag('floor-plan', 'Upload Floor Plan'),
			'price' => $this->PriceLabel,
			'rent_paid' => $tags->getTag('rent_paid_on', 'Rent Paid On'),
			'FloorNo' => $tags->getTag('property_on_floor', 'Property on Floor'),
			'total_floor' => $tags->getTag('total_floors', 'Total Floors'),
			'balconies' => $tags->getTag('total_balconies', 'Total Balconies'),
			'listing_type' => $tags->getTag('listing_type', 'Listing Type'),
			'categoty_id' => $tags->getTag('categoty_id', 'Category'),
			'listing_type' => $tags->getTag('type', 'Type'),
			'country_name' => $tags->getTag('area', 'Area'),
			'developer_id' => $tags->getTag('developer', 'Developer'),
			'city_2' => $tags->getTag('community', 'Community'),
			'promoted' => $tags->getTag('hot', 'Hot'),
			'is_new' => $tags->getTag('premium', 'Premium'),
			'city_3' => $tags->getTag('tower', 'Tower'),
			'city_4' => $tags->getTag('tower_sub', 'Sub Tower'),
			'h_c' => 'Hide Contact',
			's_r' => 'Sold | Rented',
			'city' => $tags->getTag('city', 'City'),
			'recommended' => $tags->getTag('verified', 'Verified'),
			'year_built' => $this->section_id == '3' ? $tags->getTag('completion_year', 'Completion Year')  : $tags->getTag('year_built', 'Year Built'),
		);
		if (!empty($this->category->section_id) and $this->category->section_id == self::RENT_ID) {

			$label2['price']  = 'Rent';
		}
		$_ars = $label2;
		return 	$_ars;
		// return array_replace($label2,$label1);
		return array_merge($label2, $label1);
	}
	public function getTitleLabel()
	{
		$tags = Yii::app()->tags;
		switch ($this->section_id) {
			case '3':
				return $tags->getTag('project_/_development_title', 'Project / Development Title');
				break;
			default:
				return $tags->getTag('title_of_your_ad', 'Title of Your AD');
				break;
		}
	}
	public function getTitleLabelAr()
	{
		$tags = Yii::app()->tags;
		switch ($this->section_id) {
			case '3':
				return $tags->getTag('project_/_development_title_ar', 'Project / Development Title (arabic)');
				break;
			default:
				return $tags->getTag('title_of_your_ad_ar', 'Title of Your AD (arabic)');
				break;
		}
	}
	public function getDescriptionLabel()
	{
		$tags = Yii::app()->tags;
		switch ($this->section_id) {
			case '3':
				return $tags->getTag('about_project', 'About Project');
				break;
			default:
				return $tags->getTag('describe_your_properties', 'Describe your Properties');
				break;
		}
	}
	public function getDescriptionLabelAr()
	{
		$tags = Yii::app()->tags;
		switch ($this->section_id) {
			case '3':
				return $tags->getTag('about_project_ar', 'About Project (Arabic)');
				break;
			default:
				return $tags->getTag('describe_your_properties_ar', 'Describe your Properties  (Arabic)');
				break;
		}
	}
	public function getPriceLabel()
	{
		$tags = Yii::app()->tags;
		switch ($this->section_id) {
			case '3':
				return $tags->getTag('starting_price', 'Starting Price');
				break;
			default:
				return $tags->getTag('price', 'Price');
				break;
		}
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function getExpiryDateTitle()
	{
		if (!empty($this->expiry_date)) {
			return date('d-M-Y', strtotime($this->expiry_date));
		}
	}
	public $licence_no;
	public $broker_no;
	public function getCurrencyTitle()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 'cn.code';
		$criteria->join  = ' LEFT JOIN  {{currency}} cn ON cn.currency_id = t.default_currency ';
		$criteria->condition = 't.country_id =:country ';
		$criteria->params[':country'] = $this->country;
		$default_currency = Countries::model()->find($criteria);
		if ($default_currency) {
			return $default_currency->code;
		} else {
			return '';
		}
	}
	public function getMainTitle()
	{
		switch ($this->user_type) {
			case 'D':
				return $this->company_name;
				break;
			case 'K':
				return $this->company_name;
				break;
			default:
				return  $this->first_name . ' ' . $this->last_name;
				break;
		}
	}
	public function getUserUrl()
	{
		return CHtml::link($this->MainTitle, '#', array('class' => $this->user_trash == '1' ? 'covr' : ''));
	}
	public function getUserDetailUrl()
	{
		if ($this->user_type == 'A') {
			return Yii::app()->createUrl('user_listing/detail', array('slug' => $this->user_slug));
		} else if (in_array($this->user_type, array('D', 'K'))) {

			return Yii::app()->createAbsoluteUrl('real-estate-agencies/' . $this->user_slug);
		}

		return '#commin';
	}
	public function getUserNaame()
	{
		return  $this->first_name . ' ' . $this->last_name;
	}
	public $first_name;
	public $last_name;
	public $slug;
	public $u_slug;
	public $country_name;
	public $state_name;
	public $user_trash;
	public $active_customers;
	public function getSmallDate()
	{
		return date('d m,Y', strtotime($this->dateAdded));
	}
	public function getCountryName()
	{
		return $this->country_name . '/ ' . $this->state_name;
	}
	public function getCountryNameSection()
	{
		return  $this->city_name;
	}

	public $ref_id;
	public function getRefreshOrders()
	{
		return '(CASE 
        WHEN DATE(t.refresh_date) = CURDATE() THEN 1 
        ELSE 0 
    	END) = "1" desc';
	}
	public function getHotOrders()
	{
		return '(CASE 
        WHEN COALESCE(t.promoted, "0") = "1" THEN 1 
        WHEN COALESCE(t.hot2, 0) = "Y" AND t.hot_e >= CURDATE() THEN 1 
        ELSE 0 
    	END) = "1" desc';
	}
	public function getFetauredOrders(){
		return '(CASE 
        WHEN COALESCE(t.featured, "N") = "Y" THEN 1 
        WHEN COALESCE(t.featured_e, 0) = 1 AND t.f_e_d >= CURDATE() THEN 1 
        ELSE 0 
    	END) = "1" desc';
	}
	public function getFetauredQuery()
	{
		return '
    ,CASE 
        WHEN COALESCE(t.featured, "N") = "Y" THEN 1 
        WHEN COALESCE(t.featured_e, 0) = 1 AND t.f_e_d >= CURDATE() THEN 1 
        ELSE 0 
    END AS featured2,
    
    CASE 
        WHEN t.featured_e IS NULL AND t.featured = "Y" THEN "unlimited" 
        WHEN t.featured_e = "1" AND TIMESTAMPDIFF(HOUR, NOW(), t.f_e_d) > 0 AND t.f_status = "A" 
        THEN TIMESTAMPDIFF(DAY, NOW(), t.f_e_d) 
        ELSE 0 
    END AS featured_days_remaining';
	}
	public function getHotQuery()
	{
		return '
		,CASE
		WHEN COALESCE(t.promoted, "1") = "1" THEN 1
		WHEN COALESCE(t.hot2, 0) = "Y" AND t.hot_e >= CURDATE() THEN 1
		ELSE 0
		END AS promoted2,

		CASE
		WHEN t.hot2 IS NULL AND t.promoted = "1" THEN "unlimited"
		WHEN t.hot2 = "Y" AND TIMESTAMPDIFF(HOUR, NOW(), t.hot_e) > 0 AND t.h_status = "A"
		THEN TIMESTAMPDIFF(DAY, NOW(), t.hot_e)
		ELSE 0
		END AS hot_days_remaining';
	}
	public $city_id2;
	public $promoted_list;
	public function search($return = false)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->select = 't.*' . $this->FetauredQuery.''. $this->HotQuery.',usr.first_name,usr.last_name,usr.user_type,usr.company_name,usr.isTrash as user_trash,usr.slug as u_slug,cn.country_name,st.state_name,cm.community_name,ct.city_name';

		$criteria->condition = '1';
		$criteria->compare('id', $this->id);
		if (!empty($this->ref_id)) {
			$ref_id = Yii::t('app', $this->ref_id, array('AP-' => ''));
			$criteria->compare('id', $ref_id);
		}
		$criteria->compare('section_id', $this->section_id);
		if (!empty($this->user_id)) {
			$criteria->condition .= ' and CASE WHEN usr.parent_user is NOT NULL THEN (usr.parent_user = :thisusr or   t.user_id = :thisusr )   ELSE     t.user_id = :thisusr  END ';
			$criteria->params[':thisusr'] = (int) $this->user_id;
		}
		if (!empty($this->s_r1) || !empty($this->s_r2)) {
			if (!empty($this->s_r1) and !empty($this->s_r2)) {
				$criteria->compare('s_r', 1);
			} else if (!empty($this->s_r1)) {
				$criteria->compare('s_r', 1);
				$criteria->compare('section_id', 1);
			} else if (!empty($this->s_r2)) {
				$criteria->compare('s_r', 1);
				$criteria->compare('section_id', 2);
			}
		}
		if(!empty($this->city_id2)){
			$criteria->compare('t.city', $this->city_id2);
		}
		if(!empty($this->promoted_list)){
			switch($this->promoted_list){
				case 'featured':
					$this->featured = '1'  ;
				break; 
				case 'hot':
					$this->promoted = '1'  ;
				break; 
				case 'premium':
					$this->is_new = '1'  ;
				break; 
			}
			
		}
		$criteria->compare('s_r', $this->s_r);
		$criteria->compare('h_c', $this->h_c);
		$criteria->compare('listing_type', $this->listing_type);
		$criteria->compare('category_id', $this->category_id);
		$criteria->compare('sub_category_id', $this->sub_category_id);
		$criteria->compare('ad_title', $this->ad_title, true);
		if(!empty($this->featured)){
			$criteria->condition .= ' and CASE  WHEN COALESCE(t.featured, "N") = "Y" THEN 1 
        WHEN COALESCE(t.featured_e, 0) = 1 AND t.f_e_d >= CURDATE() THEN 1 
        ELSE 0 
    END = "1" ';
		}
		if (!empty($this->promoted)) {
			$criteria->condition .= ' and (CASE 
        WHEN COALESCE(t.promoted, "0") = "1" THEN 1 
        WHEN COALESCE(t.hot2, 0) = "Y" AND t.hot_e >= CURDATE() THEN 1 
        ELSE 0 
    	END) = "1" ';
		}
		$criteria->compare('t.recmnded', $this->recmnded);
		$criteria->compare('t.bedrooms', $this->bedrooms);
		//$criteria->compare('t.promoted', $this->promoted);
		$criteria->compare('t.is_new', $this->is_new);
		if (Yii::app()->isAppName('frontend')) {
			
			$criteria->compare('t.isTrash', '0');
		} else {
			$criteria->select .= ' ,(SELECT image_name FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0"  limit 1  )   as ad_image ';

			$criteria->compare('t.isTrash', $this->isTrash);
		}
		$criteria->compare('t.status', $this->status);
		$criteria->join  = ' INNER JOIN {{listing_users}} usr on t.user_id = usr.user_id';
		$criteria->join  .= ' INNER JOIN {{countries}} cn on cn.country_id = t.country';
		$criteria->join  .= ' INNER JOIN {{states}} st on st.state_id = t.state';
		$criteria->join  .= ' LEFT JOIN {{city}} ct on ct.city_id = t.city';
		$criteria->join  .= ' LEFT  JOIN {{community}} cm on cm.community_id = t.community_id';
		if (!empty($this->_not_development)) {
			$criteria->condition .= ' and t.section_id != "3" ';
		}
		if (!empty($this->user_name)) {
			$criteria->compare(new CDbExpression('CONCAT(usr.first_name, " ", usr.last_name)'), $this->user_name, true);
			$criteria->compare('usr.company_name', $this->user_name, true, 'OR');
		}
		if (!empty($this->country_name)) {
			$criteria->compare('lower(ct.city_name)', strtolower($this->country_name), true);
		}
		if (Yii::app()->isAppName('backend')) {
			if (!empty($criteria->order_by)) {
				switch ($criteria->order_by) {
					case 'last_updated':
						$criteria->order = "t.last_updated desc";
						break;
				}
			} else {
				$criteria->order = "t.id desc";
			}
		} else {
			$criteria->order = "t.id desc,-t.priority desc , t.recmnded='1' desc ,   t.featured='Y' desc";
		}
		if (defined('DASHBOARD')) {
			$criteria->order = "t.id desc";
		}
		if (defined('OFFPLAN')) {
			$criteria->addInCondition('t.channel', array('O', 'B'));
		} else {
			$criteria->addInCondition('t.channel', array('A', 'B'));
		}
		if ($this->active_customers) {
			$criteria->condition .= ' and usr.status = "A" and usr.isTrash="0"';
		}

		if ($return) {
			return $criteria;
		}

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination'    => array(
				'pageSize'  => $this->paginationOptions->getPageSize(),
				'pageVar'   => 'page',
			),
		));
	}
	public function findproperty($id){
		$criteria = new CDbCriteria;
		$criteria->select = 't.*,st.state_name as state_name,ct.city_name as city_name,cat.category_name as category_name';
		$criteria->compare('t.isTrash', '0', true);
		$criteria->join  .= ' INNER JOIN {{states}} st on st.state_id = t.state';
		$criteria->join  .= ' LEFT JOIN {{city}} ct on ct.city_id = t.city';
		$criteria->join  .= ' left join {{category}} cat ON cat.category_id = t.category_id ';
		$criteria->compare('t.id',(int) $id); 
		return self::model()->find($criteria);
	}
	public function latestFiles($limit = 10)
	{
		$criteria = $this->search(1);
		$criteria->limit = $limit;
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination'    => false,
		));
	}
	public function statusArray()
	{
		return array("A" => "Active", "I" => "Inactive", "W" => "Waiting", 'R' =>'Rejected', 'D' => 'Draft');
	}
	public $s_count;
	public function getCounter($user_id)
	{
		$placead = new PlaceAnAd();
		$clone_criteria =   $placead->findAds(array('user_id' => $user_id, 'no_status'=>1), false, 1);

		$criteria = clone  $clone_criteria;
		$criteria->compare('t.status', 'W');
		$waiting = self::model()->count($criteria);


		$criteria = clone  $clone_criteria;
		$criteria->compare('t.status', 'A');
		$approved = self::model()->count($criteria);

		$criteria = clone  $clone_criteria;
		$criteria->compare('t.status', 'R');
		$rejected = self::model()->count($criteria);

		$criteria = clone  $clone_criteria;
		$criteria->compare('t.status', 'I');
		$inactive = self::model()->count($criteria);

		$criteria5 = clone  $clone_criteria;
		$criteria5->compare('t.status', 'D');
 
		$draft = self::model()->count($criteria5);


		$criteria = clone  $clone_criteria;
		$criteria->compare('t.status', 'A');
		$criteria->compare('t.section_id', '1');
		$sale = self::model()->count($criteria);


		$criteria = clone  $clone_criteria;
		$criteria->compare('t.status', 'A');
		$criteria->compare('t.section_id', '2');
		$rent = self::model()->count($criteria);

		return array('waiting' => $waiting, 'approved' => $approved, 'rejected' => $rejected, 'draft'=> $draft, 'inactive' => $inactive, 'sale'=> $sale,'rent'=>$rent);
	}

	public function constructionArray()
	{
		$tags = Yii::app()->tags;
		return array('R' => $tags->getTag('ready', 'Ready'), 'N' => $tags->getTag('off-plan', 'Off-Plan'));
	}
	public function TransactionType()
	{
		$tags = Yii::app()->tags;
		return array('N' => $tags->getTag('new_property', 'New Property'), 'R' => $tags->getTag('resale', 'Resale'));
	}
	public function getConstructionTitle()
	{
		$ar = $this->constructionArray();
		return isset($ar[$this->construction_status]) ?  $ar[$this->construction_status] : '';
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlaceAnAd the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	/*
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			array(
				'SlugBehavior' => array(
					'class' => 'common.models.SlugBehavior.SlugBehavior',
					'slug_col' => 'slug',
					'title_col' => 'ad_title',
					'overwrite' => true
				)
			)
		);
	}
		*/
	public function bathrooms()
	{
		$ar = array();

		for ($i = 0; $i <= 14; $i++) {
			$ar[] =  ($i == 14) ? '13+' : $i;
		}
		return $ar;
	}
	public function bedrooms()
	{
		$ar = array();
		$ar[15] =  'Studio';
		for ($i = 1; $i <= 14; $i++) {

			$ar[$i] =  ($i == 14) ? '13+' : $i;
		}
		return $ar;
	}
	public function year()
	{
		$ar = array();

		if ($this->section_id == '3') {
			$start = 2035;
		} else {
			$start = date("Y");
		}
		for ($i = $start; $i >= 1920; $i--) {
			$ar[$i] = $i;
		}
		return $ar;
	}
	public $package_used;
	public function beforeSave()
	{
		parent::beforeSave();
		$check_package = false; 
		if (Yii::app()->isAppName('frontend') and empty($this->status)) {
			$check_package = true; 
			$this->user_id = Yii::app()->user->getId();
			if ($this->status != 'D') {
				$this->status  = Yii::app()->options->get('system.common.frontend_default_ad_status', 'W');
			}
		}
		if (!Yii::app()->request->isAjaxRequest and $check_package ) {

			$result = $this->active_plan_check();
			if (!empty($result)) {
				Yii::app()->user->setState('package_expired',1);
				$this->status  = 'D' ; 
			}
		}

		if (defined('OFFPLAN') and empty($this->channel)) {
			$this->channel  = 'O';
		}
		if (!empty($this->expiry_date)) {
			$this->expiry_date = date('Y-m-d', strtotime($this->expiry_date));
		}
		if (!empty($this->s_date)) {
			$this->s_date = date('Y-m-d', strtotime($this->s_date));
		} else {
			$this->s_date = null;
		}
		if (Yii::app()->isAppName('frontend') and $this->isNewRecord and $this->status != 'D') {
			$packagemodel = Package::model()->userActivePackage(1, $this->user_id);
			if ($packagemodel) {
				$this->package_used = $packagemodel->uap_id;
			}
		}
		return true;
	}

	public function warranty()
	{
		return array("Y" => "Yes", "N" => "No", "D" => "Does not apply");
	}
	public function cylinders()
	{
		return array(
			"3" => "3 Cylinder",
			"4" => "4 Cylinder",
			"5" => "5 Cylinder",
			"6" => "6 Cylinder",
			"7" => "7 Cylinder",
			"8" => "8 Cylinder",
			"9" => "9 Cylinder",
			"10" => "10 Cylinder",
			"11" => "11 Cylinder",
			"12" => "12 Cylinder",
			"13" => "Unknown",
		);
	}
	public function getCylinders($id)
	{
		$ar =  $this->cylinders();
		if (isset($ar[$id])) {
			return $ar[$id];
		} else {
			return "Unknown";
		}
	}
	public function getWarranty($id)
	{
		$ar =  $this->warranty();
		if (isset($ar[$id])) {
			return $ar[$id];
		} else {
			return "No ";
		}
	}

	public function YesNoArray()
	{
		return array("Y" => "Yes", "N" => "No", "I" => "Inactive", "A" => "Active");
	}
	public function YesNoArray2()
	{
		$tags = Yii::app()->tags;
		return array("Y" => $tags->getTag('yes', "Yes"), "N" => $tags->getTag('no', "No"));
	}
	public function YesNo($val)
	{

		if ((string)$val == "Y") {
			return "'Featured1'";
		} else {
			return "'Featured'";
		}
	}
	public function search_2()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->compare('t.isTrash', '0', true);
		$criteria->order = "t.id desc";
		$criteria->with = array("adImagesOnView4");

		
		$criteria->together = true;
		$pageSize = (Yii::app()->request->getQuery("page_size")) ?  (int) Yii::app()->request->getQuery("page_size") : $pageSize = 20;
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => $pageSize,
			),

		));
	}

	public function getFeaturedAd()
	{
		$criteria = new CDbCriteria;
		$condition		 =  "t.isTrash='0' and t.status='A' and featured='Y' and t.country=:country";;
		$paramsArray[":country"] = Yii::app()->request->cookies['country']->value;
		//FOR SPECIFI STATES

		if (Yii::app()->request->cookies['state']->value != 0) {
			$condition		 .=  " and t.state=:state";
			$paramsArray[":state"] = Yii::app()->request->cookies['state']->value;
		}
		$criteria->condition = $condition;
		$criteria->params = $paramsArray;
		$criteria->order  = "t.id desc";
		$criteria->limit = "18";
		return  $this->findAll($criteria);
	}
	public function getFeaturedListings($limit)
	{
		$criteria			 	 =	new CDbCriteria;
		$condition			 =  self::FEATURED_CONDITION;
		$criteria->condition   =  $condition;
		$criteria->order  	 =  self::FEATURED_ORDER;
		$criteria->limit 		 =  $limit;
		return  $this->findAll($criteria);
	}

	public static function getCommonCondition()
	{
		return " t.isTrash='0' and t.status='A' and t.country=" . Countries::model()->getDefaultCountryId();
	}

	public function getRealtedAds($notinIds, $section, $limit)
	{
		$criteria			 	  =	 new CDbCriteria;
		$condition			  =  self::COMMON_CONDITION;
		if ($section != "") {
			$condition			 .=  Yii::t('ad', ' and t.section_id  = "{section}"', array('{section}' => $section));
		}
		$criteria->condition   =  $condition;
		$criteria->addNotInCondition('id',  $notinIds);
		$criteria->order  	 =  self::COMMON_ORDER;
		$criteria->limit 		 =  $limit;
		return  $this->findAll($criteria);
	}

	public function getLatestListings($limit, $category = null)
	{
		$criteria			 	 =	new CDbCriteria;
		$condition			 =  self::LATEST_CONDITION;
		if ($category != "") {
			$condition			 .=  " and t.section_id=:sec";
			$criteria->params[':sec'] = $category;
		}
		$criteria->condition   =  $condition;
		$criteria->order  	 =  self::FEATURED_ORDER;
		$criteria->limit 		 =  $limit;
		return  $this->findAll($criteria);
	}
	public function publish_property(){
		$notify = Yii::app()->notify;
		if($this->status=='D'){
			
			$packagemodel = Package::model()->userActivePackage(1, $this->user_id); 
			if ($packagemodel) {
				$this->package_used = $packagemodel->uap_id;
				//$this->updateByPk((int)$this->id,['status'=> Yii::app()->options->get('system.common.frontend_default_ad_status', 'W'), 'package_used'->$this->package_used]);
			} 
			if (!empty($this->package_used)) {
				if(defined('NEW_PACKAGE')){
					$utilityUpdate = new UserPackageUtility();
					$utilityUpdate->ad_id = (int)$this->id;
					$utilityUpdate->package_id = $this->package_used;
					$utilityUpdate->f_type =  'L';
					$utilityUpdate->save();
				}
				$this->updateByPk((int)$this->id, ['status' =>  'A', 'package_used'->$this->package_used]);
				try {
					
					$values =  "('{$this->package_used}','1')";
					$sql = "insert into  {{user_packages}} (id,used_ad) values {$values} ON DUPLICATE KEY UPDATE used_ad=used_ad+1";
					Yii::app()->db->createCommand($sql)->execute();
				} catch (Exception $e) {
				}
				if(Yii::app()->isAppName('frontend')){
				$notify->addSuccess(Yii::t('app', 'Successfully published your property'));
				//	$this->package_used = $packagemodel->active_package;
				Yii::app()->controller->redirect(Yii::app()->createUrl('post_ad/draft', ['id' => $this->primaryKey]));
				}
			}
			$result = $this->active_plan_check();
			if (empty($result) and empty($this->package_used)) { 
				$this->updateByPk((int)$this->id, ['status' =>  'A']);
			}else{
				Yii::app()->notify->addError(Yii::t('app',  'Your package has expired. Please renew or subscribe to a new plan to continue using our services.'));
 				Yii::app()->controller->redirect(Yii::app()->createUrl('member/addons', ['option' => 'package_expired', 'id' => $this->primaryKey, 'show_message' => 1]));
			}
		}
		if (Yii::app()->isAppName('frontend')) {
			//$notify->addError(Yii::t('app', 'Failed to activate'));
			
			Yii::app()->controller->redirect(Yii::app()->createUrl('post_ad/draft', ['id' => $this->primaryKey]));
		}
	}
	protected function beforeValidate()
	{
		  
		$this->slug = $this->generateSlug();
		 
		return parent::beforeValidate();
	}
	public function generateSlug()
	{
		Yii::import('common.vendors.Urlify.*');
		$string = str_replace('�', '', trim(mb_convert_encoding($this->ad_title, 'UTF-8')));
		
		$slug = URLify::filter($string);
		$slug = str_replace(['�','?'], '', trim(mb_convert_encoding($slug, 'UTF-8')));

		if (!Yii::app()->request->isAjaxRequest) {
			 
		} 
		//$slug = preg_replace('/[^\x20-\x7E]/', '', $slug);
		$id = (int)$this->id;

		$criteria = new CDbCriteria();
		$criteria->addCondition('id != :id AND slug = :slug');
		$criteria->params = array(':id' => $id, ':slug' => $slug);
		$exists = $this->find($criteria);

		$i = 0;
		while (!empty($exists)) {
			++$i;
			$slug = preg_replace('/^(.*)(\d+)$/six', '$1', $slug);
			$slug = URLify::filter($slug . ' ' . $i);
			$criteria = new CDbCriteria();
			$criteria->addCondition('id != :id AND slug = :slug');
			$criteria->params = array(':id' => $id, ':slug' => $slug);
			$exists = $this->find($criteria);
		}

		return $slug;
	}
	public function afterSave()
	{

		parent::afterSave();
		if(in_array($this->section_id,['1','2'])){
			$insertLog = new CustomerActionLog();
			$insertLog->customer_id = $this->user_id;
			$insertLog->category = $this->isNewRecord  ? 'customer.placed.ad' :  'customer.update.ad';
			$insertLog->reference_id = $this->primaryKey;
			$insertLog->save();
		}
		 
		if (!empty($this->package_used)) {
			try {

				$utilityUpdate = new UserPackageUtility();
				$utilityUpdate->ad_id = (int)$this->id;
				$utilityUpdate->package_id = $this->package_used;
				$utilityUpdate->f_type =  'L';
				$utilityUpdate->save();

				$values =  "('{$this->package_used}','1')";
				$sql = "insert into  {{user_packages}} (id,used_ad) values {$values} ON DUPLICATE KEY UPDATE used_ad=used_ad+1";
				Yii::app()->db->createCommand($sql)->execute();
			} catch (Exception $e) {
			}
			//	$this->package_used = $packagemodel->active_package;
		}
		 
		$AdFloorPlan = new AdFloorPlan();
		$AdPropertyTypes = new AdPropertyTypes;
		$AdFaq = new AdFaq();
		$AdSchool = new AdNearestSchool();
		if (!$this->isNewRecord) {
			$AdFloorPlan->deleteAllByAttributes(array('ad_id' => $this->primaryKey));
			$AdPropertyTypes->deleteAllByAttributes(array('ad_id' => $this->primaryKey));
			$AdFaq->deleteAllByAttributes(array('ad_id' => $this->primaryKey));
			$AdSchool->deleteAllByAttributes(array('ad_id' => $this->primaryKey));
		}
		$imgArr =  array_filter(explode(',', $this->floor_plan));
	 
		if (!empty($imgArr)) {
		
			foreach ($imgArr as $k) {
				$AdFloorPlan->isNewRecord = true;
				$AdFloorPlan->floor_id = "";
				$AdFloorPlan->ad_id = $this->id;
				$string = implode('-', explode('_', $k, -1));
				$string =  Yii::t('trn', $string, array('-' => ' ', '_' => ' '));
				//$AdFloorPlan->floor_title   =  ucfirst($string) ;
				$AdFloorPlan->floor_file   =  $k;
				$AdFloorPlan->save();
			}
		}
		$post =  Yii::App()->request->getPost('add_property_types', array());

		if (!empty($post)) {


			for ($i = 0; $i < sizeOf($post['title']); $i++) {


				$AdPropertyTypes->isNewRecord = true;
				$AdPropertyTypes->id = '';
				$AdPropertyTypes->ad_id  = $this->id;;
				$AdPropertyTypes->title = $post['title'][$i];
				$AdPropertyTypes->type_id = $post['type_id'][$i];
				$AdPropertyTypes->bed = $post['bed'][$i];
				$AdPropertyTypes->bath = $post['bath'][$i];

				$pro_description = $post['description'][$i];
				if (!empty($pro_description)) {
					$AdPropertyTypes->description = $pro_description;
				} else {
					$AdPropertyTypes->description = null;
				}
				$pro_image = $post['image'][$i];
				if (!empty($pro_image)) {
					$AdPropertyTypes->image = $pro_image;
				} else {
					$AdPropertyTypes->image = null;
				}
				$AdPropertyTypes->area_unit = '';
				$AdPropertyTypes->price_unit = '';
				$AdPropertyTypes->size = $this->formatnuber($post['size'][$i]);
				//$AdPropertyTypes->size_to =$this->formatnuber($post['size_to'][$i]);
				$AdPropertyTypes->from_price = $this->formatnuber($post['from_price'][$i]);
				//$AdPropertyTypes->to_price =$this->formatnuber($post['to_price'][$i]);
				$AdPropertyTypes->save();
			};
		}
		/*saving faq */
		$post =  Yii::App()->request->getPost('faq', array());

		if (!empty($post)) { 
			for ($i = 0; $i < sizeOf($post['title']); $i++) {
				if (empty($post['title'][$i])) {
				} else {
					$AdFaq->isNewRecord = true;
					$AdFaq->faq_id= '';
					$AdFaq->ad_id  = $this->id;;
					$AdFaq->title = $post['title'][$i];
					$AdFaq->file  = $post['file'][$i];
					$AdFaq->save();
				}
			};
		}
		/*saving faq */
		$post =  Yii::App()->request->getPost('nearest_schools', array());

		if (!empty($post)) {

			//print_r($post);exit; 
			for ($i = 0; $i < sizeOf($post['title']); $i++) {
				if (empty($post['title'][$i])) {
				} else {
					$AdSchool->isNewRecord = true;
					$AdSchool->id  = '';
					$AdSchool->ad_id  = $this->id;;
					$AdSchool->name = $post['title'][$i];
					$AdSchool->f_type  =  $post['f_type'][$i];
					$AdSchool->user_ratings_total  =  $post['user_ratings_total'][$i];
					$AdSchool->distance  = $post['distance'][$i];
					$AdSchool->vicinity  = $post['vicinity'][$i];
					$AdSchool->save();
				}
			};
		}
		if ($this->isNewRecord and !empty($this->random)) {
			$criteria = new CDbCriteria;
			$criteria->condition = 't.source_tag like :random';
			$criteria->params[':random'] = '%_[CREATE]_' . $this->random . '%';
			$found = Translate::model()->findAll($criteria);
			if ($found) {
				foreach ($found as $k => $v) {
					$new_string = Yii::t('trans', $v->source_tag, array('[CREATE]_' . $this->random => $this->primaryKey));
					$v->source_tag = $new_string;
					$v->save(false);
				}
			}
		}
		$quality = $this->CalculateMark;
		 Yii::app()->user->setState('random_id','');
		 Yii::app()->user->setState('session_',[]); 
		if (defined('OFFPLAN')) {
			$this->saveSchoolInfo();
		}
		if (!empty($this->package_used)) {
			PlaceAnAd::model()->updatebyPk((int)$this->id,['package_used2'=> $this->package_used]);
		}
		$ad = PlaceAnAd::model()->findByPk((int)$this->primaryKey);
		if ($ad->status == 'D') {
			PlaceAnAd::model()->updateByPk((int)$this->primaryKey, ['draft_date' => date('Y-m-d')]);
		}
		return true;
	}
	public function formatnuber($num)
	{
		return Yii::t('app', $num, array(',' => ''));
	}
	public function afterFind()
	{
		$this->fieldDecorator->onHtmlOptionsSetup = array($this, '_setDefaultEditorForContent');
		$this->amenities =  CHtml::listData($this->adAmenities, 'amenities_id', 'amenities_id');
		parent::afterFind();
	}
	public function findByUid($list_uid)
	{
		return self::model()->findByAttributes([
			'ad_uid' => $list_uid,
		]);
	}
	public function generateUid(): string
	{
		$unique = rand(100000000, 100000000000);
		$exists = $this->findByUid($unique);

		if (!empty($exists)) {
			return $this->generateUid();
		}

		return $unique;
	}
	protected function afterConstruct()
	{
		if ($this->scenario == 'insert') {
			$this->random = rand(10, 1000);
		}
		if (empty($this->ad_uid)) {
			$this->ad_uid = $this->generateUid();
		}
		$this->fieldDecorator->onHtmlOptionsSetup = array($this, '_setDefaultEditorForContent');
		parent::afterConstruct();
	}

	public function _setDefaultEditorForContent(CEvent $event)
	{
		if ($event->params['attribute'] == 'ad_description' and Yii::app()->isAppName('backend')) {
			$options = array();
			if ($event->params['htmlOptions']->contains('wysiwyg_editor_options')) {
				$options = (array)$event->params['htmlOptions']->itemAt('wysiwyg_editor_options');
			}
			$options['id'] = CHtml::activeId($this, 'ad_description');
			$options['height'] = 200;
			$options['toolbar'] = 'Simple';
			$event->params['htmlOptions']->add('wysiwyg_editor_options', $options);
		}
	}


	public function listDataFromSlug($slug)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "t.isTrash='0' and t.status='A'";
		$criteria->with = array("subCategory" => array("on" => "subCategory.isTrash='0' and subCategory.status='A'", "condition" => "subCategory.slug=:sname", "params" => array(":sname" => $slug), 'joinType' => 'INNER JOIN'));
		return  $this->findAll($criteria);
	}
	public function AdFromSlug($slug)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "t.isTrash='0' and t.status='A' and t.slug=:slug";
		$criteria->params[':slug'] = $slug;
		return  $this->find($criteria);
	}
	public function SearchCondition($search)
	{

		$condition		 =  "t.isTrash='0' and t.status='A' and t.country=:country";
		$paramsArray[":country"] = Yii::app()->request->cookies['country']->value;


		//FOR SPECIFI STATES

		if (Yii::app()->request->cookies['state']->value != 0) {
			$condition		 .=  " and t.state=:state";
			$paramsArray[":state"] = Yii::app()->request->cookies['state']->value;
		}




		//PRICE SEARCH
		if (isset($search["price__from"]) and $search["price__from"] != "") {
			$condition 			   .=  " and t.price>=:price_from";
			$paramsArray[":price_from"]  =  $search["price__from"];
		}

		if (isset($search["price__to"]) and $search["price__to"] != "") {
			$condition 			 .=  " and t.price<=:price_to";
			$paramsArray[":price_to"]  =  $search["price__to"];
		}
		//KILOMETER SEARCH
		if (isset($search["kilometer__from"]) and $search["kilometer__from"] != "") {
			$condition 					.=  " and t.killometer>=:kilometer__from";
			$paramsArray[":kilometer__from"]  =  $search["kilometer__from"];
		}

		if (isset($search["kilometer__to"]) and $search["kilometer__to"] != "") {
			$condition 				  .=  " and t.killometer<=:kilometer__to";
			$paramsArray[":kilometer__to"]  =  $search["kilometer__to"];
		}

		//BEDROOM SEARCH
		if (isset($search["bedrooms_min"]) and $search["bedrooms_min"] != "") {
			$condition 				 .=  " and t.bedrooms>=:bedrooms_min";
			$paramsArray[":bedrooms_min"]  =  $search["bedrooms_min"];
		}

		if (isset($search["bedrooms_max"]) and $search["bedrooms_max"] != "") {
			$condition 				 .=  " and t.bedrooms<=:bedrooms_max";
			$paramsArray[":bedrooms_max"]  =  $search["bedrooms_max"];
		}

		//BATHROOM SEARCH
		if (isset($search["bathrooms_min"]) and $search["bathrooms_min"] != "") {
			$condition 				  .=  " and t.bathrooms>=:bathrooms_min";
			$paramsArray[":bathrooms_min"]  =  $search["bathrooms_min"];
		}

		if (isset($search["bathrooms_max"]) and $search["bathrooms_max"] != "") {
			$condition 				  .=  " and t.bathrooms<=:bathrooms_max";
			$paramsArray[":bathrooms_max"]  =  $search["bathrooms_max"];
		}

		//YEAR SEARCH
		if (isset($search["year_min"]) and $search["year_min"] != "") {
			$condition 			 .=  " and t.year>=:year_min";
			$paramsArray[":year_min"]  =  $search["year_min"];
		}

		if (isset($search["year_max"]) and $search["year_max"] != "") {
			$condition 			 .=  " and t.year<=:year_max";
			$paramsArray[":year_max"]  =  $search["year_max"];
		}
		//Model
		if (isset($search["model"]) and $search["model"] != "") {
			$condition 			 .=  " and t.model=:model";
			$paramsArray[":model"]  =  $search["model"];
		}
		//Section
		if (isset($search["section_id"]) and $search["section_id"] != "") {
			$condition 			 .=  " and t.section_id=:section_id";
			$paramsArray[":section_id"]  =  $search["section_id"];
		}
		//CATEGORY
		if (isset($search["category_id"]) and $search["category_id"] != "") {
			$condition 			 .=  " and t.category_id=:category_id";
			$paramsArray[":category_id"]  =  $search["category_id"];
		}
		//SUBCATEGORY
		if (isset($search["sub_category_id"]) and $search["sub_category_id"] != "") {
			$condition 			 .=  " and t.sub_category_id=:sub_category_id";
			$paramsArray[":sub_category_id"]  =  $search["sub_category_id"];
		}


		//COLOR
		if (isset($search["color_id"]) and !empty($search["color_id"])) {
			$list =  implode(',', $search["color_id"]);
			$condition  .= " and  t.color in (:list)";
			$paramsArray[":list"] =  $list;
		}
		//DOOR
		if (isset($search["door_id"]) and !empty($search["door_id"])) {
			$list =  implode(',', $search["door_id"]);
			$condition  .= " and  t.door in (:list2)";
			$paramsArray[":list2"] =  $list;
		}
		//bodycondition
		if (isset($search["bodycondition_id"]) and !empty($search["bodycondition_id"])) {
			$list =  implode(',', $search["bodycondition_id"]);
			$condition  .= " and  t.bodycondition in (:list3)";
			$paramsArray[":list3"] =  $list;
		}

		//mechanicalcondition
		if (isset($search["mechanicalcondition_id"]) and !empty($search["mechanicalcondition_id"])) {
			$list =  implode(',', $search["mechanicalcondition_id"]);
			$condition  .= " and  t.mechanicalcondition in (:list4)";
			$paramsArray[":list4"] =  $list;
		}
		//user ID
		if (isset($search["user_id"]) and !empty($search["user_id"])) {

			$condition  .= " and  t.user_id in (:usr)";
			$paramsArray[":usr"] = $search["user_id"];
		}
		//fuel_id
		if (isset($search["fuel_id"]) and !empty($search["fuel_id"])) {
			$list =  implode(',', $search["fuel_id"]);
			$condition  .= " and  t.fuel_type in (:list5)";
			$paramsArray[":list5"] =  $list;
		}
		//body_type
		if (isset($search["body_type_id"]) and !empty($search["body_type_id"])) {
			$list =  implode(',', $search["body_type_id"]);
			$condition  .= " and  t.body_type in (:list6)";
			$paramsArray[":list6"] =  $list;
		}

		//DATE COMPARE
		if (isset($search["added__date"]) and $search["added__date"] != "") {
			switch ($search["added__date"]) {
				case 0:
					$condition  .= " and  DATE(t.added_date) = CURDATE()";
					break;
				case 3:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -3 DAY)";
				case 7:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
				case 14:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -14 DAY)";
				case 30:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -1 MONTH)";
				case 90:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -3 MONTH)";
				case 190:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -6 MONTH)";
			}
		}





		//KEYWORD
		if (isset($search["keyword"]) and $search["keyword"] != "") {
			$condition  .= " and ( t.ad_title like :keyword or t.ad_description like :keyword ) ";
			$paramsArray[":keyword"] = "%{$search['keyword']}%";
		}

		return array("condition" => $condition, "params" => $paramsArray);
	}
	public function SearchConditionCount($search)
	{
		$condition		 =  "t.isTrash='0' and t.status='A' and t.country=:country";
		$paramsArray[":country"] = Yii::app()->request->cookies['country']->value;


		//FOR SPECIFI STATES

		if (Yii::app()->request->cookies['state']->value != 0) {
			$condition		 .=  " and t.state=:state";
			$paramsArray[":state"] = Yii::app()->request->cookies['state']->value;
		}
		//PRICE SEARCH
		if (isset($search["price__from"]) and $search["price__from"] != "") {
			$condition 			   .=  " and t.price>=:price_from";
			$paramsArray[":price_from"]  =  $search["price__from"];
		}

		if (isset($search["price__to"]) and $search["price__to"] != "") {
			$condition 			 .=  " and t.price<=:price_to";
			$paramsArray[":price_to"]  =  $search["price__to"];
		}
		//KILOMETER SEARCH
		if (isset($search["kilometer__from"]) and $search["kilometer__from"] != "") {
			$condition 					.=  " and t.killometer>=:kilometer__from";
			$paramsArray[":kilometer__from"]  =  $search["kilometer__from"];
		}

		if (isset($search["kilometer__to"]) and $search["kilometer__to"] != "") {
			$condition 				  .=  " and t.killometer<=:kilometer__to";
			$paramsArray[":kilometer__to"]  =  $search["kilometer__to"];
		}

		//BEDROOM SEARCH
		if (isset($search["bedrooms_min"]) and $search["bedrooms_min"] != "") {
			$condition 				 .=  " and t.bedrooms>=:bedrooms_min";
			$paramsArray[":bedrooms_min"]  =  $search["bedrooms_min"];
		}

		if (isset($search["bedrooms_max"]) and $search["bedrooms_max"] != "") {
			$condition 				 .=  " and t.bedrooms<=:bedrooms_max";
			$paramsArray[":bedrooms_max"]  =  $search["bedrooms_max"];
		}

		//BATHROOM SEARCH
		if (isset($search["bathrooms_min"]) and $search["bathrooms_min"] != "") {
			$condition 				  .=  " and t.bathrooms>=:bathrooms_min";
			$paramsArray[":bathrooms_min"]  =  $search["bathrooms_min"];
		}

		if (isset($search["bathrooms_max"]) and $search["bathrooms_max"] != "") {
			$condition 				  .=  " and t.bathrooms<=:bathrooms_max";
			$paramsArray[":bathrooms_max"]  =  $search["bathrooms_max"];
		}

		//YEAR SEARCH
		if (isset($search["year_min"]) and $search["year_min"] != "") {
			$condition 			 .=  " and t.year>=:year_min";
			$paramsArray[":year_min"]  =  $search["year_min"];
		}

		if (isset($search["year_max"]) and $search["year_max"] != "") {
			$condition 			 .=  " and t.year<=:year_max";
			$paramsArray[":year_max"]  =  $search["year_max"];
		}
		//KEYWORD
		if (isset($search["keyword"]) and $search["keyword"] != "") {
			$condition  .= " and ( t.ad_title like :keyword or t.ad_description like :keyword ) ";
			$paramsArray[":keyword"] = "%{$search['keyword']}%";
		}
		//Model
		if (isset($search["model"]) and $search["model"] != "") {
			$condition 			 .=  " and t.model=:model";
			$paramsArray[":model"]  =  $search["model"];
		}

		//Section
		if (isset($search["section_id"]) and $search["section_id"] != "") {
			$condition 			 .=  " and t.section_id=:section_id";
			$paramsArray[":section_id"]  =  $search["section_id"];
		}
		//CATEGORY
		if (isset($search["category_id"]) and $search["category_id"] != "") {
			$condition 			 .=  " and t.category_id=:category_id";
			$paramsArray[":category_id"]  =  $search["category_id"];
		}
		//SUBCATEGORY
		if (isset($search["sub_category_id"]) and $search["sub_category_id"] != "") {
			$condition 			 .=  " and t.sub_category_id=:sub_category_id";
			$paramsArray[":sub_category_id"]  =  $search["sub_category_id"];
		}

		//COLOR
		if (isset($search["color_id"]) and !empty($search["color_id"])) {
			$list =  implode(',', $search["color_id"]);
			$condition  .= " and  t.color in (:list)";
			$paramsArray[":list"] =  $list;
		}
		//DOOR
		if (isset($search["door_id"]) and !empty($search["door_id"])) {
			$list =  implode(',', $search["door_id"]);
			$condition  .= " and  t.door in (:list2)";
			$paramsArray[":list2"] =  $list;
		}
		//bodycondition
		if (isset($search["bodycondition_id"]) and !empty($search["bodycondition_id"])) {
			$list =  implode(',', $search["bodycondition_id"]);
			$condition  .= " and  t.bodycondition in (:list3)";
			$paramsArray[":list3"] =  $list;
		}

		//mechanicalcondition
		if (isset($search["mechanicalcondition_id"]) and !empty($search["mechanicalcondition_id"])) {
			$list =  implode(',', $search["mechanicalcondition_id"]);
			$condition  .= " and  t.mechanicalcondition in (:list4)";
			$paramsArray[":list4"] =  $list;
		}

		//fuel_id
		if (isset($search["fuel_id"]) and !empty($search["fuel_id"])) {
			$list =  implode(',', $search["fuel_id"]);
			$condition  .= " and  t.fuel_type in (:list5)";
			$paramsArray[":list5"] =  $list;
		}
		//user ID
		if (isset($search["user_id"]) and !empty($search["user_id"])) {

			$condition  .= " and  t.user_id in (:usr)";
			$paramsArray[":usr"] = $search["user_id"];
		}
		//body_type
		if (isset($search["body_type_id"]) and !empty($search["body_type_id"])) {
			$list =  implode(',', $search["body_type_id"]);
			$condition  .= " and  t.body_type in (:list6)";
			$paramsArray[":list6"] =  $list;
		}
		//DATE COMPARE
		if (isset($search["added__date"])) {
			switch ($search["added__date"]) {
				case 0:
					$condition  .= " and  DATE(t.added_date) = CURDATE()";
					break;
				case 3:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -3 DAY)";
				case 7:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
				case 14:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -14 DAY)";
				case 30:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -1 MONTH)";
				case 90:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -3 MONTH)";
				case 190:
					$condition  .= " and   t.added_date >= DATE_ADD(CURDATE(), INTERVAL -6 MONTH)";

				default:

					break;
			}
		}
		//echo $condition;exit;
		return array("condition" => $condition, "params" => $paramsArray);
	}
	function updateXmlAds($status)
	{

		$criteria		=	new CDbCriteria;
		$criteria->condition = "xml_inserted=:xml_status";
		$criteria->params[':xml_status'] = '1';
		$this->updateAll(array('status' => $status), $criteria);
	}
	public function getAllxmlPk($SectionID)
	{
		$criteria		=	new CDbCriteria;
		$criteria->select = "t.xml_pk";
		$criteria->condition = "xml_inserted=:xml_status and t.section_id=:sec";
		$criteria->params[':xml_status'] = '1';
		$criteria->params[':sec'] = $SectionID;
		return $this->findAll($criteria);
	}
	public function getAllProspace_xml()
	{

		$criteria		=	new CDbCriteria;
		$criteria->select = "t.xml_reference,t.xml_update_date";
		$criteria->condition = "xml_inserted=:xml_status and t.xml_type=:type";
		$criteria->params[':xml_status'] = '1';
		$criteria->params[':type'] = 'P';
		return $this->findAll($criteria);
	}
	public function getAllxml($section)
	{

		$criteria		=	new CDbCriteria;
		$criteria->select = "t.id,t.modified_date,t.code";
		$criteria->condition = "t.xml_type=:type and t.section_id=:section";
		$criteria->params[':type'] = 'P';
		$criteria->params[':section'] = $section;
		return $this->findAll($criteria);
	}

	function category_isert($SectionID, $v1)
	{
		$category_model = new Category;
		$category_model->isNewRecord = true;
		$category_model->category_id     = "";
		$category_model->section_id     = $SectionID;
		$category_model->category_name  = $v1;
		$category_model->amenities_required  = 'Y';
		$category_model->xml_inserted  = '1';
		$category_model->slug = $category_model->getUniqueSlug();
		$category_model->save();
		return  Yii::app()->db->getLastInsertId();
	}


	function fieldInsertion($category_id)
	{

		$Fileds =  new   CategoryFieldList;
		$attributes2 = array('price', 'area', 'bathrooms', 'bedrooms');
		foreach ($attributes2 as $field) {
			$Fileds->isNewRecord = true;
			$Fileds->field_name = $field;
			$Fileds->category_id = $category_id;
			$Fileds->save();
		}
	}
	function subcategory_insert($category_id, $unitType, $SectionID)
	{
		$sub_category_model = new Subcategory;
		$subcategory = CHtml::listData(Subcategory::model()->ListDataForCategory($category_id), 'sub_category_id', 'sub_category_name');
		if (!$this->in_arrayi($unitType, $subcategory)) {
			$sub_category_model->isNewRecord    = true;
			$sub_category_model->section_id     = $SectionID;
			$sub_category_model->sub_category_id     = "";
			$sub_category_model->category_id     = $category_id;
			$sub_category_model->sub_category_name  = $unitType;
			$sub_category_model->amenities_required  = 'Y';
			$sub_category_model->xml_inserted  = '1';
			$sub_category_model->slug = $sub_category_model->getUniqueSlug();
			$sub_category_model->save();
			return  Yii::app()->db->getLastInsertId();
		} else {
			return  array_search(strtolower($unitType), array_map('strtolower', $subcategory));
		}
	}
	function country_insert($con, $v1)
	{
		$country = new Countries;
		if (!$this->in_arrayi($v1, $con)) {
			$country->country_name = $v1;
			$country->country_code = 'XXX';
			$country->location_longitude = '1';
			$country->location_latitude = '1';
			$country->save();
			return  Yii::app()->db->getLastInsertId();
		} else {

			return  array_search(strtolower($v1), array_map('strtolower', $con));
		}
	}
	function state_insert($con, $v1, $country_id)
	{
		$state = new States;
		if (!$this->in_arrayi($v1, $con)) {
			$state->state_name = $v1;
			$state->country_id = $v1;
			$state->location_longitude = '1';
			$state->location_latitude = '1';
			$state->save();
			return  Yii::app()->db->getLastInsertId();
		} else {

			return  array_search(strtolower($v1), array_map('strtolower', $con));
		}
	}
	function in_arrayi($needle, $haystack)
	{

		return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}
	function insertUser($user_email, $user_phone, $user_name, $user_image)
	{

		$model = new ListingUsers();
		$img_user = "";
		/*
			if (@GetImageSize($user_image)) {



			$path =  Yii::app()->basePath . '/../../uploads' ;
			$img_user = 'usr'.rand(0,9999).'_'.time().".jpg";
			$content = file_get_contents($path);
			file_put_contents($path."/avatar/{$img_user}", $content);
			}
			* */

		$model->email = $user_email;
		$model->phone = $user_phone;
		$serexplode = explode(' ', $user_name);
		$model->first_name = @$serexplode['0'];
		$model->last_name = @$serexplode['1'];
		$password = '123456';
		$model->image = $img_user;
		$model->con_password =  $password;
		$model->password = $password;
		$model->status = 'A';
		$model->xml_inserted = '1';
		$model->xml_image = $user_image;
		$model->save();
		return  Yii::app()->db->getLastInsertId();
	}
	function imageinsert($imagearray, $ad_id, $delete = 0)
	{

		$room_image = new AdImage;
		if ($delete == 1) {
			$room_image->deleteAll(array("condition" => "ad_id=:ad_id", "params" => array(":ad_id" => $ad_id)));
		}

		if (!empty($imagearray)) {
			foreach ($imagearray as  $photo) {

				$img = "";

				if (@GetImageSize($photo)) {

					$path =  Yii::app()->basePath . '/../../uploads';
					$img = rand(0, 9999) . '_' . time() . ".jpg";
					$content = file_get_contents($photo);
					file_put_contents($path . "/ads/{$img}", $content);
				}


				$room_image->isNewRecord = true;
				$room_image->id = "";
				$room_image->ad_id = $ad_id;
				$room_image->image_name = $img;
				$room_image->xml_image = $photo;
				$room_image->status = "A";
				$room_image->save();
			}
		}
	}
	function adsMessage($totalcount, $totalinsertcount, $totalupdatecount, $fetched, $section = "")
	{
		echo "Dear Admin ,<br />";
		$remaining = $totalcount - ($fetched + $totalinsertcount + $totalupdatecount);
		$remainin_msg = "";
		if ((int)$remaining > 0) {
			$remainin_msg = " and remaining <b>{$remaining} </b> Ads to fetch";
		}
		echo "Total Ads {$totalcount} found and {$totalinsertcount} inserted and {$totalupdatecount} updated on section {$section}" . $remainin_msg;
		exit;
	}
	function renderImage($image = "", $xml = "P", $image_name = "")
	{


		$image = Yii::app()->basePath . '/../../uploads/ads/' . $image_name;
		if (is_file($image)) {

			return Yii::app()->apps->getBaseUrl('uploads/ads/' . $image_name);
		} else {
			return   Yii::app()->theme->baseUrl . '/images/ucnoimage.jpg';
		}


		if ($xml == "N") {

			$image = Yii::app()->basePath . '/../../uploads/ads/' . $image_name;
			if (is_file($image)) {

				return Yii::app()->apps->getBaseUrl('uploads/ads/' . $image_name);
			} else {
				return   Yii::app()->theme->baseUrl . '/images/ucnoimage.jpg';
			}
		}
		if (@GetImageSize($image)) {
			return   $image;
		} else {

			return   Yii::app()->theme->baseUrl . '/images/ucnoimage.jpg';
		}
	}
	function renderImageNew($image_name = "")
	{


		$image = Yii::app()->basePath . '/../../uploads/ads/' . $image_name;
		if (is_file($image)) {

			return Yii::app()->apps->getBaseUrl('uploads/ads/' . $image_name);
		} else {
			return   Yii::app()->theme->baseUrl . '/images/ucnoimage.jpg';
		}
	}
	public $s_code;
	public function getCompanyName()
	{
		if (!empty($this->company_name)) {
			$code = '';
			!empty($this->s_code) ? ' (' . $this->s_code . ')' : '';
			return $this->company_name . $code;
		}
	}
	public function getCompanyNameOnly()
	{
		if (!empty($this->company_name)) {
			$code = '';
			return $this->company_name . $code;
		}
	}
	function currencyAbreviation($currency = "")
	{
		return ($currency == "") ? Yii::app()->options->get('system.common.defalut_currency') : $currency;
	}
	function getPriceWithCurrncy()
	{
		return  Yii::app()->options->get('system.common.defalut_currency') . ' ' .  number_format($this->price, 0, '.', ',');
	}
	function getBuiltUpArea()
	{

		if ($this->builtup_area != '0.00' and  !empty($this->builtup_area)) {
			return     number_format($this->builtup_area, 0, '.', ',') . '  Sq. Ft.';
		}
	}
	function getBuiltUpAreaTitleS()
	{
		if ($this->builtup_area != '0.00') {
			return     number_format($this->builtup_area, 0, '.', ',') . '  <small>Sq. Ft.</small>';
		}
	}
	function getPloatArea()
	{
		if (!empty($this->plot_area)) {
			return     number_format($this->plot_area, 0, '.', ',') . '  Sq. Ft.';
		}
	}
	public $sub_community_name;
	function getSystemRefNo()
	{
		if (!empty($this->RefNo)) {
			return $this->RefNo;
		} else {
			return 'MA' . str_pad($this->id, 5, 0, STR_PAD_LEFT);
		}
	}
	function getPriceHtml()
	{
		$htmlTag =  '<div class="Price_span"> ' . $this->priceWithCurrncy;
		if ($this->section_id == self::RENT_ID) {
			$htmlTag .= '<br><span class="rentpermonth" style="font-size:12px">(rent per  ' . $this->rentPaid2 . ')</span>';
		}
		$htmlTag .= ' </div> ';
		return $htmlTag;
	}
	function getDetailsPriceHtml()
	{

		$htmlTag = '<span class="unit-price-div">' . Yii::app()->options->get('system.common.defalut_currency') . ' <span class="price-text">' . number_format($this->price, 0, '.', ',') . '</span>';
		if ($this->section_id == self::RENT_ID) {
			$htmlTag .= '<span class="rentpermonth">(rent per  ' . $this->rentPaid2 . ')</span>';
		}
		$htmlTag .= '</span>';

		return $htmlTag;
	}

	public function rentPaidArray()
	{
		$tags = Yii::app()->tags;
		return array(
			'monthly' => $tags->getTag('monthly', 'Monthly'),
			'yearly' => $tags->getTag('yearly', 'Yearly')
		);
	}

	function getRentPaid()
	{
		if (empty($this->rent_paid)) {
			$this->rent_paid = 'yearly';
		}
		return  ' / ' . $this->rent_paid;
	}
	function getRentPaid2()
	{
		if (empty($this->rent_paid)) {
			$this->rent_paid = 'yearly';
		}
		return   $this->rent_paid;
	}
	function getRentPaid3()
	{
		if (empty($this->rent_paid)) {
			$this->rent_paid = 'yearly';
		}
		switch ($this->rent_paid) {
			case 'yearly':
				return 'yr';
				break;
			case 'monthly':
				return 'mo';
				break;
			default:
				return $this->rent_paid;
				break;
		}
	}
	function getLocationString()
	{
		if (!empty($this->district)) {
			return 	$this->district0->district_name;
		} else {
			return 	$this->state0->state_name;
		}
	}
	function getReadyString2()
	{
		if (!empty($this->occupant_status) and $this->occupant_status == 'Vacant') {
			return '<div class="readynow-div2"> <span>Ready Now</span> </div>';
		}
	}
	function getReadyString()
	{
		if (!empty($this->occupant_status) and $this->occupant_status == 'Vacant') {
			return '<div class="readynow-div"> <span>Ready Now</span> </div>';
		}
	}
	function getPropertyDatilUrl()
	{
		return   Yii::app()->createUrl($this->slug . '/detailView');
	}
	function getPropertyAbsoluteDatilUrl()
	{
		return   Yii::app()->createAbsoluteUrl($this->slug . '/detailView');
	}
	function getBuiltupAreaString()
	{
		if (!empty($this->builtup_area)) {
			return 	$this->builtup_area .  ' sq.ft.';
		}
	}
	function getLocalBedString()
	{
		$htmlTag = '';
		if (!empty($this->bedrooms)) {
			$htmlTag .= '<span style="float:left;">' . (int) $this->bedrooms . '</span><span title="' . (int) $this->bedrooms . ' Bedroom(s)" class="unitbeds"></span>';
		}
		if (!empty($this->bathrooms)) {
			$htmlTag .= '<span style="float:left;">' . (int) $this->bathrooms . '</span><span title="' . (int) $this->bathrooms . ' Bathroom(s)" class="unitbaths"></span>';
		}
		if (!empty($this->parking)) {
			$htmlTag .= '<span style="float:left;">' . (int) $this->parking  . '</span><span title="' . (int) $this->parking . ' Car Parking(s)" class="unitparkng"></span>';
		}
		return $htmlTag;
	}
	function FomatMoney($money = "0")
	{
		return  number_format($money, 0, '.', ',');
	}
	function priceArray()
	{
		return array(
			10000 => '10000 ' . Yii::app()->options->get('system.common.defalut_currency'),
			50000 => '50000 ' . Yii::app()->options->get('system.common.defalut_currency'),
			100000 => '100000 ' . Yii::app()->options->get('system.common.defalut_currency'),
			200000 => '200000 ' . Yii::app()->options->get('system.common.defalut_currency'),
			300000 => '300000 ' . Yii::app()->options->get('system.common.defalut_currency'),
			400000 => '400000 ' . Yii::app()->options->get('system.common.defalut_currency'),
			500000 => '500000 ' . Yii::app()->options->get('system.common.defalut_currency'),
			1000000 => '1000000 ' . Yii::app()->options->get('system.common.defalut_currency'),
		);
	}
	function getMinPriceHtml()
	{
?>
		<option value="">Min Price</option>
		<option value="10000" <?php echo (Yii::app()->request->getQuery("min-price") == "10000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('10000.00', 2, '.', ','); ?></option>
		<option value="50000" <?php echo (Yii::app()->request->getQuery("min-price") == "50000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('50000.00', 2, '.', ','); ?></option>
		<option value="100000" <?php echo (Yii::app()->request->getQuery("min-price") == "100000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('100000.00', 2, '.', ','); ?></option>
		<option value="200000" <?php echo (Yii::app()->request->getQuery("min-price") == "200000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('200000.00', 2, '.', ','); ?></option>
		<option value="300000" <?php echo (Yii::app()->request->getQuery("min-price") == "300000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('300000.00', 2, '.', ','); ?></option>
		<option value="400000" <?php echo (Yii::app()->request->getQuery("min-price") == "400000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('400000.00', 2, '.', ','); ?></option>
		<option value="500000" <?php echo (Yii::app()->request->getQuery("min-price") == "500000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo  number_format('500000.00', 2, '.', ','); ?></option>
		<option value="1000000" <?php echo (Yii::app()->request->getQuery("min-price") == "1000000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('1000000.00', 2, '.', ','); ?></option>

		<?
	}
	function getMaxPriceHtml()
	{
		?>Bathrooms
		<option value="">Max Price</option>
		<option value="10000" <?php echo (Yii::app()->request->getQuery("max-price") == "10000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('10000.00', 2, '.', ','); ?></option>
		<option value="50000" <?php echo (Yii::app()->request->getQuery("max-price") == "50000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('50000.00', 2, '.', ','); ?></option>
		<option value="100000" <?php echo (Yii::app()->request->getQuery("max-price") == "100000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('100000.00', 2, '.', ','); ?></option>
		<option value="200000" <?php echo (Yii::app()->request->getQuery("max-price") == "200000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('200000.00', 2, '.', ','); ?></option>
		<option value="300000" <?php echo (Yii::app()->request->getQuery("max-price") == "300000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('300000.00', 2, '.', ','); ?></option>
		<option value="400000" <?php echo (Yii::app()->request->getQuery("max-price") == "400000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('400000.00', 2, '.', ','); ?></option>
		<option value="500000" <?php echo (Yii::app()->request->getQuery("max-price") == "500000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo  number_format('500000.00', 2, '.', ','); ?></option>
		<option value="1000000" <?php echo (Yii::app()->request->getQuery("max-price") == "1000000") ? "selected=true" : ""; ?>><?php echo Yii::app()->options->get('system.common.defalut_currency'); ?> <?php echo number_format('1000000.00', 2, '.', ','); ?></option>

	<?
	}
	function getBedroomHtml()
	{
	?>
		<option value="">Bedrooms</option>
		<option value="1" <?php echo (Yii::app()->request->getQuery("bedrooms") == "1") ? "selected=true" : ""; ?>>1 Bedrooms</option>
		<option value="2" <?php echo (Yii::app()->request->getQuery("bedrooms") == "2") ? "selected=true" : ""; ?>>2 Bedrooms</option>
		<option value="3" <?php echo (Yii::app()->request->getQuery("bedrooms") == "3") ? "selected=true" : ""; ?>>3 Bedrooms</option>
		<option value="4" <?php echo (Yii::app()->request->getQuery("bedrooms") == "4") ? "selected=true" : ""; ?>>4 Bedrooms</option>
		<option value="5" <?php echo (Yii::app()->request->getQuery("bedrooms") == "5") ? "selected=true" : ""; ?>>5 Bedrooms</option>
		<option value="6" <?php echo (Yii::app()->request->getQuery("bedrooms") == "6") ? "selected=true" : ""; ?>>6 Bedrooms</option>
		<option value="7" <?php echo (Yii::app()->request->getQuery("bedrooms") == "7") ? "selected=true" : ""; ?>>7 Bedrooms</option>
		<option value="8" <?php echo (Yii::app()->request->getQuery("bedrooms") == "8") ? "selected=true" : ""; ?>>8 Bedrooms</option>
		<option value="9" <?php echo (Yii::app()->request->getQuery("bedrooms") == "9") ? "selected=true" : ""; ?>>9 Bedrooms</option>
		<option value="10" <?php echo (Yii::app()->request->getQuery("bedrooms") == "10") ? "selected=true" : ""; ?>>10 Bedrooms</option>
		<option value="bedroom-equal-and-more" <?php echo (Yii::app()->request->getQuery("bedrooms") == "bedroom-equal-and-more") ? "selected=true" : ""; ?>>10+ Bedrooms</option>

	<?
	}
	function getMBathroomHtml()
	{
	?>
		<option value="">Bathrooms</option>
		<option value="1" <?php echo (Yii::app()->request->getQuery("bathrooms") == "1") ? "selected=true" : ""; ?>>1 Bathrooms</option>
		<option value="2" <?php echo (Yii::app()->request->getQuery("bathrooms") == "2") ? "selected=true" : ""; ?>>2 Bathrooms</option>
		<option value="3" <?php echo (Yii::app()->request->getQuery("bathrooms") == "3") ? "selected=true" : ""; ?>>3 Bathrooms</option>
		<option value="4" <?php echo (Yii::app()->request->getQuery("bathrooms") == "4") ? "selected=true" : ""; ?>>4 Bathrooms</option>
		<option value="5" <?php echo (Yii::app()->request->getQuery("bathrooms") == "5") ? "selected=true" : ""; ?>>5 Bathrooms</option>
		<option value="6" <?php echo (Yii::app()->request->getQuery("bathrooms") == "6") ? "selected=true" : ""; ?>>6 Bathrooms</option>
		<option value="7" <?php echo (Yii::app()->request->getQuery("bathrooms") == "7") ? "selected=true" : ""; ?>>7 Bathrooms</option>
		<option value="8" <?php echo (Yii::app()->request->getQuery("bathrooms") == "8") ? "selected=true" : ""; ?>>8 Bathrooms</option>
		<option value="9" <?php echo (Yii::app()->request->getQuery("bathrooms") == "9") ? "selected=true" : ""; ?>>9 Bathrooms </option>
		<option value="10" <?php echo (Yii::app()->request->getQuery("bathrooms") == "10") ? "selected=true" : ""; ?>>10 Bathrooms</option>
		<option value="bathroom-equal-and-more" <?php echo (Yii::app()->request->getQuery("bathrooms") == "bathroom-equal-and-more") ? "selected=true" : ""; ?>>10+ Bathrooms</option>

<?
	}
	public function getShortName($length = 20)
	{
		return StringHelper::truncateLength($this->ad_title, (int)$length);
	}
	public function getShortName2($length = 20)
	{
		return StringHelper::truncateLength($this->AdTitle2, (int)$length);
	}
	public function getlistedByTitle()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
	public function getSecTitle()
	{
		return $this->section->fieldName . '<br />' . $this->category->fieldName;
	}
	public function getAdTitleWithIcons()
	{
		$html =  $this->ad_title;
		if ($this->featured == "Y") {
			$html .=  '<i title="FEATURED" class="glyphicon glyphicon-star"></i>';
		}
		if ($this->status == "I") {
			$html .=  '<i title="DISABLED" class="glyphicon glyphicon-ban-circle"></i>';
		}
		return  $html;
	}
	public $category_name;
	public $community_name;
	public $cate_name_oth;
	public $community_oth;
	public function getCategoryNameNew()
	{
		if (!empty($this->cate_name_oth)) {
			return $this->cate_name_oth;
		} else {
			return $this->cate_name_oth;
		}
	}
	public function getCommunityNameNew()
	{
		if (!empty($this->community_oth)) {
			return $this->community_oth;
		} else {
			return $this->community_name;
		}
	}
	public function newDevelopments($country_id = null, $state = null, $limit = 2)
	{
		$langaugae = '';
		if (Yii::app()->isAppName('frontend')) {
			$langaugae = OptionCommon::getLanguage();
		}
		$criteria = self::model()->search(1);
		$criteria->select = 't.*,   (SELECT image_name FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0"  limit 1  )   as ad_image';
		$criteria->join  .= ' left join {{category}} cat ON cat.category_id = t.category_id ';
		$criteria->join  .= ' left join {{community}} com ON com.community_id = t.community_id ';
		if (!empty($langaugae) and  $langaugae != 'en') {
			$criteria->params[':lan'] = $langaugae;
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.category_id = t.category_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->join  .= ' left join `mw_translate_relation` translationRelation2    on translationRelation2.community_id = t.community_id   LEFT  JOIN mw_translation_data tdata2 ON (translationRelation2.translate_id=tdata2.translation_id and tdata2.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdata.message   IS NOT NULL THEN tdata.message ELSE cat.category_name  END as  category_name  ';
			$criteria->select .= ' ,tdata2.message as  community_oth ';

			/*joining state*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelations` on translationRelations.state_id = t.state    LEFT  JOIN mw_translation_data tdatas ON (`translationRelations`.translate_id=tdatas.translation_id and tdatas.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdatas.message  IS NOT NULL THEN tdatas.message ELSE st.state_name END as  state_name  ';

			/*joining ad title */
			$criteria->join  .= 'left join `mw_translate` `translatea` on (  translatea.source_tag = concat("PlaceAnAd_ad_title_",t.id) )          left join `mw_translate_relation` `translationRelationa` on translationRelationa.ad_id = t.id  and  translationRelationa.translate_id = translatea.translate_id  LEFT  JOIN mw_translation_data tdataa ON (`translationRelationa`.translate_id=tdataa.translation_id and tdataa.lang=:lan  ) ';
			$criteria->select   .= ' , CASE WHEN tdataa.message IS NOT NULL AND translatea.source_tag IS NOT NULL    THEN  tdataa.message ELSE t.ad_title END	 as  ad_title2  ';
			$criteria->group = 't.id';
		} else {
			$criteria->select .= ',cat.category_name as  category_name,ad_title as ad_title2,st.state_name ';
		}
		$criteria->condition  .= '  and  t.status="A" and t.section_id =:section ';
		$criteria->params[':section'] = self::NEW_ID;
		if ($country_id) {
			$criteria->condition .= ' and t.country = :country ';
			$criteria->params[':country'] = $country_id;
		}
		if ($state) {
			$criteria->condition .= ' and t.state = :state ';
			$criteria->params[':state'] = $state;
		}
		if (Yii::app()->user->getId()) {
			$criteria->select .= ' ,fav.ad_id as fav ';
			$criteria->join  .= ' left join {{ad_favourite}} fav ON fav.ad_id = t.id and fav.user_id =:user_me';
			$criteria->params[':user_me'] = Yii::app()->user->getId();
		}
		$criteria->limit = $limit;
		return self::model()->findAll($criteria);
	}
	public $ad_image;
	public $ad_description2;
	public function faturedProjects($country_id = null, $state = null, $limit = 2, $section_id = null)
	{
		$langaugae = '';
		if (Yii::app()->isAppName('frontend')) {
			$langaugae = OptionCommon::getLanguage();
		}
		$criteria = self::model()->search(1);
		$criteria->select = 't.*,cat.category_name as  category_name,com.community_name,   (SELECT image_name FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0"  limit 1 )   as ad_image';
		$criteria->join  .= ' left join {{category}} cat ON cat.category_id = t.category_id ';
		$criteria->join  .= ' left join {{community}} com ON com.community_id = t.community_id ';
		$criteria->condition  .= ' and  t.status="A"   and t.featured="Y"   ';
		if (!empty($langaugae) and  $langaugae != 'en') {

			$criteria->params[':lan'] = $langaugae;

			/*joining category*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.category_id = t.category_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdata.message   IS NOT NULL THEN tdata.message ELSE cat.category_name  END as  category_name  ';

			/*joining state*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelations` on translationRelations.state_id = t.state    LEFT  JOIN mw_translation_data tdatas ON (`translationRelations`.translate_id=tdatas.translation_id and tdatas.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdatas.message  IS NOT NULL THEN tdatas.message ELSE st.state_name END as  state_name  ';

			/*joining ad title */
			$criteria->join  .= 'left join `mw_translate` `translatea` on (  translatea.source_tag = concat("PlaceAnAd_ad_title_",t.id) )          left join `mw_translate_relation` `translationRelationa` on translationRelationa.ad_id = t.id  and  translationRelationa.translate_id = translatea.translate_id  LEFT  JOIN mw_translation_data tdataa ON (`translationRelationa`.translate_id=tdataa.translation_id and tdataa.lang=:lan  ) ';
			$criteria->select   .= ' , CASE WHEN tdataa.message IS NOT NULL AND translatea.source_tag IS NOT NULL    THEN  tdataa.message ELSE t.ad_title END	 as  ad_title2    ';

			/*joining ad description */
			$criteria->join  .= 'left join `mw_translate` `translated` on (  translated.source_tag = concat("PlaceAnAd_ad_description_",t.id) )          left join `mw_translate_relation` `translationRelationd` on translationRelationd.ad_id = t.id  and  translationRelationd.translate_id = translated.translate_id  LEFT  JOIN mw_translation_data tdatad ON (`translationRelationd`.translate_id=tdatad.translation_id and tdatad.lang=:lan  ) ';
			$criteria->select   .= ' , CASE WHEN tdatad.message IS NOT NULL AND translated.source_tag IS NOT NULL    THEN  tdatad.message ELSE t.ad_description END	 as  ad_description2    ';


			$criteria->group = 't.id';
		} else {
			$criteria->select .= ',cat.category_name as  category_name,ad_title as ad_title2,st.state_name,t.ad_description as  ad_description2  ';
		}
		//	$criteria->params[':section'] = self::NEW_ID ; 
		if (!empty($section_id)) {
			$criteria->condition  .= ' and  t.section_id =:section    ';
			$criteria->params[':section'] = $section_id;
		}
		if ($country_id) {
			$criteria->condition .= ' and t.country = :country ';
			$criteria->params[':country'] = $country_id;
		}
		if ($state) {
			$criteria->condition .= ' and t.state = :state ';
			$criteria->params[':state'] = $state;
		}
		if (Yii::app()->user->getId()) {
			$criteria->select .= ' ,fav.ad_id as fav ';
			$criteria->join  .= ' left join {{ad_favourite}} fav ON fav.ad_id = t.id and fav.user_id =:user_me';
			$criteria->params[':user_me'] = Yii::app()->user->getId();
		}
		$criteria->limit = $limit;
		return self::model()->findAll($criteria);
	}
	public $ad_images_g;
	const BULK_ACTION_DELETE = 'delete';
	const BULK_ACTION_RESTORE = 'restore';
	public function getBulkActionsList()
	{
		return
			array(
				//self::BULK_ACTION_DELETE         => Yii::t('app', 'Delete Permanently'),
				self::BULK_ACTION_RESTORE         => Yii::t('app', 'Restore'),
			);
	}
	public $ad_titleN;
	public $ad_descriptionN;
	public function new_homes($country_id = null, $state = null, $limit = 2, $section_id = null, $category_id = null, $featured_type = null, $default_order = null, $show_multiple = false, $not_in_array = array(), $tit = false)
	{

		$langaugae = '';
		if (Yii::app()->isAppName('frontend')) {
			$langaugae = OptionCommon::getLanguage();
		}
		$criteria = self::model()->search(1);

		//$ids =implode(',', Yii::app()->options->get('system.common.residential_categories'));

		$criteria->join  .= ' left join {{category}} cat ON cat.category_id = t.category_id ';
		//$criteria->join  .= ' left join {{community}} com ON com.community_id = t.community_id ';
		$criteria->join  .= ' left join {{section}} sec ON sec.section_id = t.section_id ';
		//$criteria->join  .= ' left join {{states}} st ON st.state_id = t.state ';
		$criteria->join  .=   ' LEFT JOIN {{listing_users}} p_usr1 on p_usr1.user_id = usr.parent_user ';
		$criteria->select = 't.*,cat.slug as cat_slug1,sec.slug as sec_slug1, ct.slug as city_slug,cm.community_name,usr.user_type,(CASE WHEN p_usr1.company_logo is not null THEN p_usr1.company_logo ELSE usr.company_logo END) as company_logo,usr.first_name,usr.last_name, (CASE WHEN p_usr1.company_name is not null THEN p_usr1.company_name ELSE usr.company_name END) as company_name';
		if ($show_multiple) {
			$criteria->select .= ',(SELECT  group_concat(`image_name`)  FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0"    )   as ad_images_g';
		}
		$criteria->select .= ',(SELECT image_name FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0" limit 1 )   as ad_image';

		if (!empty($langaugae) and  $langaugae != 'en') {

			$criteria->params[':lan'] = $langaugae;

			/*joining category*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.category_id = t.category_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdata.message   IS NOT NULL THEN tdata.message ELSE cat.category_name  END as  category_name  ';

			/*joining state*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelations` on translationRelations.state_id = t.state    LEFT  JOIN mw_translation_data tdatas ON (`translationRelations`.translate_id=tdatas.translation_id and tdatas.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdatas.message  IS NOT NULL THEN tdatas.message ELSE st.state_name END as  state_name  ';

			/*joining city*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelationcc` on translationRelationcc.city_id = t.city    LEFT  JOIN mw_translation_data tdatacc ON (`translationRelationcc`.translate_id=tdatacc.translation_id and tdatacc.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdatacc.message  IS NOT NULL THEN tdatacc.message ELSE ct.city_name END as  city_name  ';


			/*section*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelationsc` on translationRelationsc.section_id = t.section_id    LEFT  JOIN mw_translation_data tdatasc ON (`translationRelationsc`.translate_id=tdatasc.translation_id and tdatasc.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdatasc.message  IS NOT NULL THEN tdatasc.message ELSE sec.section_name END as  section_name2  ';
			if (!empty($tit)) {
				$criteria->join  .= 'left join `mw_translate` `translateAD` on (  translateAD.source_tag = concat("PlaceAnAd_ad_title_",t.id) )          left join `mw_translate_relation` `translationRelationAD` on translationRelationAD.ad_id = t.id  and  translationRelationAD.translate_id = translateAD.translate_id  LEFT  JOIN mw_translation_data tdataAD ON (`translationRelationAD`.translate_id=tdataAD.translation_id and tdataAD.lang=:lan  ) ';
				$criteria->join  .= ' left join `mw_translate` `translateAD1` on ( translateAD1.source_tag = concat("PlaceAnAd_ad_description_","",t.id) )   left join `mw_translate_relation` `translationRelationAD1` on translationRelationAD1.ad_id = t.id  and  translationRelationAD1.translate_id = translateAD1.translate_id  LEFT  JOIN mw_translation_data tdataAD1 ON (`translationRelationAD1`.translate_id=tdataAD1.translation_id and tdataAD1.lang=:lan  ) ';
				$criteria->select   .= ' , CASE WHEN tdataAD.message IS NOT NULL AND translateAD.source_tag IS NOT NULL    THEN  tdataAD.message ELSE t.ad_title END	 as  ad_titleN , CASE WHEN tdataAD1.message IS NOT NULL AND translateAD1.source_tag IS NOT NULL THEN  tdataAD1.message    ELSE t.ad_description END	 as  ad_descriptionN ';

				$criteria->distinct = 'id';
			}

			/*joining ad title */
			//$criteria->join  .= 'left join `mw_translate` `translatea` on (  translatea.source_tag = concat("PlaceAnAd_ad_title_",t.id) )          left join `mw_translate_relation` `translationRelationa` on translationRelationa.ad_id = t.id  and  translationRelationa.translate_id = translatea.translate_id  LEFT  JOIN mw_translation_data tdataa ON (`translationRelationa`.translate_id=tdataa.translation_id and tdataa.lang=:lan  ) ';
			//$criteria->select   .= ' , CASE WHEN tdataa.message IS NOT NULL AND translatea.source_tag IS NOT NULL    THEN  tdataa.message ELSE t.ad_title END	 as  ad_title2  ';
			$criteria->group = 't.id';
		} else {
			$criteria->select .= ',ct.city_name,cat.category_name as  category_name,ad_title as ad_title2,st.state_name,sec.section_name as  section_name2 ';
		}
		if (!empty($not_in_array)) {
			$criteria->addNotInCondition('t.id', $not_in_array);
		}
		//$criteria->condition  .= ' and  t.status="A" and t.category_id in  ('.$ids.')  and t.section_id in ("'.self::SALE_ID.'","'.self::RENT_ID.'")  ';
		//$ids = join('","',Yii::app()->options->get('system.common.residential_categories'));
		//$criteria->params[':category'] =  '(30,95,32,31)' ; 
		if (!empty($section_id)) {
			$criteria->condition  .= ' and  t.section_id =:section    ';
			$criteria->params[':section'] = $section_id;
		}
		if ($country_id) {
			$criteria->condition .= ' and t.country = :country ';
			$criteria->params[':country'] = $country_id;
		}
		if ($state) {
			$criteria->condition .= ' and t.state = :state ';
			$criteria->params[':state'] = $state;
		}
		if (!empty($category_id)) {
			if ($category_id == '31') {
				$criteria->condition .= ' and t.category_id in ("107","31","99") ';
				if (defined('REFRESH_LIST')) {
					$criteria->addNotInCondition('t.id', REFRESH_LIST);
				}
			} else {
				$criteria->condition .= ' and t.category_id = :cats ';
				$criteria->params[':cats'] = $category_id;
			}
		}

		if (!empty($featured_type)) {
			switch ($featured_type) {
				case 'F':
					$criteria->condition .= ' and t.featured = "Y" ';
					break;
				case 'R':
					$criteria->condition .= ' and t.recmnded = "1" ';
					break;
				case 'P':
					$criteria->condition .= ' and t.promoted = "1" ';
					break;
				case 'N':
					$criteria->condition .= ' and t.is_new = "1" ';
					break;
			}
		}

		if (Yii::app()->user->getId()) {
			$criteria->select .= ' ,fav.ad_id as fav ';
			$criteria->join  .= ' left join {{ad_favourite}} fav ON fav.ad_id = t.id and fav.user_id =:user_me';
			$criteria->params[':user_me'] = Yii::app()->user->getId();
		}
		$criteria->condition .= ' and usr.status = "A" and usr.isTrash="0"';
		$criteria->compare('t.status', 'A');
		if (!empty($default_order)) {
			switch ($default_order) {
				case 'best-asc':
					$order  = 't.is_new="1" desc,-t.priority desc , t.id desc  ';
					break;
				case 'date-desc':
					$order  = 't.id desc';
					break;
				case 'price-asc':
					$order  = 't.price  asc';
					break;
				case 'price-desc':
					$order  = 't.price  desc';
					break;
				case 'baths-desc':
					$order  = 't.bathrooms  desc';
					break;
				case 'beds-desc':
					$order  = 't.bedrooms  desc';
					break;
				case 'sqft-desc':
					$order  = 't.builtup_area  desc';
					break;
				case 'new':
					$order  = 't.id desc';
					break;
				default:
					$order  = ' t.recmnded = "1" desc , t.is_new = "1" desc , t.promoted = "1" desc  ,-t.priority desc , t.id desc ';
					break;
			}
			$criteria->order = $order;
		}
		$criteria->limit = $limit;
		return self::model()->findAll($criteria);
	}

	public $section_name;
	public $section_name2;
	public $fav;
	public $company_logo;
	public $cat_slug1;
	public $sec_slug1;
	public function findAds($formData = array(), $count_future = false, $returnCriteria = false, $calculate = false, $user_id = false, $featured = false)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.*'. $this->FetauredQuery.  $this->HotQuery.',st.state_name as state_name,cat.slug as cat_slug1,sec.slug as sec_slug1, ct.slug as city_slug, usr.user_type, usr.company_name,(SELECT image_name FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0" limit 1 )   as ad_image,usr.image as user_image,(CASE WHEN p_usr1.company_logo is not null THEN p_usr1.company_logo ELSE usr.company_logo END) as company_logo,usr.first_name,usr.last_name';
		$criteria->select .= ',(SELECT  group_concat(`image_name`)  FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0"    )   as ad_images_g';
		$criteria->compare('t.isTrash', '0');
		if(!isset($formData['no_status'])){
		$criteria->compare('t.status', 'A');
		}
		if (isset($formData['sector']) and $formData['sector'] == 'property-sold') {
			$criteria->compare('t.section_id', '1');
			$criteria->condition .= ' and coalesce(t.s_r,0) = "1"  ';
		} else if (isset($formData['sector']) and $formData['sector'] == 'property-rented') {
			$criteria->compare('t.section_id', '2');
			$criteria->condition .= ' and coalesce(t.s_r,0) = "1"  ';
		} else if (isset($formData['project_type']) and $formData['project_type'] == 'sold') {

			$criteria->condition .= ' and coalesce(t.s_r,0) = "1"  ';
		} else if (defined('HIDE_SOLD')) {

			$criteria->condition .= ' and coalesce(t.s_r,0) !="1"  ';
		}
		if (!empty($user_id)) {
			$criteria->condition .= ' and (CASE WHEN usr.parent_user is NOT NULL THEN (usr.parent_user = :thisusr or   t.user_id = :thisusr )   ELSE     t.user_id = :thisusr  END) ';
			$criteria->params[':thisusr'] = (int) $user_id;
		}
		$criteria->distinct =  't.id';
		$criteria->join  .= ' left join {{countries}} cn ON cn.country_id = t.country ';
		$criteria->join  .= ' left join {{category}} cat ON cat.category_id = t.category_id ';
		$criteria->join  .= ' left join {{city}} city2 ON city2.city_id = t.city_2 ';
		$criteria->join  .= ' left join {{city}} city3 ON city3.city_id = t.city_3 ';
		$criteria->join  .= ' left join {{city}} city4 ON city4.city_id = t.city_4 '; 
		//$criteria->join  .= ' left join {{community}} com ON com.community_id = t.community_id ';
	    $criteria->join  .= ' left join {{states}} st ON st.state_id = t.state ';

		$criteria->join  .= ' left join {{category}} category ON category.category_id = t.listing_type ';
		$criteria->join  .= ' left join {{category}} property_type ON property_type.category_id = t.category_id ';


		$criteria->join  .= ' left join {{city}} ct ON ct.city_id = t.city ';
		$criteria->join  .= ' left join {{section}} sec ON sec.section_id = t.section_id ';
		$criteria->join  .=   ' INNER JOIN {{listing_users}} usr on usr.user_id = t.user_id ';
		$criteria->join  .=   ' LEFT JOIN {{listing_users}} p_usr1 on p_usr1.user_id = usr.parent_user ';

		$criteria->condition .= ' and usr.status = "A" and usr.isTrash="0"';
		if (Yii::app()->user->getId()) {
			$criteria->select .= ' ,fav.ad_id as fav ';
			$criteria->join  .= ' left join {{ad_favourite}} fav ON fav.ad_id = t.id and fav.user_id =:user_me';
			$criteria->params[':user_me'] = Yii::app()->user->getId();
		}
		if (isset($formData['filter']) and !empty($formData['filter'])) {
			switch ($formData['filter']) {
				case 'featured':
					$criteria->condition .= ' and t.featured="Y" ';
					break;
				case 'month':
					$criteria->condition .= ' and   t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ';
					break;
				case 'week':
					$criteria->condition .= ' and   t.date_added BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ';
					break;
			}
		}
		// print_r($formData);exit;
		if (isset($formData['user_id']) and !empty($formData['user_id'])) {
			//$criteria->condition .= ' and t.user_id =:user_id ';
			//	$criteria->params[':user_id'] = $formData['user_id'];
			$criteria->condition .= ' and CASE WHEN usr.parent_user is NOT NULL THEN (usr.parent_user = :user_id or   t.user_id = :user_id )   ELSE     t.user_id = :user_id  END ';
			$criteria->params[':user_id'] = (int) $formData['user_id'];
		}
		if (isset($formData['s_r']) and !empty($formData['s_r'])) {
			$criteria->condition .= ' and coalesce(t.s_r,0) ="1"  ';
		}
		if (isset($formData['property_type']) and !empty($formData['property_type'])) {
			$criteria->condition .= ' and property_type.slug =:property_type ';
			$criteria->params[':property_type'] = $formData['property_type'];
		}
		if (isset($formData['category']) and !empty($formData['category'])) {
			$criteria->condition .= ' and category.slug =:category ';
			$criteria->params[':category'] = $formData['category'];
		}
		if (isset($formData['_sec_id']) and !empty($formData['_sec_id'])) {
			$criteria->condition .= ' and t.section_id =:new_section_id ';
			$criteria->params[':new_section_id'] = $formData['_sec_id'];
		}


		if (isset($formData['developer_id']) and !empty($formData['developer_id'])) {

			$developerModel = Developers::model()->findbyAttributes(array('slug' => $formData['developer_id']));
			if ($developerModel) {
				$criteria->condition .= ' and t.developer_id =:developer_id ';
				$criteria->params[':developer_id'] = $developerModel->developer_id;
			}
		}

		if (isset($formData['_state_id']) and !empty($formData['_state_id'])) {
			$criteria->condition .= ' and t.state =:_state_id ';
			$criteria->params[':_state_id'] = $formData['_state_id'];
		}
		$location_search = false;
		if (isset($formData['state']) and !empty($formData['state'])) {
			$criteria->condition .= ' and st.slug=:state ';
			$criteria->params[':state'] = $formData['state'];
			$location_search = true;
		}
		if (isset($formData['project_type']) and !empty($formData['project_type'])) {
			if ($formData['project_type'] == 'sold') {
			} else {
				$criteria->condition  .=  ' and t.construction_status = :project_type ';
				$criteria->params[':project_type'] = $formData['project_type'];
			}
		}
		if (isset($formData['c1']) and !empty($formData['c1'])) {
			$criteria->condition  .=  ' and t.c1 = :c1 ';
			$criteria->params[':c1'] = $formData['c1'];
		}
		if (isset($formData['p_type']) and !empty($formData['p_type'])) {


			$pp_type =  Category::model()->findByAttributes(array('slug' => $formData['p_type']));
			if ($pp_type) {
				$criteria->join  .= ' left join {{ad_property_types}} apt ON apt.ad_id = t.id and apt.type_id  =    :p_type    ';
				$criteria->params[':p_type'] = $pp_type->category_id;
				$criteria->condition  .=  ' and apt.ad_id is NOT NULL ';
			}
		}
		if (isset($formData['area']) and !empty($formData['area'])) {

			$list_stat = array_filter((array)explode('|', $formData['area']));
			$list_stat[] = $formData['state'];
			$list_stat[] = @$formData['city'];

			$criteria->addInCondition('ct.slug', $list_stat);
			$location_search = true;
		} else if (isset($formData['city']) and !empty($formData['city'])) {
			if (isset($formData['level'])) {
				switch ($formData['level']) {
					case '2':
						$criteria->condition .= ' and city2.slug = :city  ';
						$criteria->params[':city'] = $formData['city'];
						$location_search = true;
						break;
					case '3':
						$criteria->condition .= ' and city3.slug = :city  ';
						$criteria->params[':city'] = $formData['city'];
						$location_search = true;
						break;
					case '4':
						$criteria->condition .= ' and city4.slug = :city  ';
						$criteria->params[':city'] = $formData['city'];
						$location_search = true;
						break;
					default:
						$criteria->condition .= ' and ct.slug = :city  ';
						$criteria->params[':city'] = $formData['city'];
						$location_search = true;
						break;
				}
			} else {
				$criteria->condition .= ' and ct.slug like :city  ';

				$criteria->params[':city'] = $formData['city'] . '%';
				$location_search = true;
			}
		}

		if (isset($formData['loc']) and !empty($formData['loc']) and !$location_search) {
			$criteria->condition .= ' and (    LOWER(ct.city_name) like :loc ) ';
			$criteria->params[':loc'] = '%' . $formData['loc'] . '%';
		}

		/*
		else if(isset($formData['country']) and !empty($formData['country'])){
			$criteria->join  .= ' left join {{countries}} cn ON cn.country_id = t.country ';
			$criteria->condition .= ' and cn.slug=:country ';$criteria->params[':country'] = $formData['country'];
		}
		* */ else if (isset($formData['country']) and !empty($formData['country'])) {

			$criteria->condition .= ' and cn.slug=:country ';
			$criteria->params[':country'] = $formData['country'];
		}
		if (isset($formData['section_id']) and !empty($formData['section_id'])) {
			//$criteria->join  .= ' left join {{section}} sec ON sec.section_id = t.section_id ';
			$criteria->condition .= ' and sec.slug=:section_id ';
			$criteria->params[':section_id'] = $formData['section_id'];
		}
		if (isset($formData['sec']) and !empty($formData['sec'])) {
			//$criteria->join  .= ' left join {{section}} sec ON sec.section_id = t.section_id ';
			$criteria->condition .= ' and sec.slug=:sec ';
			$criteria->params[':sec'] = $formData['sec'];
		}
		if (isset($formData['minPrice']) and !empty($formData['minPrice'])) {

			$criteria->condition .= ' and t.price>=:minPrice ';
			$criteria->params[':minPrice'] = $formData['minPrice'];
		}
		if (isset($formData['maxPrice']) and !empty($formData['maxPrice'])) {

			$criteria->condition .= ' and t.price<=:maxPrice ';
			$criteria->params[':maxPrice'] = $formData['maxPrice'];
		}
		if (isset($formData['furnished']) and !empty($formData['furnished'])) {
			switch ($formData['furnished']) {
				case 'Y':
					$criteria->condition .= ' and t.furnished = "Y" ';
					break;
				case 'N':
					$criteria->condition .= ' and t.furnished = "N" ';
					break;
			}
		}
		if (isset($formData['verified']) and !empty($formData['verified'])) {
			switch ($formData['verified']) {
				case '1':
					$criteria->condition .= ' and t.recmnded = "1" ';
					break;
			}
		}
		if (isset($formData['property_status']) and !empty($formData['property_status'])) {
			switch ($formData['property_status']) {
				case 'ready':
					$criteria->condition .= ' and t.construction_status = "R" ';
					break;
				case 'off-plan':
					$criteria->condition .= ' and t.construction_status = "N" ';
					break;
			}
		}
		if (isset($formData['bedrooms']) and !empty($formData['bedrooms'])) {
			if ($formData['bedrooms'] == '15') {
				$criteria->condition .= ' and t.bedrooms =:bedrooms ';
			} else {
				/*15 is studio*/
				if ($formData['bedrooms'] == '5') {
					$criteria->condition .= ' and   t.bedrooms >=:bedrooms  and t.bedrooms != "15"';
				} else {
					$criteria->condition .= ' and t.bedrooms =:bedrooms and t.bedrooms != "15"';
				}
			}
			$criteria->params[':bedrooms'] = $formData['bedrooms'];
		}
		if (isset($formData['beds']) and !empty($formData['beds'])) {
			if ($formData['beds'] == '15') {
				$criteria->condition .= ' and t.bedrooms =:beds ';
			} else {
				/*15 is studio*/
				if ($formData['beds'] == '5') {
					$criteria->condition .= ' and   t.bedrooms >=:beds  and t.bedrooms != "15"';
				} else {
					$criteria->condition .= ' and t.bedrooms =:beds and t.bedrooms != "15"';
				}
			}
			$criteria->params[':beds'] = $formData['beds'];
		}
		if (isset($formData['type_of'])  and is_array($formData['type_of'])) {
			$arm =  	array_filter($formData['type_of']);
			if (!empty($arm)) {
				if (sizeOf($formData['type_of']) == '1') {
					$criteria->condition .= ' and t.listing_type =:type_of ';
					$criteria->params[':type_of'] = @$formData['type_of'][0];
				} else {
					$criteria->addInCondition('t.listing_type', $formData['type_of']);
				}
			}
		}
		if (isset($formData['cat'])  and is_array($formData['cat'])) {
			$arm =  	array_filter($formData['cat']);
			if (!empty($arm)) {
				if (sizeOf($formData['cat']) == '1') {
					$criteria->condition .= ' and t.category_id =:cat ';
					$criteria->params[':cat'] = @$formData['cat'][0];
				} else {
					$criteria->addInCondition('t.category_id', $formData['cat']);
				}
			}
		}
		$langaugae = OptionCommon::getLanguage();
		if (isset($formData['keyword']) and !empty($formData['keyword'])) {
			$word = '';
			if (!empty($langaugae) and  $langaugae != 'en') {
				$word = ' or tdataa.message like :keyword ';
			}
			$refr = '';
			if (strpos($formData['keyword'], 'AP-') !== false) {
				$refr =  ' or concat("AP","-",LPAD(t.id,5,"0")) = :keyword2 ';
				$criteria->params[':keyword2'] =  $formData['keyword'];
			}
			$criteria->condition .= ' and ( t.ad_title like :keyword or t.ad_description like :keyword ' . $word . ' ' . $refr . '  )   ';


			$criteria->params[':keyword'] = '%' . $formData['keyword'] . '%';
		}
		if (isset($formData['keywords']) and !empty($formData['keywords'])) {
			$word = '';
			if (!empty($langaugae) and  $langaugae != 'en') {
				$word = ' or tdataa.message like :keywords ';
			}
			$refr = '';
			if (strpos($formData['keywords'], 'AP-') !== false) {
				$refr =  ' or concat("AP","-",LPAD(t.id,5,"0")) = :keywords2 ';
				$criteria->params[':keyworsd2'] =  $formData['keywords'];
			}
			$criteria->condition .= ' and ( t.ad_title like :keywords or t.ad_description like :keywords or   ct.city_name like :keywords  ' . $word . ' ' . $refr . '  )   ';


			$criteria->params[':keywords'] = '%' . $formData['keywords'] . '%';
		}
		if (isset($formData['keywords'])) {
			$kywordsList = $this->findCategoryyfromkeyword($formData['keywords']);
			if (!empty($kywordsList)) {
				$ids = implode(', ', $kywordsList);
				$criteria->condition .= ' and (t.listing_type in (' . $ids . ') or t.category_id in (' . $ids . ') ) ';
			}
		}

		if (isset($formData['recmnded']) and !empty($formData['recmnded'])) {
			$criteria->condition .= ' and t.recmnded = "1" ';
		}
		if (isset($formData['poplar_area']) and !empty($formData['poplar_area'])) {
			$PopularCities =   PopularCities::model()->findByPk($formData['poplar_area']);
			if ($PopularCities) {
				$criteria->addInCondition('t.city', $PopularCities->cities);
			}
		}
		if (isset($formData['bathrooms']) and !empty($formData['bathrooms'])) {
			if ($formData['bathrooms'] == '5') {
				$criteria->condition .= ' and t.bathrooms  >=:bathrooms ';
			} else {
				$criteria->condition .= ' and t.bathrooms  =:bathrooms ';
			}

			$criteria->params[':bathrooms'] = $formData['bathrooms'];
		}
		if (isset($formData['baths']) and !empty($formData['baths'])) {
			if ($formData['baths'] == '5') {
				$criteria->condition .= ' and t.bathrooms  >=:baths ';
			} else {
				$criteria->condition .= ' and t.bathrooms  =:baths ';
			}

			$criteria->params[':baths'] = $formData['baths'];
		}
		if (isset($formData['minSqft']) and !empty($formData['minSqft'])) {
			$criteria->condition .= ' and t.builtup_area >=:minSqft ';
			$criteria->params[':minSqft'] = $formData['minSqft'];
		}
		if (isset($formData['maxSqft']) and !empty($formData['maxSqft'])) {
			$criteria->condition .= ' and t.builtup_area <=:maxSqft ';
			$criteria->params[':maxSqft'] = $formData['maxSqft'];
		}
		if (isset($formData['community']) and !empty($formData['community'])) {
			$criteria->condition .= ' and com.community_id  =:community ';
			$criteria->params[':community'] = $formData['community'];
		}
		if (!empty($featured)) {
			$criteria->condition .= ' and t.featured="Y" ';
		}
		if (defined('OFFPLAN')) {
			$criteria->addInCondition('t.channel', array('O', 'B'));
		} else {
			$criteria->addInCondition('t.channel', array('A', 'B'));
		}
		if (!empty($langaugae) and  $langaugae != 'en' and !defined('NO_LNGUAGE')) {
			$criteria->params[':lan'] = $langaugae;

			/*joining category*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.category_id = t.category_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdata.message   IS NOT NULL THEN tdata.message ELSE cat.category_name  END as  category_name  ';

			/*joining state*/
			//$criteria->join  .= ' left join `mw_translate_relation` `translationRelations` on translationRelations.state_id = t.state    LEFT  JOIN mw_translation_data tdatas ON (`translationRelations`.translate_id=tdatas.translation_id and tdatas.lang=:lan) ';
			//$criteria->select .= ' ,CASE WHEN tdatas.message  IS NOT NULL THEN tdatas.message ELSE st.state_name END as  state_name  ';
			/*joining ad title */
			/*joining city*/
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelationc` on translationRelationc.city_id = t.city    LEFT  JOIN mw_translation_data tdatac ON (`translationRelationc`.translate_id=tdatac.translation_id and tdatac.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdatac.message  IS NOT NULL THEN tdatac.message ELSE ct.city_name END as  city_name  ';
			/*joining ad title */
			/*joining section*/

			$criteria->join  .= ' left join `mw_translate_relation` `translationRelationsc` on translationRelationsc.section_id = t.section_id    LEFT  JOIN mw_translation_data tdatasc ON (`translationRelationsc`.translate_id=tdatasc.translation_id and tdatasc.lang=:lan) ';
			$criteria->select .= ' ,CASE WHEN tdatasc.message  IS NOT NULL THEN tdatasc.message ELSE sec.section_name END as  section_name  ';

			/*joining ad title */
			$criteria->join  .= 'left join `mw_translate` `translatea` on (  translatea.source_tag = concat("PlaceAnAd_ad_title_",t.id) )          left join `mw_translate_relation` `translationRelationa` on translationRelationa.ad_id = t.id  and  translationRelationa.translate_id = translatea.translate_id  LEFT  JOIN mw_translation_data tdataa ON (`translationRelationa`.translate_id=tdataa.translation_id and tdataa.lang=:lan  ) ';
			$criteria->select   .= ' , CASE WHEN tdataa.message IS NOT NULL AND translatea.source_tag IS NOT NULL    THEN  tdataa.message ELSE t.ad_title END	 as  ad_title2  ';

			if (isset($formData['sec']) and $formData['sec'] == 'development') {
				$criteria->join  .= 'left join `mw_translate` `translatedesc` on (  translatedesc.source_tag = concat("PlaceAnAd_ad_description_",t.id) )          left join `mw_translate_relation` `translationRelationdesc` on translationRelationdesc.ad_id = t.id  and  translationRelationdesc.translate_id = translatedesc.translate_id  LEFT  JOIN mw_translation_data tdatadesc ON (`translationRelationdesc`.translate_id=tdatadesc.translation_id and tdatadesc.lang=:lan  ) ';
				$criteria->select   .= ' , CASE WHEN tdatadesc.message IS NOT NULL AND translatedesc.source_tag IS NOT NULL    THEN  tdatadesc.message ELSE t.ad_description END	 as  ad_descriptionN  ';
			}
			$criteria->group = 't.id';
		} else {
			$criteria->select .= ',cat.category_name as  category_name ,ct.city_name , sec.section_name,ad_title as    ad_title2  ';
		}


		$order_val = '';
		if (isset($formData['sort'])  and !empty($formData['sort'])) {
			$order_val = $formData['sort'];
		}
		if (isset($formData['order'])  and !empty($formData['order'])) {
			$order_val = $formData['order'];
		}
		if (isset($formData['a']) and !empty($formData['a']) and isset($formData['b']) and !empty($formData['b']) and isset($formData['c']) and !empty($formData['c']) and isset($formData['d']) and !empty($formData['d'])) {
			$condition1 = $formData['a'] < $formData['c'] ? "t.location_latitude > :a AND t.location_latitude < :c" : "(t.location_latitude > :a OR t.location_latitude < :c)";
			$condition2 = $formData['b'] < $formData['d'] ? "t.location_longitude > :b AND t.location_longitude < :d" : "(t.location_longitude > :d OR t.location_longitude < :b)";
			$q = " and ( $condition1 ) AND ( $condition2 )";
			$criteria->condition .=  $q;
			//$criteria->condition .=  ' and   t.location_latitude > :a AND  t.location_latitude  < :c AND  t.location_longitude > :b AND  t.location_longitude < :d ' ; 
			//$criteria->condition .=  ' and   (CASE WHEN :a < :c         THEN  t.location_latitude BETWEEN :a AND :c         ELSE  t.location_latitude BETWEEN :c AND :a END) AND (CASE WHEN :b < :d         THEN  t.location_longitude BETWEEN :b AND :d         ELSE  t.location_longitude BETWEEN :d AND :b END) ' ; 
			$criteria->params[':a'] = $formData['a'];
			$criteria->params[':b'] = $formData['b'];
			$criteria->params[':c'] = $formData['c'];
			$criteria->params[':d'] =  $formData['d'];
		}
		switch ($order_val) {

			case 'best-asc':
				$order  = '  t.recmnded = "1" desc , t.is_new = "1" desc , t.promoted = "1" desc  ,-t.priority desc , t.id desc ';
				break;
			case 'date-desc':
				$order  = 't.id desc';
				break;
			case 'fur-desc':

				$criteria->join  .= ' left join {{ad_amenities}} adam ON adam.ad_id = t.id and amenities_id = "287" ';
				$order  = 't.furnished="Y" desc, adam.ad_id desc  ,t.ad_title like "%furnished%" desc ,  t.recmnded="1" desc,t.is_new="1" desc,t.promoted="1" desc ,t.id desc   ';
				break;
			case 'fur-asc':
				$order  = 't.furnished="N" desc  ,t.ad_title like "%unfurnished%" desc ,  t.recmnded="1" desc,t.is_new="1" desc,t.promoted="1" desc ,t.id desc   ';
				break;
			case 'price-asc':
				$order  = 't.price  asc';
				break;
			case 'price-desc':
				$order  = 't.price  desc';
				break;
			case 'baths-desc':
				$order  = 't.bathrooms  desc';
				break;
			case 'beds-desc':
				$order  = 't.bedrooms  desc';
				break;
			case 'sqft-desc':
				$order  = 't.builtup_area  desc';
				break;
			case 'popular-desc':
				$order  = ' t.recmnded="1" desc,t.is_new="1" desc,t.promoted="1" desc ,t.quality desc,t.id desc ';
				$order  = $this->getFetauredOrders() . ','.$this->getHotOrders().',t.recmnded = "1" desc , t.is_new = "1" desc , t.promoted = "1" desc  ,-t.priority desc , t.id desc ';
				break;
			default:
			 
				$order  = ' t.category_id ="30" desc,  t.recmnded = "1" desc , t.is_new = "1" desc , t.promoted = "1" desc  ,-t.priority desc ,  t.category_id ="31" desc ,  t.id desc';
				
				if(isset($formData['city'])){
					
				 	 $order  = $this->getFetauredOrders() . ',' . $this->getHotOrders() . ',' . $this->getRefreshOrders() . ',  t.category_id ="30" desc ' .  ', t.recmnded = "1" desc , t.is_new = "1" desc , t.promoted = "1" desc  ,-t.priority desc ,  t.category_id ="31" desc ,  t.id desc';
				} 
				break;
		}

		$criteria->order  =   $order;
		$total = false;
		if ($returnCriteria) {
			return $criteria;
		}

		$criteria->limit  = Yii::app()->request->getQuery('limit', '10');
		$criteria->offset = Yii::app()->request->getQuery('offset', '0');
		/* SaFE neighbours */
		if (isset($formData['sort'])  and  $formData['sort'] == 'custom') {
			$criteria->limit  =  5;
			$criteria->order  =   $formData['custom_order'];
		}
		if (isset($formData['sort'])  and  $formData['sort'] == 'featured') {
			$criteria->limit  =  15;
			//$criteria->order  =   't.featured="Y" desc,-t.priority desc , t.id desc ';
			$criteria->order  =   't.featured="Y" desc, t.id desc ';
		}
		if ($calculate and $criteria->offset == 0) {
			$total = self::model()->count($criteria);
		}
		if (!empty($count_future)) {
			$Result = self::model()->findAll($criteria);
			$criteria->offset = $criteria->limit + $criteria->offset;
			$criteria->select = 't.id';
			$criteria->limit = '1';
			$future_count = self::model()->find($criteria);
			return array('result' => $Result, 'future_count' => $future_count, 'total' => $total);
		} else {
			return  self::model()->findAll($criteria);
		}
	}
	public $state_slug;
	public $sec_slug;
	public $sub_category_name;
	public function getPriceTitle($code = '')
	{

		$code = $this->currencyTitle;
		$html =  $code .' '. number_format($this->price, 0, '.', ',');
		if ($this->section_id == self::RENT_ID) {
			$html .= '/' . $this->RentPaid3;
		}
		return $html;
	}
	public function getPriceTitleSpan($code = '')
	{

		$code = $this->currencyTitle;
		$html =  '<span class="pri sec_' . $this->section_id . '">' . number_format($this->price, 0, '.', ',') . '</span> ' . $code;
		if ($this->section_id == self::RENT_ID) {
			$html .= '/' . $this->RentPaid3;
		}
		return $html;
	}
	public function getPriceTitleDetail()
	{
		if ($this->p_o_r == '1') {
			return $this->mTag()->getTag('ask_for_price', 'Ask for Price');
		}
		if ($this->price == '0.00') {
			return $this->mTag()->getTag('ask_for_price', 'Ask for Price');
		}
		$html = '<span data-id="' . $this->price . '">' . $this->currencyTitle . '</span>' . number_format($this->price, 0, '.', ',');
		if ($this->section_id == self::RENT_ID) {
			$html .= '/' . $this->RentPaid3;
		}
		return $html;
	}

	public function hide_bed_bath()
	{
		return array('83', '101');
	}
	public function getBuiltUpAreaTitle()
	{
		return  $this->BuiltUpArea;
	}
	public function getBathroomTitle()
	{
		if ($this->bathrooms == '14') {
			return '13+';
		}
		return  $this->bathrooms;
	}
	public function getBedroomTitle()
	{
		if ($this->bedrooms == '14') {
			return '13+';
		}
		if ($this->bedrooms == '15') {
			return 'Studio';
		}
		return  $this->bedrooms;
	}
	public $ad_title2;
	public $city_name;
	public function getAdTitle()
	{
		if (defined('LANGUAGE')) {
			$lan = LANGUAGE;
		} else {
			$lan = 'en';
		}
		switch ($lan) {
			case 'en':
				return $this->ad_title;
				break;
			case 'ar':
				return    !empty($this->ad_title_ar) ? $this->ad_title_ar : $this->ad_title;
				break;
			default:
				return $this->ad_title;
				break;
		}
	}
	public function getAdTitle2()
	{
		return  $this->ad_title2;
	}
	public function getDetailUrlOffplan()
	{

		return Yii::app()->createUrl('listings/project', array('slug' => $this->slug));
		return '#come';
	}
	public function getDetailUrlOffplanAbsolute()
	{

		return Yii::app()->createAbsoluteUrl('listings/project', array('slug' => $this->slug));
		return '#come';
	}
	public function getAdCriteria($id)
	{
		$criteria = new CDbCriteria;
		$criteria->select  = 't.section_id,cat1.slug as cat_slug1,sec1.slug as sec_slug1, ct.slug as city_slug,t.slug';
		$criteria->join  .= ' left join {{category}} cat1 ON cat1.category_id = t.category_id ';
		$criteria->join  .= ' left join {{section}} sec1 ON sec1.section_id = t.section_id ';
		$criteria->join  .= ' left join {{city}} ct ON ct.city_id = t.city ';
		$criteria->compare('t.id', (int) $id);
		return self::model()->find($criteria);
	}
	public function getDetailUrl()
	{

		if ($this->section_id == self::NEW_ID) {
			return Yii::app()->createUrl('detail/project', array('slug' => $this->slug));
		}
		if (!empty($this->cat_slug1)) {
			return  Yii::app()->createAbsoluteUrl('detail/index', array('sec' => $this->sec_slug1, 'city' => $this->city_slug, 'cat' => $this->cat_slug1, 'slug' => $this->slug));
		}
		$ad = $this->getAdCriteria($this->primaryKey);
		return $ad->DetailUrl;
	}
	public function getDetailUrlAbsolute()
	{
		if ($this->section_id == self::NEW_ID) {
			return Yii::app()->createAbsoluteUrl('detail/project', array('slug' => $this->slug));
		}
		if (!empty($this->cat_slug1)) {
			return  Yii::app()->createAbsoluteUrl('detail/index', array('sec' => $this->sec_slug1, 'city' => $this->city_slug, 'cat' => $this->cat_slug1, 'slug' => $this->slug));
		}
		$ad = $this->getAdCriteria($this->primaryKey);
		return $ad->DetailUrlAbsolute;
	}
	public function getLocationTitle()
	{
		if (!empty($this->city_name)) {
			return $this->city_name . ',' . $this->state_name;
		} else {
			return $this->state_name;
		}
	}
	public function getMainImage()
	{
		return Yii::app()->apps->getBaseUrl('uploads/images/' . $this->ad_image);
	}
	public function getMainImageResized($height = 100, $width = 100)
	{
		$app = Yii::app();
		return $app->apps->getBaseUrl('timthumb.php') . '?src=' . $app->apps->getBaseUrl('uploads/images/' . $this->ad_image) . '&h=' . $height . '&w=' . $width . '&zc=1';
	}
	public function GetMapHtml()
	{
		$app = Yii::app();
		if (defined('offline')) {
			$this->ad_image = '1019_1571412256global-city-ajman-ajman-properties_.jpg';
		}
		$img =  $app->apps->getBaseUrl('timthumb.php') . '?src=' . $app->apps->getBaseUrl('uploads/images/' . $this->ad_image) . '&h=80&w=200&zc=1';

		$html =  '<div class="d-flex-itm"><div class="cardPhoto backgroundPulse " style=" background-image:url(' . $img . ');" data-reactid="' . $this->id . '"></div><div class="cardDetails man pts pbn phm h6 typeWeightNormal" data-reactid="' . $this->id . '"><div data-reactid="' . $this->id . '"><span class="cardPrice h5 man pan typeEmphasize noWrap typeTruncate" data-reactid="59">' . $this->PriceTitle . '</span></div><div data-reactid="61"> <ul class="listInline typeTruncate mvn" data-reactid="62">  ';
		if (!empty($this->builtup_area)) {
			$html .= '<li data-auto-test="sqft" data-reactid="">' . $this->BuiltUpAreaTitle . '</li>';
		}
		$html .= '</ul></div><div class=" man pts pbn phm h6 typeWeightNormal" data-reactid="' . $this->id . '"><div data-reactid="' . $this->id . '"><span class="cardPrice h5 man pan typeEmphasize noWrap typeTruncate dep-text"  >' . $this->ad_title . '</span></div><div></div></div>';
		return $html;
	}
	public function getCurrencyCode()
	{
		return $this->currencyTitle;
	}
	public function getPriceArray()
	{
		$code = $this->currencycode;
		return
			array(
				'10000' => $code . '10k',
				'20000' => $code . '20k',
				'30000' => $code . '30k',
				'50000' => $code . '50k',
				'100000' => $code . '100k',
				'130000' => $code . '130k',
				'150000' => $code . '150k',
				'200000' => $code . '200k',
				'250000' => $code . '250k',
				'300000' => $code . '300k',
				'350000' => $code . '350k',
				'400000' => $code . '400k',
				'450000' => $code . '450k',
				'500000' => $code . '500k',
				'550000' => $code . '550k',
				'600000' => $code . '600k',
				'650000' => $code . '650k',
				'700000' => $code . '700k',
				'750000' => $code . '750k',
				'800000' => $code . '800k',
				'850000' => $code . '850k',
				'900000' => $code . '900k',
				'950000' => $code . '950k',
				'1000000' => $code . '1m',

			);
	}
	public $minPrice;
	public $maxPrice;
	public function getPriceViewTitle()
	{
		$price_array = $this->getPriceArray();
		$maxPrice = '';
		$minPrice = '';
		if (!empty($this->minPrice) and isset($price_array[$this->minPrice])) {
			$minPrice = $price_array[$this->minPrice];
		}
		if (!empty($this->maxPrice) and isset($price_array[$this->maxPrice])) {
			$maxPrice = $price_array[$this->maxPrice];
		}
		if (empty($maxPrice) and empty($minPrice)) {
			return Yii::app()->tags->getTag('any-price', 'Any Price');
		} else if (empty($maxPrice)) {
			return $minPrice;
		} else if (empty($minPrice)) {
			return $maxPrice;
		} else {
			return $minPrice . '-' . $maxPrice;
		}
	}
	public function bedroomSearchIndex()
	{
		$tags = Yii::app()->tags;
		return array(
			'' => $tags->getTag('any', 'Any'),
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5+',
			'15' => $tags->getTag('studio', 'Studio'),
		);
	}
	public function bathroomSearchIndex()
	{
		$tags = Yii::app()->tags;
		return array(
			'' =>  $tags->getTag('any', 'Any'),
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5+',
		);
	}
	public function squareFeetSearch()
	{
		return
			array(
				'2000' => '2000 sqft',
				'3000' => '3000 sqft',
				'4000' => '4000 sqft',
				'5000' => '5000 sqft',
				'7500' => '7500 sqft',
				'10890' => '0.25+ acre',
				'21780' => '0.5+ acre',
				'43560' => '1+ acre',
				'87120' => '2+ acre',
				'217800' => '5+ acre',
				'435600' => '10+ acre',
			);
	}

	public $type_of;
	public $cat;
	public function getBedRoomTitleIndex()
	{

		if (!empty($this->bedrooms) and array_key_exists($this->bedrooms, $this->bedroomSearchIndex())) {
			return $this->bedroomSearchIndex()[$this->bedrooms];
		} else {
			return Yii::app()->tags->getTag('all-beds', 'All Beds');
		}
	}

	public function getHomeTypeTitle()
	{

		$arm = (array) $this->type_of;
		$arms = array_filter($arm);
		if (empty($arms)) {
			return Yii::app()->tags->getTag('all-category', 'All Category');
		} else if (sizeOf($this->type_of) > 1) {
			//return 'Category ('.sizeOf($this->type_of).')';
			return Yii::t('app', Yii::app()->tags->getTag('category_count', 'Category ({n})'), array('{n}' => sizeOf($this->type_of)));
		} else {
			$cate = Category::model()->categoryIdLan(@$this->type_of[0]);
			if ($cate) {
				return $cate->PluralTitle;
			}
			return 'Unknown';
		}
	}
	public function getCategoryTypeTitle()
	{

		$arm = (array) $this->cat;
		$arms = array_filter($arm);
		if (empty($arms)) {
			return Yii::app()->tags->getTag('all_type', 'Property Type');
		} else if (sizeOf($this->cat) > 1) {
			return  Yii::t('app', Yii::app()->tags->getTag('type_count', 'Type ({n})'), array('{n}' => sizeOf($this->cat)));
		} else {
			$cate = Category::model()->categoryIdLan(@$this->cat[0]);
			if ($cate) {
				return $cate->PluralTitle;
			}
			return 'Unknown';
		}
	}
	public $maxSqft;
	public $minSqft;
	public function getSectionViewTitle()
	{

		if (empty($this->section_id)) {
			return  Yii::app()->tags->getTag('all', 'All');
		} else {
			switch ($this->section_id) {
				case 'property-for-sale':
					return $this->mTag()->getTag('sale', 'Sale');
					break;
				case 'property-for-rent':
					return $this->mTag()->getTag('rent', 'Rent');
					break;
			}
			$sec =   Section::model()->sectionTitleFromSlug($this->section_id);
			if ($sec) {
				$html = !empty($sec->section_other) ? $sec->section_other : $sec->section_name;
				return   $html;
			} else {
				return 'Unknown';
			}
		}
	}
	public function getSectionBanner()
	{
		switch ($this->section_id) {
			case '1':
				$section_name = empty($this->section_name) ? 'Sale' : $this->section_name;
				return '<span class="block_tag for_sale_tag">' . $section_name . '</span>';
				break;
			case '2':
				$section_name = empty($this->section_name) ? 'Rent' : $this->section_name;
				return '<span class="block_tag for_rent_tag">' . $section_name . '</span>';
				break;
		}
	}
	public function getMoreTitle()
	{

		if (!empty($this->keyword) or !empty($this->bathrooms) or !empty($this->minSqft) or !empty($this->maxSqft)) {
			return Yii::app()->tags->getTag('searched-more', 'Searched More') . '++';
		} else {
			return Yii::app()->tags->getTag('more', 'More');
		}
	}
	public $sort;
	public function getSortHTml()
	{

		if (!empty($this->sort) and array_key_exists($this->sort, $this->sortArray())) {
			return $this->sortArray()[$this->sort];
		} else {
			return Yii::app()->tags->getTag('popular', 'Popular');
		}
	}
	public $user_image;
	public $user_type;
	public function sortArray()
	{
		$tags = Yii::app()->tags;
		return array(
			'best-asc' => $tags->getTag('popular', 'Popular'),
			//'fur-desc'=>$tags->getTag('sort-furnished','Furnished'),
			//'fur-asc'=>$tags->getTag('sort-unfurnished','Unfurnished'),
			'date-desc' => $tags->getTag('sort-newest', 'Newest'),
			'price-asc' => $tags->getTag('lowest-price', 'Lowest Price'),
			'price-desc' => $tags->getTag('highest-price', 'Highest Price'),
			//	'beds-desc'=>$tags->getTag('sort-bedrooms','Bedrooms'),
			//	'baths-desc'=>$tags->getTag('sort-bathrooms','Bathrooms'),
			//	'sqft-desc'=>$tags->getTag('sort-square','Square Feet'),
		);
	}
	public function sortArrayBackend()
	{
		$ar = $this->sortArray();
		$ar['best-asc'] = 'Featured first , priority , latest';
		return  $ar;
	}
	public function all_images()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.image_name,t.id';
		$criteria->condition = 't.status="A" and isTrash="0" and  t.ad_id = :ad   ';
		$criteria->params[':ad'] = $this->id;
		$criteria->order = '-t.priority desc,id asc';
		return AdImage::model()->findAll($criteria);
	}
 
	public function all_amentitie()
	{
		if(empty($this->id) and !empty($this->amenities)){
			$criteria = new CDbCriteria;
			$criteria->addInCondition('t.amenities_id',(array)$this->amenities);
			$criteria->order = ' amenities_name asc';
			$amenities =  Amenities::model()->findAll($criteria);
			return  $amenities ;  
		}
		$criteria = new CDbCriteria;
		$criteria->select = 't.amenities_id';
		$criteria->condition = 'am.status="A" and am.isTrash="0" and t.ad_id = :ad   ';
		$criteria->join = ' INNER JOIN {{amenities}} am on am.amenities_id = t.amenities_id ';
		$langaugae = OptionCommon::getLanguage();
		$criteria->params[':ad'] = $this->id;
		if (!empty($langaugae) and  $langaugae != 'en') {
			$criteria->params[':lan'] = $langaugae;
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.amenities_id = t.amenities_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' , CASE WHEN tdata.message IS NOT NULL THEN   tdata.message ELSE am.amenities_name END  as  amenities_name  ';
		} else {
			$criteria->select .= ' ,am.amenities_name';
		}
		$criteria->order = ' amenities_name asc';
		return AdAmenities::model()->findAll($criteria);
	}

	public $user_number;
	public $user_description;
	public function getUserName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
	public $user_slug;
	public function getUserProfile()
	{
		if (empty($this->user_slug)) {
			return  '#';
		}
		switch ($this->user_type) {
			case 'A':
				return   Yii::app()->createUrl('user_listing/detail', array('slug' => $this->user_slug));
				break;
			case 'D':
				return   Yii::app()->createUrl('user_listing/detail', array('slug' => $this->user_slug));
				break;
			case 'K':
				return   Yii::app()->createUrl('user_listing/detail', array('slug' => $this->user_slug));
				break;

			default:
				return  '#';
				break;
		}
		return  '#';
	}
	public function getPreviewUrlTrash()
	{

		if ($this->section_id == self::NEW_ID) {
			return Yii::app()->apps->getAppUrl('frontend', 'project/' . $this->slug . '?showTrash=1', true);
		}
		return   str_replace('backend/index.php/', '', $this->detailUrlAbsolute) . '?showTrash=1';
	}
	public function getStatusLinkFront()
	{
		$title = $this->StatusTitle;
		switch ($this->status) {
			case 'A':
				return '<span class="btn btn-xs  btn-success" title="' . $title . '">' . $title . '</span>';
				break;
			case 'W':
				return '<span class="btn btn-xs btn-teal"   title="' . $title . '">' . $title . '</span>';
				break;
			case 'I':
				return '<span class="btn btn-xs btn-warning"   title="' . $title . '">' . $title . '</span>';
				break;
			case 'R':
				return '<span class="btn btn-xs  btn-danger" title="' . $title . '">' . $title . '</span>';
				break;
			case 'D':
				return '<span class="btn btn-xs  btn-danger" title="' . $title . '">' . $title . '</span>';
				break;
		}
	}
	public function getMarkedSuccess()
	{
		if (in_array($this->section_id, array('1', '2'))) {
			$s_title = '';
			switch ($this->section_id) {
				case '1':
					$title = 'Mark  as Sold';
					if (!empty($this->s_r)) {
						$s_title = '<span class="success">Sold:</span> ';
						$title = 'Undo Sold';
					}
					break;
				case '2':
					$title = 'Mark  as Rented';
					if (!empty($this->s_r)) {
						$s_title = '<span class="success">Rented:</span> ';
						$title = 'Undo Rented';
					}
					break;
			}
			return $s_title . '' . CHtml::link($title, 'javascript:void(0)', array('onclick' => 'marksold(this)', 'data-id' => $this->primaryKey, 'data-status' => (int)$this->s_r, 'class' => 'marksold', 'section' => $this->section_id));
		}
	}
	public function getStatusLink()
	{
		$usrl = Yii::App()->createUrl('place_an_ad/view', array('id' => $this->id));
		switch ($this->status) {
			case 'A':
				return '<span id="as_' . $this->id . '" class="label  bg-green" title="Active" onclick="previewthis(this,event)"   href="' . $usrl . '">A</span>';
				break;
			case 'W':
				return '<span id="as_' . $this->id . '" class="label  bg-blue" onclick="previewthis(this,event)" title="Waiting" href="' . $usrl . '">W</span>';
				break;
			case 'I':
				return '<span id="as_' . $this->id . '" class="label  btn-warning" onclick="previewthis(this,event)" title="Inactive" href="' . $usrl . '"  >I</span>';
				break;
			case 'R':
				return '<span id="as_' . $this->id . '" class="label   bg-red" onclick="previewthis(this,event)" title="Waiting" href="' . $usrl . '"  title="Rejected">R</span>';
				break;
			case 'D':
				return '<span id="as_' . $this->id . '" class="label   bg-info" onclick="previewthis(this,event)" title="Waiting" href="' . $usrl . '"  title="Draft">D</span>';
				break;
		}
	}
	public function getStatusTitle()
	{
		$tags = Yii::app()->tags;
		switch ($this->status) {
			case 'A':
				return $tags->getTag('published', 'Published');
				break;
			case 'W':
				return $tags->getTag('waiting-approval', 'Waiting Approval');
				break;
			case 'I':
				return $tags->getTag('inactive', 'Inactive');;
				break;
			case 'R':
				return $tags->getTag('rejections', 'Rejected');
				break;
			case 'D':
				return $tags->getTag('draft', 'Draft');
				break;
		}
	}
	public function thumbnailImage()
	{
		return array(
			'jpg',
			'jpeg',
			'png',
		);
	}
	public function generateFormat()
	{
		$merged_array =  $this->thumbnailImage();
		$str = '';
		foreach ($merged_array as $format) {
			$str .= '.' . $format . ',';
		}
		return rtrim($str, ',');
	}
	public function getShortDescription($length = 130)
	{
		$descrip = !empty($this->ad_descriptionN) ? $this->ad_descriptionN : $this->ad_description;
		return StringHelper::truncateLength($descrip, (int)$length);
	}
	public function getShortDescription2($length = 130)
	{
		return StringHelper::truncateLength($this->ad_description2, (int)$length);
	}

	public function listDataForAjax()
	{


		$limit = 30;
		$criteria = $this->search(1);
		$criteria->compare('t.status', 'A');
		$criteria->select .= ' ,(SELECT image_name FROM {{ad_image}} img  WHERE  img.ad_id = t.id and  img.status="A" and  img.isTrash="0"  limit 1  )   as ad_image ';
		$query = Yii::app()->request->getQuery('q');
		$criteria->condition  .= ' and ( t.ad_title like :query or t.ad_description like :query )  ';
		$criteria->params[':query'] =  '%' . $query . '%';
		$count = self::model()->count($criteria);
		$criteria->order = 'ad_title ASC';
		$criteria->limit   =  $limit;
		$page = Yii::app()->request->getQuery('page', 1);

		$offset = ($page == 1) ? '0' : ($page - 1) *  $limit + 1;
		$criteria->offset =  $offset;

		$data = self::model()->findAll($criteria);
		$ar = array();

		if ($data) {
			foreach ($data as $k => $v) {
				$image = Yii::app()->apps->getBaseUrl('uploads/images/' . $v->ad_image);
				$icontactIcon =  '<div style="background-image:url(' . $image . '); " class="backimg"></div>';
				$icontactIcon .=  '<div class="backimg-detail"><h3>' . $v->ad_title . '</h3><p>' . $v->first_name . ',' . $v->country_name . '</p></div><div class="clearfix"></div>';
				$ar[] = array('id' => $v->id, 'text' => $icontactIcon);
			}
		}
		$record = array("total_count" => $count, "incomplete_results" => false, "items" => $ar);
		echo  json_encode($record);
		Yii::app()->end();
	}
	public function common_not_mandatory_field()
	{
		return    array(
			'rera_no' => 'rera_no',
			'floor_plan' => 'floor_plan',
			'PrimaryUnitView' => 'PrimaryUnitView',
			'plot_area' => 'plot_area',
			'balconies' => 'balconies',
			'FloorNo' => 'FloorNo',
			'parking' => 'parking',
			'transaction_type' => 'transaction_type',
			'total_floor' => 'total_floor',
			'furnished' => 'furnished',
			'maid_room' => 'maid_room',
			'year_built' => 'year_built',
			'mandate' => 'mandate',
			'car_parking' => 'car_parking',
			'pantry' => 'Pantry',
			'kitchen' => 'Kitchen',
			'no_of_units' => 'no_of_units',
			'no_of_stories' => 'no_of_stories',
			'kitchen' => 'kitchen',
			'pantry' => 'pantry',
		);
	}
	public function excludecommon_not_mandatory_field($array)
	{
		$ar =   $this->common_not_mandatory_field();
		if (empty($array)) {
			return $ar;
		} else {
			foreach ($array as $k => $v) {
				if (in_array($k, $ar)) {
					unset($array[$k]);
				}
			}
			return  $array;
		}
	}
	public function  getLanguagesHtml($fields = array())
	{
		$items = array();
		$query = 'SELECT  tdata.message,translate.source_tag FROM `mw_translate_relation` `translationRelation` INNER  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan)  INNER JOIN mw_translate translate ON (`translationRelation`.translate_id=translate.translate_id) WHERE (`translationRelation`.`ad_id`=:ad_id) ';
		if (!empty($fields)) {
			$ids = join("','", $fields);
			$query .= " and translate.source_tag in  ('$ids')";
		}
		$command = Yii::app()->db->createCommand($query);
		$rows = $command->queryAll(true, array(':lan' => OptionCommon::getLanguage(), ':ad_id' => $this->primaryKey));
		foreach ($rows as $row) {
			$items[$row['source_tag']] = $row['message'];;
		}
		return $items;
	}
	public function FloorNoArray()
	{
		$ar = array();
		$tag = Yii::app()->tags;
		$ar['2001'] = $tag->getTag('ap_basement', 'Basement');
		$ar['2002'] = $tag->getTag('ap_lower_ground', 'Lower Ground');
		$ar['2003'] = $tag->getTag('ap_ground', 'Ground');
		$ar['2004'] = $tag->getTag('ap_n/a', 'N/A');
		for ($i = 1; $i <= 41; $i++) {
			$ar[] =  ($i == 41) ? '40+' : $i;
		}
		return $ar;
	}

	public function getFloorNoTitle()
	{
		$ar = $this->FloorNoArray();
		return isset($ar[$this->FloorNo]) ? $ar[$this->FloorNo] : $this->FloorNo;
	}
	public function getTotal_floorTitle()
	{
		$ar = $this->TotalFloorArray();
		return isset($ar[$this->total_floor]) ? $ar[$this->total_floor] : $this->total_floor;
	}

	public function TotalFloorArray()
	{
		$ar = array();
		for ($i = 0; $i <= 41; $i++) {
			$ar[] =  ($i == 41) ? '40+' : $i;
		}
		return $ar;
	}
	public function parkingArray()
	{
		$tag = Yii::app()->tags;
		return array("N" => $tag->getTag('ap_none', "None"), "Y" => $tag->getTag('ap_open', "Open"), 'C' => $tag->getTag('ap_covered', 'Covered'));
	}
	public function getParkingTitle()
	{
		$ar = $this->parkingArray();
		return isset($ar[$this->parking]) ? $ar[$this->parking] : $this->parking;
	}
	public function getFurnishedTitle()
	{
		$ar = $this->YesNoArray2();
		return isset($ar[$this->furnished]) ? $ar[$this->furnished] : $this->furnished;
	}
	public function getMaidRooMTitle()
	{
		$ar = $this->YesNoArray2();
		return isset($ar[$this->maid_room]) ? $ar[$this->maid_room] : $this->maid_room;
	}
	public function balconiesArray()
	{
		$ar = array();
		$ar['501'] = 'None';
		for ($i = 1; $i <= 4; $i++) {

			$ar[] =  ($i == 4) ? 'More Than 3' : $i;
		}
		return $ar;
	}
	public function getBalconiesTitle()
	{
		$ar = $this->balconiesArray();
		return isset($ar[$this->balconies]) ? $ar[$this->balconies] : '';
	}
	public function getTransactionTypeTitle()
	{
		$ar = $this->TransactionType();
		return isset($ar[$this->transaction_type]) ? $ar[$this->transaction_type] : $this->transaction_type;
	}
	public function getListingType()
	{
		$ty = Category::model()->categoryIdLan($this->listing_type);
		if (!empty($ty)) {
			return $ty->PluralTitle;
		}
	}
	public function getListingTypeCategory()
	{
		$ty = Category::model()->categoryIdLan($this->category_id);
		if (!empty($ty)) {
			return $ty->PluralTitle;
		}
	}
	public function getTypeColor()
	{
		switch ($this->section_id) {
			case '1':
				return  '#F15B61';
				break;
			case '2':
				return  '#008489';
				break;
			case '3':
				return  '#20C063';
				break;
			default:
				return  '#FD6D35';
				break;
		}
	}
	public function getPropertyOwnerType()
	{
		switch ($this->user_type) {
			case 'A':
				return   'Agent';
				break;
			case 'D':
				return  'Developer';
				break;
			case 'K':
				return  'Agency';
				break;
			default:
				return  'Owner';
				break;
		}
	}
	public $user_address;
	public function getAdTitleWithTags()
	{
		$html =  $this->ad_title;
		$found = false;
		if ($this->featured == "Y") {
			$found = true;
			$html .=  ' <span class="label  bg-green" title="Featured" onclick="previewthis(this,event)" href="javascript:void(0)">Featured</span>';
		}
		if ($this->recmnded == "1") {
			$found = true;
			$html .=  ' <span class="label  bg-red" title="Recommanded" onclick="previewthis(this,event)" href="javascript:void(0)">Verified</span>';
		}
		if ($this->promoted == "1") {
			$found = true;
			$html .=  ' <span class="label  bg-yellow" title="Promoted" onclick="previewthis(this,event)" href="javascript:void(0)">Hot</span>';
		}
		if ($this->is_new == "1") {
			$found = true;
			$html .=  ' <span class="label  bg-aqua" title="New" onclick="previewthis(this,event)" href="javascript:void(0)">New</span>';
		}
		if ($this->status == "I") {
			$html .=  '<i title="DISABLED" class="glyphicon glyphicon-ban-circle"></i>';
		}
		return  $html;
	}
	public function getLitleWithTag()
	{
		return CHtml::Link(@$this->AdTitleWithTags, Yii::app()->createUrl("place_an_ad/update", array("id" => $this->id)));
	}
	public function canShowTag()
	{
		static $_can_show;
		if ($_can_show !== null) {
			return $_can_show;
		}

		if (empty($_can_show)) {
			$_can_show =  Yii::app()->options->get('system.common.show_featured_tag', 'no');
		}
		return $_can_show;
	}
	public function getTagList($check = false)
	{
		$html = '';
		if ($this->featured == "Y") {
			$html .=  '<li class="F">' . $this->mTag()->getTag('featured', 'Featured') . '</li>';
		} else if ($this->is_new == "1") {
			$html .=  '<li class="N">Premium</li>';
		}
		//else if($this->recmnded=="1"){  $html .=  '<li class="R">Recommanded</li>'; }
		else if ($this->promoted == "1") {
			$html .=  '<li class="P">' . $this->mTag()->getTag('hot', 'Hot') . '</li>';
		}
		return $html;
		if ($this->canShowTag() == 'no') {
			return '';
		}
		$html = '';
		if (!$check) {
			if ($this->featured == "Y") {
				$html .=  '<li class="F">Featured</li>';
			} else if ($this->is_new == "1") {
				$html .=  '<li class="N">New</li>';
			} else if ($this->recmnded == "1") {
				$html .=  '<li class="R">Recommanded</li>';
			} else if ($this->promoted == "1") {
				$html .=  '<li class="P">Hot</li>';
			}
		} else {
			switch ($check) {
				case 'P':
					if ($this->promoted == "1") {
						$html .=  '<li class="P">Hot</li>';
					}
					break;
				case 'R':
					if ($this->recmnded == "1") {
						$html .=  '<li class="R">Recommanded</li>';
					}
					break;
				case 'F':
					if ($this->featured == "Y") {
						$html .=  '<li class="F">Featured</li>';
					}
					break;
				case 'N':
					if ($this->is_new == "N") {
						$html .=  '<li class="F">New</li>';
					}
					break;
			}
		}

		return $html;
	}
	public function generateImage($apps, $h = 190, $w = 285, $s_id = null, $bg = null, $opaciti = 60, $wateri = '10')
	{
		$html = '';
		if (!empty($this->ad_images_g) and !empty($bg)) {
			$itemsI = explode(',', $this->ad_images_g);
			if (!empty($itemsI)) {
				foreach ($itemsI as $k => $ad_img) {

					$html .= '<div>';

					if (defined('offline')) {
						$ad_img = '1019_1571412256global-city-ajman-ajman-properties_.jpg';
					}
					$image = $apps->getBaseUrl('uploads/images/' . $ad_img);
					if (!empty($bg)) {
						if ($k == 0) {
							$htm = 'style="background-image:url(\'' . $this->generateImageWaterMark($ad_img, $w, $h, $opaciti, $wateri) . '\')"';
							$c = 'is-lazy-loaded';
						} else {
							$htm = 'data-lazy="' . $apps->getBaseUrl('timthumb.php') . '?src=' . $image . '&h=' . $h . '&w=' . $w . '&zc=1"';
							$c = 'lazy-bg-img';
						}
						$html .= '<div class="bg-image ' . $c . '" ' . $htm . ' ></div>';
					} else {
						$html .= '<img data-lazy="' . $apps->getBaseUrl('timthumb.php') . '?src=' . $image . '&h=' . $h . '&w=' . $w . '&zc=1" alt="">';
					}
					$html .= '</div>';
				}
			}
		}
		if (!empty($html)) {

			return $html;
		} else {

			$html .= '';
			//$image =  $apps->getBaseUrl('uploads/images/'.$this->ad_image); 
			//$html .= '<img src="'.$apps->getBaseUrl('timthumb.php').'?src='.$image.'&h='.$h.'&w='.$w.'&zc=1" alt="">';
			$image =  $this->generateImageWaterMark($this->ad_image, $w, $h, $opaciti, $wateri);
			$html .= '<img src="' . $image . '" alt="">';
			$html .= '';
		}
		return $html;
	}
	public function getdetailImages($im, $status, $w = '960')
	{
		return $this->generateImageWaterMarkNo($im, $w, $h = '450', $opaciti = 80, $wateri = 20);
	}
	function generateImageWaterMarkNo($image = null, $width = null, $height = null, $opacity = 60, $water_size = 10)
	{
		if (defined('offline')) {
			$image = '0919_1567488950Untitled_.jpg';
		}
		if (empty($width) and empty($height)) {

			return   Yii::app()->easyImage->thumbSrcOf(
				Yii::getpathOfAlias('webroot')  . '/uploads/images/' . $image,
				array(
					'sharpen' =>  0,
					'background' => '#E7ED67',
					'type' => 'jpg',
					'quality' => 95
				)
			);
		}
		return   Yii::app()->easyImage->thumbSrcOf(
			Yii::getpathOfAlias('webroot')  . '/uploads/images/' . $image,
			array(
				'resize' => array('width' => $width, 'height' => $height, "master" => EasyImage::RESIZE_AUTO),
				'sharpen' => 0,
				'background' => '#E7ED67',
				'type' => 'jpg',
				'quality' => 95
			)
		);
	}
	public function generateImageWaterMark($image = null, $width = null, $height = null, $opacity = 60, $water_size = 10)
	{
		if (defined('offline')) {
			$image = '0919_1567488950Untitled_.jpg';
		}
		switch ($water_size) {
			case  '10':
				$marker = '10X10.png';
				break;
			case  '30':
				$marker = '30X30.png';
				break;
			case  '50':
				$marker = '50X50.png';
				break;
			default:
				$marker = '100X100.png';
				break;
		}

		if (empty($width) and empty($height)) {

			return   Yii::app()->easyImage->thumbSrcOf(
				Yii::getpathOfAlias('webroot')  . '/uploads/images/' . $image,
				array(
					'watermark' => array('watermark' => 'watermark/' . $marker, 'opacity' => $opacity),
					'sharpen' => 20,
					'background' => '#E7ED67',
					'type' => 'jpg',
					'quality' => 100
				)
			);
		}
		return   Yii::app()->easyImage->thumbSrcOf(
			Yii::getpathOfAlias('webroot')  . '/uploads/images/' . $image,
			array(
				'resize' => array('width' => $width, 'height' => $height, "master" => EasyImage::RESIZE_AUTO),
				'watermark' => array('watermark' => 'watermark/' . $marker, 'opacity' => $opacity),
				// 'scaleAndCrop' => array('width' => $width, 'height' => $height),
				// 'resize' => array('width' => $width, 'height' =>$height,"master"=>EasyImage::RESIZE_AUTO),															

				'sharpen' => 20,
				'background' => '#E7ED67',
				'type' => 'jpg',
				'quality' => 100
			)
		);
	}
	protected function beforeDelete()
	{
		if (!empty($this->adImagesAll)) {

			foreach ($this->adImagesAll as $image) {

				$image->delete();
			}
		}
		return true;
	}
	public function getMandateTitle()
	{
		if (!empty($this->developer_id)) {
			$found = Developers::model()->findByPk($this->developer_id);
			if ($found) {
				return $found->developer_name;
			}
		}
		if (!empty($this->mandate)) {
			return $this->mandate;
		}
	}
	public function detailList()
	{
		if ($this->listing_type == '120') {
			return 	   array(
				'listing_type'	 =>  $this->ListingType,
				'category_id' 	 =>     $this->ListingTypeCategory,
				'bedrooms'		 =>  in_array('bedrooms', $this->getFieldsList()) ? $this->BedroomTitle : '',
				'bathrooms' 	 =>  in_array('bathrooms', $this->getFieldsList()) ?  $this->BathroomTitle : '',
				'builtup_area' 	 => in_array('builtup_area', $this->getFieldsList()) ?  $this->BuiltUpArea : '',

				'car_parking'		 =>  in_array('car_parking', $this->getFieldsList()) ? $this->CarparkingTitle : '',
				'punit' 	   =>  $this->punit,
				'mandate'		   =>  in_array('mandate', $this->getFieldsList()) ? $this->mandateTitle : '',
				'construction_status' => in_array('construction_status', $this->getFieldsList()) ?  $this->ConstructionTitle : '',

				'pets' 	   =>  $this->pets,
				'year_built' 	   =>  in_array('year_built', $this->getFieldsList()) ? $this->year_built : '',
				'reference' 	 =>     $this->ReferenceNumberTitle
			);
		}
		return  array(
			'listing_type'	 =>  $this->ListingType,
			'category_id' 	 =>     $this->ListingTypeCategory,
			'bedrooms'		 =>  in_array('bedrooms', $this->getFieldsList()) ? $this->BedroomTitle : '',
			'bathrooms' 	 =>  in_array('bathrooms', $this->getFieldsList()) ?  $this->BathroomTitle : '',
			'builtup_area' 	 => in_array('builtup_area', $this->getFieldsList()) ?  $this->BuiltUpArea : '',

			'car_parking'		 =>  in_array('car_parking', $this->getFieldsList()) ? $this->CarparkingTitle : '',

			'construction_status' => in_array('construction_status', $this->getFieldsList()) ?  $this->ConstructionTitle : '',
			'mandate'		   =>  in_array('mandate', $this->getFieldsList()) ? $this->mandateTitle : '',
			'punit' 	   =>  $this->punit,
			'pets' 	   =>  $this->pets,
			'year_built' 	   =>  in_array('year_built', $this->getFieldsList()) ? $this->year_built : '',
			'reference' 	 =>     $this->ReferenceNumberTitle,
			/*
		'balconies'		 =>  in_array('balconies',$this->getFieldsList()) ? $this->BalconiesTitle: '',
	
        'no_of_units'		 =>  in_array('no_of_units',$this->getFieldsList()) ? $this->no_of_units: '',
        'no_of_stories'		 =>  in_array('no_of_stories',$this->getFieldsList()) ? $this->no_of_stories: '',
        'pantry'		     =>  in_array('pantry',$this->getFieldsList()) ? $this->PantryTitle: '',
        'kitchen'		     =>  in_array('kitchen',$this->getFieldsList()) ? $this->KithenTitle: '',
		
		
		'plot_area' 	 => in_array('plot_area',$this->getFieldsList()) ?  $this->PloatArea: '',            
		'sub_category_id' => in_array('sub_category_id',$this->getFieldsList()) ?  $this->sub_category_name: '',
		'FloorNo' 		 => in_array('FloorNo',$this->getFieldsList()) ?  $this->FloorNoTitle: '',
		'total_floor' 	 =>  in_array('total_floor',$this->getFieldsList()) ? $this->total_floorTitle: '',
		'parking' 		 =>  in_array('parking',$this->getFieldsList()) ?  $this->parkingTitle: '',
		'transaction_type' =>  in_array('transaction_type',$this->getFieldsList()) ? $this->TransactionTypeTitle: '',
	
		//'rera_no'		   =>  in_array('rera_no',$this->getFieldsList()) ? $this->rera_no: '',
		'furnished'		   => in_array('furnished',$this->getFieldsList()) ? $this->FurnishedTitle: '',
		'maid_room'		   => in_array('maid_room',$this->getFieldsList()) ? $this->MaidRooMTitle: '',
		'community_id'	   => in_array('community_id',$this->getFieldsList()) ? $this->community_name: '',
		'sub_community_id' => in_array('sub_community_id',$this->getFieldsList()) ? $this->sub_community_name: '',
		'section_id'	   => in_array('section_id',$this->getFieldsList()) ?  $this->section_name: '',            
		//'construction_status'		   =>  $this->ConstructionTitle ,
			'expiry_date'	   =>  in_array('expiry_date',$this->getFieldsList()) ? $this->expiryDateTitle: '',
			*/

		);
	}
	public function detailList2()
	{
		return  array(
			'reference' 	 =>     $this->ReferenceNumberTitle,
			//'bedrooms'		 =>  in_array('bedrooms',$this->getFieldsList()) ? $this->BedroomTitle: '',
			//'bathrooms' 	 =>  in_array('bathrooms',$this->getFieldsList()) ?  $this->BathroomTitle: '',
			//'balconies'		 =>  in_array('balconies',$this->getFieldsList()) ? $this->BalconiesTitle: '',
			'builtup_area' 	 => in_array('builtup_area', $this->getFieldsList()) ?  $this->BuiltUpArea : '',

			//'car_parking'		 =>  in_array('car_parking',$this->getFieldsList()) ? $this->CarparkingTitle: '',
			//'no_of_units'		 =>  in_array('no_of_units',$this->getFieldsList()) ? $this->no_of_units: '',
			// 'no_of_stories'		 =>  in_array('no_of_stories',$this->getFieldsList()) ? $this->no_of_stories: '',
			// 'pantry'		     =>  in_array('pantry',$this->getFieldsList()) ? $this->PantryTitle: '',
			// 'kitchen'		     =>  in_array('kitchen',$this->getFieldsList()) ? $this->KithenTitle: '',


			//	'plot_area' 	 => in_array('plot_area',$this->getFieldsList()) ?  $this->PloatArea: '',            
			//	'sub_category_id' => in_array('sub_category_id',$this->getFieldsList()) ?  $this->sub_category_name: '',
			//'FloorNo' 		 => in_array('FloorNo',$this->getFieldsList()) ?  $this->FloorNoTitle: '',
			//'total_floor' 	 =>  in_array('total_floor',$this->getFieldsList()) ? $this->total_floorTitle: '',
			//	'parking' 		 =>  in_array('parking',$this->getFieldsList()) ?  $this->parkingTitle: '',
			//	'construction_status' => in_array('construction_status',$this->getFieldsList()) ?  $this->ConstructionTitle: '',
			//	'transaction_type' =>  in_array('transaction_type',$this->getFieldsList()) ? $this->TransactionTypeTitle: '',
			//	'year_built' 	   =>  in_array('year_built',$this->getFieldsList()) ? $this->year_built: '',
			//	'expiry_date'	   =>  in_array('expiry_date',$this->getFieldsList()) ? $this->expiryDateTitle: '',
			'mandate'		   =>  in_array('mandate', $this->getFieldsList()) ? $this->mandate : '',
			//'rera_no'		   =>  in_array('rera_no',$this->getFieldsList()) ? $this->rera_no: '',
			//	'furnished'		   => in_array('furnished',$this->getFieldsList()) ? $this->FurnishedTitle: '',
			//	'maid_room'		   => in_array('maid_room',$this->getFieldsList()) ? $this->MaidRooMTitle: '',
			//	'community_id'	   => in_array('community_id',$this->getFieldsList()) ? $this->community_name: '',
			//	'sub_community_id' => in_array('sub_community_id',$this->getFieldsList()) ? $this->sub_community_name: '',
			'section_id'	   => in_array('section_id', $this->getFieldsList()) ?  $this->section_name : '',
			'status'		   => in_array('status', $this->getFieldsList()) ? $this->StatusTitle : '',
		);
	}


	public function detailList3()
	{

		return  array(
			'reference' 	 =>     $this->ReferenceNumberTitle,
			'city' 	 =>     $this->city_name,

			'price'	 =>  $this->PriceTitleDetail,

			'developer_id'	   =>    $this->MandateTitle,



		);
	}

	public function getFieldsList()
	{
		static $_defaultFields;
		if ($_defaultFields !== null) {
			return $_defaultFields;
		}

		$fields = Yii::app()->db->createCommand()
			->select('field_name')
			->from('{{category_field_list}} f')
			->where('f.category_id=:id', array(':id' => $this->category_id))
			->queryAll();
		$ar = array();
		if (!empty($fields)) {
			foreach ($fields as $field) {
				$ar[$field['field_name']] = $field['field_name'];
			}
		}
		$_defaultFields = $this->getExcludeArray((array)$ar);
		return $_defaultFields;
	}

	public function all_amentitie_from_array($from_array)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.amenities_id,t.amenities_name';
		$criteria->addInCondition('t.amenities_id', $from_array);
		$criteria->order = ' amenities_name asc';
		return Amenities::model()->findAll($criteria);
	}
	public function getListTypeDetail()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.category_id,t.category_name';
		$criteria->compare('t.category_id', $this->category_id);
		return Category::model()->find($criteria);
	}
	public function getNewBanner()
	{
		switch ($this->is_new) {
			case '1':
				return false;
				return '<span class="block_tag2n for_new_tag">New</span>';
				break;
		}
	}
	public function getLitleWithTag2()
	{
		return CHtml::Link(@$this->ad_title, Yii::app()->createUrl("place_an_ad/update", array("id" => $this->id)), array('class' => 'oneline', 'title' => $this->ad_title)) . $this->AdTitleWithTags2 . '<a href="' . Yii::App()->apps->getBaseUrl('uploads/images/' . $this->ad_image) . '" style="float:right;" target="_blank"><img src="' . Yii::App()->apps->getBaseUrl('timthumb.php') . '?src=' . Yii::App()->apps->getBaseUrl('uploads/images/' . $this->ad_image) . '&h=60&w=100&zc=1" style="width:100px;" /></a>';;
	}
	public function getLitleWithTag21()
	{
		$html ='<div class="d-flex">';
		$html .= '<div class="dv-image"><a href="' . Yii::App()->apps->getBaseUrl('uploads/images/' . $this->ad_image) . '" style="float:right;" target="_blank"><img src="' . Yii::App()->apps->getBaseUrl('timthumb.php') . '?src=' . Yii::App()->apps->getBaseUrl('uploads/images/' . $this->ad_image) . '&h=60&w=100&zc=1" style="width:100px;" /></a>';
		$html .= '</div>';
		$html .= '<div class="dv-details">';
		$html .=
		CHtml::Link(@$this->ad_title, Yii::app()->createUrl("place_an_ad/update", array("id" => $this->id)), array('class' => 'oneline', 'title' => $this->ad_title)) . $this->AdTitleWithTags2 . '';
		$html .= '</div>';
		$html .= '</div>'; ;
		return $html ;
	}
	//recmnded=verified(1|0) | Premium=is_new(1|0) | Hot = promoted (1|0)


	public function getAdTitleWithTags2()
	{
		$found = false;
		$html = '';
		//  $html =  ' <span class="label  bg-green '; $html .= $this->featured=="Y" ? '' : 'dis';  $html .= '" data-fun="F" data-id="'.$this->id.'" title="Featured" onclick="updateTag(this,event)" href="#">Fea</span>';  
		$html = '';
		$html .=  ' <span class="label  bg-red ';
		$html .=  ($this->recmnded == "1") ? '' : 'dis';
		$html .= '"  data-fun="R" data-id="' . $this->id . '"  title="Recommanded" onclick="updateTag(this,event)" href="#">Verified</span>';
		$html .=  ' <span class="label  bg-aqua ';
		$html .= ($this->is_new == "1") ? '' : 'dis';
		$html .= '"  data-fun="N" data-id="' . $this->id . '" title="New" onclick="updateTag(this,event)" href="#">Premium</span>';
		$html .=  ' <span class="label  bg-yellow ';
		$html .= ($this->promoted == "1") ? '' : 'dis';
		$html .= '"  data-fun="P" data-id="' . $this->id . '" title="Promoted" onclick="updateTag(this,event)" href="#">Hot</span>';
		if ($this->status == "I") {
			$html .=  '<i title="DISABLED" class="glyphicon glyphicon-ban-circle"></i>';
		}
		return  $html;
	}
	public function getPriceTitleSpanL($code = '')
	{

		$code = $this->currencyTitle;
		$html =  '<span class="pri sec_' . $this->section_id . '"><span class="codc"> ' . $code . ' </span>' . number_format($this->price, 0, '.', ',') . '</span>';

		return $html;
	}
	function getRentPaidL($home = false)
	{
		if (empty($this->rent_paid)) {
			$this->rent_paid = 'year';
		}
		switch ($this->rent_paid) {
			case 'yearly':
				return 'Year';
				break;
			case 'monthly':
				return 'Month';
				break;
			default:
				return $this->rent_paid;
				break;
		}
	}
	public $city_slug;
	public $sec_id;
	public $listing_type_name;
	public function getBedClass()
	{
		return 'iconBed';
	}
	public function getBathClass()
	{
		return 'iconBath';
	}
	public function parking()
	{
		$ar = array();

		for ($i = 0; $i <= 11; $i++) {

			$ar[] =  ($i == 11) ? '10+' : $i;
		}
		return $ar;
	}
	public function kitchen()
	{
		$ar = array();

		for ($i = 0; $i <= 11; $i++) {

			$ar[] =  ($i == 11) ? '10+' : $i;
		}
		return $ar;
	}
	public function pantry()
	{
		$ar = array();

		for ($i = 0; $i <= 14; $i++) {

			$ar[] =  ($i == 14) ? '13+' : $i;
		}
		return $ar;
	}
	public function getCarparkingTitle()
	{
		if (empty($this->car_parking)) {
			return false;
		}
		if ($this->car_parking == '14') {
			return '13+';
		}
		return  $this->car_parking . ' Car';
	}
	public function getKithenTitle()
	{

		if ($this->kitchen == '14') {
			return '13+';
		}
		return  $this->kitchen;
	}

	public function getPantryTitle()
	{

		if ($this->pantry == '14') {
			return '13+';
		}
		return  $this->pantry;
	}


	public function getCarClass()
	{
		return 'iconCar';
	}
	public function  Ltag()
	{
		static $_for_sale_tag;
		if ($_for_sale_tag !== null) {
			return $_for_sale_tag;
		}
		$_for_sale_tag = Yii::app()->tags;
		return $_for_sale_tag;
	}

	public function getSectionCategoryFullTitle()
	{
		switch ($this->section_id) {

			case '1':
				return Yii::t('app', $this->Ltag()->getTag('ap_for_sale_tag', '{c} for Sale'), array('{c}' => $this->category_name));
				break;
			case '2':
				return Yii::t('app', $this->Ltag()->getTag('ap_for_rent_tag', '{c} for Rent'), array('{c}' => $this->category_name));
				break;
			case '3':
				return $this->Ltag()->getTag('ap_developement', 'Development');
				break;
			default:
				return $this->category_name;
				break;
		}
	}
	public function getSectionListingFullTitle()
	{
		$title = $this->ListingType;
		switch ($this->section_id) {

			case '1':
				return $title . '  for Sale';
				break;
			case '2':
				return $title . '  for Rent';
				break;
			default:
				return $title;
				break;
		}
	}
	public function listRow1()
	{
		$mainRow =  '<span class="property-price new_sec">' . $this->PriceTitleSpanL;
		if ($this->section_id == '2') {
			$mainRow .= '<span class="rent_head">/' . $this->getRentPaidL(1) . '</span>';
		}
		$mainRow .= '</span> <span class="sec_divdr">|</span> <span class="sq_ft">' . $this->BuiltUpAreaTitleS . '</span>';
		return $mainRow;
	}
	public function listRow2()
	{

		$found = false;
		$list = '';
		if ($this->category_id == '33') {
			if ($this->car_parking != '') {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text"  title="' . $this->CarparkingTitle . ' Pantry"><span class="general-features__icon ' . $this->CarClass . '">  ' . $this->CarparkingTitle . '</span></li>');
			}
			if ($this->pantry != '') {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" title="' . $this->PantryTitle . ' Pantry"><span class="general-features__icon iconCoffee"> ' . $this->PantryTitle . ' </span></li>');
			}
			if (!empty($this->bathrooms)) {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" title="' . $this->BathroomTitle . ' Bathrooms"><span class="general-features__icon ' . $this->BathClass . '"> ' . $this->BathroomTitle . '</span></li>');
				$found = true;
			}
			//$list .= Yii::t('app','<li class="general-features__feature" role="text" aria-label="Pantry"><span class="general-features__icon iconFood"> 1 </span></li>');
		} else if ($this->category_id == '113') {
			if (!empty($this->bedrooms)) {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" aria-label="2 bedrooms"><span class="general-features__icon ' . $this->bedClass . '"> ' . $this->BedroomTitle . '</span></li>');
			}

			if (!empty($this->bathrooms)) {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" title="' . $this->BathroomTitle . ' Bathrooms"><span class="general-features__icon ' . $this->BathClass . '"> ' . $this->BathroomTitle . '</span></li>');
				$found = true;
			}
			if ($this->kitchen != '') {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text"  title="' . $this->KithenTitle . ' kitchen"><span class="general-features__icon iconFood"> ' . $this->KithenTitle . ' </span></li>');
			}
			//$list .= Yii::t('app','<li class="general-features__feature" role="text" aria-label="Pantry"><span class="general-features__icon iconFood"> 1 </span></li>');
		} else if ($this->category_id == '101') {
			if ($this->no_of_units != '') {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" title="' . $this->no_of_units . ' units"><span class="general-features__icon iconBlock"> ' . $this->no_of_units . '</span></li>');
			}
			if ($this->no_of_stories != '') {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" title="' . $this->no_of_stories . ' stories"><span class="general-features__icon iconNoteBeamed"> ' . $this->no_of_stories . '</span></li>');
			}
		} else {
			if (!empty($this->bedrooms)) {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" aria-label="2 bedrooms"><span class="general-features__icon ' . $this->bedClass . '"> ' . $this->BedroomTitle . '</span></li>');
			}
			if (!empty($this->bathrooms)) {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" aria-label="2 bedrooms"><span class="general-features__icon ' . $this->BathClass . '"> ' . $this->BathroomTitle . '</span></li>');
				$found = true;
			}

			if ($this->car_parking != '') {
				$found = true;
				$list .= Yii::t('app', '<li class="general-features__feature" role="text" aria-label="2 bedrooms"><span class="general-features__icon ' . $this->CarClass . '">  ' . $this->CarparkingTitle . '</span></li>');
			}
			if ($this->no_of_units != '') {
				$found = true;

				$list .= Yii::t('app', '<li class="general-features__feature" role="text" aria-label="2 bedrooms"><span class="general-features__icon iconBlock"> ' . $this->no_of_units . ' Units</span></li>');
			}
		}
		$category_title = '';
		if ($found) {
			$category_title .= '<span class="sec_divdr2">|</span>';
		}
		$category_title .= '<h2 class="residential-card__address-heading"><span class="details-link residential-card__details-link"><span class="sizeParmas dark">' . $this->SectionCategoryFullTitle . '</span></span></h2>';
		if (!empty($list)) {
			$list = '<div class="primary-features residential-card__primary"><ul class="general-features rui-clearfix " role="presentation">' . $list . '</ul></div>';
		} else {
			$list = '';
		}
		return Yii::t('app', $this->listRow2Template(), array('{row2}' => $list, '{item2}' => $category_title));
	}
	public function listRow2Template()
	{
		$html = '<div class="piped-content"><div class="piped-content__outer"><div class="piped-content__inner">{row2}{item2}</div></div></div>';
		return 	 $html;
	}
	public function listRow3()
	{
		$html = '<h2 class="residential-card__address-heading "><span class="details-link residential-card__details-link"><span class="">';
		if (!empty($this->city_name)) {
			$html .= $this->city_name;
		} else {
			$html .= '&nbsp;';
		}
		if (!empty($this->state_name)) {
			$html .= ' , ' . $this->state_name;
		}
		$html .= ', UAE</span></span></h2>';
		return 	 $html;
	}
	public function getUserImageResized($width = 100)
	{
		$app = Yii::app();
		return $app->apps->getBaseUrl('timthumb.php') . '?src=' . $app->apps->getBaseUrl('uploads/images/' . $this->user_image) . '&w=' . $width . '&zc=1';
	}
	public function getCompanyImageResized($width = 100)
	{
		$app = Yii::app();
		return $app->apps->getBaseUrl('timthumb.php') . '?src=' . $app->apps->getBaseUrl('uploads/images/' . $this->company_logo) . '&w=' . $width . '&zc=1';
	}
	public function getdirectionLink()
	{
		return '#';
	}
	public function getActivePropertys2()
	{
		$criteria = new CDbCriteria;
		$criteria->condition  = '1';

		$criteria->select = 't.* 
			,(select COALESCE(count(ad.id),0) from {{place_an_ad}} ad where t.user_id = ad.user_id and ad.status="A" and ad.isTrash="0" and ad.section_id=' . PlaceAnAd::SALE_ID . ' ) as sale_total
			,(select COALESCE(count(ad.id),0) from {{place_an_ad}} ad where t.user_id = ad.user_id and ad.status="A" and ad.isTrash="0" and ad.section_id=' . PlaceAnAd::RENT_ID . ' ) as rent_total
			';
		$criteria->distinct =  't.id';

		$criteria->compare('t.user_id', (int)$this->user_id);
		$totalResult =  ListingUsers::model()->find($criteria);

		if (!empty($totalResult)) {
			return array('sale_total' => $totalResult->sale_total, 'rent_total' => $totalResult->rent_total);
		}
	}
	public function getUserImage($width = 100)
	{
		$app = Yii::app();
		return $app->apps->getBaseUrl('timthumb.php') . '?src=' . $app->apps->getBaseUrl('uploads/images/' . $this->user_image) . '&w=' . $width . '&zc=1';
	}
	public function getStatisticsCls()
	{
		$statistcs = StatisticsPage::model()->pageCount('', $this->id);
		$callCount = Statistics::model()->callCount('', $this->id);
		$mainCount = Statistics::model()->mailCount('', $this->id);
		$html  = '<div class="stat-div1"><span class="text-primary">' . Yii::t('app', '{n} {p}', array('{n}' => (int) $statistcs->s_count, '{p}' => $this->mTag()->gettag('page_views', 'Page Views'))) . '</span> 
		<br /> <span  class="text-success" >' . Yii::t('app', '{n} {p}', array('{n}' => (int) $callCount->s_count, '{p}' => $this->mTag()->gettag('call_clicks', 'Call Clicks'))) . '</span>
		<br /><span  href="javascript:void(0)" class="text-warning">' . Yii::t('app', '{n} {p}', array('{n}' => (int) $mainCount->s_count, '{p}' => $this->mTag()->gettag('email_clicks', 'Email Clicks'))) . '</span>
		</div>';
		return $html;
	}
	public $avg_r;
	public function findCategoryyfromkeyword($keyword = null)
	{
		$keyword_pieces = explode(' ', $keyword);
		$langaugae = OptionCommon::getLanguage();
		$criteria = new CDbCriteria;
		$criteria->condition = "t.isTrash='0' and status='A'   ";
		$criteria->select = "t.category_id,t.category_name";
		if (!empty($langaugae) and  $langaugae != 'en') {
			$criteria->params[':lan'] = $langaugae;
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.category_id = t.category_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' ,tdata.message as  category_other';
			if (is_array($keyword_pieces)) {
				$criteria->addInCondition('(case when s_term !="" then s_term when tdata.message is not null then tdata.message  else  t.category_name end)', $keyword_pieces);
			} else {
				$criteria->compare('(case when s_term !="" then s_term when tdata.message is not null then tdata.message else  t.category_name end)', $keyword_pieces, true);
			}
		} else {
			if (is_array($keyword_pieces)) {
				$criteria->addInCondition('case when s_term !="" then s_term  else  t.category_name end', $keyword_pieces);
			} else {
				$criteria->compare('(case when s_term !="" then s_term else  t.category_name end)', $keyword_pieces, true);
			}
		}
		return  CHtml::listData(Category::model()->findAll($criteria), 'category_id', 'category_id');
	}
	public function latestFiles2($limit = 10)
	{
		$criteria = $this->search(1);
		$criteria->limit = $limit;
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination'    => array(
				'pageSize'  => $limit,
				'pageVar'   => 'page',
			),
		));
	}
	public function getPriceTitleNew()
	{
		$mainRow =  '<span class="property-price new_sec">' . $this->PriceTitleSpanL;
		if ($this->section_id == '2') {
			$mainRow .= '<span class="rent_head">/' . $this->getRentPaidL(1) . '</span>';
		}
		$mainRow .= '</span>';
		return $mainRow;
	}
	public function getLocationTitleNew()
	{
		$html = '';
		if (!empty($this->city_name)) {
			$html .= $this->city_name;
		} else {
			$html .= '&nbsp;';
		}
		if (!empty($this->state_name)) {
			$html .= ', ' . $this->state_name;
		}
		$html .= ', UAE';
		return $html;
	}
	public function getPriceArray2()
	{
		$code = '';
		return
			array(
				'10000' => $code . '10k',
				'20000' => $code . '20k',
				'30000' => $code . '30k',
				'50000' => $code . '50k',
				'100000' => $code . '100k',
				'130000' => $code . '130k',
				'150000' => $code . '150k',
				'200000' => $code . '200k',
				'250000' => $code . '250k',
				'300000' => $code . '300k',
				'350000' => $code . '350k',
				'400000' => $code . '400k',
				'450000' => $code . '450k',
				'500000' => $code . '500k',
				'550000' => $code . '550k',
				'600000' => $code . '600k',
				'650000' => $code . '650k',
				'700000' => $code . '700k',
				'750000' => $code . '750k',
				'800000' => $code . '800k',
				'850000' => $code . '850k',
				'900000' => $code . '900k',
				'950000' => $code . '950k',
				'100000' => $code . '1m',

			);
	}
	public function getSqft_aray()
	{

		return
			array(
				'800' => '800',
				'1000' => '1000',
				'1500' => '1500',
				'2000' => '2000',
				'2500' => '2500',
				'3000' => '3000',
				'3500' => '3500',
				'4000' => '4000',
				'4500' => '4500',
				'5000' => '5000',
				'5500' => '5500',
				'6000' => '6000',
				'6500' => '6500',
				'7000' => '7000',
				'7500' => '7500',
				'8000' => '8000',
				'8500' => '8500',
				'9000' => '9000',
				'9500' => '9500',
				'10000' => '10,000',
				'11000' => '11,000',
				'12000' => '12,000',
				'13000' => '13,000',
				'14000' => '14,000',
				'15000' => '15,000',
				'17500' => '17,500',
				'20000' => '20,000',
				'22500' => '22,500',
				'25000' => '25,000',
				'30000' => '30,000',
				'35000' => '35,000',

			);
	}
	public function getWishButton()
	{
		return false;
		$html =  '<a href="javascript:void(0)" onclick="';
		if (Yii::app()->user->getId()) {
			$html .= 'savetofavourite(this,event)';
		} else {
			$html .= 'OpenSignUp(this,event)';
		}
		$html .= '" data-function="save_wisth" data-id="' . $this->id . '"  data-after="saved_wisth" class="wish_bt ';
		$html .= !empty($this->wish) ? 'liked' : '';
		$html .= '"></a>';
		return $html;
	}
	public $featured2; 
	public $promoted2; 
	public function getTagListNew()
	{
		$html = '';
		if ($this->Is_verified) {
			$html .= '<div class="verified_tag ">  <svg viewBox="0 0 12 9" class="verifiedtag_icon"> <use xlink:href="#svg-check-icon"></use></svg> ' . $this->mTag()->getTag('verified', 'Verified') . '</div>';
		} else if ($this->is_new == "1") { //Preminim
			$html .= '<div class="verified_tag premium">  <svg viewBox="0 0 12 9" class="verifiedtag_icon"> <use xlink:href="#svg_premium"></use></svg> ' . $this->mTag()->getTag('premium', 'Premium') . '</div>';
		} else if ($this->promoted2 == "1") { //Gold
			$html .= '<div class="verified_tag hot">  <svg viewBox="0 0 12 9" class="verifiedtag_icon"> <use xlink:href="#svg_hot"></use></svg> ' . $this->mTag()->getTag('hot', 'Hot') . '</div>';
		} else if ($this->featured2 == "1") { //Gold
			$html .= '<div class="verified_tag  hot featured1">  <svg viewBox="0 0 12 9" class="verifiedtag_icon"> <use xlink:href="#svg_verified"></use></svg> ' . $this->mTag()->getTag('featured', 'Featured') . '</div>';
		}
		return $html;
	}


	public function getIs_verified()
	{
		if ($this->recmnded == '1') {
			return true;
		}
		return false;
	}
	public function getOwnerName()
	{

		return $this->first_name . ' ' . $this->last_name;
	}
	public function getShareUrl()
	{
		$share_u_abs = $this->DetailUrlAbsolute;
		$number = Yii::t('app', !empty($this->whatsapp) ? $this->whatsapp : $this->mobile_number, array('+' => '', ' ' => ''));
		$number_copy = $number;
		$number = (substr($number, 0, 1) === "0") ? 'replace' : $number;
		if ($number == 'replace') {
			$number = '971' . substr($number_copy, 1, strlen($number_copy));
		}
		$text_message = Yii::t('app', 'Hello, I am interested in this property and would like to make an appointment for a visit. Please contact me as soon as possible.{s}Thank you so much,', array('{s}' => ' %0a', '{1}' => $this->ad_title, '{2}' => $this->ReferenceNumberTitle, '{3}' => 'Property Link')) . ' %0a' .   urlencode($share_u_abs);
		return    Yii::t('app', 'https://wa.me/{number}?text={text}', array('{number}' => $number, '{text}' => $text_message));
	}
	public function getUserLogoS()
	{
		if (!empty($this->company_logo)) {
			return '<div class=""><picture class=""><img src="' . Yii::App()->apps->getBaseUrl('uploads/images/' . $this->company_logo) . '" class="small-right-logo"></picture></div>';
		}
	}
	public function getMainAgentDetailUrl()
	{
		$typr = !empty($this->main_company_type) ? $this->main_company_type : $this->user_type;
		$slug = !empty($this->main_company_slug) ? $this->main_company_slug : $this->u_slug;

		if ($typr == 'D') {

			return Yii::app()->createUrl('user_listing/detail_deveopers', array('slug' => $slug));
		}
		if ($typr == 'K') {
			return Yii::app()->createUrl('user_listing/detail_agencies', array('slug' => $slug));
		}
		return Yii::app()->createUrl('user_listing/detail', array('slug' => $slug));
	}

	public function getCompanyImage()
	{
		if (!empty($this->company_logo)) {
			return Yii::App()->apps->getBaseUrl('uploads/images/' . $this->company_logo);
		}
	}
	public $license_type;
	public function getLicenceLabel()
	{
		return false;
		$Ar = $this->getlicense_type();
		return isset($Ar[$this->license_type]) ? $Ar[$this->license_type] : 'Licence#';
	}

	public function getShareIcon()
	{
		return '<div class="opnshr hide">
   <div class="_12e55ca6  e4584a38">
      <ul class="_55bd3d93">
         <li class="_6fdf20b6">
            <a target="_blank" href="' . $this->ShareUrlFacebook . '" title="Share on Facebook" class="c6481bc9">
               <svg class="_52f0a589" viewBox="0 0 32 32"  > <use xlink:href="#share-facebook"></use> </svg>
               <span>Share on Facebook</span>
            </a>
         </li>
         <li class="_6fdf20b6">
            <a target="_blank" href="' . $this->ShareUrlTwitter . '" title="Share on Twitter" class="c6481bc9">
              
                 <svg class="_52f0a589" viewBox="0 0 32 32"  > <use xlink:href="#share-twiter"></use> </svg>
            
               <span>Share on Twitter</span>
            </a>
         </li> 
         <li class="_6fdf20b6">
            <a target="_blank" href="' . $this->ShareUrl . '" title="Share on WhatsApp" class="c6481bc9">
             <svg class="_52f0a589" viewBox="0 0 32 32"  > <use xlink:href="#share-whatsapp"></use> </svg> <span>Share on WhatsApp</span>
            </a>
         </li>
          
      </ul>
   </div>
</div>';
	}
	public function geticonList()
	{
		$html =  '<ul class="icon mb0">
						<li class="list-inline-item"><a href="javascript:void(0)" class="fav ';
		$html .=    !empty($this->fav) ?  'active' : '';
		$html .= '"  onclick="savefavnew(this,event)" id="fav_button_' . $this->primaryKey . '" data-function="save_favourite" data-id="' . $this->primaryKey . '"  data-after="saved_fave"><span class="icon-hert"></span></a></li>
						<li class="list-inline-item"><a href="javascript:void(0)" onclick="openshare(this)"> <span class="icon-shr"></span></a>
						' . $this->ShareIcon . '
						</li>
						</ul>';
		return $html;
	}
	public function getShareUrlTwitter()
	{
		$share_u_abs = $this->DetailUrlAbsolute;
		$text_message = Yii::t('app', 'Hello, I am interested in this property and would like to make an appointment for a visit. Please contact me as soon as possible.{s}Thank you so much,', array('{s}' => ' %0a', '{1}' => $this->ad_title, '{2}' => $this->ReferenceNumberTitle, '{3}' => 'Property Link')) . ' %0a' .   urlencode($share_u_abs);
		return    Yii::t('app', 'https://www.facebook.com/sharer/sharer.php?u={text}', array('{number}' => Yii::t('app', !empty($this->whatsapp) ? $this->whatsapp : $this->mobile_number, array('+' => '', ' ' => '')), '{text}' => $text_message));
	}
	public function getShareUrlFacebook()
	{
		$share_u_abs = $this->DetailUrlAbsolute;
		$text_message = Yii::t('app', 'Hello, I am interested in this property and would like to make an appointment for a visit. Please contact me as soon as possible.{s}Thank you so much,', array('{s}' => ' %0a', '{1}' => $this->ad_title, '{2}' => $this->ReferenceNumberTitle, '{3}' => 'Property Link')) . ' %0a' .   urlencode($share_u_abs);
		return    Yii::t('app', 'https://twitter.com/intent/tweet?text={text}', array('{number}' => Yii::t('app', !empty($this->whatsapp) ? $this->whatsapp : $this->mobile_number, array('+' => '', ' ' => '')), '{text}' => $text_message));
	}
	public $cat_slug;
	public function generateLinks($formData, $category_text = null)
	{
		$region_list = array('dubai' => 'Dubai', 'abu-dhabi' => 'Abu Dhabi', 'sharjah' => 'Sharjah', 'ajman' => 'Ajman', 'al-ain' => 'Al Ain', 'ras-al-khaimah' => 'Ras Al Khaimah', 'umm-al-quwain' => 'Umm Al Quwain', 'fujairah' => 'Fujairah');
		$data =  array_filter($formData);
		$html = '';

		if (!empty($category_text)) {
			$category = $category_text;
		} else {
			$category = isset($data['type_of']) ? Yii::t('app', strtolower($data['type_of']), array('_' => ' ', 'commercial' => '', 'residential' => '')) : '';
		}
		$sale_in = $this->mTag()->getTag('{c}_for_sale_in', '{c} for sale in') . ' ';
		$rent_in = $this->mTag()->getTag('{c}_for_rent_in', '{c} for rent in') . ' ';
		$all_in = $this->mTag()->getTag('{c}_in', '{c} in') . ' ';
		$sale_in2 = $this->mTag()->getTag('properties_for_sale_in', 'Properties for sale in') . ' ';
		$rent_in2 = $this->mTag()->getTag('properties_for_rent_in', 'Properties for rent in') . ' ';
		$all_in2 = $this->mTag()->getTag('properties_in', 'Properties in') . ' ';
		switch ($data['sector']) {
			case 'property-for-sale':
				$titlte =  !empty($category) ? Yii::t('app', $sale_in, array('{c}' => '<span>' . $category . '</span>')) : $sale_in2;
				break;
			case 'property-for-rent':
				$titlte = !empty($category) ? Yii::t('app', $rent_in, array('{c}' => '<span>' . $category . '</span>')) : $rent_in2;
				break;
			default:
				$titlte =  !empty($category) ?  Yii::t('app', $all_in, array('{c}' => '<span>' . $category . '</span>')) :  $all_in2;
				break;
		}
		$ar = array();
		if (isset($data['city'])) {
			$ar['city'] = $data['city'];
		}
		if (isset($data['sector'])) {
			$ar['sector'] = $data['sector'];
		}
		if (isset($data['property_type'])) {
			$ar['property_type'] = $data['property_type'];
		}
		if (isset($data['category'])) {
			$ar['category'] = $data['category'];
		}
		if (isset($data['city'])) {

			$criteria =  PlaceAnAd::model()->findAds($ar, false, 1);

			$criteria->group = 't.category_id';
			$criteria->select .= ',cat.slug as cat_slug';
			$criteria->order = 'count(t.id) desc';
			$criteria->limit = 15;
			$items = PlaceAnAd::model()->findAll($criteria);

			switch ($data['sector']) {
				case 'property-for-sale':
					$titlte =   Yii::t('app', $this->mTag()->getTag('{c}_for_sale_in_{ct}', '{c} for sale in {ct}'), array('{c}' => '<span>{category}</span>', '{ct}' => '{city}'));
					break;
				case 'property-for-rent':
					$titlte =    Yii::t('app', $this->mTag()->getTag('{c}_for_rent_in_{ct}', '{c} for rent in {ct}'), array('{c}' => '<span>{category}</span>', '{ct}' => '{city}'));
					break;
				default:
					$titlte =    Yii::t('app', $this->mTag()->getTag('{c}_in_{ct}', '{c} in {ct}'), array('{c}' => '<span>{category}</span>', '{ct}' => '{city}'));
					break;
			}
			foreach ($items as $k => $v) {
				$ar['property_type'] = $v->cat_slug;
				$html .= '<li>' . Chtml::link(Yii::t('app', $titlte, array('{category}' => $v->category_name, '{city}' => $v->city_name)), Yii::app()->createUrl('listing/index', $ar)) . '<li>';
			}
		} else if (isset($data['category'])) {

			$criteria =  PlaceAnAd::model()->findAds($ar, false, 1);
			$criteria->group = 't.city';
			$criteria->select .= ',ct.slug as city_slug';
			$criteria->order = 'count(t.id) desc';
			$criteria->limit = 15;
			$items = PlaceAnAd::model()->findAll($criteria);

			foreach ($items as $k => $v) {
				$ar['city'] = $v->city_slug;
				$html .= '<li>' . Chtml::link($titlte . $v->city_name, Yii::app()->createUrl('listing/index', $ar)) . '<li>';
			}
		} else {

			$criteria =  PlaceAnAd::model()->findAds($ar, false, 1);

			$criteria->group = 't.city';
			$criteria->order = 'count(t.id) desc';
			$criteria->limit = 15;
			$items = PlaceAnAd::model()->findAll($criteria);

			foreach ($items as $k => $v) {
				$ar['city'] = $v->city_slug;
				$html .= '<li>' . Chtml::link($titlte . $v->city_name, Yii::app()->createUrl('listing/index', $ar)) . '<li>';
			}
		}
		return $html;
	}
	public function project_type_slug()
	{
		return array(
			'all' => 'All',
			'new-projects' => 'Completed',
			'off-plan-projects' => 'Off Plan',
			'under-construction-projects' => 'Construction Going'
		);
	}
	public function generateImageListing($apps, $h = 190, $w = 285, $s_id = null, $bg = null, $opaciti = 60, $wateri = '10')
	{
		$hml = '';
		if (!empty($this->ad_images_g)) {
			$itemsI = explode(',', $this->ad_images_g);
			if (!empty($itemsI)) {

				foreach ($itemsI as $k => $ad_img) {

					$image = $apps->getBaseUrl('uploads/images/' . $ad_img);
					$img =  $apps->getBaseUrl('timthumb.php') . '?src=' . $image . '&h=' . $h . '&w=' . $w . '&zc=1"';
					$hml .= '<a href="' . $this->detailUrl . '"  style="display:block;width:100%;height:100%;"><picture class="card_img card_img-style1 item"><img class="card_img card_img-style1 lozad"  data-placeholder-background="#eee" style="border:0px;" data-src="' . $img . '" alt=""></picture></a>';
				}
			}
		} else {
			$image = $apps->getBaseUrl('uploads/images/' . $this->ad_image);
			$img =  $apps->getBaseUrl('timthumb.php') . '?src=' . $image . '&h=' . $h . '&w=' . $w . '&zc=1"';
			$hml .= '<a href="' . $this->detailUrl . '"  style="display:block;width:100%;height:100%;"><picture class="card_img card_img-style1 item"><img class="card_img card_img-style1 lozad"  data-placeholder-background="#eee" style="border:0px;" data-src="' . $img . '" alt=""></picture></a>';
		}
		return $hml;
	}
	public function generateImageListingSingle($apps, $h = 190, $w = 285, $s_id = null, $bg = null, $opaciti = 60, $wateri = '10')
	{
		$hml = '';

		$image = $apps->getBaseUrl('uploads/images/' . $this->ad_image);
		$img =  $apps->getBaseUrl('timthumb.php') . '?src=' . $image . '&h=' . $h . '&w=' . $w . '&zc=1"';
		$hml .= '<picture class="card_img card_img-style1 item"><img class="card_img card_img-style1 lozad"  data-placeholder-background="#eee" style="border:0px;" data-src="' . $img . '" alt=""></picture>';


		return $hml;
	}
	public function validateAddProperty($attribute, $params)
	{
		$post =  Yii::App()->request->getPost('add_property_types', array());
		$errorFound = false;
		if (!empty($post)) {


			for ($i = 0; $i < sizeOf($post['title']); $i++) {
				if (empty($post['type_id'][$i]) || empty($post['title'][$i]) ||  empty($post['size'][$i])   ||  empty($post['from_price'][$i])) {

					$errorFound = true;
				}
			};
			if ($errorFound) {
				$this->addError($attribute,  Yii::t('app', 'Please fill all row values.', array('{attribute}' => $this->getAttributeLabel($attribute))));
			}
		}
	}
	public function validatePrice($attribute, $params)
	{
		if (empty($this->price) and empty($this->p_o_r)) {

			$this->addError($attribute,  $this->mTag()->getTag('required', 'Required'));
		}
	}
	public function all_property_types_details()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = '(case when t.type_id = "135" then "" when t.type_id = "121" then "" else t.bed end ) as bed,t.type_id,bath,title,from_price,to_price,size,size_to,area_unit,price_unit';
		$criteria->condition = 't.ad_id = :ad   ';
		$criteria->params[':ad'] = $this->id;
		$criteria->order = ' t.id asc';
		return AdPropertyTypes::model()->findAll($criteria);
	}
	public function all_property_types_detailsgroup()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.*,count(type_id) as  type_id_c,group_concat((case when t.type_id = "135" then "" when t.type_id = "121" then "" else t.bed end )) as bed_c';
		$criteria->condition = 't.ad_id = :ad   ';
		$criteria->params[':ad'] = $this->id;
		$criteria->order = ' t.id asc';
		$criteria->group = 't.type_id';
		return AdPropertyTypes::model()->findAll($criteria);
	}
	public function all_bed_detailsTitle()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.bed';
		$criteria->distinct = 't.bed';
		$criteria->condition = 't.ad_id = :ad and t.bed > 0  ';
		$criteria->params[':ad'] = $this->id;
		$criteria->order = ' t.id asc';
		$data =  AdPropertyTypes::model()->findAll($criteria);
		$html = '';
		if ($data) {
			foreach ($data as $k => $v) {
				$html[] = $v->bed;
			}
		}
		return $html;
	}
	const TITL_MIN = '30';
	const TITL_MAX = '90';
	const DESC_MIN = '500';
	const DESC_MAX = '1000';
	const image_MAX = '20';
	public $mark = 0;
	public function getCalculateMark()
	{

		if (empty($this->quality)) {
			$this->SizeOfTitle;
			$this->SizeOfImages;
			$this->SizeOfDescription;
			$this->CityTitlev;
			$qualiy = $this->mark;
			$this->updateByPk($this->id, array('quality' => $qualiy));
		} else {
			$qualiy =   $this->quality;
		}

		return $qualiy;
	}
	public function getAdImagesC()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = ' isTrash="0" and  t.ad_id = :ad   ';
		$criteria->params[':ad'] = $this->id;
		$criteria->order = '-t.priority desc';
		return AdImage::model()->count($criteria);
	}
	public function getSizeOfImages()
	{
		$validateClass = array();
		$strlen =   $this->adImagesC;
		if ($strlen == self::image_MAX) {
			$validateClass['class'] = 'success';
			$validateClass['title'] = $strlen . '/' . self::image_MAX;
			$this->mark += 25;
		} else if ($strlen > 10) {
			$validateClass['class'] = 'warning';
			$validateClass['title'] = $strlen . '/' . self::image_MAX;
			$this->mark += ($strlen * 0.8);
		} else {
			$validateClass['class'] = 'danger';
			$this->mark += ($strlen * 0.8);
			$validateClass['title'] = $strlen . '/' . self::image_MAX;
		}
		return $validateClass;
	}
	public function getSizeOfTitle()
	{
		$validateClass = array();
		$strlen =  strlen($this->ad_title);
		if ($strlen >= self::TITL_MIN  && $strlen <= self::TITL_MAX) {
			$validateClass['class'] = 'success';
			$validateClass['title'] =   $this->mTag()->getTag('good', 'Good');
			$this->mark += 25;
		} else {
			$validateClass['class'] = 'danger';
			if ($strlen < self::TITL_MIN) {
				$remaining = self::TITL_MIN - $strlen;
				if ($remaining >= 25) {
					$this->mark += 20;
				} else if ($remaining >= 15) {
					$this->mark += 15;
				} else if ($remaining >= 10) {
					$this->mark += 10;
				} else {
					$this->mark += 5;
				}


				$validateClass['title'] = $this->mTag()->getTag('short', 'Short');
				$validateClass['message'] = Yii::t('app', $this->mTag()->getTag('shorter_than_recommanded_lengt', 'Shorter than Recommanded length {s}{min} - {max}{e}'), array('{s}' => '<span dir="ltr" style="white-space:nowrap;">', '{e}' => '</span>', '{min}' => self::TITL_MIN, '{max}' => self::TITL_MAX));
			} else {
				$validateClass['title'] = $this->mTag()->getTag('long', 'Long');
				$remaining =   $strlen = self::TITL_MAX;
				if ($remaining <= 75) {
					$this->mark += 20;
				} else if ($remaining <= 100) {
					$this->mark += 15;
				} else if ($remaining <= 150) {
					$this->mark += 10;
				} else {
					$this->mark += 5;
				}
				$validateClass['message'] = Yii::t('app', $this->mTag()->getTag('above_recommanded_length_{mi', 'Above   Recommanded length  {s}{min} - {max}{e}'), array('{s}' => '<span dir="ltr" style="white-space:nowrap;">', '{e}' => '</span>', '{min}' => self::TITL_MIN, '{max}' => self::TITL_MAX));
			}
		}
		return $validateClass;
	}

	public function getSizeOfDescription()
	{
		$validateClass = array();
		$strlen =  strlen($this->ad_description);
		if ($strlen >= self::DESC_MIN  && $strlen <= self::DESC_MAX) {
			$validateClass['class'] = 'success';
			$validateClass['title'] =  $this->mTag()->getTag('good', 'Good');
			$this->mark += 25;
		} else {
			$validateClass['class'] = 'danger';

			if ($strlen < self::DESC_MIN) {
				$remaining = self::DESC_MIN - $strlen;
				if ($remaining <= 50) {
					$this->mark += 20;
				} else if ($remaining <=  100) {
					$this->mark += 15;
				} else if ($remaining <=  200) {
					$this->mark += 10;
				} else {
					$this->mark += 5;
				}
				$validateClass['title'] = 'Short';
				$validateClass['message'] = Yii::t('app', $this->mTag()->getTag('shorter_than_recommanded_lengt', 'Shorter than Recommanded length  {s}{min} - {max}{e}'), array('{s}' => '<span dir="ltr" style="white-space:nowrap;">', '{e}' => '</span>', '{min}' => self::DESC_MIN, '{max}' => self::DESC_MAX));
			} else {
				$remaining =  $strlen - self::DESC_MIN;

				if ($remaining <= 100) {
					$this->mark += 20;
				} else if ($remaining <= 200) {
					$this->mark += 15;
				} else if ($remaining <= 300) {
					$this->mark += 10;
				} else {
					$this->mark += 5;
				}
				$validateClass['title'] = 'Long';
				$validateClass['message'] = Yii::t('app', $this->mTag()->getTag('above_recommanded_length_{mi', 'Above  Recommanded length {s} {min} - {max} {e}'), array('{s}' => '<span dir="ltr" style="white-space:nowrap;">', '{e}' => '</span>', '{min}' => self::DESC_MIN, '{max}' => self::DESC_MAX));
			}
		}
		return $validateClass;
	}
	public function getCityTitlev()
	{
		$validateClass = array();
		if (!empty($this->state) and !empty($this->location_latitude)) {
			$validateClass['class'] = 'success';
			$validateClass['title'] = '2/2';
			$this->mark += 25;
		} else if (empty($this->location_latitude)) {
			$validateClass['class'] = 'danger';
			$this->mark += 15;
			$validateClass['title'] = '1/2';
			$validateClass['message'] = Yii::t('app', $this->mTag()->getTag('street_address_not_picked_from', 'Street address not picked from map dropdown list'));
		}
		return $validateClass;
	}
	public $city_name2;
	public $city_name3;
	public $city_name4;
	public $city_slug2;
	public $city_slug3;
	public $city_slug4;
	public $category_slug;
	public function getCityNameTitle()
	{
		$html = '';
		if (!empty($this->city_name4)) {
			$html .= $this->city_name4 . ', ';
		}
		if (!empty($this->city_name3)) {
			$html .= $this->city_name3 . ', ';
		}
		if (!empty($this->city_name2)) {
			$html .= $this->city_name2 . ', ';
		}
		$html .= $this->city_name;
		return $html;
	}
	public function getshowDateAddedField()
	{
		return $this->order_by != 'last_updated' ? true : false;
	}
	public function getshowUpdatedField()
	{
		return $this->order_by == 'last_updated' ? true : false;
	}
	public function getLastUpdateSmall()
	{
		return date('Y, m d', strtotime($this->last_updated));
	}
	public function getCityList()
	{
		$cacheKey =  'fame_cities_cache';

		if ($items = Yii::app()->cache->get($cacheKey) and !isset($_GET['refresh'])) {

			return $items;
		}
		$criteria               =   $this->findAds(array('_sec_id' => 3), false, 1);;
		$criteria->group         =    '  t.city  ';
		$criteria->select    =    'st1.slug as state_slug, ct.city_name, ct.slug as city_slug ';
		$criteria->join  .= ' INNER JOIN {{states}} st1 on st1.state_id = t.state';
		$criteria->order = 'count(t.id) desc';
		$criteria->limit = 10;
		$data =  $this->findAll($criteria);
		$items = array();
		foreach ($data as $k => $v) {
			$items[$v->city_slug] = array('state_slug' => $v->state_slug, 'city_name' => $v->city_name);
		}
		Yii::app()->cache->set($cacheKey, $items, 60 * 60 * 24 * 1);
		return $items;
	}
	public $developer_name;
	public $developer_logo;
	public function getDeveloperName()
	{
		return $this->developer_name;
	}
public function getDName(){
		if (!empty($this->developer_id)) {

			$developerModel = Developers::model()->findbyPk($this->developer_id);
			if ($developerModel) {
				 return $developerModel->developer_name;
			}else{
				return $this->DeveloperName; 
			}
		}
}
	public function getBgImage()
	{
		if (!empty($this->developer_logo)) {
			return   Yii::app()->controller->asset_parent('uploads/images/' . $this->developer_logo);
		} else {
			return Yii::app()->apps->getBaseUrl('assets/img/office-building.png');
		}
	}
	public function getAtttributePropertyDetails()
	{
		$type_is     =    $this->all_property_types_details();
		$type_is_c   =    $this->all_property_types_detailsgroup();
		$overViewhtm  = '';
		$proprty_type_title = '';
		$bed_title =  'Beds';
		$size_title = 'Size';
		if (!empty($type_is_c)) {
			foreach ($type_is_c as $k => $v) {
				if ($k != '0') {
					continue;
				}
				$c = $v->type_id_c > 1 ? '1' : '';
				$overViewhtm .= ' <li>' . $v->type->categoryIdLanItSelf($c) . '</li>';
				if (!empty($v->bed)) {
					if (!empty(rtrim($v->bed_c, ','))) {
						if ($v->bed == '14') {
							$tit = '13+';
						} else {
							$tit = !empty($v->bed_c) ? rtrim($v->bed_c, ',') : '';
						}

						$overViewhtm .= '<li><label> ' . $bed_title . '</label> ' . $tit . ' </li>';
					}
				} else if (!empty($v->size) and $v->size != '0.00') {
					$overViewhtm .= '<li><label> ' . $size_title . '</label> ' . number_format(Yii::t('app', $v->size, array('.00' => '')), 0, '.', ',') . ' Sq.Ft.</li>';
				}
			}
		}
		return $overViewhtm;
	}
	public function distance_calculate($lat1, $lon1, $lat2, $lon2, $unit)
	{

		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);

		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	public function saveSchoolInfo()
	{
		$ch = curl_init();
		$lat = substr($this->location_latitude, 0, 8);
		$lng =  substr($this->location_longitude, 0, 8);
		curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=' . $lat . '%2C' . $lng . '&radius=15000&type=school|primary_school|secondary_school&key=AIzaSyAGyr9A1wVJxnuXi_GKQrZu4GH7lzcbsyY');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
		$results = json_decode($result)->results;
		$find = SchoolsInfo::model()->findByAttributes(array('base_lat' => $lat, 'base_lng' => $lng));

		if (!empty($find) and  isset($_GET['update'])) {
			SchoolsInfo::model()->deleteAllByAttributes(array('base_lat' => $lat, 'base_lng' => $lng));
		} else if ($find) {
			return true;
		}

		$found = new SchoolsInfo();
		foreach ($results as $oprtion) {

			if ($oprtion->name) {
				$newfound = clone $found;
				$newfound->base_lat = $lat;
				$newfound->base_lng = $lng;
				$newfound->name = $oprtion->name;
				$newfound->address = $oprtion->vicinity;
				$newfound->lat = $oprtion->geometry->location->lat;
				$newfound->lng = $oprtion->geometry->location->lng;
				if (in_array('primary_school', $oprtion->types)) {
					$newfound->is_primary = 1;
				}
				if (in_array('secondary_school', $oprtion->types)) {
					$newfound->is_secondary = 1;
				}
				$kilo = $this->distance_calculate($lat, $lng, $oprtion->geometry->location->lat, $oprtion->geometry->location->lng, "K");
				$newfound->distance = number_format($kilo, 2, '.', '');
				$newfound->save();
			}
		}
		return true;
	}
	public function gettextSold()
	{
		if ($this->section_id == '1') {
			return 'Sold';
		}
		if ($this->section_id == '2') {
			return 'Rented';
		}
	}
	public function getPropertyTypeSingle(){
		$criteria = new CDbCriteria;
		$criteria->select = 'cat.category_name as category_name' ; 
		$criteria->compare('t.ad_id',(int)$this->primaryKey);
		$criteria->join = 'inner join {{category}} cat on cat.category_id = t.type_id'; 
		$adType = AdPropertyTypes::model()->find($criteria);
		if($adType){
			return $adType->category_name.' for Sale'; 
		}else{
			return 'No Property Types Mentioned';
		}
	}
	public function getCompletionYear()
	{
		if (!empty($this->c1)) {
			 
			$criteria = new CDbCriteria;
			$criteria->compare('t.master_id', (int)$this->c1);
			$adType = Master::model()->find($criteria); 
			return $adType->name  ;
		} else {
			return '#';
		}
	}
	public function getFetchSchoolsList(){
		return false; 
		if ($this->section_id == '3' and Yii::App()->isAppName('backend')  ) {
			/*
			if (empty($this->fetch_schools)) {
				return '<br /><div class="p-html"><button data-id="' . $this->primaryKey . '" type="button" class="btn btn-sm inactive fetchSchools" onclick="fetchSchools(this)">Fetch Schools</button></div>';
			} else {
				return '<br /><div class="p-html"><button data-id="'.$this->primaryKey. '" type="button" class="btn btn-sm fetchSchools active" >Fetched Schools</button></div>';
			}
				*/
		}
	}
	function formatPhoneNumber($phoneNumber)
	{
		// Remove any non-numeric characters
		$cleaned = preg_replace('/\D/', '', $phoneNumber);

		// Ensure the phone number has at least 7 digits to format properly
		if (strlen($cleaned) >= 7) {
			$areaCode = substr($cleaned, 0, 3); // First 3 digits
			$middle = substr($cleaned, 3, 3);  // Next 3 digits
			$hidden = "XX";                   // Mask last two digits

			return "{$areaCode}-{$middle}-{$hidden}";
		}

		// Return original input if the number is too short
		return $phoneNumber;
	}
	public $featured_days_remaining;
	public function getPublishAd(){
		return
		'<a class="boost-button refresh-btn publishadStyle"   style="  "  title="Publish Property"  onclick="processpublish(this)"  data-href="' . Yii::app()->createUrl('post_ad/publish',['id'=>$this->primaryKey]) . '" >Publish Your Ad</a>';
	}
	public function getBoost()
	{
		$html = '<div class="boost-options">'; 
		if($this->status=='D'){
			$html .= $this->PublishAd; 
	
		} else if($this->status=='A'){
			if (!$this->IsFeatured) {
				$html .= '<button class="boost-button featured-btn inactive-b" type="button"  href="javascript:void(0)"    onclick="processfeatured(this)"  data-href="' . Yii::app()->createUrl('member/add_featured', array('id' => $this->primaryKey)) . '" ><i class="fa fa-plus"></i> FEATURED</button>';
			}else{
				$txt = '';
				if($this->featured_days_remaining!='UNLIMITED'){
					$txt .= '<small>'. ($this->featured_days_remaining+1). 'days</small> ';
				}
				$html .= '<div class="featured-item-t"><i class="fa fa-star"></i> Featured '. $txt. '</div> ';
			}
			if (!$this->IsHot) {
			 	$html .= '<button class="boost-button hot-btn inactive-b" type="button"  href="javascript:void(0)"  style=" "  onclick="processfeatured(this)"  data-href="' . Yii::app()->createUrl('member/add_hot', array('id' => $this->primaryKey)) . '" ><i class="fa fa-plus"></i> HOT</button>';
			} else {
				$txt = '';
				if ($this->hot_days_remaining != 'UNLIMITED') {
					$txt .= '<small>' . ($this->hot_days_remaining + 1) . 'days</small> ';
				}
				$html .= '<div class="hot-item-t"><i class="fa fa-fire"></i> Hot Listing ' . $txt . '</div> ';
			}
			if(!empty($this->refresh_date) and date('Y-m-d',strtotime($this->refresh_date)) == date('Y-m-d')){
				$html .= '<div class="hot-item-t refresh"><i class="fa fa-refresh"></i> Refreshed</div> ';
			}else{
				$html .= '<a class="boost-button refresh-btn" href="javascript:void(0)" style="    width: auto;    display: block;    margin-left: 0px;    max-width: 132px;    padding: 5px 10px;    line-height: 1;    color: var(--blue);    border: 1px solid var(--blue);"  title="Refresh Quoata"  onclick="processrefresh(this)"  data-href="' . Yii::app()->createUrl('member/add_reset', array('id' => $this->primaryKey)) . '" >Refresh Your Ad</a>';
			}
		}
		$html .='</div>';
		return $html;
	}
	public function getIsFeatured()
	{
		return (!empty($this->featured_days_remaining)) ? true : false;
	}
	public $hot_days_remaining;
	public function getIsHot()
	{
		return (!empty($this->hot_days_remaining)) ? true : false;
	}
	public function getSectionName1()
	{
		 switch($this->section_id){
			case '1':
				return '<span class="sect-btn sale-1" >Sale</span>';
			break;
			case '2':
				return '<span class="sect-btn rent-1" >Rent</span>';
			break;
			case '3':
				return '<span class="sect-btn dev-1" >New Project</span>';
			break;
		 }
	}
	public function dynamicErrors($fields)
	{
		$errors = [];
		foreach ($fields as $field) {
			if ($this->hasErrors($field)) {
				$errors[$field] = $this->getErrors($field);
			}
		}
		return $errors;
	}
	public function detailView($im)
	{

		 
 			return Yii::app()->apps->getBaseUrl('uploads/images/' . $im);;
		 
	}
    public function active_plan_check(){
		$user = ListingUsers::model()->findByPk($this->user_id);
		$result =  [] ;
		if (!empty($user->parent_user)) {
			$parent_member = ListingUsers::model()->findByPk($user->parent_user);
			$result =    $parent_member->getvalidateUserCurrentPackage(1);
		} else {
			$result = $user->getvalidateUserCurrentPackage(1);
		}
		if(!empty($result) and isset($result['success'])){
			return false; 
		}
		 
		return $result;
	}
	public function getMapImage(){
		if(!empty($this->id)){
			return Yii::app()->apps->getBaseUrl('map_images/' . $this->primaryKey . '.png');
		}else{
			return 'https://maps.googleapis.com/maps/api/staticmap?scale=2&&markers=icon:https://www.ajmanproperties.ae/assets/img/Mapn.png|' . $this->location_latitude . ',' .  $this->location_longitude . '&zoom=14&size=840x400&center=' . $latitude . ',' . $longitude . '&key=AIzaSyAuq0074pFpCc_GKeTNEIpLTrNbQWTFRBQ&&style=saturation:-20&style=element:labels.text.fill%7Ccolor:0x6e6e6e%7Cvisibility:on&style=feature:landscape%7Celement:geometry.fill%7Ccolor:0xececec%7Cvisibility:on&style=feature:poi%7Celement:labels%7Cvisibility:off&style=feature:road%7Celement:geometry.fill%7Ccolor:0xffffff&style=feature:road%7Celement:geometry.stroke%7Ccolor:0xc9c9c9%7Cvisibility:on&style=feature:road%7Celement:labels.icon%7Cvisibility:off&style=feature:road%7Celement:labels.text.fill%7Ccolor:0x5f5f5f%7Cvisibility:on&style=feature:road.highway%7Celement:geometry.fill%7Ccolor:0xffffff%7Cvisibility:on&style=feature:transit%7Celement:labels.icon%7Csaturation:-50%7Cvisibility:on';
		}
	}
	public function getMobileDetails(){
		return '<div class="show-new-mobile"><div class="property-card">
					<h2 class="property-header">'. @$this->category->category_name. '</h2>
					<div class="property-details">
					<p><span class="innr1">Ref #:</span> ' .  $this->ReferenceNumberTitleLink . '</p>
					<p><span class="innr1">Bedrooms:</span> ' . @$this->BedroomTitle  . '</p>
					<p><span class="innr1">Community:</span> ' . @$this->CountryNameSection . '</p>
					<p><span class="innr1">Price:</span> '. @$this->PriceTitle. '</p>
					<p><span class="innr1">Purpose:</span> ' . @$this->section->fieldName . "&nbsp;<span class=parnth>" . $this->MarkedSuccess . "</span>" . '</p>
					<p><span class="innr1">Date:</span> '. $this->SmallDate. '</p>
					<p><span class="innr1">Status:</span> '.  $this->StatusLinkFront .'</p>
					</div>
					<div class="statistics">'. $this->statisticsCls.'</div>
					<div class="button-group">
					 '. $this->Boost. '
					</div>
				</div></div>';
	}
	public function actionDraftNotification(){
		$criteria = new CDbCriteria;
		$criteria->compare('t.status','D');
		$criteria->condition .= ' and  t.draft_date is not null and  DATEDIFF(CURDATE(),t.draft_date ) IN ( 1, 2, 3) AND (t.draft_send IS NULL OR t.draft_send < CURDATE())    ';
        $ads = PlaceAnAd::model()->findAll($criteria);
		 
		if($ads){
			foreach($ads as $k=>$v){
				  $v->sendDraftNoyification();  
			}
		}
		exit; 
	}
	public function sendDraftNoyification(){
		$options = Yii::app()->options;
		$emailTemplate =  CustomerEmailTemplate::model()->findByAttributes(array('template_uid' => "gb828w6943ac1"));;

		if ($emailTemplate) {

			$customer = ListingUsers::model()->findByPk($this->user_id);
			//$customer->email = 'vineethnjalil@gmail.com';
			$subject        = $emailTemplate->subject;
			$emailTemplate  = $emailTemplate->content;
			// $emailTemplate_common = $this->commonTemplate();
			$receipeints = serialize(array($customer->email));
			//$receipeints = serialize(array('vineethnjalil@gmail.com'));
			$status = 'S';
			$adminEmail = new Email();
			$renewal_link = ASKAAN_PATH . 'post_ad/draft/id/'.$this->primaryKey; // BASE_PATH . Yii::app()->createUrl('member/payment_package', ['plan' => $plan->plan_uid, 'renewal_id' => $this->primaryKey]);
			 
			$FeatureName = '';
			 
			$adminEmail->subject = str_replace(['[PROJECT_NAME]'], [$options->get('system.common.site_name', 'support@feeta.pk')], $subject);

			$adminEmail->message = str_replace(
				[
					'[Customer Name]',
					'_URL_'
				],
				[ 
					 $customer->fullName, 
					 $renewal_link , 
				],
				$emailTemplate
			);
			//$adminEmail->message = str_replace('[INVOICE DETAILS]', $InvoiceDetails, $adminEmail->message);
			$emailTemplate_common = Yii::app()->tags->getTag('common');
			if (empty($emailTemplate_common)) {

				$emailTemplate_common = $options->get('system.email_templates.common');
			}
			$adminEmail->message =   Yii::t('app', $emailTemplate_common, array('[CONTENT]' => $adminEmail->message));
 
			$adminEmail->status = $status;
			$adminEmail->receipeints = $receipeints;
			$adminEmail->sent_on =   1;
			$adminEmail->type =   'REGISTER';
			$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
			$adminEmail->save(false);
			$adminEmail->send;
			$this->updateByPk((int) $this->primaryKey, ['draft_send' => date('Y-m-d')]);
		}
	} 
	public function getImageList(){
	 
		$apps = Yii::app()->apps; 
		$criteria = new CDbCriteria;
		$criteria->compare('t.ad_id', (int)$this->id);
		$criteria->compare('t.status','A');
		$image = AdImage::model()->find($criteria);  
		$img_link = $apps->getBaseUrl('timthumb.php') . '?src=' . $apps->getBaseUrl('uploads/images/' . $image->image_name) . '&h=80&w=80&zc=1';
		return '<img src="' . $img_link. '" class="rounded-circle" style="width:80;">';;

	 
	}
	public function getdivOfTitle(){
		return '<div class="property-info-1">
				<div class="property-info-img">
				<a href="' . $this->PreviewUrlTrash . '" target="_blank">'.$this->ImageList. '</a>
				</div>
				<div class="property-info-title">
				<div class="top-list-sect"><span dir=ltr class="date-of"  >'.@$this->SmallDate.'</span> <span>REF# <b>'. $this->ReferenceNumberTitle.'</b></span> '.$this->statusLinkFront.'</div>
					<div class="property-info-title-index">
					<p><a href="'.$this->PreviewUrlTrash. '" target="_blank">'.$this->adTitle. '</a><p>
					<p>'. $this->BedroomTitleN. $this->category->category_name. ', <i class="fa   fa-map-marker"></i> '. $this->CountryNameSection.'</p>
					</div>
					<div class="property-info-title-by">By <span class="text-capitalize text-bold">' . $this->listedByTitle . '<span></div>
					<div>'. $this->Boost.'</div>
				</div>
		</div>';
	}
	public function getBedroomTitleN()
	{
		if ($this->bedrooms == '14') {
			return '13+ beds, ';
		}
		if ($this->bedrooms == '15') {
			return 'Studio, ';
		}
		return  $this->bedrooms. ' beds, ';
	}
	public function promoted_list_types(){
		return [
			'featured' => 'Featured',
			'hot' => 'Hot',
			'premium' => 'Premium',
		];
	}
	public function getStatusLinkFrontN()
	{
		$title = $this->StatusTitle;
		switch ($this->status) {
			case 'A':
				return '<span class="green-text pe-3" title="' . $title . '">' . $title . '</span>';
				break;
			case 'W':
				return '<span class="teal-text pe-3"   title="' . $title . '">' . $title . '</span>';
				break;
			case 'I':
				return '<span class="warning-text pe-3"   title="' . $title . '">' . $title . '</span>';
				break;
			case 'R':
				return '<span class="btn-danger danger-text pe-3" title="' . $title . '">' . $title . '</span>';
				break;
			case 'D':
				return '<span class="danger-text pe-3" title="' . $title . '">' . $title . '</span>';
				break;
		}
	}
	public function getCategoryName2(){
		$category = Category::model()->findbyPk($this->category_id);
		if($category){
			return $category->category_name; 
		}
	}
	public function getCommunity2()
	{
		return $this->city_name;  
	}
	public function getAdImageLink()
	{

		$apps = Yii::app()->apps;
		$criteria = new CDbCriteria;
		$criteria->compare('t.ad_id', (int)$this->id);
		$criteria->compare('t.status', 'A');
		$image = AdImage::model()->find($criteria);
		return $apps->getBaseUrl('uploads/images/' . $image->image_name)  ;
	}

	public function getBoost2()
	{
		$html = '<div class="d-flex">';
		if ($this->status == 'D') {
			$html .= $this->PublishAd;
		} else if ($this->status == 'A') {
			if (!$this->IsFeatured) {
				$html .= '<button class="btn btn-round btn-purple-light purple-text btn-sm me-2 featured-btn inactive-b" type="button"  href="javascript:void(0)"    onclick="processfeatured(this)"  data-href="' . Yii::app()->createUrl('member/add_featured', array('id' => $this->primaryKey)) . '" >Featured</button>';
			} else {
				$txt = '';
				if ($this->featured_days_remaining != 'UNLIMITED') {
					$txt .= '<small>' . ($this->featured_days_remaining + 1) . 'days</small> ';
				}
				$html .= '<div class="featured-item-t"><i class="fa fa-star"></i> Featured ' . $txt . '</div> ';
			}
			if (!$this->IsHot) {
				$html .= '<button class="btn btn-round btn-red-light red-text  btn-sm me-2 hot-btn inactive-b" type="button"  href="javascript:void(0)"  style=" "  onclick="processfeatured(this)"  data-href="' . Yii::app()->createUrl('member/add_hot', array('id' => $this->primaryKey)) . '" >Hot Property</button>';
			} else {
				$txt = '';
				if ($this->hot_days_remaining != 'UNLIMITED') {
					$txt .= '<small>' . ($this->hot_days_remaining + 1) . 'days</small> ';
				}
				$html .= '<div class="hot-item-t"><i class="fa fa-fire"></i> Hot Listing ' . $txt . '</div> ';
			}
			if (!empty($this->refresh_date) and date('Y-m-d', strtotime($this->refresh_date)) == date('Y-m-d')) {
				$html .= '<div class="hot-item-t refresh"><i class="fa fa-refresh"></i> Refreshed</div> ';
			} else {
				$html .= '<a class="btn btn-round btn-blue-light blue-text  btn-sm me-2 refresh-btn" href="javascript:void(0)"   title="Refresh Quoata"  onclick="processrefresh(this)"  data-href="' . Yii::app()->createUrl('member/add_reset', array('id' => $this->primaryKey)) . '" >Refresh your ad</a>';
			}
		}
		$html .= '</div>';
		return $html;
	}
	public function getpropertyDetails($hide_boost = false){
		$asset_path = '/assets/dashboard/';
		$category = $this->CategoryName2; 
		$community = $this->Community2; 
		return '<div class="d-flex">
                                        <div class="avatar avatar-onlin p-image">
                                            <img src="'.  $this->AdImageLink . '" alt="pimg" class="rounded w-100 mt-2">
                                        </div>
                                        <div class="flex-1 ms-3 pt-1">
										' . (!empty($hide_boost) ?('
                                            <div class="d-flex">
                                                 <div class="text-muted">'.date('d m Y',strtotime($this->date_added)).' <strong class="ms-2">REF# '.$this->ReferenceNumberTitle.'</strong></div>
                                            </div>') : '').'
                                            <div class="d-flex">
                                                <h6 class="  mb-1 blue-text">
                                                    '.$this->adTitle.'
                                                </h6>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <span class="text-dark pe-3">'.$this->BedroomTitleN.' '.  $category . ',</span>
                                                <span class="text-dark pe-3"><i class="fas fa-map-marker-alt"></i> ' . $community. '</span>
                                                '.$this->StatusLinkFrontN. '
												 
                                            </div>
											' . (empty($hide_boost) ?  ''  : ' <div class=" mt-1 d-block add-by">By <span class="fw-semibold text-capitalize">'.$this->AddedBy. '<span></div>') . '
                                             '. (empty($hide_boost) ? $this->Boost2 : '' ). '
                                        </div>
										
                                    </div>';
	}
	public function getNewDate(){
		return date('d M Y',strtotime($this->date_added));
	}
	public function getnewRefNumber(){
		return  $this->ReferenceNumberTitle;
	}
	public function getAddedBy(){
		return $this->listedByTitle;
	}
	public function getPurpose(){
		return  $this->section->fieldName."&nbsp;<span class=parnth>". $this->MarkedSuccess."</span>" ; 
	}
	public function getPriceDetails(){
		return $this->PriceTitle;
	}
}
