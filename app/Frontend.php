<?php
namespace forteroche\app;

use framework\Application;
use framework\HTTPRequest;
use framework\HTTPResponse;
use framework\Router;
use framework\Route;


class Frontend extends Application
{
    protected $httpRequest;
    protected $httpResponse;
    protected $name;

    public function __construct()
    {
        $this->httpRequest = new HTTPRequest($this);
        $this->httpResponse = new HTTPResponse($this);
        $this->name = 'Frontend';
    }

    public function run()
    {
        $controller = $this->getController();
        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }

    public function getController()
    {
        $router = new Router;
    
        $xml = new \DOMDocument;
        $xml->load(__DIR__.'/../config/routes.xml');

        $routes = $xml->getElementsByTagName('route');
        foreach ($routes as $route ) {
            $vars = [];
            // On regarde si des variables sont présentes dans l'URL.
            if ($route->hasAttribute('vars'))
            {
                $vars = explode(',', $route->getAttribute('vars'));
            }
            // On ajoute la route au routeur.
            $router->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action'), $vars));
        }

        try
        {
            // On récupère la route correspondante à l'URL.
            $matchedRoute = $router->getRoute($this->httpRequest->requestURI());
        }
        catch (\RuntimeException $e)
        {
            if ($e->getCode() == Router::NO_ROUTE)
            {
                // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
                $this->httpResponse->redirect404();
            }
        }

        // On ajoute les variables de l'URL au tableau $_GET.
        $_GET = array_merge($_GET, $matchedRoute->vars());

        $module = $matchedRoute->module();
        $action = $matchedRoute->action();
        // On instancie le contrôleur.
        $controllerClass = 'forteroche\\app\\modules\\'.$module.'\\'.ucfirst($module).'Controller';
        return new $controllerClass($this, $module, $action);
    }
    
    public function httpRequest()
    {
        return $this->httpRequest;
    }

    public function httpResponse()
    {
        return $this->httpResponse;
    }
}
