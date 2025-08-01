<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    protected $connection = 'mysql_legacy';
    protected $table = 'mw_listing_users';
    protected $primaryKey = 'user_id';

    const UPDATED_AT = 'date_added';
    const CREATED_AT = null;
    
    // User Roles
    const ROLE_AGENCY = 'K';
    const ROLE_AGENT = 'A';
    const ROLE_DEVELOPER = 'D';
    const ROLE_USER = 'U';
    

    const STATUS_APPROVED = 'A';
    const STATUS_INACTIVE = 'I';
    const STATUS_WAITING = 'W';
    const STATUS_REJECTED = 'R';
    const STATUS_DRAFT = 'D';
    const STATUS_PENDING_DOCUMENTS = 'U';

    // Override the tokens relationship to use the main database
    public function tokens()
    {
        return $this->morphMany(\Laravel\Sanctum\PersonalAccessToken::class, 'tokenable');
    }

    // Override createToken to ensure it uses the main database connection
    public function createToken(string $name, array $abilities = ['*'], \DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = \Illuminate\Support\Str::random(40);

        $token = new \Laravel\Sanctum\PersonalAccessToken();
        $token->setConnection('mysql');

        $token->name = $name;
        $token->token = hash('sha256', $plainTextToken);
        $token->abilities = $abilities;
        $token->expires_at = $expiresAt ?: now()->addDays(7);
        $token->tokenable_id = $this->getKey();
        $token->tokenable_type = get_class($this);

        $token->save();

        return new \Laravel\Sanctum\NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'whatsapp',
        'company_name',
        'user_type',
        'google_id',
        'apple_id',
        'facebook_id',
        'otp',
        'otp_expires_at',
        'email_verified',
        'whatsapp_verified',
        'password_reset_token',
        'password_reset_expires_at',
    ];

    protected $attributes = [
        'first_name' => '',
        'last_name' => '',
        'phone' => '',
        'address' => '',
        'city' => '',
        'state' => '',
        'country' => 0,
        'status' => 'W',
        'zip' => '',
        'fax' => '',
        'isTrash' => 0,
        'send_me' => 'N',
        'verification_code' => '',
        'reset_key' => '',
        'calls_me' => 'M',
        'education_level' => 0,
        'position_level' => 0,
        'updates' => '',
        'advertisement' => '',
        'cover_letter' => '',
        'image' => '',
        'xml_inserted' => '0',
        'xml_image' => '',
        'mobile' => '',
        'featured' => 'N',
        'email_verified' => '0',
        'whatsapp_verified' => '0',
        'admin_approved' => '0',
        'filled_info' => '0',
        'total_reviews' => '',
        'verified' => '0',
        'documents_submitted' => '',
        'google_id' => null,
        'otp' => null,
        'otp_expires_at' => null,
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
        'password_reset_expires_at' => 'datetime',
    ];


 
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->user_type, $role);
        }
        return $this->user_type === $role;
    }
    
    public function isAgency()
    {
        return $this->user_type === self::ROLE_AGENCY;
    }
    
    public function isAgent()
    {
        return $this->user_type === self::ROLE_AGENT;
    }
    
    public function isDeveloper()
    {
        return $this->user_type === self::ROLE_DEVELOPER;
    }
    
    public function isUser()
    {
        return $this->user_type === self::ROLE_USER;
    }
    
    public function getRoleName()
    {
        $roles = [
            self::ROLE_AGENCY => 'Agency',
            self::ROLE_AGENT => 'Agent',
            self::ROLE_DEVELOPER => 'Developer',
            self::ROLE_USER => 'User',
        ];
        
        return $roles[$this->user_type] ?? 'Unknown';
    }
    
    public function getStatusName()
    {
        $statuses = [
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_WAITING => 'Waiting',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING_DOCUMENTS => 'Pending Documents',
        ];
        
        return $statuses[$this->status] ?? 'Unknown';
    }
    
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }
    
    public function hasLimitedAccess()
    {
        return in_array($this->status, [self::STATUS_WAITING, self::STATUS_PENDING_DOCUMENTS]);
    }
    
    public function isBlocked()
    {
        return in_array($this->status, [self::STATUS_INACTIVE, self::STATUS_REJECTED, self::STATUS_DRAFT]);
    }
    
    // Relationships
    public function mw_user_languages()
    {
        return $this->hasMany(\App\Models\MwUserLanguage::class, 'user_id', 'user_id');
    }

    public function mw_user_main_categories()
    {
        return $this->hasMany(\App\Models\MwUserMainCategory::class, 'user_id', 'user_id');
    }

}
