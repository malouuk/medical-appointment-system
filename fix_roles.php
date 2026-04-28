<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Fix users with null role
$fixed = \App\Models\User::whereNull('role')->update(['role' => 'patient']);
echo "Fixed $fixed users with null role -> set to 'patient'\n";

// Show all users and their roles
$users = \App\Models\User::all(['id','name','email','role']);
foreach ($users as $u) {
    echo "  [{$u->id}] {$u->name} | {$u->email} | role={$u->role}\n";
}
