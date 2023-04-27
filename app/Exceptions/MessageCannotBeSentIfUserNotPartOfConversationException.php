<?php

namespace App\Exceptions;

use RuntimeException;

class MessageCannotBeSentIfUserNotPartOfConversationException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('You can only send Messages to your Zbros!');
    }
}
