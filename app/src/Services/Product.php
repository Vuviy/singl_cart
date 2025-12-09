<?php

namespace App\Services;

class Product
{

    public function __construct(
        private readonly string $id,
        private readonly int $price,
        private readonly string $name,
        private readonly int $quantity,
    )
    {}


    public function getId(): string
    {
        return $this->id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getQuantity(): int
    {
        return $this->quantity;
    }
}