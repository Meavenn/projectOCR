<?php


namespace App\router;

use App\Http\Request;
//use App\controller\HomeController;

require_once 'Route.php';
//require_once '../src/controller/HomeController.php';
//require_once '../src/controller/UserController.php';
//require_once '../src/Http/Request.php';


class Router
{
    public $url;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct($url)
    {
        $this->url = $url;
        $this->request = new Request();
    }


    public function get($path, $callable, $name = null){
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null){
        return $this->add($path, $callable, $name, 'POST');
    }


    public function add($path, $callable, $name, $method){
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if(is_string($callable) && $name = null){
            $name = $callable;
        }

        if($name){
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function run()
    {
    //echo '<pre>';var_dump($this->request);echo '</pre>';

        if(!isset($this->routes[$this->request->server['REQUEST_METHOD']])){
            throw new \Exception('REQUEST_METHOD doesn\'t exist');
        }

        foreach($this->routes[$this->request->server['REQUEST_METHOD']] as $route)
        {
            if($route->match($this->url))
            {
                return $route->call();
            }
        }
        throw new \Exception('No matching routes');
    }

    public function url ($name, $params = [])
    {
        if(!isset($this->namedRoutes[$name]))
        {
            throw new \Exception('No routes matches this name.');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

}