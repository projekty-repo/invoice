<?php

class App
{
    /**
     * @var string
     */
    private $action;

    /**
     * @var Controller
     */
    private $controller;

    public function __construct()
    {
        session_start();
    }

    final public function run(): void
    {
        //try {
            $this->redirectToIndexFile();
            $this->defineDirectory();
            $this->runAutoloader();
            $this->setController();
            $this->setAction();
            $this->executeAction();
        //} catch (Exception $e) {
//            Loader::loadViewByFileName('/Layout/error.html');
//            die();
//        }
    }

    private function redirectToIndexFile(): void
    {
        if (!preg_match('/index.php/', $_SERVER['REQUEST_URI'])) {
            header('Location: ' . '//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'index.php');
        }
    }

    private function defineDirectory(): void
    {
        $dirElements = explode('/', __DIR__);
        $dirElementsWithoutLastTow = array_slice($dirElements, 0, -2);
        define('DIRECTORY', implode('/', $dirElementsWithoutLastTow));
    }

    private function runAutoloader(): void
    {
        spl_autoload_register(function ($className) {
            include_once DIRECTORY . '/src/Lib/Loader.php';
            Loader::autoLoad($className);
        });
    }

    private function setController(): void
    {
        $controllerName = Router::getController();
        Loader::loadController($controllerName);

        if (!class_exists($controllerName)) {
            throw new RuntimeException("Class $controllerName not found");
        }

        $this->controller = new $controllerName();
    }

    private function setAction(): void
    {
        $this->action = Router::getAction();
    }

    private function executeAction(): void
    {
        if (!method_exists($this->controller, $this->action)) {
            throw new BadMethodCallException('Function ' . $this->action . ' in class ' . $this->controller . ' not found');
        }

        $actionParameters = $this->prepareActionParametersFromUrl();
        $action = $this->action;

        $this->controller->$action($actionParameters);
    }

    private function prepareActionParametersFromUrl(): string
    {
        $parameters = [];
        $urlParameters = Router::getParameters();
        $reflection = new ReflectionMethod($this->controller, $this->action);

        $actionParameters = $reflection->getParameters();
        foreach ($actionParameters as $actionParameter) {
            $actionParameterName = $actionParameter->getName();
            if (!isset($urlParameters[$actionParameterName])) {
                continue;
            }

            $parameters[] = $urlParameters[$actionParameterName];
        }

        return implode(', ', $parameters);
    }

    final public function display(): void
    {
        $this->controller->getView()->render();
    }
}