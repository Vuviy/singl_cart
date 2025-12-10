<?php

namespace App\DTO;

final class CartItem
{
    public function __construct(
        public string $productId,
        public string $name,
        public int $price,
        public int $quantity = 1
    ) {}
}

