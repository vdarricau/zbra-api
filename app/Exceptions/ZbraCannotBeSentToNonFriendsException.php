<?php

namespace App\Exceptions;

use RuntimeException;

class ZbraCannotBeSentToNonFriendsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('You can only send Zbras to your Zbros!');
    }
}
