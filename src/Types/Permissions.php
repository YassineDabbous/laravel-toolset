<?php namespace Ysn\SuperCore\Types;


abstract class Permissions
{

    // GENERAL
    const MANAGE_TENANTS = 1;

    // COMMON
    const MANAGE_CATEGORIES = 10;
    const MANAGE_LOCATIONS = 20;
    const MANAGE_TAGS = 30;
    const MANAGE_ATTRIBUTES = 40;
    const MANAGE_BANNERS = 50;
    const MANAGE_ALERTS = 60;
    const MANAGE_REPORTS = 70;
    const MANAGE_FILTERS = 80;
    const MANAGE_NOTIFICATIONS = 90;
    const MANAGE_COLLECTIONS = 100;


    // AUTH
    const MANAGE_ROLES = 1000;
    const MANAGE_ACCOUNTS = 1100;


    // FEED
    const MANAGE_POSTS = 2000;
    // const POST_CREATE = 2001;
    // const POST_UPDATE = 2002;
    // const POST_DELETE = 2003;
    // const POST_PUBLISH = 2004;
    // const POST_ASK_REVIEW = 2005;

    const MANAGE_COMMENTS = 2100;
    const MANAGE_APPEALS = 2200;
    const MANAGE_REACTIONS = 2300;

    
    // MARKET
    const MANAGE_REVIEWS = 3005;
    const MANAGE_ADDRESSES = 3101;
    const MANAGE_ORDERS = 3102;
    const MANAGE_SHIPMENTS = 3103;
    const MANAGE_OFFERS = 3104;
    const MANAGE_SHIPPINHG_METHODS = 3105;
    const MANAGE_ADJUSTMENTS = 3106;
    
    // CHAT
    const MANAGE_CONVERSATIONS = 4000;

    // DELIVERY
    const MANAGE_INVOICES = 5000;
    const MANAGE_REPOSITORIES = 5001;
    const MANAGE_BRANCHES = 5002;
    const MANAGE_LOCATION_PRICES = 5003;
    const MANAGE_DEBTS = 5004;
    const MANAGE_SHIPMENT_AUTOMATORS = 5005; 
    const MANAGE_EXPENSES = 5006; 


