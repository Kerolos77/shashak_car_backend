<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SavedCard;
use App\Models\User;

try {
    echo "Saved cards total count: " . SavedCard::count() . "\n";
    $cards = SavedCard::all();
    foreach ($cards as $card) {
        echo "ID: {$card->id}, UserID: {$card->user_id}, Masked: {$card->masked_pan}\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
