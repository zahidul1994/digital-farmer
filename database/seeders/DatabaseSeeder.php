<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\CreateSuperAdmin;
use Database\Seeders\SettingTableSeeder;
use Database\Seeders\PermissionTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(CreateSuperAdmin::class);
        $this->call(SettingTableSeeder::class);
         $this->call(CountrySeeder::class);
        $this->call(PaymentSeeder::class);
       
    }
}
