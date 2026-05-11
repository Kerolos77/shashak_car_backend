<?php

use App\Models\User;
use App\Models\Role;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$roles = Role::all();
echo "Roles in DB:\n";
foreach ($roles as $role) {
    echo "- ID: {$role->id}, Title: {$role->title}\n";
}

$drivers = User::whereHas('roles', function($q) {
    $q->where('title', 'Driver');
})->count();

echo "\nDrivers count by role: $drivers\n";

$usersWithProfile = User::has('profile')->count();
echo "Users with DriverProfile: $usersWithProfile\n";
