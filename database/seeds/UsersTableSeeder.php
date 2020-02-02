<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!(DB::table('users')->count() > 0)){
            DB::table('users')->insert([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'phone' => '01234567891',
                'password' => bcrypt('test1234'),

                'activated' => 1,
                'role_id' => 1,
                'department_id' => 1,
                'remember_token' => str_random(10),
            ]);

            //Department 1
            DB::table('users')->insert([
                'name' => 'Supervisor1',
                'email' => 'supervisor1@gmail.com',
                'phone' => '01234567891',
                'password' => bcrypt('test1234'),

                'activated' => 1,
                'role_id' => 2,
                'department_id' => 1,
                'supervisor_id' => 1,
                'remember_token' => str_random(10),
            ]);

            //Department 2
            DB::table('users')->insert([
                'name' => 'Supervisor2',
                'email' => 'supervisor2@gmail.com',
                'phone' => '01234567891',
                'password' => bcrypt('test1234'),

                'activated' => 1,
                'role_id' => 2,
                'department_id' => 2,
                'supervisor_id' => 1,
                'remember_token' => str_random(10),
            ]);


            DB::table('users')->insert([
                'name' => 'Field Stuff 1',
                'email' => 'fs1@gmail.com',
                'phone' => '01234567891',
                'password' => bcrypt('test1234'),

                'activated' => 1,
                'role_id' => 3,
                'department_id' => 1,
                'supervisor_id' => 2,
                'remember_token' => str_random(10),
            ]);
            DB::table('users')->insert([
                'name' => 'Field Stuff 2',
                'email' => 'fs2@gmail.com',
                'phone' => '01234567891',
                'password' => bcrypt('test1234'),

                'activated' => 1,
                'role_id' => 3,
                'department_id' => 1,
                'supervisor_id' => 2,
                'remember_token' => str_random(10),
            ]);


            DB::table('users')->insert([
                'name' => 'Field Stuff 3',
                'email' => 'fs3@gmail.com',
                'phone' => '01234567891',
                'password' => bcrypt('test1234'),

                'activated' => 1,
                'role_id' => 3,
                'department_id' => 2,
                'supervisor_id' => 3,
                'remember_token' => str_random(10),
            ]);
            DB::table('users')->insert([
                'name' => 'Field Stuff 4',
                'email' => 'fs4@gmail.com',
                'phone' => '01234567891',
                'password' => bcrypt('test1234'),

                'activated' => 1,
                'role_id' => 3,
                'department_id' => 2,
                'supervisor_id' => 3,
                'remember_token' => str_random(10),
            ]);

            $departments = \App\Department::all();
            foreach ($departments as $department){
                factory(App\User::class, random_int(3, 5))->make()->each(function ($user) use($department) {
                    $user->department_id = $department->id;
                    $user->supervisor_id = random_int(2,3);
                    $user->save();
                });
            }
        }
    }
}
