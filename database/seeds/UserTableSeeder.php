<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * Пароль должен храниться в хэше bcrypt - это встроенная функция лары
     *
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'vadim@mail.com',
            'password' => '$2b$10$Rm2yjADxt2WJPwWGkYqCG.t35x1wY/7ok/p2/L4KTdJ/jOwiR51Ra',
            'first_name' => 'Вадим',
            'second_name' => 'Зайчиков',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
