<?php

namespace FabtoszBlog\Utils;

use FabtoszBlog\Utils\DependencyInjector;
use FabtoszBlog\Controller\UserController;

class Router {
    private $di;
    private $routeMap;
    private static $regexPatters = [
        'number' => '\d+',
        'string' => '\w'
    ];

    public function __construct(DependencyInjector $di) {
        $this->di = $di;

        $json = file_get_contents(__DIR__ . '/../../config/routes.json');
        $this->routeMap = json_decode($json, true);
    }

	public function route(Request $request) {
        $path = $request->getPath();

        foreach ($this->routeMap as $route => $info) {
            $regexRoute = $this->getRegexRoute($route, $info);
            if (preg_match("@^/$regexRoute$@", $path)) {
                return $this->executeController($route, $path, $info, $request);
            }
        }

		return 'Nie ma takiego kontrolera';
    }

    private function getRegexRoute(string $route, array $info): string {
        if (isset($info['params'])) {
            foreach ($info['params'] as $name => $type) {
                $route = str_replace(':' . $name, self::$regexPatters[$type], $route);
            }
        }

        return $route;
    }

    private function executeController(
        string $route,
        string $path,
        array $info,
        Request $request
	) {
        $controllerName = '\FabtoszBlog\Controller\\' . $info['controller'] . 'Controller';
        $controller = new $controllerName($this->di, $request);

		
		if (isset($info['login']) && $info['login']) {
            if ($request->getSession()->exists('user_id')) {
                $userId = $request->getSession()->get('user_id');
                $controller->setUserId($userId);
            } else {
                $errorController = new UserController($this->di, $request);
				$request->getSession()->flash('login_info', 'Tylko zalogowani użytkownicy mają dostęp');
                //return $errorController->loginPanel();
				$request->redirect('/login');
            }
        }
		
        $params = $this->extractParams($route, $path);
        return call_user_func_array([$controller, $info['method']], $params);
    }

    private function extractParams(string $route, string $path): array {
        $params = [];

        $pathParts = explode('/', $path);
        $routeParts = explode('/', $route);

        foreach ($routeParts as $key => $routePart) {
            if (strpos($routePart, ':') === 0) {
                $name = substr($routePart, 1);
                $params[$name] = $pathParts[$key+1];
            }
        }

        return $params;
    }
	
}