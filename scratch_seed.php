<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'patient@mediluxe.com')->first();
if ($user) {
    \App\Models\Appointment::factory(5)->create(['user_id' => $user->id]);
    echo "Appointments assigned to patient.\n";
} else {
    echo "User not found.\n";
}
echo "Total appointments: " . \App\Models\Appointment::count() . "\n";
