<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/api/v1/location/demand-map', 'GET', [
    'latitude' => 30.0444,
    'longitude' => 31.2357
]);

$controller = new \App\Http\Controllers\Api\V1\DemandMapController();
$response = $controller->getDemandMap($request);

echo $response->getContent();
echo "\n";
