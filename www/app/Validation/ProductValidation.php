<?php

namespace App\Validation;

use Src\Database\Database;

class ProductValidation
{
    public static function checkProductArray(?array $items)
    {
        if (!isset($items) || empty($items)) {
            return ['result' => false, 'message' => '"items" array must contain at least one element'];
        }
        
        $itemsCount = count($items);
        if ($itemsCount > 5000) {
            return ['result' => false, 'message' => '"items" array cannot contain more than 5000 elements'];
        }
        
        foreach ($items as $item) {
            if (!is_int($item)) {
                return ['result' => false, 'message' => 'all elements of the "items" array must be integer values'];
            }
        }

        $db = new Database();

        $clause = implode(',', array_fill(0, $itemsCount, '?'));

        $existingProducts = $db->preparedQuery("SELECT product_id FROM products WHERE product_id IN ($clause)", $items);

        $nonExistentProducts = array_diff($items, array_column($existingProducts, 'product_id'));

        if (!empty($nonExistentProducts)) {
            return ['result' => false, 'message' => 'following products does not exist: ' . implode(',', $nonExistentProducts)];
        }

        return ['result' => 'success'];
    }
}
