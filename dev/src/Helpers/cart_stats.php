<?php
require_once(__DIR__ . '/../Database/Database.php');
require_once(__DIR__ . '/Auth.php'); 

function countItemsInCart(): int
{
    if (!isLoggedIn() || !is_numeric(user_id())) {
        return 0;
    }

    $query = "
        SELECT SUM(ci.amount) AS total_items
        FROM cart c
        LEFT JOIN cart_items ci ON c.id = ci.cart_id
        WHERE c.ordered = 0 AND c.customer_id = :id
        GROUP BY c.id
        LIMIT 1
    ";

    $success = Database::query($query, [':id' => user_id()]);
    
    if (!$success) {
        return 0;
    }

    $record = Database::get();

    return (!empty($record) && isset($record->total_items)) ? intval($record->total_items) : 0;
}
