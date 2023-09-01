<?php

namespace Database\Seeders;

use App\Models\MerchSale;
use DateInterval;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchSalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $namePrefix = 'RandomMerchSale';
        for ($i = 1000; $i > 0; $i--) {
            $userId = rand(1, 10);

            $now = new DateTime();
            $intervalSpec = 'P' . rand(1, 91) . 'D';


            $merchSale = new MerchSale();
            $merchSale->user_id = $userId;
            $merchSale->name = $namePrefix . $i;
            $merchSale->amount = rand(1, 9);
            $merchSale->price = rand(1, 9);
            $merchSale->created_at = $now->sub(new DateInterval($intervalSpec));
            $merchSale->save();
        }
    }
}
