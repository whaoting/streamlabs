<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use DateInterval;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscribersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 10; $i > 0; $i--) {
            for ($j = 10; $j > 0; $j--) {
                if ($i === $j) {
                    continue;
                }
                $now = new DateTime();
                $intervalSpec = 'P' . $i * 10 . 'D';

                $subscriber = new Subscriber;
                $subscriber->user_id = $i;
                $subscriber->target_user_id = $j;
                $subscriber->tier = rand(1, 3);
                $subscriber->created_at = $now->sub(new DateInterval($intervalSpec));
                $subscriber->save();
            }
        }
    }
}
