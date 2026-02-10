<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidatePostSize as Middleware;

class ValidatePostSize extends Middleware
{
    /**
     * The names of the cookie attributes to encrypt.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
