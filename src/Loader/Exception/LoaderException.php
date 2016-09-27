<?php

namespace Eve\Loader\Exception;

use RuntimeException;

final class LoaderException extends RuntimeException
{
    /**
     * @return LoaderException
     */
    public static function create(): LoaderException
    {
        return new self('There is no data loader set.');
    }
}
