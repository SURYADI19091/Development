<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\User::class),
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

    /*
    |--------------------------------------------------------------------------
    | Custom Authentication Settings
    |--------------------------------------------------------------------------
    |
    | These settings control various aspects of the authentication system
    | specific to this application.
    |
    */

    // Registration settings
    'allow_registration' => env('AUTH_ALLOW_REGISTRATION', true),
    'registration_requires_approval' => env('AUTH_REGISTRATION_REQUIRES_APPROVAL', true),
    'auto_activate_users' => env('AUTH_AUTO_ACTIVATE_USERS', false),

    // Login security settings
    'max_login_attempts' => env('AUTH_MAX_LOGIN_ATTEMPTS', 5),
    'lockout_duration' => env('AUTH_LOCKOUT_DURATION', 300), // 5 minutes
    'remember_duration' => env('AUTH_REMEMBER_DURATION', 2592000), // 30 days

    // Admin IP restriction (optional)
    'admin_allowed_ips' => env('AUTH_ADMIN_ALLOWED_IPS') ? 
        explode(',', env('AUTH_ADMIN_ALLOWED_IPS')) : [],

    // Session settings
    'session_lifetime' => env('SESSION_LIFETIME', 120),
    'force_logout_on_password_change' => env('AUTH_FORCE_LOGOUT_ON_PASSWORD_CHANGE', true),

    // Password policy
    'password_min_length' => env('AUTH_PASSWORD_MIN_LENGTH', 8),
    'password_require_uppercase' => env('AUTH_PASSWORD_REQUIRE_UPPERCASE', true),
    'password_require_lowercase' => env('AUTH_PASSWORD_REQUIRE_LOWERCASE', true),
    'password_require_numbers' => env('AUTH_PASSWORD_REQUIRE_NUMBERS', true),
    'password_require_symbols' => env('AUTH_PASSWORD_REQUIRE_SYMBOLS', true),
    'password_history_limit' => env('AUTH_PASSWORD_HISTORY_LIMIT', 5),

    // User roles and permissions
    'default_role' => env('AUTH_DEFAULT_ROLE', 'user'),
    'available_roles' => [
        'user' => 'Regular User',
        'member' => 'Village Member',
        'resident' => 'Village Resident',
        'village_officer' => 'Village Officer',
        'population_officer' => 'Population Officer',
        'finance_officer' => 'Finance Officer',
        'service_officer' => 'Service Officer',
        'cs_officer' => 'Customer Service Officer',
        'report_officer' => 'Report Officer',
        'editor' => 'Content Editor',
        'moderator' => 'Content Moderator',
        'admin' => 'Administrator',
        'super_admin' => 'Super Administrator',
    ],

    // Account status options
    'available_statuses' => [
        'pending' => 'Pending Approval',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
        'banned' => 'Banned',
    ],

];
