<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$compiler = app('blade.compiler');
$path = __DIR__.'/resources/views/admin/services/create.blade.php';
$content = file_get_contents($path);
$compiled = $compiler->compileString($content);

file_put_contents('compiled_create.php', $compiled);
echo "Compiled successfully.\n";
