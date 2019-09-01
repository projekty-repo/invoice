<?php

class Router
{
    private const DEFAULT_ACTION = 'index';
    private const DEFAULT_CONTROLLER = 'Invoice';

    public static function getController(): string
    {
        return self::getControllerName() . 'Controller';
    }

    private static function getControllerName(): string
    {
        return Request::createForRouter()->retrieve('controller') ?: self::DEFAULT_CONTROLLER;
    }

    public static function getAction(): string
    {
        return self::getActionName() . 'Action';
    }

    public static function getActionName(): string
    {
        return Request::createForRouter()->retrieve('action') ?: self::DEFAULT_ACTION;
    }

    public static function getParameters(): ?array
    {
        $urlElements = array_flip([
            'controller',
            'action',
        ]);

        return array_diff_key(Request::createForRouter()->all(), $urlElements) ?: null;
    }

    public static function getParameter(string $name): ?string
    {
        return self::getParameters()[$name] ?? null;
    }

    public static function getView(): string
    {
        return ucfirst(self::getControllerName()) . '/' . self::getActionName();
    }

    public static function redirectToReferer(): void
    {
        $referer = $_SERVER['HTTP_REFERER'];
        if (!isset($referer)) {
            self::redirect();
        }

        header('Location: ' . $referer);
        die();
    }

    public static function redirect(string $controller = null, string $action = null, array $parameter = null): void
    {
        $url = self::generateUrl($controller, $action, $parameter);
        header('Location: ' . $url);
        die();
    }

    public static function generateUrl(?string $controller = null, ?string $action = null, ?array $parameters = []): string
    {
        $urlElements = [];

        if ($controller) {
            $urlElements[] = 'controller=' . $controller;
        }

        if ($action) {
            $urlElements[] = 'action=' . $action;
        }

        foreach ($parameters as $name => $value) {
            $urlElements[] = $name . '=' . $value;
        }

        $url = $_SERVER['DOCUMENT_URI'];
        if ($urlElements) {
            $url .= '?' . implode('&', $urlElements);
        }

        return $url;
    }
}