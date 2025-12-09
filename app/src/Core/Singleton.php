<?php

namespace App\Core;

abstract class Singleton
{
    private static ?Singleton $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}