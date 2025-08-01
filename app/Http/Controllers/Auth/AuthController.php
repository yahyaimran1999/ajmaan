<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Hautelook\Phpass\PasswordHash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon; 
use App\Http\Resources\MwListingUserResource;
use Laravel\Socialite\Facades\Socialite;
use Twilio\Rest\Client;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->email)->first();
            
            if (!$user) {
                $user = User::create([
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'first_name' => $googleUser->user['given_name'] ?? '',
                    'last_name' => $googleUser->user['family_name'] ?? '',
                    'user_type' => 'U',
                    'google_id' => $googleUser->id,
                    'email_verified' => '1'
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User signed in successfully with Google',
                'data' => [
                    'access_token' => $token,
                    'user' => new MwListingUserResource($user)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate with Google',
                'error' => $e->getMessage()
            ], 500);
        }
    }

      public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback(Request $request)
    {
        try {
            $appleUser = Socialite::driver('apple')->user();
            
            $user = User::where('apple_id', $appleUser->getId())->first();
            
            if (!$user && $appleUser->getEmail()) {
                $user = User::where('email', $appleUser->getEmail())->first();
                if ($user) {
                    $user->apple_id = $appleUser->getId();
                    $user->save();
                }
            }
            
            if (!$user) {
                $firstName = $appleUser->user['name']['firstName'] ?? '';
                $lastName = $appleUser->user['name']['lastName'] ?? '';
                
                $user = User::create([
                    'email' => $appleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'user_type' => 'U',
                    'apple_id' => $appleUser->getId(),
                    'email_verified' => '1'
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User signed in successfully with Apple',
                'data' => [
                    'access_token' => $token,
                    'user' => new MwListingUserResource($user)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Apple OAuth error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate with Apple',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:mysql_legacy.mw_listing_users,email',
            'password' => [
            'required', 
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            ],
            'is_real_estate_agent' => 'boolean',
            'phone' => 'required|string|max:20',
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number and one special character (@$!%*?&)'
        ]);


        if ($request->is_real_estate_agent) {
            $rules['first_name'] = 'required|string|max:255';
            $rules['last_name'] = 'required|string|max:255';
            $rules['company_name'] = 'nullable|string|max:255';
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $otp = mt_rand(100000, 999999);

        try {

            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $twilioWhatsappNumber = config('services.twilio.whatsapp_number');

            $twilio = new Client($twilioSid, $twilioToken);
            
            $message = $twilio->messages->create(
                "whatsapp:" . $request->phone,
                [
                    'from' => "whatsapp:" . $twilioWhatsappNumber,
                    'body' => "Your Ajmaan verification code is: " . $otp
                ]
            );

            $userData = [
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'whatsapp' => $request->phone,
                'otp' => password_hash($otp, PASSWORD_DEFAULT),
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ];

            if ($request->is_real_estate_agent) {
                $userData['first_name'] = $request->first_name;
                $userData['last_name'] = $request->last_name;
                $userData['company_name'] = $request->company_name;
                $userData['user_type'] = 'K';
            } else {
                $userData['user_type'] = 'U';
            }

            $user = User::create($userData);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully. Please verify your phone number.',
                'data' => [
                    'user_id' => $user->user_id,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:mysql_legacy.mw_listing_users,user_id',
        'otp' => 'required|string|size:6'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::find($request->user_id);

    if (!$user || !$user->otp || Carbon::now()->isAfter($user->otp_expires_at)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP'
        ], 400);
    }

    
    if (!password_verify($request->otp, $user->otp)) {
        
        Log::warning('Failed OTP verification attempt', [
            'user_id' => $user->user_id,
            'ip' => request()->ip()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP'
        ], 400);
    }

    $user->whatsapp_verified = '1';
    $user->otp = null; 
    $user->otp_expires_at = null;
    $user->save();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Phone number verified successfully',
        'data' => [
            'access_token' => $token,
            'user' => new MwListingUserResource($user)
        ]
    ]);
}
    public function reSendOtp(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:mysql_legacy.mw_listing_users,user_id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::find($request->user_id);
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }
    
    $otp = mt_rand(100000, 999999);
    
    try {
        $twilioSid = config('services.twilio.sid');
        $twilioToken = config('services.twilio.token');
        $twilioWhatsappNumber = config('services.twilio.whatsapp_number');

        $twilio = new Client($twilioSid, $twilioToken);
        
        $message = $twilio->messages->create(
            "whatsapp:" . $user->whatsapp,
            [
                'from' => "whatsapp:" . $twilioWhatsappNumber,
                'body' => "Your Ajmaan verification code is: " . $otp
            ]
        );

        $user->otp = password_hash($otp, PASSWORD_DEFAULT);
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully. Please verify your phone number.',
            'data' => [
                'user_id' => $user->user_id
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send OTP',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Send OTP directly to a user (helper method)
     */
    public function verifyPhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:mysql_legacy.mw_listing_users,user_id',
            'whatsapp_number' => 'required|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $otp = mt_rand(100000, 999999);
        
        try {
            $twilioSid = config('services.twilio.sid');
            $twilioToken = config('services.twilio.token');
            $twilioWhatsappNumber = config('services.twilio.whatsapp_number');

            $twilio = new Client($twilioSid, $twilioToken);
            
            $message = $twilio->messages->create(
                "whatsapp:" . $request->whatsapp_number,
                [
                    'from' => "whatsapp:" . $twilioWhatsappNumber,
                    'body' => "Your Ajmaan verification code is: " . $otp
                ]
            );

            $user = User::where('user_id', $request->user_id)->first();
            
            $user->whatsapp = $request->whatsapp_number;
            $user->otp = password_hash($otp, PASSWORD_DEFAULT);
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully. Please verify your phone number.',
                'data' => [
                    'user_id' => $user->user_id
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function signIn(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user || !Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }

    //     if ($user->whatsapp_verified != '1' && $user->email_verified != '1') {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Please verify your WhatsApp number.',
    //         ], 403);
    //     }

    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User signed in successfully',
    //         'data' => [
    //             'access_token' => $token,
    //             'user' => new MwListingUserResource($user)
    //         ]
    //     ]);
    // }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => [
                // 'user' => $request->user()
                'user' => new MwListingUserResource($request->user()->load(['mw_user_languages', 'mw_user_main_categories']))
            ]
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link has been sent to your email address'
            ]);
        }
        
        $resetToken = Str::random(60);

        $user->update([
            'password_reset_token' => $resetToken,
            'password_reset_expires_at' => Carbon::now()->addHour(),
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($resetToken));
            
            return response()->json([
                'success' => true,
                'message' => 'Password reset link has been sent to your email address'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset email',
                'error' => $e->getMessage()
            ], 500);
        }
    }    
    
    
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ]
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number and one special character (@$!%*?&)'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('password_reset_token', $request->token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reset token'
            ], 400);
        }

        if (Carbon::now()->isAfter($user->password_reset_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Reset token has expired'
            ], 400);
        }
        
        try {
            $user->update([
                'password' => Hash::make($request->password),
                'password_reset_token' => null,
                'password_reset_expires_at' => null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Password has been reset successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            $user = User::where('facebook_id', $facebookUser->id)->first();
            
            if (!$user) {
                $user = User::where('email', $facebookUser->email)->first();
                
                if ($user) {
                    $user->facebook_id = $facebookUser->id;
                    $user->save();
                } else {
                    $user = User::create([
                        'email' => $facebookUser->email,
                        'password' => Hash::make(Str::random(16)),
                        'first_name' => $facebookUser->name ?? '',
                        'last_name' => '',
                        'user_type' => 'U',
                        'facebook_id' => $facebookUser->id,
                        'email_verified' => '1'
                    ]);
                }
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User signed in successfully with Facebook',
                'data' => [
                    'access_token' => $token,
                    'user' => new MwListingUserResource($user)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate with Facebook',
                'error' => $e->getMessage()
            ], 500);
        }
    }  


    public function signIn(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        
        $emailExists = User::where('email', $request->email)->where('isTrash', 0)->exists();
        
        if (!$emailExists) {
            return $this->invalidCreds();
        }

        $user = User::where('email', $request->email)
            ->where('isTrash', 0)
            ->where(function($query) {
                $query->where(function($subQuery) {
                    // For agencies (user_type = 'K'), allow A, W, U statuses
                    $subQuery->where('user_type', 'K')
                             ->whereIn('status', ['A', 'W', 'U']);
                })->orWhere(function($subQuery) {
                    // For other user types, only allow A status
                    $subQuery->where('user_type', '<>', 'K')
                             ->where('status', 'A');
                });
            })
            ->first();
        
        if (!$user) return $this->invalidUsers();
        
        $hash = $user->password;
        $pwdOK = false;

        /* ---------- Is it bcrypt / argon2? ---------- */
        $algo = password_get_info($hash)['algoName'];   // "bcrypt" | "argon2i" | "argon2id" | "unknown"
        if ($algo !== 'unknown') {
            $pwdOK = Hash::check($request->password, $hash);
        }

        /* ----------  WordPress / PhpPass ---------- */
        if (!$pwdOK && preg_match('/^\$(P|H|9)\$/', $hash)) {
            $wp = new PasswordHash(8, true);       
            $pwdOK = $wp->CheckPassword($request->password, $hash);

            if ($pwdOK) {                               
                $user->password = Hash::make($request->password);
                $user->save();
            }
        }

        /* ----------  Legacy plain sha1 ---------- */
        if (!$pwdOK && preg_match('/^[0-9a-f]{40}$/i', $hash)) {
            $pwdOK = hash_equals($hash, sha1($request->password));

            if ($pwdOK) {
                $user->password = Hash::make($request->password);
                $user->save();
            }
        }

        /* ----------  Fail? Throw them out ---------- */
        if (!$pwdOK) return $this->invalidCreds();

    
        if ($user->whatsapp_verified != '1' && $user->email_verified != '1') {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your WhatsApp number.',
                'data' => [
                     'user_id' => $user->user_id
                 ]
            ], 403);
        }

        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User signed in successfully',
            'data'    => [
                'access_token' => $token,
                'user'         => new MwListingUserResource($user->load(['mw_user_languages', 'mw_user_main_categories'])),
            ],
        ]);
    }

    protected function invalidCreds()
    {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }
    protected function invalidUsers()
    {
        return response()->json([
            'success' => false,
            'message' => 'Your access to this account has been restricted. Please contact support.',
        ], 401);
    }


}
