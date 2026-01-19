<?php

namespace App\Exceptions;

class ProductNotFoundException extends ResourceNotFoundException
{
    public function __construct(string $identifier = "")
    {
        parent::__construct("Product not found in favorite list", $identifier);
    }
}
