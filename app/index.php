<?php

declare(strict_types=1);

use App\Services\Cart;
use App\Services\Coupon;
use App\Services\Product;

require __DIR__ . '/functions/functions.php';
require __DIR__ . '/vendor/autoload.php';
//require __DIR__ . '/src/bootstrap.php';
//require __DIR__ . '/routes/web.php';


$cart  = Cart::getInstance();


$product1  = new Product( '111',  500, 'item1');
$product2  = new Product('111',   500, 'item1');
$product3  = new Product('333',   700, 'item3');



$cart->addProduct($product1);
$cart->addProduct($product2);
$cart->addProduct($product3);


$cart->setTax(2);

$cart->setCostShipping(100);


$coupon = new Coupon('sraka', 10);
$coupon3 = new Coupon('sraka3', 1000, true);


$cart->setCoupon($coupon);
$cart->setCoupon($coupon3);

$cart->updateSubtotal();
$cart->updateTotal();

dd($cart->getProducts(), $cart->getCountProducts(), $cart->getTotal(), $cart->getSubtotal(), $cart->getCoupons());


