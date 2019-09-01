<?php

class Loader
{
    public const SRC = DIRECTORY . '/' . 'src';
    private const CONTROLLER_FOLDER = 'Controller';
    private const MODEL_FOLDER = 'Models';
    public const VIEW_FOLDER = 'Views';

    public static function loadController(string $controllerName): void
    {
        self::include(self::SRC . '/' . self::CONTROLLER_FOLDER . '/Controller.php');
        self::include(self::SRC . '/' . self::CONTROLLER_FOLDER . '/' . ucfirst($controllerName) . '.php');
    }

    public static function loadModel(string $modelName): void
    {
        self::include(self::getModelFilePath($modelName));
    }

    public static function getModelFilePath(string $modelName): string
    {
        return self::SRC . '/' . self::MODEL_FOLDER . '/' . $modelName . '.php';
    }

    public static function loadView(): void
    {
        self::include(self::SRC . '/' . self::VIEW_FOLDER . '/View.php');
    }

    public static function loadViewByFileName(string $filename): void
    {
        self::include(self::SRC . '/' . self::VIEW_FOLDER . '/' . $filename);
    }

    public static function autoLoad(string $className): void
    {
        if (self::loadClassFromLib($className)) {

            return;
        }
        if (self::loadClassFromModel($className)) {

            return;
        }
    }

    private static function loadClassFromLib(string $className, string $currentDirectory = null): bool
    {
        if (!$currentDirectory) {
            $currentDirectory = DIRECTORY . '/src/Lib';
        }

        foreach (glob($currentDirectory . '/*', GLOB_NOSORT) as $fileOrDirectory) {
            $classFile = $currentDirectory . '/' . $className . '.php';
            if (file_exists($classFile)) {
                include_once $classFile;

                return true;
            } elseif (is_dir($fileOrDirectory)) {
                $classLoaded = self::loadClassFromLib($className, $fileOrDirectory);
                if ($classLoaded) {

                    return true;
                }
            }
        }

        return false;
    }

    private static function loadClassFromModel(string $className): bool
    {
        if (!file_exists(self::getModelFilePath($className))) {

            return false;
        }
        self::loadModel($className);

        return true;
    }

    private static function include(string $include): void
    {
        if (file_exists($include) && is_readable($include)) {
            include_once $include;
        } else {
            throw new RuntimeException('File include error');
        }
    }
}