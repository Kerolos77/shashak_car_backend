<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\FreightVehicle;
use App\Models\VehicleType;

try {
    echo "--- Services List ---\n";
    $services = Service::all();
    foreach ($services as $service) {
        echo "ID: {$service->id} | Title: {$service->title} | Type: {$service->service_type} | Enable: " . ($service->enable ? 'Yes' : 'No') . " | Intercity: " . ($service->intercity_type ? 'Yes' : 'No') . "\n";
    }

    echo "\n--- Freight Vehicles List ---\n";
    $freights = FreightVehicle::all();
    foreach ($freights as $freight) {
        echo "ID: {$freight->id} | Name: {$freight->name} | Description: {$freight->description} | Charge/km: {$freight->km_charge} | Size (LxWxH): {$freight->length}x{$freight->width}x{$freight->height}\n";
    }

    echo "\n--- Vehicle Types List ---\n";
    $vtypes = VehicleType::all();
    foreach ($vtypes as $vt) {
        echo "ID: {$vt->id} | Name: {$vt->name} | Enabled: " . ($vt->enable ? 'Yes' : 'No') . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
