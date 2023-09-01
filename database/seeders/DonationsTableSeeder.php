<?php

namespace Database\Seeders;

use App\Models\Donation;
use DateInterval;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $msgPrefix = 'RandomDonation';
        for ($i = 1000; $i > 0; $i--) {
            $userId = rand(1, 10);
            $targetId = rand(1, 10);
            if ($userId === $targetId) {
                continue;
            }

            $now = new DateTime();
            $intervalSpec = 'P' . rand(1, 91) . 'D';

            $donation = new Donation;
            $donation->user_id = $userId;
            $donation->target_user_id = $targetId;
            $donation->amount = rand(1, 9);
            $donation->currency = "CAD";
            $donation->message = $msgPrefix . $i;
            $donation->created_at = $now->sub(new DateInterval($intervalSpec));
            $donation->save();
        }
    }
}
