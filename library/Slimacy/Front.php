<?php

require_once 'Slimacy/AutoLoader.php';
require_once 'Slimacy/Controller.php';

class Slimacy_Front
{
    /**
     * dispatch and response
     *
     * @param   string  $appPath    app absolute path
     * @return  void
     *
     */
    public static function run($appPath)
    {
        $autoLoader = new Slimacy_AutoLoader($appPath);

        // parse URL and parameters
        $requestParser  = new Slimacy_RequestParser($appPath);
        $controller     = $requestParser->controller;
        $action         = $requestParser->action;
        $params         = $requestParser->params;

        try {
            if (!class_exists($controller)) {
                throw new Exception('Invalid controller: ' . $controller, 400);
            }

            $object = new $controller($controller, $action, $params);
            $object->run();
        } catch (Exception $e) {
            header('HTTP/1.0 400');
            echo($e->getMessage());
            exit;
        }
    }
}

