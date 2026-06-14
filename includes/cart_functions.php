<?php

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function get_cart(): array
{
    $cart = $_SESSION['cart'] ?? [];
    $result = [];

    if (!is_array($cart)) {
        return $result;
    }

    foreach ($cart as $productId => $quantity) {
        $productId = (int) $productId;
        $quantity = (int) $quantity;

        if ($productId > 0 && $quantity > 0) {
            $result[$productId] = $quantity;
        }
    }

    return $result;
}

function set_cart_quantity(int $productId, int $quantity): void
{
    $cart = get_cart();

    if ($quantity <= 0) {
        unset($cart[$productId]);
    } else {
        $cart[$productId] = $quantity;
    }

    $_SESSION['cart'] = $cart;
}

function get_cart_items(PDO $pdo): array
{
    $cart = get_cart();
    if (!$cart) {
        return [];
    }

    $productIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $stmt = $pdo->prepare(
        "SELECT product_id, name, price, stock
         FROM products
         WHERE product_id IN ($placeholders)"
    );
    $stmt->execute($productIds);
    $items = [];

    while ($product = $stmt->fetch()) {
        $productId = (int) $product['product_id'];
        $quantity = min($cart[$productId], (int) $product['stock']);

        if ($quantity < 1) {
            set_cart_quantity($productId, 0);
            continue;
        }

        set_cart_quantity($productId, $quantity);
        $product['quantity'] = $quantity;
        $product['subtotal'] = (float) $product['price'] * $quantity;
        $items[] = $product;
    }

    return $items;
}

function calculate_total(array $items): float
{
    return array_sum(array_column($items, 'subtotal'));
}

function money(float $amount): string
{
    return 'RM ' . number_format($amount, 2);
}

function set_message(string $message): void
{
    $_SESSION['cart_message'] = $message;
}

function get_message(): string
{
    $message = $_SESSION['cart_message'] ?? '';
    unset($_SESSION['cart_message']);
    return $message;
}

function go_to(string $location): never
{
    header('Location: ' . $location);
    exit;
}
