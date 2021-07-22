<?php

use Illuminate\Database\Seeder;
use App\User;
class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // $role=['ROLE_MANAGEMENT_ADMIN'];
       // $role=['ROLE_SALES_MANAGER'];
        $role=['ROLE_SALES'];
       // $role=['ROLE_FINANCE_MANAGER'];
        //$role=['ROLE_FINANCE'];
        //$role=['ROLE_OPERATION_MANAGER'];        
       // $role=['ROLE_OPERATION'];
        //$role=['ROLE_TECHNICAL_MANAGER'];
        //$role=['ROLE_TECHNICAL'];
        User::create([
            'name'              =>'Ramy Al Quthamy',
            'email'             =>'r.alquthamy@dbroker.com.sa',
            'password'          =>Hash::make('12345678'),
            'remember_token'    =>str_random(10),
            'roles'             => $role,
            'user_type'         =>'user'
            ]);

        // User::create([
        //     'name'              =>'Rawabi Alharbi',
        //     'email'             =>null,
        //     'password'          =>null,
        //     'remember_token'    =>str_random(10),
        //     'roles'             => null,
        //     'user_type'  =>'agent'
        //     ]);
    }
}
