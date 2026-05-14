<?php
use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$purchase = \Illuminate\Support\Facades\DB::table('driver_purchases')->latest()->first();
echo "Last Purchase in DB: " . json_encode($purchase) . "\n";

if ($purchase) {
    $user = \App\Models\User::find($purchase->driver_id);
    echo "User ID: " . $user->id . " ({$user->name})\n";
    echo "Active Purchase Attribute: " . ($user->active_purchase ? "ID: " . $user->active_purchase->id : 'NULL') . "\n";
    echo "Active Package Attribute: " . ($user->active_package ? "Name: " . $user->active_package->name : 'NULL') . "\n";
    echo "Now: " . now()->toDateTimeString() . "\n";
    
    if (!$user->active_purchase) {
        $allPurchases = \App\Models\Purchase::where('driver_id', $user->id)->get();
        echo "All Purchases for this user: " . $allPurchases->count() . "\n";
        foreach($allPurchases as $p) {
            echo "Purchase ID: {$p->id}, Expires: {$p->expires_at}, Is Past? " . ($p->expires_at->isPast() ? 'YES' : 'NO') . "\n";
        }
    }
}
