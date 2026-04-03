<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cards = \App\Models\SavedCard::all();
echo "Total cards: " . $cards->count() . "\n";
foreach ($cards as $c) {
    echo "ID: {$c->id}, UserID: {$c->user_id}, Email: {$c->card_holder_name}, Default: {$c->is_default}\n";
}

$user = \App\Models\User::find(54);
if ($user) {
    echo "\nUser 54: name={$user->name}, email={$user->email}\n";
}
