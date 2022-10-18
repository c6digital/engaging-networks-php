<?php

namespace C6Digital\EngagingNetworks\Exceptions;

use Exception;

final class AuthenticationException extends Exception
{
    public static function make(string $message): self
    {
        return new AuthenticationException('Failed to authenticate. [Message] ' . $message);
    }
}