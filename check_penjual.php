<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Cek Auth::id() behavior dengan custom PK id_user
echo "=== AUTH::ID() TEST ===\n";
$user = App\Models\User::find(5); // Farhan penjual
if ($user) {
    echo "User PK (primaryKey): " . $user->getKeyName() . "\n";
    echo "User Key Value (getKey): " . $user->getKey() . "\n";
    echo "user->id: " . ($user->id ?? 'null (not available)') . "\n";
    echo "user->id_user: " . $user->id_user . "\n";
}

// Cek category status values
echo "\n=== CATEGORY STATUS VALUES ===\n";
$cats = DB::table('categories')->select('id_category', 'name', 'status')->limit(5)->get();
foreach ($cats as $c) {
    echo "id:{$c->id_category} name:{$c->name} status:{$c->status}\n";
}

// Cek notifications table structure  
echo "\n=== NOTIFICATIONS TABLE ===\n";
try {
    $cols = collect(DB::select('DESCRIBE notifications'))->pluck('Field');
    echo "Columns: " . $cols->join(', ') . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Cek Order model relations
echo "\n=== ORDER MODEL ===\n";
$orderModel = new App\Models\Order();
echo "Order PK: " . $orderModel->getKeyName() . "\n";
$orderItem = new App\Models\OrderItem();
echo "OrderItem PK: " . $orderItem->getKeyName() . "\n";

// Cek products table
echo "\n=== PRODUCTS TABLE ===\n";
$prodsCol = collect(DB::select('DESCRIBE products'))->pluck('Field');
echo "Columns: " . $prodsCol->join(', ') . "\n";
