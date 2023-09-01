<?php

namespace Database\Seeders;

use App\Models\User;
use DateInterval;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $userNamePrefix = 'RandomUser';
        $mailSuffix = "@gmail.com";
        $month = 3;
        for ($i = 1; $i <= 10; $i++) {
            $now = new DateTime();
            $intervalSpec = 'P' . $month . 'M' . $i . 'D';

            $user = new User;
            $user->name = $userNamePrefix . $i;
            $user->email = $userNamePrefix . $i . $mailSuffix;
            $user->created_at = $now->sub(new DateInterval($intervalSpec));
            $user->save();
        }
    }
}
