<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payments = [[
            'id'         => 1,
            'name'      => 'Paypal',
            'status'	 => 1,
            'created_at' => '2020-11-10 19:13:32',
            'updated_at' => '2020-11-10 19:13:32',
            'deleted_at' => null,
        ], 
		[
            'id'         => 2,
            'name'      => 'Stripe',
            'status'	 => 1,
            'created_at' => '2020-11-10 19:13:32',
            'updated_at' => '2020-11-10 19:13:32',
            'deleted_at' => null,
        ]
		];

        foreach ($payments as $payment) {
            PaymentMethod::updateOrCreate(['id' => $payment['id']], $payment);
        }
    }
}
