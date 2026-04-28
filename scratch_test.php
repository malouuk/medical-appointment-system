<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/appointments', 'GET');
$user = \App\Models\User::where('email', 'patient@mediluxe.com')->first();
$app->make('auth')->login($user);
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
if ($response->getStatusCode() === 500) {
    echo "Exception: " . $response->exception->getMessage() . "\n";
} else {
    echo "Content length: " . strlen($response->getContent()) . "\n";
}
