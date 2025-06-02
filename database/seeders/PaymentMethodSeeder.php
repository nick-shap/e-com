<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_methods')->insert(
            [
                [
                    'name' => 'Кредитная карта',
                    'description' => 'Оплата картой',
                    'slug' => 'credit_card',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'PayPal',
                    'description' => 'Оплата PayPal',
                    'slug' => 'paypal',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'СБП (Сбер)',
                    'description' => 'Оплата СБП',
                    'slug' => 'sbp',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
