<?php

namespace App\Repositories;

use App\Services\Product;
use App\Services\Storage;

readonly class ProductRepository
{
    public function __construct( private Storage $storage)
    {
    }

    public function findById(string $id): ?Product
    {
        $data =  $this->storage->findById($id);

        if(1 <= count($data)){
            return new Product('222', 3,'test',6);
        }
        return null;
    }
}