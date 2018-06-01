<?php

abstract class Slimacy_Controller
{
    // request parameters
    protected $params = array();
    // invoked controller name
    protected $controller = '';
    // invoked action name
    protected $action = '';
    // for unit test
    protected $isUnitTest = false;

    /**
     * constructor
     *
     * @param   string  $controller     invoked controller name
     * @param   string  $action         invoked action name
     * @param   array   $params         request parameters
     * @return  void
     */
    public function __construct($controller, $action, $params)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params; 
    }

    /**
     * main method to be called from the front controller
     *
     * @param   string  $action action name
     * @return  void
     */
    public function run()
    {
        $this->setup();

        // validate request parameters of each action
        $this->validateRequests($this->getAllParams());

        $action = $this->getActionName();
        $this->$action();

        $this->tearDown();
    }

    /**
     * validate request parameters
     *
     * @throws  Exception   occured when invalid input exists
     * @return  void
     */
    protected function validateRequests()
    {
        $settings   = $this->getRequestValidators();
        $actionName = $this->getActionName();

        // no validator
        if (!$settings[$actionName]) {
            return;
        }

        $slimacyValidator = new Slimacy_Validator();

        foreach ($settings[$actionName] as $requestKey => $validators) {
            foreach($validators as $methods) {
                // parse validator settings
                $parsed = explode(':', $methods);

                // validator's function name
                $method = $parsed[0];

                // validator's options
                $options = array();
                if (isset($parsed[1])) {
                    $options = explode(',', $parsed[1]);
                }

                // validate target
                $requestParam = $this->getParam($requestKey);

                // invalid request parameter exists
                if ($slimacyValidator->$method($requestParam, $options) !== true) {
                    throw new Exception(
                        sprintf(
                            'Invalid parameter: %s, %s', $requestKey,
                            $method
                        )
                    );
                }
            }
        }
    }

    /**
     * when invoking invalid method, throw Exception and catched by the front controller
     *
     * @param   string      $name       invoked function name
     * @param   array       $arguments  invoked function name
     * @throws  Exception
     * @return  void
     */
    public function __call($name, $arguments)
    {
        $message = sprintf('Invalid function invoked: %s',$name);
        throw new Exception($message);
    }

    /**
     * get controller name
     *
     * @return string controller name
     */
    protected function getControllerName()
    {
        return $this->controller;
    }

    /**
     * get action name
     *
     * @return string action name
     */
    protected function getActionName()
    {
        return $this->action;
    }

    /**
     * get the all request parameters
     *
     * @return array all request parameters
     */
    protected function getAllParams()
    {
        return $this->params;
    }

    /**
     * get the specific input parameter
     *
     * @param   string  $key    the parameter's key
     * @return  array           the parameter value
     */
    protected function getParam($key)
    {
        $all= $this->getAllParams();

        $param = '';
        if (isset($all[$key])) {
            $param = $all[$key];
        }

        return $param;
    }

    /**
     * response message
     *
     * @param   string      $message    response message
     * @param   int         $code       http status code
     * @return  void
     */
    protected function response($message, $code = 200)
    {
        header('HTTP/1.0 ' . $code);
        echo $message;

        if (!$this->isUnitTest) {
            exit;
        }
    }

    abstract protected function setup();
    abstract protected function tearDown();
    abstract protected function getRequestValidators();
}
