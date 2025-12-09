<?php

namespace App\Services;

use App\Core\Singleton;
use App\Repositories\ProductRepository;

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
     * @var Product[]
     */
    private array $products = [];

    /**
     * @var array
     */
    private array $countProducts = [];

    /**
     * @var Coupon[]
     */
    private array $coupons = [];

    /**
     * @param Product $product
     * @return void
     */
    public function addProduct(Product $product): void
    {
        if ($this->checkExistingProduct($product)) {
            if (array_key_exists($product->getId(), $this->products)) {
                $this->countProducts[$product->getId()] += 1;
                $this->updateSubtotal();
                $this->updateTotal();
            } else {
                $this->products[$product->getId()] = $product;
                $this->countProducts[$product->getId()] = 1;
                $this->updateSubtotal();
                $this->updateTotal();
            }
        }
    }

    private function checkExistingProduct(Product $product): bool
    {
//        переробити по нормальному
        $storage = new Storage();
        $repo = new ProductRepository($storage);
//        переробити по нормальному
        $product = $repo->findById($product->getId());

        if (null === $product || $product->getQuantity() <= 0) {
            return false;
        }
        return true;
    }

    /**
     * @param string $productId
     * @return void
     */
    public function removeProduct(string $productId): void
    {
        unset($this->products[$productId]);
        unset($this->countProducts[$productId]);
    }

    /**
     * @param string $productId
     * @return void
     */
    public function decrementCountOfProducts(string $productId): void
    {
        if (1 === $this->countProducts[$productId]) {
            $this->removeProduct($productId);
        } else {
            $this->countProducts[$productId]--;
        }
    }

    /**
     * @return Product[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }


    /**
     * @return array
     */
    public function getCountProducts(): array
    {
        return $this->countProducts;
    }

    /**
     * @param string $productId
     * @return int
     */
    public function getCountOfProduct(string $productId): int
    {
        return $this->countProducts[$productId];
    }

    /**
     * @return void
     */
    public function updateSubtotal(): void
    {
        $this->subtotal = 0;
        foreach ($this->products as $productId => $product) {
            $this->subtotal += $this->getCountOfProduct($productId) * $product->getPrice();
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
        foreach ($this->products as $productId => $product) {
            $price = $product->getPrice();
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