<?php 
use Ysn\SuperCore\Types\TenantType; 
use Ysn\SuperCore\Types\PostType; 

return [
    'id'  => 1, //9999,
    'type'  => TenantType::UNDEFINED,
    'name'  => 'Default App',
    'images' =>  [
        'avatar' => 'https://avatars.hsoubcdn.com/3db068a7a56b908278a1bc8f82f9fdcd?s=128', // 'https://cdn.qarya.net/images/users/placeholder.png'
    ],
    'available_locales' => ['en', 'ar', ],
    // 'required_data' => [
    //     // auth
    //     'password_required' => true,
    //     'email_verification_required' => true,
    //     'phone_verification_required' => true,
    //     'social_google_required' => true,
    //     'social_facebook_required' => true,

    //     // profile
    //     'photo' => false,
    //     'category_id' => false,
    //     'country_id' => false,
    //     'location_id' => false,
    //     'gender' => false,
    //     'birthday' => false,
    //     // ...
    // ],
    'config_fixed' => [
        // 'accounts_calculate_visits' =>  true,
        // 'accounts_following' =>  true,
        // 'accounts_following' =>  true,

        'searches_saving' =>  true,
    ],
    'services' =>  [
        'twilio_sid' => 'AC7656f02a2adbcd562fcc8f71c14c2bac', 
        'twilio_token' => '7b77ab95a39bea0d352829ea10584128', 
        'twilio_number' => '+19282966752', 
        'vonage_key' => '6af17e63', 
        'vonage_secret' => 'oaVe6OuEizf50tOi', 
        'onesignal_app_id' => 'f841a015-b3eb-4c08-bd09-80406f799f75', 
        'onesignal_rest_api' => 'NWNkZDRiMTMtMjY3ZS00MGE0LTg5ZjYtMzA0NTNkM2U0MTJk',
     ],
    'config' => [
        'types_require_category' =>  [], // AccountType::BUSINESS

        'auth_methods' =>  ['email', 'phone', 'social'],
        'auth_strict_type' =>  false,

        'accounts_completion_check' =>  true,
        'accounts_approving' =>  true,
        'accounts_max_business' =>  1,
        'accounts_visits_notifications' =>  false,

        'posts_approving'    =>  true,
        'posts_as_anonymous' =>  true,
        'posts_scheduling'   =>  true,
        'posts_expiring'     =>  true,
        'posts_min_media'    =>  0,
        'posts_feed_exclude' =>  [PostType::POLL],

        'comments_approving'    =>  true,
        'comments_as_anonymous' =>  true,

        'reviews_approving'     =>  true,

        'searchs_matching_notifications' =>  true,

        
        'cart_multi_seller'    =>  false,
        'delivery_picker_mode'    =>  true, // true: driver will pick items
        
        'tax_percent'               => 0,
        'shipping_fee'              => 7,
        'shipping_free_threshold'   => 10000,

        'points_mode' => true,
        'points_exchange_rate' => 0.5, // amount * points_exchange_rate
    ],
];
