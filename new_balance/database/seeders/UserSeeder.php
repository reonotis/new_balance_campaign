<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '藤澤怜臣',
                'email' => 'fujisawa@reonotis.jp',
                'password' => Hash::make('reonotis'),
            ],[
                'name' => '平瀬 尚久',
                'email' => 'hirase@fluss.co.jp',
                'password' => Hash::make('fluss'),
            ]
        ]);
    }
}
