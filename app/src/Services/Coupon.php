<?php

namespace App\Services;

class Coupon
{

    /**
     * @param int $discount
     * @param bool $fix
     */
    public function __construct(
        private readonly string $name,
        private readonly int $discount,
        private readonly bool $fix = false,
    )
    {
    }


    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return int
     */
    public function getDiscount(): int
    {
        return $this->discount;
    }

    /**
     * @return bool
     */
    public function getFix(): bool
    {
        return $this->fix;
    }

}