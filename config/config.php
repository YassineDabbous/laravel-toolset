<?php


//use Ysn\SuperChat\Tests\Account;
//use Ysn\SuperAuth\Tests\Account;
// use Ysn\SuperCommon\Tests\Account;
// use Ysn\SuperFeed\Tests\Account;
// use Ysn\SuperMarket\Tests\Account;

use Ysn\SuperCore\Models\Account;
use Ysn\SuperAuth\Models\IdentityModel;
use Ysn\SuperCore\Types\PostType;
use Ysn\SuperFeed\Models\PostModel;

return [
    'tenantable' =>  false,
    'cache_enable' => false,
    'account' => Account::class,
    'post_model' => PostModel::class,
    'identity' => IdentityModel::class,

    'demo' => [
        'code' => '000000', // otp code, verification code, ...
        'email' => 'demo@test.com',
        'phone_subscriber' => '00000000', // without + and country code
    ],

    'disks' => [
        'qarya' => [
            'driver' => 'local',
            'root' => env('PUBLIC_STORAGE_PATH') ?? storage_path('app/public'),
            'url' => env('PUBLIC_STORAGE_URL') ?? env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'qarya_app' => [
            'driver' => 'local',
            'root' => env('PUBLIC_STORAGE_PATH').'/app',
            'url' => env('PUBLIC_STORAGE_URL').'/app',
            'visibility' => 'public',
        ],
        'qarya_content' => [
            'driver' => 'local',
            'root' => env('PUBLIC_STORAGE_PATH').'/content',
            'url' => env('PUBLIC_STORAGE_URL').'/content',
            'visibility' => 'public',
        ],
    ],

    'connections' => [
        'commonCnx' => [
            'database' => env('DB_DATABASE_COMMON'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ],
        'authCnx' => [
            'password_reset'  => env('DB_DATABASE_AUTH').'password_resets', // from auth.passwords.users.password_resets
            'database' => env('DB_DATABASE_AUTH'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ],
        'feedCnx' => [
            'database' => env('DB_DATABASE_FEED'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ],
        'marketCnx' => [
            'database' => env('DB_DATABASE_MARKET'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ],
        'deliveryCnx' => [
            'database' => env('DB_DATABASE_DELIVERY'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ],
        'chatCnx' => [
            'database' => env('DB_DATABASE_CHAT'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ],
        'monitorCnx' => [
            'database' => env('DB_DATABASE_MONITOR'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD')
        ],
    ],




    'market' => [
        'dicimal_places' => 3,
        'cart' => [
            'session_key' => 'qarya_cart', // The session key where the cart id gets saved
            'auto_assign_user' => true, // Whether to automatically set the user_id on new carts (based on Auth::user())
        ]
    ],



// config('settings.type')
    // 'default' => config('default'),
    // 'default' => [
    //     'id'  => 0, //9999,
    //     'type'  => TenantType::UNDEFINED,
    //     'name'  => 'Default App',
    //     'images' =>  [
    //         'avatar' => 'https://avatars.hsoubcdn.com/3db068a7a56b908278a1bc8f82f9fdcd?s=128', // 'https://cdn.qarya.net/images/users/placeholder.png'
    //     ],
    //     'services' => [
    //         'onesignal_app_id'      =>  'f841a015-b3eb-4c08-bd09-80406f799f75',
    //         'onesignal_rest_api'    =>  'NWNkZDRiMTMtMjY3ZS00MGE0LTg5ZjYtMzA0NTNkM2U0MTJk',
    //     ],
    //     // 'required_data' => [
    //     //     // auth
    //     //     'password_required' => true,
    //     //     'email_verification_required' => true,
    //     //     'phone_verification_required' => true,
    //     //     'social_google_required' => true,
    //     //     'social_facebook_required' => true,

    //     //     // profile
    //     //     'photo' => false,
    //     //     'category_id' => false,
    //     //     'country_id' => false,
    //     //     'location_id' => false,
    //     //     'gender' => false,
    //     //     'birthday' => false,
    //     //     // ...
    //     // ],
    //     'config_fixed' => [
    //         // 'accounts_calculate_visits' =>  true,
    //         // 'accounts_following' =>  true,
    //         // 'accounts_following' =>  true,

    //         'searches_saving' =>  true,
    //     ],
    //     'config' => [
    //         'auth_methods' =>  ['email', 'phone', 'social'],

    //         'accounts_completion_check' =>  true,
    //         'accounts_approving' =>  true,
    //         'accounts_max_business' =>  1,
    //         'accounts_visits_notifications' =>  true,

    //         'posts_approving'    =>  true,
    //         'posts_as_anonymous' =>  true,
    //         'posts_scheduling'   =>  true,
    //         'posts_expiring'     =>  true,
    //         'posts_min_media'    =>  0,
    //         'posts_feed_exclude' =>  [PostType::POLL],

    //         'comments_approving'    =>  true,
    //         'comments_as_anonymous' =>  true,

    //         'reviews_approving'     =>  true,

    //         'searchs_matching_notifications' =>  true,
    //     ],
    // ],
];
