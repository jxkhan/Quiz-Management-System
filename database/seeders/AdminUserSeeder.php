<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if roles already exist before creating them
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']);
        
        // Create permissions if they don't already exist
        $permissions = ['createQuizzes', 'manageQuizzes', 'attemptQuizzes', 'scheduleQuizzes', 'gradeQuizzes', 'assignQuiz', 'addStudents', 'assignManager'];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(['createQuizzes', 'addStudents', 'assignManager']);
        $managerRole->givePermissionTo(['manageQuizzes', 'gradeQuizzes', 'assignQuiz', 'scheduleQuizzes']);
        $studentRole->givePermissionTo(['attemptQuizzes']);
        $supervisorRole->givePermissionTo(['manageQuizzes', 'gradeQuizzes', 'assignQuiz', 'scheduleQuizzes']);

        // Check if the admin user already exists before creating
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('pass1234'),
            ]
        );

        // Assign the admin role to the admin user if it's not already assigned
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole('admin');
        }
    }
}