    // NB: all permissions MUST be added here, this array used in permissions check (packages/auth/src/Concerns/InteractsWithAuth.php)
    const list = [
        Permissions::MANAGE_ROLES   =>  'MANAGE_ROLES',
        Permissions::MANAGE_CATEGORIES   =>  'MANAGE_CATEGORIES',
        Permissions::MANAGE_TAGS   =>  'MANAGE_TAGS',
        Permissions::MANAGE_LOCATIONS   =>  'MANAGE_LOCATIONS',
        Permissions::MANAGE_ATTRIBUTES   =>  'MANAGE_ATTRIBUTES',
        Permissions::MANAGE_CONVERSATIONS   =>  'MANAGE_CONVERSATIONS',
        
        Permissions::MANAGE_COLLECTIONS   =>  'MANAGE_COLLECTIONS',
        Permissions::MANAGE_NOTIFICATIONS   =>  'MANAGE_NOTIFICATIONS',
        Permissions::MANAGE_ALERTS   =>  'MANAGE_ALERTS',
        Permissions::MANAGE_REPORTS   =>  'MANAGE_REPORTS',
        Permissions::MANAGE_FILTERS   =>  'MANAGE_FILTERS',
        Permissions::MANAGE_BANNERS   =>  'MANAGE_BANNERS',
        Permissions::MANAGE_ADDRESSES   =>  'MANAGE_ADDRESSES',
        Permissions::MANAGE_ORDERS   =>  'MANAGE_ORDERS',
        Permissions::MANAGE_SHIPMENTS   =>  'MANAGE_SHIPMENTS',
        Permissions::MANAGE_SHIPMENT_AUTOMATORS   =>  'MANAGE_SHIPMENT_AUTOMATORS',
        Permissions::MANAGE_SHIPPINHG_METHODS   =>  'MANAGE_SHIPPINHG_METHODS',
        Permissions::MANAGE_ADJUSTMENTS   =>  'MANAGE_ADJUSTMENTS',
        Permissions::MANAGE_OFFERS   =>  'MANAGE_OFFERS',
        // Permissions::MANAGE_OFFERS   =>  'MANAGE_OFFERS',

        // Permissions::POST_CREATE    =>  'POST_CREATE',
        // Permissions::POST_UPDATE   =>  'POST_UPDATE',
        // Permissions::POST_DELETE   =>  'POST_DELETE',
        // Permissions::POST_PUBLISH   =>  'POST_PUBLISH',
        // Permissions::POST_ASK_REVIEW   =>  'POST_ASK_REVIEW',
        Permissions::MANAGE_ACCOUNTS   =>  'MANAGE_ACCOUNTS',

        Permissions::MANAGE_POSTS   =>  'MANAGE_POSTS',
        Permissions::MANAGE_APPEALS   =>  'MANAGE_APPEALS',
        Permissions::MANAGE_REACTIONS   =>  'MANAGE_REACTIONS',
        Permissions::MANAGE_COMMENTS   =>  'MANAGE_COMMENTS',
        Permissions::MANAGE_REVIEWS   =>  'MANAGE_REVIEWS',

        Permissions::MANAGE_INVOICES   =>  'MANAGE_INVOICES',
        Permissions::MANAGE_REPOSITORIES   =>  'MANAGE_REPOSITORIES',
        Permissions::MANAGE_BRANCHES   =>  'MANAGE_BRANCHES',
        Permissions::MANAGE_LOCATION_PRICES   =>  'MANAGE_LOCATION_PRICES',
        Permissions::MANAGE_DEBTS   =>  'MANAGE_DEBTS',
        Permissions::MANAGE_EXPENSES   =>  'MANAGE_EXPENSES',

        Permissions::MANAGE_TENANTS   =>  'MANAGE_TENANTS',
    ];

    // const map = [
    //     ['id' => Permissions::MANAGE_ROLES,         'name' => 'MANAGE_ROLES'],
    //     ['id' => Permissions::MANAGE_CATEGORIES,    'name' => 'MANAGE_CATEGORIES'],
    //     ['id' => Permissions::MANAGE_TAGS,    'name' => 'MANAGE_TAGS'],
    //     ['id' => Permissions::MANAGE_LOCATIONS,    'name' => 'MANAGE_LOCATIONS'],
    //     ['id' => Permissions::MANAGE_ATTRIBUTES,    'name' => 'MANAGE_ATTRIBUTES'],
    //     ['id' => Permissions::MANAGE_ACCOUNTS,      'name' => 'MANAGE_ACCOUNTS'],
    //     ['id' => Permissions::MANAGE_CONVERSATIONS,    'name' => 'MANAGE_CONVERSATIONS'],
        
    //     ['id' => Permissions::MANAGE_COLLECTIONS,    'name' => 'MANAGE_COLLECTIONS'],
    //     ['id' => Permissions::MANAGE_NOTIFICATIONS,    'name' => 'MANAGE_NOTIFICATIONS'],
    //     ['id' => Permissions::MANAGE_ALERTS,    'name' => 'MANAGE_ALERTS'],
    //     ['id' => Permissions::MANAGE_REPORTS,    'name' => 'MANAGE_REPORTS'],
    //     ['id' => Permissions::MANAGE_FILTERS,    'name' => 'MANAGE_FILTERS'],
    //     ['id' => Permissions::MANAGE_BANNERS,    'name' => 'MANAGE_BANNERS'],
    //     ['id' => Permissions::MANAGE_ADDRESSES,    'name' => 'MANAGE_ADDRESSES'],
    //     ['id' => Permissions::MANAGE_ORDERS,    'name' => 'MANAGE_ORDERS'],
    //     ['id' => Permissions::MANAGE_SHIPMENTS,    'name' => 'MANAGE_SHIPMENTS'],
    //     ['id' => Permissions::MANAGE_SHIPMENT_AUTOMATORS,    'name' => 'MANAGE_SHIPMENT_AUTOMATORS'],
    //     ['id' => Permissions::MANAGE_EXPENSES,    'name' => 'MANAGE_EXPENSES'],
    //     ['id' => Permissions::MANAGE_SHIPPINHG_METHODS,    'name' => 'MANAGE_SHIPPINHG_METHODS'],
    //     ['id' => Permissions::MANAGE_ADJUSTMENTS,    'name' => 'MANAGE_ADJUSTMENTS'],
    //     ['id' => Permissions::MANAGE_OFFERS,    'name' => 'MANAGE_OFFERS'],
        

