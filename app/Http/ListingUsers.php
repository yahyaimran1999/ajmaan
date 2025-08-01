<?php

/**
 * This is the model class for table "mw_booking_users".
 *
 * The followings are the available columns in table 'mw_booking_users':
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $cityimage
 * @property string $state
 * @property integer $country
 * @property string $zip
 * @property string $phone
 * @property string $fax
 * @property string $email
 * @property string $password
 * @property integer $isTrash
 * @property string $status
 */
class ListingUsers extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $con_password;
	public $old_password;
	public $checkin;
	public $user_name;
	public  $login_email;
	public  $login_password;
	public  $mul_country_id;
	public  $mul_state_id;
	public  $mul_city_id;
	public  $languages_known;
	public  $property_type;
	public  $country_slug;
	public  $confirm_email;
	public  $dob_m_birthDay;
	public  $subscribe;
	public $f_type;
	public $search_agent;
	public $ref_id;
	public $p_company_logo;

	public $click_user_type;
	public $professional_type;
	public $is_phone_validated;
	public $_recaptcha;
	public $agree;
	public $otp;

	public function tableName()
	{
		return '{{listing_users}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$tags = Yii::app()->tags;
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(



			// Required fields for user registration
			array('email, user_type', 'required', 'on' => 'userRegistration, agencyRegistration', 'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.')),
			array('email, user_type', 'required', 'on' => 'userRegistration, agencyRegistration', 'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.')),
			///array('agree', Yii::App()->isAppName('frontend') ? 'validateAgre' : 'safe', 'on' => 'agencyRegistration,userRegistration'  ),
			// array('user_type', 'required', 'on' => 'userRegistration, agencyRegistration', 'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.')),

			//array('first_name, last_name, phone', 'required', 'on' => 'agencyRegistration', 'message' =>   $tags->getTag('required', 'Required.')),

			// Password validation for user registration
			array('password', 'required', 'on' => 'userRegistration, agencyRegistration', 'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.')),
			array('password', 'length', 'max' => 250, 'on' => 'userRegistration, agencyRegistration'),
			array('password', 'length', 'min' => 5, 'on' => 'userRegistration, agencyRegistration'),
			array('password','validatePasswordRules', 'on' => 'userRegistration, agencyRegistration,updatepassword,change_password' ),

			// Email unique validation
			array('email', 'unique', 'on' => 'userRegistration, agencyRegistration', 'message' => $tags->getTag('unique_field_email_message', '{attribute} has already been taken.')),

			// Safe attributes
			array('email_verified', 'safe', 'on' => 'userRegistration, agencyRegistration'),
			array('licence_no', 'validateAgencyRegistration', 'on' => 'pending-documents'), 
			//array('is_phone_validated', 'validateHiddenInput', "on" => 'pending-documents'),


			array('email,password,password,user_type', 'required', "on" => array('latest_signup'),  'message' => $tags->getTag('required', 'Required')),
			array('_recaptcha', Yii::app()->isAppName('frontend') ? 'validateRecaptchaLatest' : 'safe', "on" => 'userRegistration, agencyRegistration'),
			array('_recaptcha', 'validateRecaptchaNew', "on" => 'latest_signup'),
			array('click_user_type', 'safe', "on" => array('latest_signup',),),
			array('user_type', 'validateLatestSignup', "on" => array('latest_signup',),),
			array('is_phone_validated', 'validateHiddenInput', "on" => 'latest_signup'),

			array('login_email,login_password', 'required', "on" => array("login"),  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.')),
			array('first_name,email', 'required', "on" => array('frontend_insert', "insert", "update", 'agent_insert', 'agent_update', 'developer_insert', 'developer_update', 'agent_update1', 'developer_update1', 'customer_insert', 'customer_update'),  'message' => $tags->getTag('required', 'Required')),
			array('last_name,mobile,whatsapp,languages_known,description,licence_no,phone,country_id,calls_me,user_status', 'required', "on" => array( 'agent_insert', 'agent_update'),  'message' => $tags->getTag('required', 'Required')),
			array('country_id,state_id, isTrash,no_of_employees', 'numerical', 'integerOnly' => true),
			array('old_password', 'required',  'message' => '{attribute} ' . Yii::app()->tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => 'updatepassword'),
			array('old_password', 'checkOldPassword', 'on' => 'updatepassword'),
			array('dob', 'validateDob', 'on' => 'agent_insert,developer_update,agent_update,agent_update1,developer_update1'),
			array('user_type', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => 'frontend_insert'),
			array('password', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => 'update-new-password'),

			array('website', 'safe', 'on' => "developer_update,agent_update,agent_update1,developer_update1"),
			array('contact_person,contact_email,facebook,twiter,google,company_name', 'safe', 'on' => "developer_update,agent_update,agent_update1,developer_update1"),
			//array('','required', 'on'=>"agent_insert,developer_update,agent_update,agent_update1,developer_update1" ),
			array('contact_person,contact_email,facebook,twiter,google,company_name', 'length', 'max' => 250),
			array('s_code', 'length', 'max' => 10),
			array('first_name,phone', 'required', 'on' => 'account-information', 'message' => 'Required'),
			array('calls_me,image', 'required', 'on' => 'profile-update', 'message' => 'Required'),
			array('company_name,a_description,phone,address,company_logo,whatsapp,xml_image', 'required', 'on' => 'company-settings', 'message' => 'Required'),
			array('first_name,email,password,company_name,a_description,phone,address,company_logo,mobile,whatsapp,xml_image', 'required', 'on' => 'agency-registartion', 'message' => 'Required'),
			array('contact_person,contact_email,facebook,twiter,google,company_name', 'length', 'max' => 250),
			array('contact_email,company_email', 'email',  'message' => '{attribute} ' . $tags->getTag('is_not_valid_email_address', 'is not a valid e-mail address.')),
			array('facebook,twiter,google,website', 'url', 'defaultScheme' => 'https'),

			array('password,con_password,first_name, phone', 'required', 'on' => "updatepassword1"),
			array('con_password', 'compare', 'compareAttribute' => 'password', "on" => "updatepassword1"),
			array('password,con_password', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => "frontend_insert,insert,updatepassword,updatepasswordu,agent_insert,developer_insert, customer_insert"),
			array('user_type,image,first_name','required',  'message' => $tags->getTag('required', 'Required'), 'on' => "agent_insert,developer_insert,developer_update,agent_update,agent_update1,developer_update1"),
			array('slug,first_name', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => "developer_insert,developer_update,agent_update,agent_update1,developer_update1"),
			array('mul_country_id', 'safe'),
			array(' phone', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => 'customer_insert,customer_update'),
			array('first_name, last_name, city, state, email', 'length', 'max' => 150),
			array('email', 'email',  'message' => '{attribute} ' . $tags->getTag('is_not_valid_email_address', 'is not a valid e-mail address.')),
			//array('description','required',  'message'=>'{attribute} '. $tags->getTag('cannot_be_empty','cannot be blank.'), 'on'=>"agent_insert,developer_update,agent_update,agent_update1,developer_update1" ),
			array('email', 'unique',   'message' => $tags->getTag('unique_field_email_message', '{attribute} has already been taken.'), 'on' => "latest_signup,frontend_insert,insert,agent_insert,developer_insert,developer_update,agent_update,agent_update1,developer_update1,customer_insert,customer_update,agency-registartion"),
			array('country_slug', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => 'find_step_1'),
			array('property_type', 'safe', 'on' => 'find_step_1'),
			array('address', 'length', 'max' => 500),
			array('licence_no,broker_no', 'length', 'max' => 50),
			array('description', 'length', 'max' => 2000),
			//array('user_type', 'required',  'message'=>'{attribute} '. $tags->getTag('cannot_be_empty','cannot be blank.'), 'on'=>'frontend_insert'),
			array('email_verified', 'safe', 'on' => 'frontend_insert'),
			array('user_type,calls_me,country_id,type_of,email_verified,subscribe,mobile,passport,visa,signature,verified,slug_name', 'safe'),
			array('image', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => 'update_logo'),
			array('user_type', 'in', 'range' => array_keys($this->getUserType()),  'message' => 'Please enter a value for {attribute_value}.'),
			array('zip', 'length', 'max' => 7),
			array('phone', 'length', 'max' => 15),
			array('fax', 'length', 'max' => 10),
			array('password,con_password', 'required','on'=> 'change_password' ),
			array('licence_no_expiry,eid_expiry_date,social_urls,designation_id', 'safe' ),
			array('upload_arra_id,trade_license_number,eid_number,upload_eid,trade_license_expiry,arra_date,arra_number,arra_doc,upload_eid', 'safe' ),
			array('password', 'length', 'max' => 250, "on" => "update-new-password,frontend_insert,insert,agent_insert,developer_insert,developer_update,agent_update,customer_insert,customer_update"),
			array('password', 'length', 'min' => 5, "on" => "update-new-password,frontend_insert,insert,agent_insert,developer_insert,developer_update,agent_update,customer_insert,customer_update"),
			array('languages_known', 'safe',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), "on" => "agent_insert,agent_update,agent_update1"),
			array('slug', 'validateSlug', 'on' => 'developer_update,agent_update,agent_update1,developer_update1'),
			array('con_password', 'compare', 'message' => $tags->getTag('password-do-not-match', 'Password do not match'), 'compareAttribute' => 'password', "on" => "latest_signup,frontend_insert,insert,updatepassword,updatepasswordu,agent_insert,developer_insert,developer_update,agent_update,customer_insert,customer_update,agency-registartion,change_password"),
			array('status', 'length', 'max' => 1),
			array('mul_country_id,mul_state_id,mul_city_id,services_offering,company_logo,max_no_users,parent_user,user_status,f_type,whatsapp,facebook,google,twiter', 'safe'),
			array('registered_via,rera,a_description,a_r_n,r_a,r_n,o_r_a,o_r_n,languages_known,services_offering,prime_user', 'safe'),
			array('send_me,featured', 'length', 'max' => 1),
			array('email', 'unique', 'on' => 'updateEmail'),
			array('first_name,user_type', 'required', "on" => array('new_update2')),
			array('email', 'required',  'message' => '{attribute} ' . $tags->getTag('cannot_be_empty', 'cannot be blank.'), 'on' => 'updateEmail'),
			array('email', 'updateEmailChecker', 'on' => 'updateEmail'),
			array('cover_letter,dob,calls_me,country,position_level,education_level,updates,advertisement,username,con_password,image,xml_inserted,xml_image,mobile,user_type,phone,city,user_name,not_validated_captcha,documents_submitted,xml_image,no_of_employees', 'safe'),
			['otp', 'required','on'=> 'verify-otp'],			
			['otp', 'match', 'pattern' => '/^\d{6}$/', 'message' =>'OTP must be exactly 6 digits.', 'on' => 'verify-otp'],
			['otp', 'validateOtp', 'on' => 'verify-otp'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, first_name,documents_submitted, last_name, address, city, state, country, zip, phone, fax, email, password, isTrash, status,date_added,ref_id', 'safe', 'on' => 'search'),
		);
	}
	public function getRequireds(){
		if(defined('AGENT')){
			return [
				'trade_license_expiry',
				//'licence_no_expiry',
				'upload_eid',
				'eid_number',
				'trade_license_number',
				'xml_image',
				//'upload_arra_id',
				'no_of_employees',
				'description',
				//'services_offering',
				'licence_no',
				'address',
				'company_logo',
				'company_email',
				'mobile',
				'whatsapp',
				//'services_offering',
				'contact_person',
				'user_status',
				'description'
				//last_name,mobile,whatsapp,languages_known,services_offering,description,licence_no,phone,country_id,calls_me,services_offering,user_status
			];
		}
		//'licence_no_expiry','upload_arra_id', 'eid_number','eid_expiry_date',   'upload_eid','address','phone','first_name',,'last_name', 'company_logo', 'calls_me','company_name','image', 'calls_me'
		return ['trade_license_expiry', 'licence_no_expiry', 'upload_eid', 'eid_number',  'trade_license_number', 'xml_image',  'no_of_employees',  'description',  'licence_no',
			'address','company_logo','company_email','mobile','whatsapp','services_offering','contact_person',
	];
	}
	public function validateAgencyRegistration($attribute, $params)
	{
		if($this->user_type=='K'){
			$fields = $this->getRequireds();
			foreach($fields as $field){
				if(empty($this->$field)){
					$this->addError($field, 'Required.');
				} 
			}

		}
	}
	public function validateAgre($attribute, $params)
	{
		if ($this->user_type != 'U') {
			if(empty($this->first_name)){
				$this->addError('first_name', Yii::t('languages', 'Required'));
			}
			if (empty($this->last_name)) {
				$this->addError('last_name', Yii::t('languages', 'Required'));
			}
			if (empty($this->company_name)) {
				$this->addError('company_name', Yii::t('languages', 'Required'));
			}
			if (empty($this->phone)) {
				$this->addError('phone', Yii::t('languages', 'Required'));
			}
		}
		if(empty($this->agree)){
			$this->addError('agree', Yii::t('languages','You must agree to the terms and conditions before proceeding.'));
		}
	}
	public function validateRecaptchaNew($attribute, $params)
	{

		if (!Yii::app()->request->isAjaxRequest and Yii::app()->isAppName('frontend')) {


			$captcha = '';
			if (isset($_POST['g-recaptcha-response'])) {
				$captcha = $_POST['g-recaptcha-response'];
			}

			if (!$captcha) {
				$this->addError($attribute, 'Please check the   captcha form.');
			}


			$data = array(
				'secret' => '6Ld2DKkjAAAAAE01L6LcM3ZUZyMgP6UBZVl963ll',
				'response' => $captcha,
				'remoteip' => $_SERVER['REMOTE_ADDR']
			);

			$verify = curl_init();
			curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($verify, CURLOPT_POST, true);
			curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
			$res = curl_exec($verify);

			$captcha = json_decode($res);


			if ($captcha->success) {
			} else {
				$this->addError($attribute,  'Spam suspecting. Please refresh and try again.');
			}
		}
	}

	public function validateLatestSignup($attribute, $params)
	{

		if (empty($this->professional_type) and in_array($this->user_type, array('K', 'L'))) {

			//$this->addError('professional_type', 'Required');
		}
		if (empty($this->company_name) and in_array($this->user_type, array('K'))) {

			$this->addError('company_name', 'Required');
		}
		if (empty($this->first_name) and in_array($this->user_type, array('K', 'L'))) {

			$this->addError('first_name', 'Required');
		}
		if (empty($this->last_name) and in_array($this->user_type, array('K', 'L'))) {

			$this->addError('last_name', 'Required');
		}
		if (empty($this->phone) and in_array($this->user_type, array('K', 'L'))) {

			$this->addError('phone', 'Required');
		}
	}
	public function validateHiddenInput($attribute, $params)
	{
		if ($this->is_phone_validated != '1' and in_array($this->user_type, array('K', 'L'))) {
			$this->addError('phone',  'Please enter valid phone number');
		}
	}

	public function checkOldPassword($attribute, $params)
	{
		$login = new UserLogin();
		$login->email = $this->email;
		$login->password = $this->old_password;
		if (!$login->validate()) {
			$this->addError($attribute, Yii::app()->tags->getTag('invalid-login-credentailsyrt', 'Invalid Credential'));
		}
	}


	public function validateDob($attribute, $params)
	{
		if (!empty($this->dob) and $this->dob != '0000-00-00') {
			$test_arr  = explode('-', $this->dob);
			if (count($test_arr) == 3) {

				if (checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
				} else {
					$this->addError($attribute, Yii::app()->tags->getTag('invalid-date', 'Invalid Date'));
				}
			} else {
				$this->addError($attribute, Yii::app()->tags->getTag('invalid-date', 'Invalid Date'));
			}
		}
	}
	public function getUserType()
	{
		$tags =  Yii::app()->tags;
		return array(
			'A' => $tags->getTag('agent', 'Agents'),
			'K' => $tags->getTag('agent', 'Agencies'),
			'D' => $tags->getTag('developers', 'Developers'),
			// 'L' => $tags->getTag('developers','Landlord'),
			'U' => $tags->getTag('advertisers', 'Visitor'),
		);
	}

	public function getCompaniesType()
	{
		return array('D', 'K');
	}

	public function getStatusArray()
	{
		return array(
			'A' => 'Approved',
			'I' => 'Inactive',
			'W' => 'WAITING',
			'R' => 'Rejected',
			'U' => 'Pending Documents',
		);
	}
	public function genderArray()
	{
		return array(
			'M' => 'Male',
			'F' => 'Female',
		);
	}
	public function getGenderArrayTitle()
	{
		$ar =  $this->genderArray();
		return isset($ar[$this->calls_me]) ? $ar[$this->calls_me] : '';
	}
	public function updateEmailChecker($attribute, $params)
	{
		if (Yii::app()->user->getId() != '') {
			if (!empty($this->email)) {
				if (Yii::app()->user->getModel()->email == $this->email) {

					$this->addError($attribute, Yii::App()->tags->getTag('change-email-must-be-differ', 'Change email must be differ from registered email'));
				}
			}
		}
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'countries' => array(self::BELONGS_TO, 'Countries', 'country_id'),
			'allUserAds' => array(self::HAS_MANY, 'PlaceAnAd', 'user_id'),
			'allUserOrder' => array(self::HAS_MANY, 'PricePlanOrder', 'customer_id'),
			'state' => array(self::BELONGS_TO, 'States', 'state_id'),
			'states' => array(self::BELONGS_TO, 'States', 'state_id'),
			'adsCount' => array(self::STAT, 'PlaceAnAd', 'user_id', 'condition' => "t.isTrash='0'"),
			'searchCount' => array(self::STAT, 'Searchlist', 'user_id'),
			'watchCount' => array(self::STAT, 'Watchlist', 'user_id'),
			'moreCountry' => array(self::HAS_MANY, 'ListingUserMoreCountry', 'user_id'),
			'moreState' => array(self::HAS_MANY, 'ListingUserMoreState', 'user_id'),
			'moreCities' => array(self::HAS_MANY, 'ListingUserMoreCity', 'user_id'),
			'moreLanguages' => array(self::HAS_MANY, 'UserLanguages', 'user_id'),
			'moreServices' => array(self::HAS_MANY, 'UserMainCategories', 'user_id'),
			'des' => array(self::BELONGS_TO, 'AgentRole', 'designation_id'),

		);
	}

	public function validateSlug($attribute, $params)
	{

		if (!empty($this->$attribute) and !preg_match('/^[A-Za-z0-9._-]+$/', $this->$attribute)) {
			$this->addError($attribute, 'Only english letters numbers and \'_-\' allowed');
		}
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributePlaceholders()
	{
		$tags =  Yii::app()->tags;
		return array(
			'email' => $tags->getTag('username-provider', 'username@provider.com')
		);
	}
	public function attributeLabels()
	{
		$tags =  Yii::app()->tags;
		return array(
			'mul_city_id' => 'Service Areas',
			'arra_date' => 'Expiry Date (ARRA)',
			'arra_number' => 'ARRA Number',
			'otp' => 'One-Time Password (OTP)',
			'arra_doc' => 'ARRA Document Upload',
			'licence_no_expiry' => 'Date Issued (ARRA)',
			'upload_arra_id' => 'ARRA Document Upload',
			'trade_license_number' => 'Trade License Number',
			'trade_license_expiry' => 'Date Issued (Trade License)',
			 
			'eid_number' => 'VAT Number',
			'upload_eid' => 'VAT Registration Certificate Upload',
			'eid_expiry_date' => 'EID Expiry Date',
			'user_id' => 'User',
			'professional_type' => $tags->getTag('professional_type', 'Professional Type'),
			's_code' => $tags->getTag('short-name', 'Short Name'),
			'first_name' => $tags->getTag('first-name', 'First Name'),
			'user_status' => $tags->getTag('user_status', 'User Status'),
			'options' => $tags->getTag('options', 'Options'),
			'last_name' => $tags->getTag('last-name', 'Last Name'),
			'email' => $tags->getTag('email', 'Email'),
			'address' => $tags->getTag('address', 'Enter Agency Address'),
			'whatsapp' => $tags->getTag('whatsapp', 'WhatsApp Number'),
			'city' => $tags->getTag('city', 'City'),
			'state' => 'State',
			'country_id' => $this->NationalityLabel,
			'zip' => $tags->getTag('zip', 'Zip Code'),
			'phone' => $tags->getTag('mobile', 'Mobile'),
			'fax' => $tags->getTag('fax', 'Fax'),
			// 'email' => 'Email',
			'password' => $tags->getTag('password', 'Password'),
			'isTrash' => 'Is Trash',
			'twiter' => $tags->getTag('twitter', 'Twitter/TikTok/YouTube'),
			'google' => $tags->getTag('instagram', 'Instagram'),
			'con_password' => $tags->getTag('confim_password', 'Confirm Password'),
			'send_me' => 'Send me newsletters with Secret Deals',
			'login_email' => 'Email',
			'login_password' => 'Password',
			'image' => $tags->getTag('profile-photo', 'Profile Photo'),
			'state_id' => $tags->getTag('region', 'Region'),
			'mul_country_id' => $tags->getTag('choose-service-areas', 'Choose Service Areas'),
			'licence_no' => $this->liceneceNumberLabel,
			'broker_no' => $tags->getTag('broker_no', 'ALD/ARRA Broker No.'),
			'mul_state_id' => $tags->getTag('select-cities-keep', 'Select Cities [Keep Blank for all cities]'),
			'description' => $this->DescriptionLabel,

			'country_slug' => 'Country',
			'rera' => $tags->getTag('rera_or_govt_registration_numb', 'RERA or Govt Registration Number'),
			'a_r_n' => $tags->getTag('agency_registered_name', 'Agency Registered Name'),
			'r_a' =>  $tags->getTag('registration_authority', 'Registration Authority'),
			'r_n' => $tags->getTag('registration_number', 'Registration Number'),
			'o_r_a' => $tags->getTag('other_registration_authority', 'Other Registration Authority'),
			'o_r_n' => $tags->getTag('other_registration_number', 'Other Registration Number'),
			'a_description' => $tags->getTag('agency_description', 'Agency Description'),
			'company_logo' => $tags->getTag('company_logo', 'Upload Agency Logo'),
			'slug' => $tags->getTag('unique-id', 'Unique ID'),
			'old_password' => $tags->getTag('current-password', 'Current Password'),
			'user_name' => 'Username',
			'user_type' => $tags->getTag('register-me', 'Registered as'),
			'languages_known' => $tags->getTag('languages-known', 'Languages'),
			'services_offering' => $tags->getTag('specialties', 'Specialization'),
			'company_name' => $tags->getTag('company-name', 'Company/Trading Name'),
			'website' => $tags->getTag('website', 'Company Website (Optional)'),
			'dob' => $this->DobLabel,
			'state_id' => $this->RegionLabelLabel,
			'calls_me' => $tags->getTag('calls_me', 'Gender'),
			'xml_image' => $this->getIDcopyLabel(),
			'designation_id' => 'Designation',
			'mobile' => $tags->getTag('landline', 'Phone Number'),
			'passport' => $tags->getTag('passport', 'Passport'),
			'visa' => $tags->getTag('work_permit_/_visa', 'Work Permit / Visa'),
			'signature' => $tags->getTag('digital_signature', 'Digital Signature'),
			'date_added' => $tags->getTag('created_on', 'Created On'),
			'facebook' => $tags->getTag('facebook', 'Facebook'),
		);
	}
	public function authorities()
	{
		return array(
			'1' => 'ADM (Abu Dhabi)',
			'2' => 'ARRA (Ajman)',
			'3' => 'DED (Dubai)',
			'4' => 'DRER (Sharjah)',
			'5' => 'Other',
		);
	}
	public function getIDcopyLabel()
	{
		switch ($this->user_type) {
			case 'A':
				return   'ALD id copy upload';
				break;
			default:
				return   $this->mTag()->getTag('company_trade_license', 'Trade License Document Upload');
				break;
		}
	}
	public function getRegionLabelLabel()
	{
		switch ($this->user_type) {
			case 'D':
				return 'Head Office';
				break;
			case 'K':
				return 'Head Office';
				break;
			default:
				return 'Region';
				break;
		}
	}
	public function getMainTitle()
	{
		switch ($this->user_type) {
			case 'D':
				$r = !empty($this->company_name) ? $this->company_name :  $this->fullName;
				return $r;
				break;
			case 'K':
				$r = !empty($this->company_name) ? $this->company_name :  $this->fullName;
				return $r;
				break;
			default:
				return $this->fullName;
				break;
		}
	}
	public function getfullName2(){
		$cls = ($this->isTrash=='1')  ? 'text-decoration-line-through' :'';
		return '<span class="' . $cls .'">'.$this->fullName.'</span>';
	}
	public function getDobLabel()
	{
		switch ($this->user_type) {
			case 'D':
				return 'Established On';
				break;
			case 'K':
				return 'Established On';
				break;
			default:
				return 'D.O.B';
				break;
		}
	}
	public function getLiceneceNumberLabel()
	{
		switch ($this->user_type) {
			case 'A':
				return  $this->mTag()->getTag('ald/arra_permit_no.', 'ARRA Number');
				break;
			default:
			//'ALD/ARRA Permit No.'
				return  $this->mTag()->getTag('ald/arra_permit_no.', 'ARRA Number');
				return 'Regisratoin No / Liecnse No';
				break;
		}
	}

	public function getNationalityLabel()
	{
		switch ($this->user_type) {

			default:
				return $this->mTag()->getTag('country', 'Country');
				break;
		}
	}
	public function getDescriptionLabel()
	{
		switch ($this->user_type) {
			case 'D':
				return  $this->mTag()->getTag('about_us', 'About Us');
				break;
			case 'K':
				return  $this->mTag()->getTag('about_us', 'Write about your agency here');
				break;
			case 'L':
				return  $this->mTag()->getTag('about_you', 'About You');
				break;
			default:
				return  $this->mTag()->getTag('about_yourself', 'About Yourself');
				break;
		}
	}
	public function getFieldsValidate()
	{
		switch ($this->user_type) {
			case 'A':
				//'xml_image'
				return array();
				return  array('calls_me', 'licence_no', 'broker_no', 'company_name', 'dob', 'country_id', 'phone', 'address', 'website', 'designation_id', 'image', 'xml_image', 'languages_known', 'services_offering');
				break;
			case 'D':
				//'xml_image'
				return array();
				return  array('licence_no', 'company_name', 'state_id', 'website', 'phone', 'address', 'dob', 'languages_known', 'xml_image', 'company_logo');
				break;
			case 'K':
				//'xml_image'
				return array();
				return array('licence_no', 'company_name', 'state_id', 'website', 'phone', 'address', 'languages_known', 'services_offering', 'xml_image', 'company_logo');
				break;
			case 'L':
				return array('calls_me', 'licence_no', 'dob', 'country_id', 'phone', 'address', 'website');
				break;
			case 'U':
				return array();
				break;
			default:
				return array();
				break;
		}
	}
	public function getFieldsHide()
	{
		switch ($this->user_type) {
			case 'A':
				return  array('state_id');
				break;
			case 'D':
				return  array('designation_id');
				break;
			case 'K':
				return array('designation_id');
				break;
			case 'L':
				return array('state_id', 'languages_known', 'services_offering', 'company_name', 'broker_no', 'designation_id');
				break;
			case 'U':
				return array('calls_me', 'dob', 'country_id', 'xml_image', 'company_logo');
				break;
			default:
				return array();
				break;
		}
	}
	public $services_offering;
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
	public function getFullName()
	{
		if (empty($this->first_name)) {
			return 'USER ' . $this->user_id;
		}
		return $this->first_name . ' ' . $this->last_name;
	}
	const BULK_ACTION_DELETE = 'delete';
	const BULK_ACTION_RESTORE = 'restore';
	public function getBulkActionsList()
	{
		return
			array(
				self::BULK_ACTION_RESTORE         => Yii::t('app', 'Restore'),
				self::BULK_ACTION_DELETE          => Yii::t('app', 'Delete'),
			);
	}
	public function search($return = false)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->condition = '1';
		$criteria->select = 't.*,cn.country_name as country_name,(case when ppuser.user_id is not null then ppuser.company_name else t.company_name END) as company_name ';
		$criteria->compare('t.user_id', $this->user_id);
		
		$criteria->compare('t.first_name', $this->first_name, true);
		$criteria->compare('t.last_name', $this->last_name, true);
		$criteria->compare('t.address', $this->address, true);
		$criteria->compare('t.city', $this->city, true);
		$criteria->compare('t.state', $this->state, true);
		$criteria->compare('t.calls_me', $this->calls_me);
		$criteria->compare('t.country_id', $this->country_id);
		$criteria->compare('t.state_id', $this->state_id);
		$criteria->compare('t.licence_no', $this->licence_no, true);
		$criteria->compare('t.broker_no', $this->broker_no, true);
		$criteria->compare('t.documents_submitted', $this->documents_submitted, true);
		if (!empty($this->company_name)) {
			$criteria->condition .= ' and (case when ppuser.user_id is not null then ppuser.company_name else t.company_name END) like :company_name';
			$criteria->params[':company_name'] = '%' . $this->company_name . '%';
		}
		if (!empty($this->ref_id)) {
			$criteria->condition .= ' and  (t.user_id  like :ref_id or t.slug like :ref_id ) ';
			$criteria->params[':ref_id'] = '%' . $this->ref_id . '%';
		}
		$criteria->compare('t.zip', $this->zip, true);
		$criteria->compare('t.phone', $this->phone, true);
		$criteria->compare('t.fax', $this->fax, true);
		$criteria->compare('t.email', $this->email, true);
		$criteria->compare('t.parent_user', $this->parent_user);

		if (!empty($this->f_type)) {
			switch ($this->f_type) {
				case 'U':
					$criteria->condition .= ' and t.parent_user is not null  ';
					break;
				case 'C':
					$criteria->condition .= ' and t.parent_user is null and t.user_type != "U" ';
					break;
				case 'KM':
					$criteria->condition .= ' and t.parent_user is null and t.user_type in ("D","K") ';
					break;
				case 'V':
					$criteria->condition .= ' and t.user_type = "U" ';
					break;
			}
		}
		$criteria->compare('password', $this->password, true);
		if (Yii::app()->isAppName('frontend')) {
			$criteria->compare('t.isTrash', '0');
		} else {
			if (isset($_GET['search_email']) and !empty($_GET['search_email'])) { 
				$criteria->compare('t.email', $_GET['search_email'], true);
			}else{
				$criteria->compare('t.isTrash', $this->isTrash);
			}
		}
		if (!empty($this->status) and $this->status == 'N') {
			$criteria->compare('t.filled_info', '0');
		} else if (!empty($this->status) and $this->status == 'W') {
			//$criteria->compare('filled_info','1');
			$criteria->compare('t.status', $this->status);
		} else {
			$criteria->compare('t.status', $this->status, true);
		}
		$criteria->compare('t.user_type', $this->user_type);
		if (!empty($this->user_name)) {
			$criteria->compare(new CDbExpression('CONCAT(t.first_name, " ", t.last_name)'), $this->user_name, true);
		}
		$criteria->join = ' LEFT  JOIN {{countries}} cn on  cn.country_id = t.country_id ';
		$criteria->join .= ' LEFT JOIN {{listing_users}} ppuser on ppuser.user_id = t.parent_user  ';
		$criteria->order = 't.user_id desc';
	
		if (!empty($return)) {
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
	public function generatePIN($digits = 6)
	{
		$i = 0; //counter
		$pin = ""; //our default pin is blank.
		while ($i < $digits) {
			//generate a random number between 0 and 9.
			$pin .= mt_rand(0, 9);
			$i++;
		}
		return $pin;
	}
	protected function beforeSave()
	{
		if (!parent::beforeSave()) {
			return false;
		}
		if ($this->isNewRecord and empty($this->verification_code)) {
			$this->verification_code =  $this->generatePIN(6);
		}
		if(isset($_POST['social_urls'])){
			$this->social_urls	= json_encode($_POST['social_urls']);
			 
		}
		if ($this->isNewRecord and Yii::app()->isAppName('frontend') and empty($this->parent_user)) {
			/*company - developer */
			if (in_array($this->user_type, array('K', 'D', 'A'))) {
				$this->status = 'W';
			} else {
				/*individual - agent */
				$this->status = 'A';
			}
		}

		if ($this->scenario == 'updateEmail') {
			$this->email_verified = '0';
		}
		if(!empty($this->licence_no_expiry)){
			$this->licence_no_expiry  = date('Y-m-d',strtotime($this->licence_no_expiry));
		}
		if (!empty($this->eid_expiry_date)) {
			$this->eid_expiry_date  = date('Y-m-d', strtotime($this->eid_expiry_date));
		}
		if (!empty($this->arra_date)) {
			$this->arra_date  = date('Y-m-d', strtotime($this->arra_date));
		}
		if (!empty($this->trade_license_expiry)) {
			$this->trade_license_expiry  = date('Y-m-d', strtotime($this->trade_license_expiry));
		}
		if (!empty($this->con_password)) {

			$this->password = Yii::app()->passwordHasher->hash($this->con_password);
		} else if (in_array($this->scenario, ['userRegistration', 'agencyRegistration'])) {
			$this->password = Yii::app()->passwordHasher->hash($this->password);
		}
		return true;
	}
	protected $_initStatus;
	public function addFreePackage(){
		 
		$user = ListingUsers::model()->findByPk($this->user_id);
		
		if(!empty($user) and $user->user_type=='K'){
			if(defined('THEME7')){
				$feature_id = '40'; 
				$criteria = new CDbCriteria;
				$criteria->condition  = '1'; 
				$criteria->join = ' inner join {{package_new}} pack on pack.package_id = t.package_id and pack.f_type="V" ';
				$criteria->condition .= ' and t.user_id = :me and t.category_id = "1" ';
				$criteria->params[':me']	=	 (int) $this->user_id;
				$order_placed = UserPackages::model()->find($criteria);
				 
			}else{
				$order_placed = UserPackages::model()->findByAttributes(['user_id'=>$this->user_id, 'category_id' => '1']);
				$feature_id = '10'; 
			}
		    
			if(empty($order_placed)){
				
				$order = new PricePlanOrder();
				$order->customer_id = $this->user_id;
				$order->p_category  = 1;
				$order->feature_id  = $feature_id;
				$order->payment_type  = 'b';
				$order->subtotal  = '0';
				$order->total  = 0;
				$order->free_package  = '1';
				$order->status  =  'complete';
				$order->save();
			}
		} 
	}
	public function afterSave()
	{
		if($this->isNewRecord){
			$insertLog = new CustomerActionLog();
			$insertLog->customer_id = $this->user_id;
			$insertLog->category =  'customer.new.registered';
			$insertLog->reference_id = $this->user_type;
			$insertLog->save();
		}
		if (in_array($this->_initStatus, array('W','R','I')) && $this->status == 'A') {
			 
			$this->sentapproved();
			
		}
		parent::afterSave();
		if (defined('PROFILE_UPDATE')) {
			UserLanguages::model()->deleteAllByAttributes(array('user_id' => $this->primaryKey));
			$cn_model = new UserLanguages();
			if (!empty($this->languages_known)) {
				foreach ($this->languages_known as $language_id) {
					$cn_model_new = clone $cn_model;
					$cn_model_new->user_id = $this->primaryKey;
					$cn_model_new->language_id = $language_id;
					$cn_model_new->save();
				}
			}
		}
		if (in_array($this->scenario, array('pending-documents','agencyRegistration','agent_insert', 'developer_insert', 'developer_update', 'agent_update', 'developer_update1', 'agent_update1', 'profile-update', 'new_update', 'company-settings', 'new_update2', 'latest_signup'))) {
			if (!$this->isNewRecord) {
				ListingUserMoreCountry::model()->deleteAllByAttributes(array('user_id' => $this->primaryKey));
				ListingUserMoreState::model()->deleteAllByAttributes(array('user_id' => $this->primaryKey));
				ListingUserMoreCity::model()->deleteAllByAttributes(array('user_id' => $this->primaryKey));
				UserLanguages::model()->deleteAllByAttributes(array('user_id' => $this->primaryKey));
				UserMainCategories::model()->deleteAllByAttributes(array('user_id' => $this->primaryKey));
			}
			$cn_model = new ListingUserMoreCountry();
			if (!empty($this->mul_country_id)) {
				foreach ($this->mul_country_id as $couuntry) {
					$cn_model_new = clone $cn_model;
					$cn_model_new->user_id = $this->primaryKey;
					$cn_model_new->country_id = $couuntry;
					$cn_model_new->save();
				}
			}

			$cn_model = new ListingUserMoreState();
			if (!empty($this->mul_state_id)) {
				foreach ($this->mul_state_id as $city) {
					$cn_model_new = clone $cn_model;
					$cn_model_new->user_id = $this->primaryKey;
					$cn_model_new->state_id = $city;
					$cn_model_new->save();
				}
			}
			$cn_model = new ListingUserMoreCity();
			if (!empty($this->mul_city_id)) {
				foreach ($this->mul_city_id as $city) {
					$cn_model_new = clone $cn_model;
					$cn_model_new->user_id = $this->primaryKey;
					$cn_model_new->city_id = $city;
					$cn_model_new->save();
				}
			}
			$cn_model = new UserLanguages();
			if (!empty($this->languages_known)) {
				foreach ($this->languages_known as $language_id) {
					$cn_model_new = clone $cn_model;
					$cn_model_new->user_id = $this->primaryKey;
					$cn_model_new->language_id = $language_id;
					$cn_model_new->save();
				}
			}
			$cn_model2 = new UserMainCategories();
			if (!empty($this->services_offering)) {
				foreach ($this->services_offering as $category_id) {
					$cn_model_new = clone $cn_model2;
					$cn_model_new->user_id = $this->primaryKey;
					$cn_model_new->category_id = $category_id;
					$cn_model_new->save();
				}
			}
		}
		if ($this->isNewRecord and  Yii::app()->apps->isAppName('frontend')) {

			if ($this->email_verified != '1' and $this->scenario != 'new_update2') {
				$this->sendVerificationEmail();
			} else {

				if ($this->scenario != 'new_update2') {
					$this->WelcomeEmail;
					$mg =  Yii::app()->options->get('system.messages._message_after_register_if_confirmed', 'Successfully registered your account.');
					$txt = Yii::t('app', Yii::app()->tags->getTag('_message_after_register_if_confirmed', $mg));
					Yii::app()->notify->addSuccess($txt);
				}
			}
		}
		if ($this->isNewRecord and $this->filled_info != '1'    and  Yii::app()->apps->isAppName('backend')) {
			ListingUsers::model()->updateByPk($this->user_id, array('filled_info' => '1'));
		}
		if ($this->filled_info != '1' and in_array($this->scenario, array('developer_update1', 'agent_update1')) and  Yii::app()->apps->isAppName('frontend')) {
			ListingUsers::model()->updateByPk($this->user_id, array('filled_info' => '1'));
		}
		if ($this->scenario == 'updateEmail') {


			$this->sendVerificationEmail();
		}
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BookingUsers the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
	public function listData()
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "t.status='A'";
		//  $criteria->select= "t.*,concat(t.first_name,t.last_name) as name";
		return $this->findAll($criteria);
	}
	public function getStatusWithStats($sta = null)
	{
		$ar = $this->activeArray();
		return (isset($ar[$sta])) ? $ar[$sta] : "Inactive";
	}
	public function getStatusTitle()
	{
		$ar = $this->activeArray();
		return (isset($ar[$this->status])) ? $ar[$this->status] : "Unknown";
	}
	public function activeArray()
	{
		$arr = $this->getStatusArray();
		return $arr;
	}
	public function dayOfMonth()
	{
		for ($i = 1; $i <= 31; $i++) {
			$day[$i] = str_pad($i, 2, 0, STR_PAD_LEFT);
		}
		return $day;
	}
	public function findByEmail($email)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "t.email=:email";
		$criteria->params[':email'] = $email;
		return $this->find($criteria);
	}
	function is_image($path)
	{
		@$a = getimagesize($path);
		$image_type = $a[2];

		if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
			return true;
		}
		return false;
	}
	public function featuredArray()
	{
		return array('N' => 'No', 'Y' => 'Yes');
	}

	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			array(
				'SlugBehavior' => array(
					'class' => 'common.models.SlugBehavior.SlugBehavior2',
					'slug_col' => 'slug',
					'title_col' =>  'slug_name',
					'overwrite' => true
				)
			)
		);
	}
	public function getUserall_languages()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.language_id,lan.name as language_name';
		$criteria->condition = ' t.user_id = :user_id   ';
		$criteria->join = ' INNER JOIN {{language}} lan on lan.language_id = t.language_id ';
		$criteria->params[':user_id'] = $this->user_id;
		$criteria->order = ' name asc';

		$langaugae = OptionCommon::getLanguage();

		if (!empty($langaugae) and  $langaugae != 'en') {
			$criteria->params[':lan'] = $langaugae;
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.language_id = t.language_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' , CASE WHEN tdata.message IS NOT NULL THEN tdata.message ELSE lan.name END as  language_name  ';
		}
		$languages = UserLanguages::model()->findAll($criteria);
		$html = '';
		if ($languages) {
			foreach ($languages as $k => $v) {
				$html .= $v->language_name . ',';
			}
		}
		if ($html != '') {
			return rtrim($html, ',');
		}
	}
	public function getServicesOfferings()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.category_id,cat.category_name as category_name';
		$criteria->condition = ' t.user_id = :user_id   ';
		$criteria->join = ' INNER JOIN {{category}} cat on cat.category_id = t.category_id ';
		$criteria->params[':user_id'] = $this->user_id;
		$criteria->order = ' category_name asc';
		$langaugae = OptionCommon::getLanguage();

		if (!empty($langaugae) and  $langaugae != 'en') {
			$criteria->params[':lan'] = $langaugae;
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.category_id = t.category_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' , CASE WHEN tdata.message IS NOT NULL THEN tdata.message ELSE cat.category_name END as  category_name  ';
		}
		$languages = UserMainCategories::model()->findAll($criteria);
		$html = '';
		if ($languages) {
			foreach ($languages as $k => $v) {
				$html .= $v->category_name . ',';
			}
		}
		if ($html != '') {
			return rtrim($html, ',');
		}
	}
	public $commercial;
	public function findAgents($formData = array(), $count_future = false, $return = false)
	{

		$criteria = new CDbCriteria;
		$criteria->select = 't.*
			,(select COALESCE(count(ad.id),0) from {{place_an_ad}} ad LEFT JOIN {{listing_users}} adsr ON (adsr.user_id = ad.user_id and  adsr.status="A" and  adsr.isTrash="0")   where (t.user_id = ad.user_id or t.user_id = adsr.parent_user)  and ad.status="A" and ad.isTrash="0" and ad.section_id=' . PlaceAnAd::SALE_ID . ' ) as sale_total
		,(select COALESCE(count(ad.id),0) from {{place_an_ad}} ad LEFT JOIN {{listing_users}} adsr ON (adsr.user_id = ad.user_id and  adsr.status="A" and  adsr.isTrash="0")   where  (t.user_id = ad.user_id or t.user_id = adsr.parent_user)   and ad.status="A" and ad.isTrash="0" and ad.section_id=' . PlaceAnAd::RENT_ID . ' ) as rent_total
		,(select COALESCE(count(ad.id),0) from {{place_an_ad}} ad LEFT JOIN {{listing_users}} adsr ON (adsr.user_id = ad.user_id and  adsr.status="A" and  adsr.isTrash="0")   where  (t.user_id = ad.user_id or t.user_id = adsr.parent_user)   and ad.status="A" and ad.isTrash="0" and ad.listing_type=120 ) as commercial
		';

		$criteria->compare('t.isTrash', '0');
		$criteria->compare('t.status', 'A');
		//$criteria->compare('t.user_type',$user_type);
		$criteria->distinct =  't.id';
		$langaugae = OptionCommon::getLanguage();
		$criteria->join  .= ' left join {{countries}} cn ON cn.country_id = t.country_id ';
		if (!empty($langaugae) and  $langaugae != 'en') {
			$criteria->params[':lan'] = $langaugae;
			$criteria->join  .= ' left join `mw_translate_relation` `translationRelation` on translationRelation.country_id = t.country_id   LEFT  JOIN mw_translation_data tdata ON (`translationRelation`.translate_id=tdata.translation_id and tdata.lang=:lan) ';
			$criteria->select .= ' ,tdata.message as  country_name  ';
		} else {
			$criteria->select .= ',cn.country_name';
		}
		$criteria->select .=  ',musr.company_name as parent_company,musr.company_logo as p_company_logo ';
		$criteria->join  .=   ' INNER JOIN {{listing_users}} usr on usr.user_id = t.user_id ';
		$criteria->join  .= ' left join {{listing_users}} musr ON musr.user_id = t.parent_user ';
		if (isset($formData['agent_language']) and !empty($formData['agent_language'])) {
			$criteria->join  .=   ' INNER JOIN {{user_languages}} lans on lans.user_id = t.user_id and lans.language_id=:lan_id ';
			$criteria->params[':lan_id'] = $formData['agent_language'];
		}
		if (isset($formData['language']) and !empty($formData['language'])) {
			$criteria->join  .=   ' INNER JOIN {{user_languages}} lans on lans.user_id = t.user_id and lans.language_id=:lan_id ';
			$criteria->params[':lan_id'] = $formData['language'];
		}

		if (isset($formData['ut']) and !empty($formData['ut'])) {
			if ($formData['ut'] == 'C') {
				$criteria->condition .= ' and t.user_type in ("D","K") ';
			} else {
				$criteria->condition .= ' and t.user_type =:ut and t.parent_user != "" ';
				$criteria->params[':ut'] = $formData['ut'];
			}
		}
		if (isset($formData['f']) and !empty($formData['f'])) {
			$criteria->condition .= ' and t.featured =:f ';
			$criteria->params[':f'] = "Y";
		}
		if (isset($formData['keyword']) and !empty($formData['keyword'])) {
			$criteria->condition  .=   ' and ( CONCAT(t.first_name, " ", t.last_name) like :keyword or t.company_name like :keyword  or t.description like :keyword  or t.address like :keyword )   ';
			$criteria->params[':keyword'] = '%' . $formData['keyword'] . '%';
		}
		if (isset($formData['property']) and !empty($formData['property'])) {
			$criteria->join  .= ' inner join {{place_an_ad}} ad ON ad.user_id = t.user_id and ad.isTrash="0" and ad.status="A"  and ( ad.ad_title  like :keyword or ad.ad_description like :keyword ) ';
			$criteria->params[':keyword'] = '%' . $formData['property'] . '%';
			$criteria->group =  'ad.user_id';
		}
		if (isset($formData['type_of'])  and is_array($formData['type_of'])) {

			$arm =  	array_filter($formData['type_of']);
			if (!empty($arm)) {
				if (sizeOf($formData['type_of']) == '1') {
					$criteria->condition .= ' and t.country_id =:type_of ';
					$criteria->params[':type_of'] = @$formData['type_of'][0];
				} else {
					$criteria->addInCondition('t.country_id', $formData['type_of']);
				}
			}
		}
		if (isset($formData['agent_search']) and !empty($formData['agent_search'])) {
			$criteria->condition  .=   ' and CONCAT(t.first_name, " ", t.last_name) like :keyword ';
			$criteria->params[':keyword'] = '%' . $formData['agent_search'] . '%';
		}
		if (isset($formData['ms']) and !empty($formData['ms'])) {
			$criteria->join  .=   ' INNER JOIN {{user_main_categories}} ums on ums.user_id = t.user_id and ums.category_id=:ums ';
			$criteria->params[':ums'] = $formData['ms'];
		}
		if (isset($formData['p_type']) and !empty($formData['p_type'])) {
			$criteria->join  .=   ' INNER JOIN {{user_main_categories}} ums on ums.user_id = t.user_id and ums.category_id=:ums ';
			$criteria->params[':ums'] = $formData['p_type'];
		}

		if (isset($formData['city']) and !empty($formData['city'])) {

			$cityModel = City::model()->findByAttributes(array('slug' => $formData['city']));
			if ($cityModel) {
				$criteria->join  .=   ' INNER JOIN {{place_an_ad}} ad on ad.user_id = t.user_id   ';
				$criteria->condition  .=  ' and  (ad.city=:city or ad.city_2=:city or ad.city_2=:city) ';
				$criteria->params[':city'] = $cityModel->primaryKey;
				$criteria->distinct = 't.user_id';
			}
		}

		if (isset($formData['cname']) and !empty($formData['cname'])) {
			$criteria->condition .= '  and t.company_name    like :comp    ';
			$criteria->params[':comp'] = '%' . $formData['cname'] . '%';
		}


		$country_joined = false;
		if (isset($formData['country_id']) and !empty($formData['country_id'])) {
			$country_joined = true;
			$criteria->join  .=   ' INNER  JOIN {{listing_user_more_country}} usr_service on usr_service.user_id = t.user_id and usr_service.country_id = :county_of_service ';
			$criteria->params[':county_of_service'] = $formData['country_id'];
		}
		if (isset($formData['agent_regi']) and !empty($formData['agent_regi'])) {

			$criteria->join  .=   ' LEFT  JOIN {{listing_user_more_state}} simple_checkeck_usr_service_state on simple_checkeck_usr_service_state.user_id = t.user_id';
			$criteria->join  .=   ' LEFT  JOIN {{listing_user_more_state}} usr_service_state on usr_service_state.user_id = t.user_id  and usr_service_state.state_id = :state_of_service ';
			$criteria->condition  .= ' and case when simple_checkeck_usr_service_state.user_id is   null then 1  when usr_service_state.user_id is not null then 1 else 0 end  ';
			$criteria->params[':state_of_service'] = $formData['agent_regi'];
		}
		$criteria->condition  .= ' and t.user_type != "U" ';
		$order  = 't.featured="Y" desc,-t.priority desc , t.first_name asc  ';
		$criteria->order  =   $order;
		if ($return) {
			return $criteria;
		}
		$criteria->limit  = Yii::app()->request->getQuery('limit', '10');
		$criteria->offset = Yii::app()->request->getQuery('offset', '0');



		if (!empty($count_future)) {
			$Result = self::model()->findAll($criteria);
			$criteria->offset = $criteria->limit + $criteria->offset;
			$criteria->select = 't.user_id';
			$criteria->limit = '1';
			$future_count = self::model()->find($criteria);
			return array('result' => $Result, 'future_count' => $future_count);
		} else {
			return  self::model()->findAll($criteria);
		}
	}
	public function getDetailUrl()
	{
		return Yii::app()->createUrl('detail/index', array('slug' => $this->ad_slug));
	}

	public function getIsFeatured()
	{
		if ($this->featured == 'Y') {
			return '<i class="glyphicon glyphicon-star" title="Featured"></i>';
		}
	}
	public function getAgentDetailUrl()
	{
		if (in_array($this->user_type, array('D', 'K'))) {
			return Yii::app()->createUrl('user_listing/detail', array('slug' => $this->slug, 'user_type' => 'real-estate-agencies'));
		}
		return Yii::app()->createUrl('user_listing/detail', array('slug' => $this->slug));
	}
	public function getAgentDetailUrl2()
	{
		if (in_array($this->user_type, array('D', 'K'))) {
			return Yii::app()->createAbsoluteUrl('user_listing/detail', array('slug' => $this->slug, 'user_type' => 'real-estate-agencies'));
		}
		return Yii::app()->createAbsoluteUrl('user_listing/detail', array('slug' => $this->slug));
	}
	public function getDeveloperDetailUrl()
	{
		return Yii::app()->createUrl('user_listing_developers/detail', array('slug' => $this->slug, 'user_type' => $this->user_type));
	}
	public function getDeveloperDetailUrl2()
	{
		return Yii::app()->createAbsoluteUrl('user_listing_developers/detail', array('slug' => $this->slug, 'user_type' => $this->user_type));
	}
	public $rent_total;
	public $sale_total;
	public $country_name;
	public function getWelcomeEmail()
	{
		$tags = Yii::app()->tags;
		$options     =   Yii::app()->options;
		$emailTemplate =  CustomerEmailTemplate::model()->getTemplateByUid("ay144yev4v1e4");
		if ($emailTemplate) {
			$subject =  $emailTemplate->subject;
			$emailTemplate = $emailTemplate->content;
		} else {
			return true;
		}

		$status = 'S';
		$emailTemplate = str_replace('[USER_NAME]', $this->fullName, $emailTemplate);
		$emailTemplate_common = $tags->getTag('common');
		if (empty($emailTemplate_common)) {
			$emailTemplate_common = $options->get('system.email_templates.common');
		}
		$emailTemplate = str_replace('[CONTENT]', $emailTemplate, $emailTemplate_common);
		$adminEmail = new Email();
		$adminEmail->subject = $subject;
		$adminEmail->message = $emailTemplate;
		$receipeints = serialize(array($this->email));
		$adminEmail->status = $status;
		$adminEmail->receipeints = $receipeints;
		$adminEmail->sent_on =   1;
		$adminEmail->type =   'S';
		$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
		$adminEmail->save(false);
		$adminEmail->send;
		return true;
	}
	public function sendVerificationEmail(){
		
		$emailTemplate =  CustomerEmailTemplate::model()->getTemplateByUid('oe7953dzvs665');
		$tags = Yii::app()->tags;
		$common_name =  $tags->getTag('site_name');
		$options     =   Yii::app()->options;
		$support_phone  =  $options->get('system.common.support_phone');
		$support_email  =  $options->get('system.common.support_email');
		$notify     = Yii::app()->notify;
		if (empty($emailTemplate)) {
			return true;
		} else {
			$subject = 	str_replace(['[CODE]', '[DATE]'],[$this->verification_code,date('F j, Y')],$emailTemplate->subject);
			$emailTemplate = $emailTemplate->content;
			$emailTemplate = str_replace('[CODE]', $this->verification_code, $emailTemplate); 
			$emailTemplate = str_replace('[User Name]', $this->fullName, $emailTemplate); 
			//$emailTemplate = str_replace('[CONTENT]', $emailTemplate, $emailTemplate_common);
			$status = 'S';
			$adminEmail = new Email();
			$adminEmail->subject = $subject;
			$adminEmail->message = $emailTemplate;
			$receipeints = serialize(array($this->email));
			$adminEmail->status = $status;
			$adminEmail->receipeints = $receipeints;
			$adminEmail->sent_on =   1;
			$adminEmail->type =   'REGISTER';
			$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
			$adminEmail->save(false);
			$adminEmail->send;
			if (!$this->isNewRecord) {

				//$mg = $options->get('system.messages._message_after_resent', 'Successfully Send Verification Email . Please Verify Your Account');
				//$notify->addSuccess(Yii::t('app',  $tags->getTag('_message_after_resent', $mg)));
			} else {
				//$mg = $options->get('system.messages._meesage_after_register','Successfully Send Verification Email . Please Verify Your Account');
				//$notify->addSuccess(Yii::t('app',  $tags->getTag('_meesage_after_register',$mg)));
			}
		}
		return true;
	}
	public function sendVerificationEmailOLd()
	{
		$emailTemplate =  CustomerEmailTemplate::model()->getTemplateByUid('rh576q825p4c0');
		//$logo =  '<a href="'.Yii::app()->createAbsoluteUrl("site/index").'" alt=""><img src="'.OptionCommon::logoUrl().'" style="width:70px"  alt=""></a> ';
		$tags = Yii::app()->tags;
		$common_name =  $tags->getTag('site_name');
		$options     =   Yii::app()->options;
		$support_phone  =  $options->get('system.common.support_phone');
		$support_email  =  $options->get('system.common.support_email');
		$notify     = Yii::app()->notify;
		if (empty($emailTemplate)) {
			return true;
		} else {
			$subject =  $emailTemplate->subject;
			$emailTemplate = $emailTemplate->content;
			$url = Yii::app()->createAbsoluteUrl('user/emailVerify', array('verify' => $this->verification_code));
			$site_link = CHtml::link(SITE_U, Yii::app()->createAbsoluteUrl('member/dashboard'));
			$emailTemplate = str_replace('[USER_NAME]', $this->fullName, $emailTemplate);
			$emailTemplate = str_replace('[ACCOUNT_LINK]', $site_link, $emailTemplate);
			$emailTemplate = str_replace('[VERIFY_LINK]', $url, $emailTemplate);

			$emailTemplate_common = $tags->getTag('common');
			if (empty($emailTemplate_common)) {
				$emailTemplate_common = $options->get('system.email_templates.common');
			}
			$emailTemplate = str_replace('[CONTENT]', $emailTemplate, $emailTemplate_common);
			$status = 'S';
			$adminEmail = new Email();
			$adminEmail->subject = $subject;
			$adminEmail->message = $emailTemplate;
			$receipeints = serialize(array($this->email));
			$adminEmail->status = $status;
			$adminEmail->receipeints = $receipeints;
			$adminEmail->sent_on =   1;
			$adminEmail->type =   'REGISTER';
			$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
			$adminEmail->save(false);
			$adminEmail->send;
			if (!$this->isNewRecord) {

				$mg = $options->get('system.messages._message_after_resent', 'Successfully Send Verification Email . Please Verify Your Account');
				$notify->addSuccess(Yii::t('app',  $tags->getTag('_message_after_resent', $mg)));
			} else {
				//$mg = $options->get('system.messages._meesage_after_register','Successfully Send Verification Email . Please Verify Your Account');
				//$notify->addSuccess(Yii::t('app',  $tags->getTag('_meesage_after_register',$mg)));
			}
		}
		return true;
	}
	public function resentverification()
	{


		$options = Yii::app()->options;
		$notify = Yii::app()->notify;
		$emailTemplate =  CustomerEmailTemplate::model()->getTemplateByUid('ed733tl6fo892');
		if (empty($emailTemplate)) {
			return false;
		}
		$tags = Yii::app()->tags;
		$common_name =  $tags->getTag('site_name');
		$support_phone  =   Yii::app()->options->get('system.common.support_phone');
		$support_email  =  Yii::app()->options->get('system.common.support_email');
		$subject     = $emailTemplate->subject;
		$emailTemplate = $emailTemplate->content;


		//$emailBody = Yii::app()->controller->renderPartial('root.apps.frontend.views.user._verification_link' , array('model'=>$this), true);
		//$emailTemplate = str_replace('[CONTENT]', $emailBody, $emailTemplate);
		//$logo =  '<a href="'.Yii::app()->createAbsoluteUrl("site/index").'" alt="'.Yii::app()->options->get('system.common.site_name').'"><img src="'.  OptionCommon::logoUrl() .'"   style="width:134px; " ></a> ';
		$login_path = Yii::app()->createAbsoluteUrl('user/signin');
		$account_path = Yii::app()->createAbsoluteUrl('user/my_profile');
		$url = Yii::app()->apps->getbaseUrl('index.php/user/emailVerify/verify/' . $this->verification_code, true);
		$emailTemplate = str_replace('{name}', $this->fullName, $emailTemplate);
		$emailTemplate = str_replace('{phone}', $support_phone, $emailTemplate);
		$emailTemplate = str_replace('{support}', $support_email, $emailTemplate);
		$emailTemplate = str_replace('{login-path}', '<a href="' . $login_path . '" style="color:#1e7ec8;" target="_blank">' . $tags->getTag('sign-in', 'Sign In') . '</a>', $emailTemplate);
		$emailTemplate = str_replace('{my-account}', '<a href="' . $account_path . '" style="color:#1e7ec8;" target="_blank">' . $tags->getTag('my-profile', 'My Profile') . '</a>', $emailTemplate);
		$emailTemplate = Yii::t('trans', $emailTemplate, array('[VERIFY_LINK]' => $url));
		$emailTemplate_common = $tags->getTag('common');
		if (empty($emailTemplate_common)) {
			$emailTemplate_common = $options->get('system.email_templates.common');
		}
		$emailTemplate = str_replace('[CONTENT]', $emailTemplate, $emailTemplate_common);

		$params = array(
			'to'            =>  $this->email,
			'fromName'      =>  $common_name,
			'subject'       =>	$subject,
			'body'          =>   Yii::t('trans', $emailTemplate, OptionCommon::commonReplacers()),
			'mailerPlugins' => array(
				'logger'    => true,
			),

		);
		$status = 'Q';
		$server = DeliveryServer::pickServer();
		if ($server) {
			if (!$server->sendEmail($params)) {
				if (Yii::app()->isAppName('frontend')) {
					$notify->addError(Yii::t('error', 'Temporary error while sending your email, please try again later or contact us!'));
				}
			} else {
				$status = 'S';
				if (Yii::app()->isAppName('frontend')) {
					$notify->addSuccess(Yii::t('success', $tags->getTag('succefully-sent-verification-e', 'Success!!! Verification  Email is Send to your account.Don\'t forget to check your Spam/Junk folder.Please verify your email account. ')));
				}
			}
		}
		$adminEmail = new Email();

		$adminEmail->subject = $subject . ' :' . $this->fullName;
		$adminEmail->message = $emailTemplate;
		$receipeints = serialize(array($this->email));
		$adminEmail->status = $status;
		$adminEmail->receipeints = $receipeints;
		$adminEmail->sent_on =   1;
		$adminEmail->type =   'RESENT';
		$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
		$adminEmail->save(false);

		return true;
	}
	public function getAvatarUrl($width = 50, $height = 50, $forceSize = false)
	{



		$fileName = $this->image;
		if (!empty($fileName)) {
			$filename =  pathinfo($fileName, PATHINFO_FILENAME);;
			$ext	  =  pathinfo($fileName, PATHINFO_EXTENSION);
		}
		//and is_file( Yii::getPathOfAlias('root.uploads.images.'.$filename) . '.'.$ext)
		if (!empty($filename) and !empty($ext)) {

			$image =  Yii::app()->apps->getBaseUrl("uploads/images/" . $fileName);
		} else {
			 
			$image =  $this->getDefaultImg();
		}
		if (empty($image)) {
			return false;
		}
		return ImageHelper::resize($image, $width, $height, $forceSize);
	}
	public function getDefaultImg(){
		$default = 'visitornew.png';
		switch ($this->user_type) {
			case 'K':
				$default = 'agencynew.png';
				break;
			case 'A':
				$default = 'agentnew.png';
				break;
		}
		return Yii::app()->apps->getBaseUrl("assets/img/" . $default);
	}
	public function getAvatarUrlCrop($width = 50, $height = 50, $forceSize = false)
	{



		$fileName = !empty($this->image)? $this->image : $this->company_logo;

		if (!empty($fileName)) {
			$image =  Yii::app()->apps->getBaseUrl("uploads/images/" . $fileName);
		} else {
			$image =  $this->getDefaultImg();
		}
		if (empty($image)) {
			return false;
		}
		return $image;
	}
	public function getTypeTile()
	{
		$ar = $this->getUserType();
		if ($this->user_type == 'C') {
			return 'Companies';
		}
		return isset($ar[$this->user_type]) ? $ar[$this->user_type] : 'Unknown';
	}
	public function getBannertTitle()
	{

		if ($this->user_type == 'C') {
			return Yii::app()->options->get('system.common.company_banner_text', 'Add Banner Title');
		}
		return  Yii::app()->options->get('system.common.agent_banner_text', 'Add Banner Title');;
	}
	public function getBannertFile()
	{

		if ($this->user_type == 'C') {
			return Yii::app()->options->get('system.common.company_banner_file', '');
		}
		return  Yii::app()->options->get('system.common.agent_banner_file', '');;
	}
	public function getCanviewcompany_name()
	{

		if ($this->f_type == 'V') {
			return  false;
		}
		return   true;
	}
	public function getMemebrApproved()
	{
		if (!$this->canUploadProperties) {
			return;
		}
		//if($this->filled_info=='1') {
		return '<span class="label  bg-blue" onclick="previewthis1(this,event)" data-status="' . $this->status . '" href="' . Yii::app()->createUrl('listingusers/view', array('id' => $this->user_id)) . '">' . $this->StatusTitle . '</span>';
		//}
		//else{
		//	return '<span class="label  btn-warning">Not Filled</span>';
		//}
	}
	public function getFillPersonalInformation()
	{
		if ($this->user_type == 'U') {
			return false;
		}
		if ($this->user_type == 'A') {
			return false;
		}
		if (!empty($this->parent_user)) {
			return false;
		}
		return true;
		if (in_array($this->user_type, array('A', 'D'))) {
			return true;
		}
		return false;
	}
	public function getImpersonateLink()
	{
		return ASKAAN_PATH_BASE . "site/impersonate/id/" . $this->user_id;
	}
	public function getImpersonate(){
		return CHtml::link('<span class="glyphicon glyphicon-random"></span>',$this->ImpersonateLink,['target'=>'_blank']); 
	}
	public function getUserAvatarUrl()
	{
		return Yii::app()->apps->getBaseUrl('uploads/images/' . $this->image);
	}
	public function getShortDescription($length = 100)
	{
		return StringHelper::truncateLength($this->description, (int)$length);
	}

	public function getWebsiteTitle()
	{
		return Yii::t('s', $this->website, array('https://' => '', 'http://' => ''));
	}
	public function getCompanyName()
	{
		if (!empty($this->company_name)) {
			$code = ''; //!empty($this->s_code) ? ' ('.$this->s_code.')' : '';
			return $this->company_name . $code;
		} else {
			return   $this->fullName;
		}
	}
	public function getContactEmail()
	{
		if (!empty($this->contact_email)) {
			return $this->contact_email;
		} else {
			return   $this->email;
		}
	}
	public function getContactPerson()
	{
		if (!empty($this->contact_person)) {
			return $this->contact_person;
		} else {
			return $this->first_name . ' ' . $this->last_name;
		}
	}
	public function getAgentAvatarUrl()
	{
		if (!empty($this->image)) {
			return Yii::app()->apps->getBaseUrl('uploads/images/' . $this->image);
		}
	}
	public function getCompanyUrl($width = 50, $height = 50, $forceSize = false)
	{
		$fileName = $this->company_logo;
		if (!empty($fileName)) {
			$filename =  pathinfo($fileName, PATHINFO_FILENAME);;
			$ext	  =  pathinfo($fileName, PATHINFO_EXTENSION);
		}
		//and is_file( Yii::getPathOfAlias('root.uploads.images.'.$filename) . '.'.$ext)
		if (!empty($filename) and !empty($ext)) {

			$image =  Yii::app()->apps->getBaseUrl("uploads/images/" . $fileName);
		} else {
			$image =  $this->getDefaultImg();
		}
		if (empty($image)) {
			return false;
		}
		return ImageHelper::resize($image, $width, $height, $forceSize);
	}
	public function getCompanyimage()
	{
		return Yii::app()->apps->getBaseUrl('uploads/images/' . $this->company_logo);
	}
	public function getAgentALDIDimage()
	{
		return Yii::app()->apps->getBaseUrl('uploads/images/' . $this->xml_image);
	}
	public function propertyUploadUsers()
	{

		static $_permit;
		if ($_permit !== null) {
			return $_permit;
		}

		if (empty($_permit)) {
			$_permit = Yii::app()->options->get('system.common.select_profile_who_can_upload_property', '');
		}
		return $_permit;
	}
	public function  getCanUploadProperties()
	{
		if (in_array($this->user_type, (array)$this->propertyUploadUsers())) {
			return   true;
		} else {
			return   false;
		}
	}
	public function getsmalDale()
	{
		return date('d-M-Y', strtotime($this->date_added));
	}

	public function getSectionViewTitle()
	{
		return  Yii::app()->tags->getTag('all', 'All');
	}
	public $type_of;
	public function getHomeTypeTitle()
	{

		$arm = (array) $this->type_of;
		$arms = array_filter($arm);
		if (empty($arms)) {
			return Yii::app()->tags->getTag('all-category', 'All Nationality');
		} else if (sizeOf($this->type_of) > 1) {
			return 'Nationality (' . sizeOf($this->type_of) . ')';
		} else {
			$cate = Countries::model()->findByPk(@$this->type_of[0]);
			if ($cate) {
				return $cate->country_name;
			}
			return 'Unknown';
		}
	}
	public function getTagList($check = false)
	{
		$html = '';
		if ($this->featured == "Y") {
			$html .=  '<li class="F">' . $this->mTag()->getTag('featured', 'Featured') . '</li>';
		}
		// if($this->verified=="1" and defined('VERIFY')){ $html .=  '<li class="" style="background: transparent;"><span class="verif"><svg viewBox="0 0 20 16" style="color: green;fill: green;width: 30px; height: 30px;margin-top: -8px;" ><use xlink:href="#verfybg"></use></svg></span></li>'; }
		return 	$html;
	}
	public function detailArray()
	{
		return
			array(
				'user_type' => $this->TypeTile,
				'full-name' => $this->fullName,
				'country_id' => $this->country_name,
				'state_id' => !empty($this->state_id) ? $this->states->state_name : '',
				'dob' => date('d-M-y', strtotime($this->dob)),
				'calls_me' => $this->GenderArrayTitle,
				'website' => $this->WebsiteTitle,
				'licence_no' => $this->licence_no,
				'broker_no' => $this->broker_no,
				'company_name' => $this->company_name,

			);
	}
	public function getUserDesignation()
	{
		if (!empty($this->designation_id)) {
			return $this->des->service_name;
		}
	}
	public function getStatge_name()
	{
		if (!empty($this->state_id)) {
			return $this->state->state_name;
		}
	}
	protected function beforeDelete()
	{
	 
		if (!empty($this->allUserOrder)) {

			foreach ($this->allUserOrder as $image) {
				$image->delete();
			}
		}
		if (!empty($this->allUserAds)) {
			
			foreach ($this->allUserAds as $image) {
				$image->delete();
			}
		}
		return true;
	}
	public function getCompanyImage2($width = 50, $height = 50, $forceSize = false)
	{



		$fileName = $this->company_logo;
		if (!empty($fileName)) {
			$filename =  pathinfo($fileName, PATHINFO_FILENAME);;
			$ext	  =  pathinfo($fileName, PATHINFO_EXTENSION);
		}

		if (!empty($filename) and !empty($ext) and is_file(Yii::getPathOfAlias('root.uploads.images.' . $filename) . '.' . $ext)) {

			$image =  Yii::app()->apps->getBaseUrl("uploads/images/" . $fileName);
		} else {
			$image =  $this->getDefaultImg();
		}
		if (empty($image)) {
			return false;
		}
		return ImageHelper::resize($image, $width, $height, $forceSize);
	}

	public function getShortDescription2($length = 130)
	{
		$descriptiomn =  $this->description;
		if (in_array($this->user_type, array('K', 'D'))) {
			$descriptiomn = !empty($this->a_description) ? $this->a_description : $this->description;
		}
		return StringHelper::truncateLength($descriptiomn, (int)$length);
	}
	public function getDescriptionAgency()
	{
		$descriptiomn =  $this->description;
		if (in_array($this->user_type, array('K', 'D'))) {
			$descriptiomn = !empty($this->a_description) ? $this->a_description : $this->description;
		}
		return $descriptiomn;
	}
	public function getListAgentPermission()
	{
		if (in_array($this->user_type, array('K', 'D'))) {
			return true;
		}
		return false;
	}
	public function getAgentsCreated()
	{
		return   ListingUsers::model()->countByAttributes(array('parent_user' => (int) $this->user_id, 'isTrash' => '0'));
	}
	public function getFirstNameN()
	{

		return $this->first_name . ' ' . $this->last_name;
	}
	public function getStatusTitleU()
	{
		$ar = $this->user_status();
		return (isset($ar[$this->user_status])) ? $ar[$this->user_status] : "Unknown";
	}
	public function user_status()
	{
		return array(
			'A' => 'Active',
			'I' => 'Inactive',

		);
	}
	public function max_no_users_count()
	{
		$user_active_package = PackageNew::model()->userActivePackage(1, $this->user_id);
		if(!empty($user_active_package->number_of_agents)){
			return $user_active_package->number_of_agents; 
		}
		return !empty($this->max_no_users) ? $this->max_no_users : '5';
	}
	public function getValidateAgentsCreated()
	{
		if($this->isNewRecord){
		$user = ListingUsers::model()->findByAttributes(array('user_id' => (int) $this->user_id));
		if (empty($user)) {
			throw new CHttpException(404, Yii::t('app', 'No Admin User Found.'));
		}
		$permitted = $user->max_no_users_count();
		if ((int) $this->AgentsCreated >= $permitted) {
			Yii::app()->controller->redirect(Yii::app()->createUrl('member/list_agents', array('message' => 'exceeded')));
			throw new CHttpException('Warning', Yii::t('app', $this->mTag()->getTag('listing_quota_exceeded', 'Listing Quota Exceeded.')));
		}
		}
	}
	public function getAvatarUrlCropNew($width = 50, $height = 50, $forceSize = false)
	{



		$fileName = !empty($this->image) ? $this->image : $this->company_logo;
		if (!empty($fileName)) {
			$filename =  pathinfo($fileName, PATHINFO_FILENAME);;
			$ext	  =  pathinfo($fileName, PATHINFO_EXTENSION);
		}
		/*
			if (!empty($filename) and !empty($ext) and is_file( Yii::getPathOfAlias('root.uploads.resized.'.$filename) . '.'.$ext)) {
			 
				$image =  Yii::app()->apps->getBaseUrl("uploads/resized/".$fileName)  ;
			}
        	else */
		if (!empty($filename) and !empty($ext) and is_file(Yii::getPathOfAlias('root.uploads.images.' . $filename) . '.' . $ext)) {
			$image =  Yii::app()->apps->getBaseUrl("uploads/images/" . $fileName);
		} else {
			$image =  $this->getDefaultImg();
		}
		if (empty($image)) {
			return false;
		}
		return $image;
	}
	public function parentCompany()
	{
		static $_parentCpmpany;
		if ($_parentCpmpany !== null) {
			return $_parentCpmpany;
		}

		$_parentCpmpany = self::model()->findByPk($this->parent_user);

		return $_parentCpmpany;
	}
	public function getPrimeName()
	{
		if (in_array($this->user_type, array('K', 'D'))) {
			if (!empty($this->parent_user)) {
				$parent =  $this->parentCompany();
				if (!empty($parent)) {
					$code = !empty($parent->s_code) ? ' (' . $parent->s_code . ')' : '';
					return $parent->company_name . $code;
				}
			} else {
				$code = !empty($this->s_code) ? ' (' . $this->s_code . ')' : '';
				return $this->company_name . $code;
			}
		}
		return $this->first_name . ' ' . $this->last_name;
	}
	public function getSecondaryName()
	{
		if (in_array($this->user_type, array('K', 'D'))) {
			return $this->first_name . ' ' . $this->last_name;
		} else {
			if (!empty($this->parent_user)) {
				$parent =    $this->parentCompany();
				$code = !empty($parent->s_code) ? ' (' . $parent->s_code . ')' : '';
				if (!empty($parent)) {
					return $parent->company_name . $code;
				}
			} else {
				$code = !empty($this->s_code) ? ' (' . $this->s_code . ')' : '';
				return $this->company_name . $code;
			}
		}
	}
	public function getFilePath($file)
	{
		return Yii::app()->apps->getBaseUrl('uploads/images/' . $file);
	}
	public function getTypeTileNew()
	{

		$type =  $this->TypeTile . '<br />';
		$link = '';
		$total_agencies  = $this->TotalAgents;
		if (!empty($total_agencies)) {
			$link = CHtml::link('Agents (' . $total_agencies . ')', Yii::App()->createUrl('listingusers/index', array('f_type' => 'U', 'parent_user' => $this->user_id)));
		}
		return $type . $link;
	}

	public function getTotalAgents()
	{
		if (empty($this->parent_user) and in_array($this->user_type, array('D', 'K'))) {
			return self::model()->countByAttributes(array('parent_user' => $this->user_id, 'isTrash' => '0'));
		}
	}
	public $parent_company;
	public $parent_slug;
	public function getImageDetails($filename)
	{
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if (in_array($ext, array('png', 'jpg', 'jpeg'))) {
			return Yii::app()->apps->getBaseUrl('uploads/images/' . $filename);
		} else {
			return Yii::app()->apps->getBaseUrl('assets/img/fileext.png');
		}
	}
	public function getUserSlug()
	{
		if (in_array($this->user_type, array('K', 'D'))) {
			$username = $this->company_name;
		}
		if (empty($username)) {
			$username = $this->first_name . '-' . $this->last_name;
		}
		return $username;
	}
	public function getCheckEmailVerified()
	{
		$html = $this->email;
		if ($this->email_verified == '1') {
			$html .= '<i class="fa fa-check text-green"></i>';
		} else {
			$html .= '<i class="fa fa-ban text-red"></i>';
		}
		return $html;
	}
	public function latestFiles($limit = 10)
	{
		$criteria = $this->search(1);
		$criteria->limit = $limit;
		$criteria->condition .= ' and t.user_type in ("D","K") ';
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination'    => array(
				'pageSize'  => $limit,
				'pageVar'   => 'page',
			),
		));
	}
	public $company_address;
	public $company_slug;
	public function getComp_logo()
	{

		if (!empty($this->p_company_logo)) {
			return Yii::app()->apps->getBaseUrl('uploads/images/' . $this->p_company_logo);
		}
	}
	public $user_slug;
	public function getUser_slug()
	{
		return $this->slug;
	}
	public function getUserImage($width = 100)
	{
		$app = Yii::app();
		return $app->apps->getBaseUrl('timthumb.php') . '?src=' . $app->apps->getBaseUrl('uploads/images/' . $this->image) . '&w=' . $width . '&zc=1';
	}
	public function sendOtpEmail()
	{

		$options     =   Yii::app()->options;
		$support_phone  =  $options->get('system.common.support_phone');
		$support_email  =  $options->get('system.common.support_email');
		$notify     = Yii::app()->notify;


		$subject =   Yii::t('app', '{x} is your AjmanProperties verification code', array('{x}' => $this->verification_code));

		$emailTemplate = '<table border="0" cellpadding="0" cellspacing="0" width="600">
	<tbody>
		<tr>
			<td>
			<p style="font-weight:600;line-height:30px;font-size:30px;color:#dc143c;">AjmanProperties.ae</p>
			</td>
		</tr>
		<tr>
			<td>
			<p style="font-weight:600;line-height:30px;font-size:23px;">Confirm your email address , </p>
			</td>
		</tr>
		<tr>
			<td>
			<p>There&rsquo;s one quick step you need to complete before creating your Askaan account. Let&rsquo;s make sure this is the right email address for you &mdash; please confirm this is the right address to use for your new account. .</p>
			<p>Please enter this verification code to get started on AjmanProperties:</p>
			<p style="font-size:24px;">' . $this->verification_code . '</p>
			<p>Verification codes expire after two hours.<br />
			<br />
			<span style="font-size:18px;">Thanks,<br />
			 AjmanProperties</span><br />
			&nbsp;</p>
			</td>
		</tr>
	</tbody>
</table>
';
		// $emailTemplate_common = $options->get('system.email_templates.common');
		// $emailTemplate = str_replace('[CONTENT]', $emailTemplate, $emailTemplate_common);
		$status = 'S';

		$adminEmail = new Email();
		$adminEmail->subject = $subject;
		$adminEmail->message = $emailTemplate;
		$receipeints = serialize(array($this->email));
		$adminEmail->status = $status;
		$adminEmail->receipeints = $receipeints;
		$adminEmail->sent_on =   1;
		$adminEmail->type =   'S';
		$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
		$adminEmail->save(false);
		$adminEmail->getSend(false);

		return true;
	}
	public $hours_different;

	public function profile_pic()
	{
		$fileName = !empty($this->image) ? $this->image : $this->company_logo;

		if (!empty($fileName)) {
			$image =  Yii::app()->apps->getBaseUrl("uploads/images/" . $fileName);
		} else {
			$image =  $this->getDefaultImg();
		}
		if (empty($image)) {
			return false;
		}
		return $image;
	}

	public function getBackendImage()
	{
		switch ($this->user_type) {

			case 'K':
				if (!empty($this->company_logo)) {
					return '<img src="' . Yii::app()->apps->getBaseUrl('uploads/images/' . $this->company_logo) . '" style="width:30px;height:30px;border-radius:50%">';;
				}
				break;
		}
	}
	public function getServiceLocationCityDetails()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('user_id', $this->user_id);
		$criteria->select = 'cn.city_name,cn.city_id ';
		$criteria->join = ' INNER  JOIN {{city}} cn ON  cn.city_id = t.city_id ';
		$criteria->limit = Yii::App()->options->get('system.common.max_cities_select', '5');
		return  ServiceCity::model()->findAll($criteria);
	}
	public $verified;
	public function serviceCityDetail()
	{

		$list =  $this->getServiceLocationCityDetails();
		$html = '';
		foreach ($list  as $k2) {
			$html .= $k2->city_name . ',';
		}
		if ($html != '') {
			return rtrim($html, ',');
		}
	}
	public $whatsapp;
	public function getContactPhone()
	{
		return $this->phone;
	}
	protected function afterConstruct()
	{
		$this->_initStatus = $this->status;
		if (defined('EDITOR')) {
			$this->fieldDecorator->onHtmlOptionsSetup = array($this, '_setDefaultEditorForContent');
		}
		parent::afterConstruct();
	}
	protected function afterFind()
	{
		$this->_initStatus = $this->status;
		if (defined('EDITOR')) {
			$this->fieldDecorator->onHtmlOptionsSetup = array($this, '_setDefaultEditorForContent');
		}
		parent::afterFind();
	}
	public function _setDefaultEditorForContent(CEvent $event)
	{
		$id = $event->params['attribute'];
		if (in_array($id, array('description', 'a_description'))) {

			$options = array();
			if ($event->params['htmlOptions']->contains('wysiwyg_editor_options')) {
				$options = (array)$event->params['htmlOptions']->itemAt('wysiwyg_editor_options');
			}
			$options['id'] = CHtml::activeId($this, $id);
			$options['height'] = 200;
			$options['toolbar'] = 'Default';
			$event->params['htmlOptions']->add('wysiwyg_editor_options', $options);
		}
	}
	public function getAvatarUrlN($width = 50, $height = 50, $forceSize = false)
	{



		if (!empty($this->image)) {

			$image =  Yii::app()->apps->getBaseUrl("uploads/images/" . $this->image);
		} else {
			$image =  $this->getDefaultImg();
		}
		return $image;
	}
	public $sold_rented;
	public function getAccountBalance()
	{
		$amount = PricePlanOrder::model()->customer_balance($this->user_id);
		return 'AED.' . number_format((int)  $amount, 0, '.', ',');
	}
	public function getAdsCountTitle()
	{
		if (!empty($this->total_ads)) {
			return '<span class="text-blue">(' . $this->total_ads . ')<span>';
		}
	}

	public $total_ads;
	public function getDefualtAdsCount()
	{

		$model =  PackageNew::model()->defaultPakcage();
		$max_can_post = 5;
		if (!empty($model)) {

			$max_can_post = $model->max_listing_per_day;
		}
		$criteria = new CDbCriteria;
		$criteria->condition = '1';
		$user_id = !empty($this->parent_user) ? $this->parent_user : $this->user_id;
		$criteria->join  = ' left join {{listing_users}}   pusr on pusr.parent_user = t.user_id';
		$criteria->condition .= ' and t.isTrash="0" and t.user_id =:me or pusr.user_id = :me ';
		$criteria->params[':me'] = $user_id;
		$count = PlaceAnAd::model()->count($criteria);
		$total_remaining = $max_can_post - $count; 
		 
		return ($total_remaining > 0) ? $total_remaining : 0 ;
	}
	public function getvalidateUserCurrentPackage($id = 1)
	{

		$model = PackageNew::model()->userActivePackage($id, $this->user_id);
		 
		if(defined('NEW_PACKAGE')){
		 
			return $this->newModel($id, $model);
		}else{
			return $this->oldModel($id, $model);
		}
	}
	public function newModel($id, $model){
		 
		if (!empty($model)) {
			switch ($id) {
				case '1':
				case '3':
				case '4':
				case '6':
				 
				if ($model->used_ad >= $model->ads_allowed) {
						 
					return array('redirect' => 'package_expired', 'message' => 'Package expired or exceeded ads limit.  ');
				}
			 
				return array('success' => 'success', 'validity' => $model->uap_validity, 'id' => $model->uap_id, 'message' => 'Successfully updated.');
			
				break;
			 
			}
		}else{
			return array('redirect' => 'package_expired', 'message' => 'Package expired or exceeded no. of  limit');
		
		}
	}
	public function oldModel($id, $model){

		if (!empty($model)) {
			switch ($id) {
				case '1':
					if ($model->used_ad >= $model->ads_allowed) {
						return array('redirect' => 'package_expired', 'message' => 'Package expired or exceeded ads limit. Please activate suitable packages');
					}
					break;
				case '2':
					if ($model->used_ad >= $model->ads_allowed) {
						return array('redirect' => 'package_expired', 'message' => 'Featured limit exceeded or expired.');
					} else {

						return array('success' => 'success', 'validity' => $model->uap_validity, 'id' => $model->uap_id, 'message' => 'Successfully updated video.<br />Video under waiting admin approval');
					}
					break;
				case '3':

					if ($model->used_ad >= $model->ads_allowed) {
						return array('redirect' => 'package_expired', 'message' => 'Featured limit exceeded or expired.');
					} else {
						return array('success' => 'success', 'validity' => $model->uap_validity, 'id' => $model->uap_id, 'message' => 'Successfully set as featured.');
					}
					break;
				case '4':

					if ($model->used_ad >= $model->ads_allowed) {
						return array('redirect' => 'package_expired', 'message' => 'Featured limit exceeded or expired.');
					} else {
						return array('success' => 'success', 'validity' => $model->uap_validity, 'id' => $model->uap_id, 'message' => 'Successfully refreshed.');
					}
					break;
				case '6':

					if ($model->used_ad >= $model->ads_allowed) {
						return array('redirect' => 'package_expired', 'message' => 'Hot limit exceeded or expired.');
					} else {
						return array('success' => 'success', 'validity' => $model->uap_validity, 'id' => $model->uap_id, 'message' => 'Successfully set as hot ad.');
					}
					break;
			}
		} else {

			switch ($id) {
				case '1':
					return array('redirect' => 'package_expired', 'message' => 'Default package limit exceeded. Please activate suitable packages');

					$model =  PackageNew::model()->defaultPakcage();
					$max_can_post = 5;
					if (!empty($model)) {

						$max_can_post = $model->max_listing_per_day;
					}
					$criteria = new CDbCriteria;
					$criteria->compare('t.user_id', (int)Yii::app()->user->getId());
					$criteria->compare('t.isTrash', "0");

					$count = PlaceAnAd::model()->count($criteria);
					if ($count >= $max_can_post) {
						return array('redirect' => 'package_expired', 'message' => 'Default package limit exceeded. Please activate suitable packages');
					}
					break;
				case '3':
					return array('redirect' => 'no_active_package', 'message' => 'No Featured Package  Subscribed!');
					break;
				case '4':
					return array('redirect' => 'no_active_package', 'message' => 'No Refresh  Package  Subscribed!');
					break;
				case '6':
					return array('redirect' => 'no_active_package', 'message' => 'No Hot Package  Subscribed!');
					break;
			}
		}
	}
	public function getSendCredentialBtns(){
		return '<div style="position:relative;"><div class="dropdown margin-top-10">
            <button class="btn btn-default btn-xs dropdown-toggle" type="button" id="about-us" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Send Login
            </button>
            <ul class="dropdown-menu" aria-labelledby="about-us">
                <li><a class="dropdown-item" onclick="sendL(this)" data-method="email-temp" data-id="'.$this->user_id. '" href="javascript:void(0)">Approve with Temp. Password <span class="loading-icon spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></a></li>
                <li><a class="dropdown-item" onclick="sendL(this)" data-method="email-no-password" data-id="' . $this->user_id . '" href="javascript:void(0)">Approve Without Password Reset <span class="loading-icon spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></a></li>
                <li><a class="dropdown-item" onclick="sendL(this)" data-method="whatsapp" data-id="' . $this->user_id . '" href="javascript:void(0)">Send via WhatsApp <span class="loading-icon spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></a></li>
            </ul>
        </div></div>';
	}
	public $package; 
	public function getActivePackage(){
		$user_active_package = PackageNew::model()->userActivePackage(1,$this->user_id);
		 
		if (!empty($user_active_package) and ($user_active_package->ads_allowed - $user_active_package->used_ad) > 0) {
			 
				$total_remaining = $user_active_package->ads_allowed - $user_active_package->used_ad;
			 $days_remaining =  ($user_active_package->uap_validity - $user_active_package->date_diff) . ' days remaining';
			 if($user_active_package->uap_validity == '0'){
				 $days_remaining = 'UNLIMITED'; 
			 } 
			return  '<p class="text-green"> (<b>' . ($user_active_package->ads_allowed - $user_active_package->used_ad) . '  ads</b>) ' . $days_remaining.'</p>';
		} else {
 			return 'No Active Package';
		} 
		 
	}
	public $not_validated_captcha = false; 
	public function validateRecaptchaLatest(){
		if(!empty($this->not_validated_captcha)){
			$this->addError('_recaptcha', 'reCAPTCHA validation failed.');
		}
	}
	public function getdocNot(){
		if($this->documents_submitted){
			return CHtml::link('Document Submitted',Yii::app()->createUrl('listingusers/documents_submitted',['id'=>$this->user_id]),['class'=>'h-link d-block']);
		}
	}
	public function sentapproved()
	{

		$this->addFreePackage();
		
		$options = Yii::app()->options;
		$notify = Yii::app()->notify;
		$emailTemplate =  CustomerEmailTemplate::model()->getTemplateByUid('oj5609sa1j951');
		if (empty($emailTemplate)) {
			return false;
		}
		$tags = Yii::app()->tags;
		$common_name =  $tags->getTag('site_name');
		$support_phone  =   Yii::app()->options->get('system.common.support_phone');
		$support_email  =  Yii::app()->options->get('system.common.support_email');
		$subject     = $emailTemplate->subject;
		$emailTemplate = $emailTemplate->content; 
		$emailTemplate = str_replace('[Agency Name/Representative]', $this->first_name.' '.$this->last_name, $emailTemplate);
		$emailTemplate = str_replace('[Support Email]', $support_email, $emailTemplate);
		$emailTemplate = str_replace('[Support Phone Number]', $support_phone, $emailTemplate); 
		$emailTemplate_common = $tags->getTag('common');
		if (empty($emailTemplate_common)) {
			$emailTemplate_common = $options->get('system.email_templates.common');
		}
		$emailTemplate = str_replace('[CONTENT]', $emailTemplate, $emailTemplate_common);
 
		$status = 'S';
		 
		$adminEmail = new Email();

		$adminEmail->subject = $subject  ;
		$adminEmail->message = $emailTemplate;
		$receipeints = serialize(array($this->email));
		$adminEmail->status = $status;
		$adminEmail->receipeints = $receipeints;
		$adminEmail->sent_on =   1;
		$adminEmail->type =   'RESENT';
		$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
		$adminEmail->save(false);

		return true;
	}
	public function thanks_message($user_id)
	{
		$options = Yii::app()->options;
		$notify = Yii::app()->notify;
		$emailTemplate =  CustomerEmailTemplate::model()->findByAttributes(array('template_uid' => "bb762s1rxx1ea"));;

		if ($emailTemplate) {

			$customer = ListingUsers::model()->findByPk($user_id);
			$subject        = $emailTemplate->subject;
			$emailTemplate  = $emailTemplate->content;
			$receipeints = serialize(array($customer->email));
			$status = 'S';
			$adminEmail = new Email();


			$adminEmail->subject = str_replace(['[PROJECT_NAME]'], [$options->get('system.common.site_name', 'support@feeta.pk')], $subject);

			$adminEmail->message = str_replace(
				[
					'[Customer Name]',
				],
				[
					$customer->fullName,
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
		}
	}
	public function docsubmitnotification()
	{
		$this->thanks_message($this->user_id);

		$options = Yii::app()->options;
		$notify = Yii::app()->notify;
		$emailTemplate =  CustomerEmailTemplate::model()->getTemplateByUid('gt907otswpfb7');
		if (empty($emailTemplate)) {
			return false;
		}
		$tags = Yii::app()->tags;
		$common_name =  $tags->getTag('site_name');
		$support_phone  =   Yii::app()->options->get('system.common.support_phone');
		$support_email  =  Yii::app()->options->get('system.common.support_email');
		$subject     = $emailTemplate->subject;
		$emailTemplate = $emailTemplate->content; 
		$emailTemplate = str_replace('[Agency Name/Representative]', $this->first_name.' '.$this->last_name, $emailTemplate);
		$emailTemplate = str_replace('[Support Email]', $support_email, $emailTemplate);
		$emailTemplate = str_replace('[Support Phone Number]', $support_phone, $emailTemplate); 
		$emailTemplate = str_replace('[URL]',  'https://www.ajmanproperties.ae/backend/index.php/listingusers/documents_submitted/id/'.$this->user_id, $emailTemplate); 
		$emailTemplate_common = $tags->getTag('common');
		if (empty($emailTemplate_common)) {
			$emailTemplate_common = $options->get('system.email_templates.common');
		}
		$emailTemplate = str_replace('[CONTENT]', $emailTemplate, $emailTemplate_common);
 
		$status = 'S';
		 
		$adminEmail = new Email();

		$adminEmail->subject = $subject  ;
		$adminEmail->message = $emailTemplate;
		$receipeints = serialize(array($this->email));
		$adminEmail->status = $status;
		$adminEmail->receipeints = $receipeints;
		$adminEmail->sent_on =   1;
		$adminEmail->type =   'RESENT';
		$adminEmail->sent_on_utc =   new CDbExpression('NOW()');
		$adminEmail->save(false);

		$insertLog = new CustomerActionLog();
		$insertLog->customer_id = $this->user_id;
		$insertLog->category =  'customer.new.document_submitted';
		$insertLog->reference_id = $this->user_type;
		$insertLog->save();

		return true;
	}
	public function getValueTextNew($field){
		if(!empty($this->$field)){
			return $this->$field;
		}else{
			return '---';
		}
	}
	public function getValueFileNew($field){
		if(!empty($this->$field)){
			return  '<a class="btn_vie"  target="_blank" style="margin-left:0px;" href="' . $this->getFilePath($this->$field) . '"><div style="width:100px;height:100px;border:1px solid #eee;border-radius:4px;" class="img-own"><img src="' . $this->getImageDetails($this->$field) . '" style="object-fit:contain;width:100%;height:100%;"></div></a>';
		}else{
			return '---';
		}
	}
	public function validatePasswordRules($attribute, $params)
	{
		$password = $this->$attribute;

		// Initialize criteria counts
		$criteriaMet = 0;

		// Check length
		if (strlen($password) >= 8) {
			$criteriaMet++;
		}

		// Check for lowercase letters
		if (preg_match('/[a-z]/', $password)) {
			$criteriaMet++;
		}

		// Check for uppercase letters
		if (preg_match('/[A-Z]/', $password)) {
			$criteriaMet++;
		}

		// Check for numbers
		if (preg_match('/[0-9]/', $password)) {
			$criteriaMet++;
		}

		// Check for special characters
		if (preg_match('/[!@#$%^&*]/', $password)) {
			$criteriaMet++;
		}

		// Validate criteria
		if (strlen($password) < 8) {
			$this->addError($attribute, 'Password must be at least 8 characters long.');
		}

		if ($criteriaMet < 3) {
			$this->addError(
				$attribute,
				'Password must include at least 3 of the following: lowercase letters, uppercase letters, numbers, or special characters (!@#$%^&*).'
			);
		}
	}
	function generateUniqueCode($length = 6)
	{
		do {
			$code = str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
			$existingCode = self::model()->find([
				'condition' => 'verification_code = :code AND v_send_at > NOW() - INTERVAL 10 MINUTE',
				'params' => [':code' => $code]
			]);
		} while ($existingCode);

		// Save the unique code
		 
		
		return $code;
		 
	}
	private $maxAttempts = 3; // Maximum OTP attempts
	private $otpExpiryMinutes = 10;
	public function validateOtp($attribute, $params)
	{
		$record = Self::model()->findByAttributes(['user_id' => $this->user_id]);

		if (!$record) {
			$this->addError($attribute, 'Invalid user record.');
			return;
		}

		$maxAttempts = $this->maxAttempts;
		$cooldownPeriod = 3600; // 1 hour in seconds
		$currentAttempts = (int)$record->otp_attempts;
		$otpTime = strtotime($record->v_send_at);
		$cooldownTime = $record->otp_cooldown_at ? strtotime($record->otp_cooldown_at) : null;
		$currentTime = time();

		// Cooldown enforcement
		if ($cooldownTime && ($currentTime - $cooldownTime) < $cooldownPeriod) {
			$remainingTime = ceil(($cooldownPeriod - ($currentTime - $cooldownTime)) / 60);
			$this->addError($attribute, "Too many failed attempts. Please try again after $remainingTime minutes.");
			return;
		}

		// Reset cooldown if applicable
		if ($cooldownTime && ($currentTime - $cooldownTime) >= $cooldownPeriod) {
			$record->otp_attempts = 0;
			$record->otp_cooldown_at = null;
		}

		if (($currentTime - $otpTime) > ($this->otpExpiryMinutes * 60)) {
			$this->addError($attribute, 'OTP has expired. Please request a new one.');
			return;
		}

		if ($record->verification_code !== $this->otp) {
			$record->otp_attempts = $currentAttempts + 1;

			// Start cooldown if maximum attempts reached
			if ($record->otp_attempts >= $maxAttempts) {
				$record->otp_cooldown_at =  date('Y-m-d H:i:s');
				$this->addError($attribute, 'Maximum OTP attempts reached. Please try again after 1 hour.');
			}

			//$record->save(false);
			if (!$this->hasErrors($attribute)) {
				$this->addError($attribute, 'Invalid OTP. Please try again.');
			}
		} else {
			// Successful OTP verification
			$record->otp_attempts = 0;
			$record->verification_code = null;
			$record->otp_cooldown_at = null;
			//$record->save(false);
		} 
		self::model()->updateByPk((int)$this->user_id, ['otp_attempts' => $record->otp_attempts, 'verification_code'=> $record->verification_code,'otp_cooldown_at'=>$record->otp_cooldown_at]);
	}
	public function getemailN(){
		$email_verified = $this->email_verified == '1' ? '<i class="fa fa-check-circle text-green"></i>' : '<i class="fa   fa-exclamation-circle text-red"></i>'; 
		return '<span class="email-verified">    '.$this->email.' ' . $email_verified. '      </span>';
	}
	public function getviewDetails1(){
		if($this->user_type=='K' and $this->documents_submitted=='1'){
			return '<a href="'.Yii::app()->createUrl('listingusers/view_details',['id'=>$this->primaryKey]). '" data-fancybox="" class="btn btn-primary" style="margin-top:10px;background-color:#31ce36" data-type="iframe">View Details</a>';
		}

	}
}
