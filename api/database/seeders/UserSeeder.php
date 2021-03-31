<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Http;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstWhere('name', 'admin');
        $employeeRole = Role::firstWhere('name', 'employee');

        // Create admin user
        $admin = User::factory()->create([
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'email' => 'admin@email.com',
            'email_verified_at' => Carbon::now()
        ]);
        $admin->assignRole($adminRole);

        // Create dummy employees
        $employees = User::factory()->count(100)->create();

        foreach($employees as $employee) {
            $employee->assignRole($employeeRole);
        }

        // More dummy employees
        $response = Http::get('https://jsonplaceholder.typicode.com/users')->json();

        foreach($response as $data) {
            $name = explode(' ',trim($data['name']));
            $firstName = $name[0];
            $lastName = $name[1];

            $employee = User::factory()->create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'email_verified_at' => Carbon::now()
            ]);
        }
    }
}
