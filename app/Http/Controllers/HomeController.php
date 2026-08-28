<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        $routes = Route::getRoutes();
        $apisData = [];
        $id = 1;

        foreach ($routes as $route) {
            $uri = $route->uri();
            $isApiRoute = in_array('api', $route->middleware())
                || str_starts_with($uri, 'api')
                || str_starts_with($uri, 'delivery')
                || str_starts_with($uri, 'rider');

            if ($isApiRoute) {
                $method = $route->methods()[0];
                if ($method === 'HEAD' && isset($route->methods()[1])) {
                    $method = $route->methods()[1];
                }

                $defaultBody = in_array($method, ['POST', 'PUT', 'PATCH']) ? new \stdClass() : null;
                $routeGroup = str_starts_with($uri, 'delivery') || str_starts_with($uri, 'rider')
                    ? 'delivery.php'
                    : 'api.php';

                $apisData[] = [
                    'id' => $id++,
                    'name' => '/' . $uri,
                    'method' => $method,
                    'endpoint' => url($uri),
                    'status' => 'active',
                    'auth' => in_array('auth:api', $route->middleware()) ? 'Bearer Token (auth:api)' : 'None',
                    'description' => 'Dynamic API endpoint mapped from ' . $routeGroup . ' for ' . $uri,
                    'headers' => ['Accept' => 'application/json'],
                    'requestBody' => $defaultBody,
                    'responseExample' => null,
                    'queryParams' => null,
                    'group' => $routeGroup,
                ];
            }
        }

        return view('home', compact('apisData'));
    }
}
