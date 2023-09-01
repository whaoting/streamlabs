<?php

namespace Database\Seeders;

use App\Models\Follower;
use DateInterval;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowersTableSeeder extends Seeder
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
                $intervalSpec = 'P' . $i*10 . 'D';

                $follower = new Follower;
                $follower->user_id = $i;
                $follower->target_user_id = $j;
                $follower->created_at = $now->sub(new DateInterval($intervalSpec));
                $follower->save();
            }
        }
    }
}
