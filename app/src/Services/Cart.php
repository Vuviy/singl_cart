<?php

namespace App\Services;

use App\Core\Singleton;
use App\DTO\CartItem;
use App\Repositories\ProductRepository;
use RuntimeException;

class Cart extends Singleton
{

    /**
     * @var int
     */
    private int $subtotal = 0;

    /**
     * @var int
     */
    private int $total = 0;

    /**
     * @var int
     */
    private int $cost_shipping = 0;

    /**
     * @var int
     */
    private int $tax = 0;

    /**
     * @var CartItem[]
     */
    private array $items = [];

    /**
     * @var Coupon[]
     */
    private array $coupons = [];

    /**
     * @param CartItem $cartItem
     * @return void
     */
    public function addItem(CartItem $cartItem): void
    {

        $this->checkExistingProduct($cartItem->productId);

        if (array_key_exists($cartItem->productId, $this->items)) {
            $this->items[$cartItem->productId]->quantity += $cartItem->quantity;
            $this->updateSubtotal();
            $this->updateTotal();
        } else {
            $this->items[$cartItem->productId] = $cartItem;
            $this->updateSubtotal();
            $this->updateTotal();
        }
    }

    private function checkExistingProduct(string $productId): void
    {
//        переробити по нормальному ак не має бути
        $storage = new Storage();
        $repo = new ProductRepository($storage);
//        переробити по нормальному
        $product = $repo->findById($productId);


        if (null === $product) {
            throw new RuntimeException("No product");
        }

        if ($product->getQuantity() === 0) {
            throw new RuntimeException("Not enough stock");
        }
    }

    /**
     * @param string $productId
     * @return void
     */
    public function removeItem(string $productId): void
    {
        unset($this->items[$productId]);
    }


    public function clear()
    {
        $this->items = [];
        $this->subtotal = 0;
        $this->total = 0;
        $this->cost_shipping = 0;
        $this->tax = 0;
        $this->coupons = [];

    }

    /**
     * @param string $productId
     * @return void
     */
    public function decrementCountOfProducts(string $productId): void
    {
        if (1 === $this->items[$productId]->quantity) {
            $this->removeItem($productId);
        } else {
            $this->items[$productId]->quantity -= 1;
        }
    }

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }


    /**
     * @return int
     */
    public function getCountItems(): int
    {
        $count = 0;
        foreach ($this->items as $item) {
            $count += $item->quantity;
        }
        return $count;
    }

    /**
     * @param string $productId
     * @return int
     */
    public function getCountOfProduct(string $productId): int
    {
        return $this->items[$productId]->quantity;
    }

    /**
     * @return void
     */
    public function updateSubtotal(): void
    {
        $this->subtotal = 0;
        foreach ($this->items as $productId => $cartItem) {
            $this->subtotal += $this->getCountOfProduct($productId) * $cartItem->price;
        }
    }

    /**
     * @return int
     */
    public function getSubtotal(): int
    {
        return $this->subtotal;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return void
     */
    public function updateTotal(): void
    {
        $this->total = 0;
        foreach ($this->items as $productId => $cartItem) {
            $price = $cartItem->price;
            $quantity = $this->getCountOfProduct($productId);

            $taxAmount = intdiv($price * $this->tax, 100);

            $lineTotal = ($price + $taxAmount) * $quantity;

            $this->total += $lineTotal;
        }
        $this->useCoupon();
        $this->total += $this->getCostShipping();


    }

    /**
     * @return void
     */
    private function useCoupon(): void
    {
        foreach ($this->coupons as $coupon) {
            if (false === $coupon->getFix()) {
                $discount = intdiv($this->total * $coupon->getDiscount(), 100);
                $this->total -= $discount;
            } else {
                $this->total -= $coupon->getDiscount();
            }
        }
    }

    /**
     * @param int $tax
     * @return void
     */
    public function setTax(int $tax): void
    {
        $this->tax = $tax;
    }

    /**
     * @return int
     */
    public function getTax(): int
    {
        return $this->tax;
    }

    /**
     * @return int
     */
    public function getCostShipping(): int
    {
        return $this->cost_shipping;
    }

    /**
     * @param int $cost_shipping
     * @return void
     */
    public function setCostShipping(int $cost_shipping): void
    {
        $this->cost_shipping = $cost_shipping;
    }

    /**
     * @param Coupon $coupon
     * @return void
     */
    public function setCoupon(Coupon $coupon): void
    {
        if (false === array_key_exists($coupon->getName(), $this->coupons)) {
            $this->coupons[$coupon->getName()] = $coupon;
        }
    }

    /**
     * @return Coupon[]
     */
    public function getCoupons(): array
    {
        return $this->coupons;
    }
}