<?php

namespace App\Exceptions;

class FavoriteListNotFoundException extends ResourceNotFoundException
{
    public function __construct(string $identifier = "")
    {
        parent::__construct("Favorite list", $identifier);
    }
}
