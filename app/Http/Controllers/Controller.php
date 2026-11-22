<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

abstract class Controller
{
    /**
     * Authorize a given action for the current user against policies/gates.
     */
    public function authorize($ability, $arguments = [])
    {
        return Gate::authorize($ability, $arguments);
    }
}
