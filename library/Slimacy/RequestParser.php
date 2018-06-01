<?php

class Slimacy_RequestParser
{
    private $controller = '';
    private $action     = '';
    private $params     = array();

    /**
     * constructor
     *
     * @param   string  $appPath    app absolute path
     * @return  void
     */
    public function __construct($appPath)
    {
        $appRootUrl = str_replace($_SERVER['DOCUMENT_ROOT'], '', $appPath);
        $requestUrl = str_replace($appRootUrl, '',  $_SERVER['REQUEST_URI']);
        $this->run($requestUrl);
    }

    /**
     * parse and set properties
     *
     * @param   string  $requestUrl request url
     */
    protected function run($requestUrl)
    {
        $parsed = explode('/', $requestUrl);
        // remove empty value
        $parsed = array_filter($parsed, "strlen");

        // set controller and action name
        $this->controller   = $this->formatController(array_shift($parsed));
        $this->action       = $this->formatAction(array_shift($parsed));
        $params             = array();

        // get GET Parameters
        while ($parsed) {

            $key = array_shift($parsed);
            $value = '';
            if ($parsed) {
                $value = array_shift($parsed);
            }

            $params[$key] = $value;
        };

        // get POST Parameters
        foreach ($_POST as $k => $v) {
            $params[$k] = $v;
        }

        // get Global
        $params['_SERVER'] = $_SERVER;

        // set the above Parameters
        $this->params = $params;
    }

    /**
     * getter
     *
     * @param   string  $name   property name
     * @return  mixed           propetiy
     */
    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }

    /**
     * format controller name
     *
     * @param   string  $name   controller name
     * @return  string          formated controller name
     */
    protected function formatController($name)
    {
        return sprintf('%sController', ucfirst(strtolower($name)));
    }

    /**
     * format action name
     *
     * @param   string  $name   action name
     * @return  string          formated action name
     */
    protected function formatAction($name)
    {
        return sprintf('%sAction', strtolower($name));
    }
}
