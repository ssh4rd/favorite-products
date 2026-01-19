<?php

namespace App\Services;

class ProductService
{
    public function getProductBySku(string $sku): array
    {
        // Deterministic mock data based on SKU
        $hash = crc32($sku);
        $names = ['Laptop', 'Phone', 'Tablet', 'Headphones', 'Mouse', 'Keyboard', 'Monitor', 'Printer', 'Router', 'Webcam'];
        $categories = ['Electronics', 'Accessories', 'Computers', 'Audio', 'Video'];

        return [
            'sku' => $sku,
            'name' => $names[$hash % count($names)] . ' ' . substr($sku, 0, 3),
            'price' => number_format(($hash % 1000) + 10, 2),
            'category' => $categories[$hash % count($categories)],
            'description' => 'Mock product description for SKU ' . $sku,
            'in_stock' => ($hash % 2) === 0,
        ];
    }
}
