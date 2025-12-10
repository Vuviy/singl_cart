<?php

declare(strict_types=1);

use App\DTO\CartItem;
use App\Services\Cart;
use App\Services\Coupon;
use App\Services\SessionCartStorage;

require __DIR__ . '/functions/functions.php';
require __DIR__ . '/vendor/autoload.php';



$cart  = Cart::getInstance();


$cartItem = new CartItem('111', 'name', 5000);
$cartItem2 = new CartItem('222', 'name2', 6000);

$cart->addItem($cartItem);
$cart->addItem($cartItem2);
$cart->addItem($cartItem2);
$cart->addItem($cartItem2);

//$cart->removeItem('222');

$cart->decrementCountOfProducts('222');
//$cart->decrementCountOfProducts('111');

$cart->setTax(15);
$cart->setCostShipping(200);

$coupon = new Coupon('fff', 5);
$coupon2 = new Coupon('fff', 5);

$cart->setCoupon($coupon);
$cart->setCoupon($coupon2);

$cart->updateTotal();
$cart->updateSubtotal();

$cartStorage = new SessionCartStorage();

$cartStorage->save($cart);


$cartStorage->clear();

$cart2 = $cartStorage->load();

//$cart2->clear();

dd($cart2);



//
//
//$product1  = new Product( '111',  500, 'item1');
//$product2  = new Product('111',   500, 'item1');
//$product3  = new Product('333',   700, 'item3');
//
//
//
//$cart->addProduct($product1);
//$cart->addProduct($product2);
//$cart->addProduct($product3);
//
//
//$cart->setTax(2);
//
//$cart->setCostShipping(100);
//
//
//$coupon = new Coupon('sraka', 10);
//$coupon3 = new Coupon('sraka3', 1000, true);
//
//
//$cart->setCoupon($coupon);
//$cart->setCoupon($coupon3);
//
//$cart->updateSubtotal();
//$cart->updateTotal();
//
//dd($cart->getProducts(), $cart->getCountProducts(), $cart->getTotal(), $cart->getSubtotal(), $cart->getCoupons());