    //     ['id' => Permissions::MANAGE_REACTIONS,       'name' => 'MANAGE_REACTIONS'],
    //     ['id' => Permissions::MANAGE_APPEALS,       'name' => 'MANAGE_APPEALS'],
    //     ['id' => Permissions::MANAGE_COMMENTS,      'name' => 'MANAGE_COMMENTS'],
    //     ['id' => Permissions::MANAGE_REVIEWS,       'name' => 'MANAGE_REVIEWS'],

    //     ['id' => Permissions::MANAGE_POSTS,          'name' => 'MANAGE_POSTS'],
    //     // ['id' => Permissions::POST_CREATE,          'name' => 'POST_CREATE'],
    //     // ['id' => Permissions::POST_UPDATE,          'name' => 'POST_UPDATE'],
    //     // ['id' => Permissions::POST_DELETE,          'name' => 'POST_DELETE'],
    //     // ['id' => Permissions::POST_PUBLISH,         'name' => 'POST_PUBLISH'],
    //     // ['id' => Permissions::POST_ASK_REVIEW,      'name' => 'POST_ASK_REVIEW'],

    //     ['id' => Permissions::MANAGE_INVOICES,      'name' => 'MANAGE_INVOICES'],
    //     ['id' => Permissions::MANAGE_REPOSITORIES,      'name' => 'MANAGE_REPOSITORIES'],
    //     ['id' => Permissions::MANAGE_BRANCHES,      'name' => 'MANAGE_BRANCHES'],
    //     ['id' => Permissions::MANAGE_LOCATION_PRICES,      'name' => 'MANAGE_LOCATION_PRICES'],
    //     ['id' => Permissions::MANAGE_DEBTS,    'name' => 'MANAGE_DEBTS'],

    //     ['id' => Permissions::MANAGE_TENANTS,      'name' => 'MANAGE_TENANTS'],
    // ];




    
    const map = [
        ['id' => Permissions::MANAGE_ROLES,         'name' => 'إدارة المهام و الصلاحيات'],
        ['id' => Permissions::MANAGE_LOCATIONS,    'name' => 'إدارة المناطق'],
        ['id' => Permissions::MANAGE_ACCOUNTS,      'name' => 'إدارة الحسابات'],
        ['id' => Permissions::MANAGE_NOTIFICATIONS,    'name' => 'إدارة التنبيهات'],
        ['id' => Permissions::MANAGE_BANNERS,    'name' => 'إدارة اللوحات الإعلانية'],
        ['id' => Permissions::MANAGE_SHIPMENTS,    'name' => 'إدارة الشحنات'],
        ['id' => Permissions::MANAGE_SHIPMENT_AUTOMATORS,    'name' => 'إدارة إعدادات التحديث التلقائي'],
        ['id' => Permissions::MANAGE_EXPENSES,    'name' => 'إدارة المصاريف'],
        ['id' => Permissions::MANAGE_INVOICES,      'name' => 'إدارة الكشوفات'],
        ['id' => Permissions::MANAGE_REPOSITORIES,      'name' => 'إدارة المخازن'],
        ['id' => Permissions::MANAGE_BRANCHES,      'name' => 'إدارة الفروع'],
        ['id' => Permissions::MANAGE_LOCATION_PRICES,      'name' => 'إدارة تسعيرة المناطق'],
        ['id' => Permissions::MANAGE_DEBTS,    'name' => 'إدارة السلف'],
    ];
}
