<?php

namespace App\Exceptions;

use Exception;

class PacktApiException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        \Log::error($this->getMessage());
    }
}
