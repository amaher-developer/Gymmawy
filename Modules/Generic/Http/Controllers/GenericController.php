<?php

namespace Modules\Generic\Http\Controllers;
use App\Http\Controllers\Controller;

class GenericController extends Controller
{
    /**
     * The middleware registered on the controller.
     */
    protected $middleware = [];

    public function __construct()
    {

    }
    
    /**
     * Register middleware on the controller (Laravel 12 compatibility).
     */
    public function middleware($middleware, array $options = [])
    {
        foreach ((array) $middleware as $m) {
            $this->middleware[] = [
                'middleware' => $m,
                'options' => &$options,
            ];
        }

        return new \Illuminate\Routing\ControllerMiddlewareOptions($options);
    }
    
    /**
     * Get the middleware assigned to the controller.
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

}
