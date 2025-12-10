<?php

namespace App\Services;

class SessionCartStorage
{
    private string $key = 'cart';

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function load(): Cart
    {
        if (array_key_exists('cart', $_SESSION) && count($_SESSION[$this->key]) > 0) {
            return unserialize($_SESSION[$this->key]);
        }

        return Cart::getInstance();
    }

    public function save(Cart $cart): void
    {
        $_SESSION[$this->key] = serialize($cart);
    }

    public function clear(): void
    {
        $_SESSION[$this->key] = [];
//        отак напевно не можна робити але шо зробиш
        $cart = Cart::getInstance();
        $cart->clear();
//        отак напевно не можна робити але шо зробиш
    }

}