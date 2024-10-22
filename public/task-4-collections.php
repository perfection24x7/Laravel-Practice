<?php

use Illuminate\Support\Collection;

// Load Laravel
require __DIR__ . '/../vendor/autoload.php'; // Adjust the path to autoload.php

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php'; // Adjust the path to app.php
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$employees = collect([
    ['name' => 'John', 'city' => 'Dallas'],
    ['name' => 'Jane', 'city' => 'Austin'],
    ['name' => 'Jake', 'city' => 'Dallas'],
    ['name' => 'Jill', 'city' => 'Dallas'],
]);

$offices = collect([
    ['office' => 'Dallas HQ', 'city' => 'Dallas'],
    ['office' => 'Dallas South', 'city' => 'Dallas'],
    ['office' => 'Austin Branch', 'city' => 'Austin'],
]);

$output = $offices->groupBy('city')->map(function ($officesGroup, $city) use ($employees) {
    return $officesGroup->pluck('office')->mapWithKeys(function ($office) use ($employees, $city) {
        return [$office => $employees->where('city', $city)->pluck('name')->toArray()];
    });
});

$output = $output->toArray();

echo '<pre>';
print_r($output);
echo '</pre>';