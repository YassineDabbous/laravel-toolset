<?php

namespace Ysn\SuperCore\Database\Seeders;
 
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            \Ysn\SuperCommon\Database\Seeders\DatabaseSeeder::class,
            \Ysn\SuperAuth\Database\Seeders\DatabaseSeeder::class,
            \Ysn\SuperFeed\Database\Seeders\DatabaseSeeder::class,
        ]);
    }
    /*
    php artisan db:seed --class=\\Ysn\\SuperCore\\Database\\Seeders\\DatabaseSeeder
    
    DROP TABLE `accounts`, `account_admins`, `authz_account_roles`, `authz_roles`, `authz_role_permissions`, `devices`, `emails`, `identities`, `oauth_identities`, `password_resets`, `personal_access_tokens`, `phones`, `relations`;

    DROP TABLE `appeals`, `bookmarks`, `broadcasts`, `collections`, `comments`, `poll_options`, `poll_votes`, `posts`, `reactions`, `searches`;
    DROP TABLE `account_offer`, `addresses`, `balances`, `carts`, `cart_items`, `offers`, `orders`, `order_items`, `promotions`, `promotions_fixers`, `reviews`, `shipments`, `shipping_methods`;

    DROP TABLE `telescope_entries`, `telescope_entries_tags`, `telescope_monitoring`;
    */
}
