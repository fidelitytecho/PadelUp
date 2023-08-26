<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyAndCurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currency = Currency::create([
            'name_en' => 'EGP',
            'sign_en' => '#'
        ]);
        $user = User::create([
            'first_name' => 'super',
            'last_name' => 'admin',
            'email' => 'admin@super.admin',
            'dial_code' => '01',
            'mobile' => '0100000000',
            'full_mobile' => '00000000',
            'password' => 'super_admin',
            'is_signed_up' => false
        ]);
        $user->assignRole(['superAdmin', 'Admin', 'Academy', 'Customer']);
        $company = Company::create([
            'user_id' => 1,
            'currency_id' => 1,
            'name' => 'Company'
        ]);
        Customer::create(['user_id' => $user->id, 'company_id' => 1]);
    }
}
