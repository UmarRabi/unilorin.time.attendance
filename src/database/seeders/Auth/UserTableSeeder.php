<?php

namespace Database\Seeders\Auth;

use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        // User::query()->create([
        //     'first_name' => 'John',
        //     'last_name' => 'Doe',
        //     'email' => 'admin@demo.com',
        //     'password' => Hash::make('123456'),
        //     'status_id' => Status::findByNameAndType('status_active')->id,
        //     'is_in_employee' => 1,
        // ]);
        User::query()->updateOrCreate(
            [
                'email' => 'eabdulwahab@unilorin.edu.ng',
            ],
            [

            'first_name' => 'Wahab Olasupo',
            'last_name' => 'Egbewole',
            'password' => Hash::make('Pa22w0rd@1234'),
            'status_id' => Status::findByNameAndType('status_active')->id,
            'is_in_employee' => 1,
        ]);
        User::query()->updateOrCreate(
            [
                'email' => 'jimoh_rasheed@unilorin.edu.ng',
            ],
            [
            'first_name' => 'Jimoh Gbenga',
            'last_name' => 'Rasheed',
            'password' => Hash::make('Raslinktime2020'),
            'status_id' => Status::findByNameAndType('status_active')->id,
            'is_in_employee' => 1,
        ]);

        $this->enableForeignKeys();
    }
}
