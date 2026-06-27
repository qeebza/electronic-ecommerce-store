<?php

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function current_user_id(): ?int
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return empty($_SESSION['user_id']) ? null : (int) $_SESSION['user_id'];
}

function require_login(string $loginPath = '../auth/login.php'): int
{
    $userId = current_user_id();

    if (!$userId) {
        go_to($loginPath);
    }

    return $userId;
}

function get_cart(PDO $pdo): array
{
    $userId = require_login();
    $stmt = $pdo->prepare(
        'SELECT product_id, quantity
         FROM cart_items
         WHERE user_id = ?'
    );
    $stmt->execute([$userId]);

    return array_column($stmt->fetchAll(), 'quantity', 'product_id');
}

function set_cart_quantity(PDO $pdo, int $productId, int $quantity): void
{
    $userId = require_login();

    if ($quantity <= 0) {
        $stmt = $pdo->prepare(
            'DELETE FROM cart_items
             WHERE user_id = ? AND product_id = ?'
        );
        $stmt->execute([$userId, $productId]);
        return;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO cart_items (user_id, product_id, quantity)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE quantity = VALUES(quantity)'
    );
    $stmt->execute([$userId, $productId, $quantity]);
}

function get_cart_items(PDO $pdo): array
{
    $cart = get_cart($pdo);
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
            set_cart_quantity($pdo, $productId, 0);
            continue;
        }

        set_cart_quantity($pdo, $productId, $quantity);
        $product['quantity'] = $quantity;
        $product['subtotal'] = (float) $product['price'] * $quantity;
        $items[] = $product;
    }

    return $items;
}

function clear_cart(PDO $pdo): void
{
    $userId = require_login();
    $stmt = $pdo->prepare('DELETE FROM cart_items WHERE user_id = ?');
    $stmt->execute([$userId]);
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
