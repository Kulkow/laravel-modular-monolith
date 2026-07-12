<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Routing\Router;

class RegisterApiRoutes
{
    use Dispatchable;

    public $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }
}
