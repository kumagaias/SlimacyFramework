<?php

class Slimacy_AutoLoader
{
    private $appDir = 'app';
    private $appPath;

    /**
     * constructor
     *
     * @param   string  $appPath    app absolute path
     * @return  void
     */
    public function __construct($appPath)
    {
        $this->appPath = $appPath;
        spl_autoload_register(array($this, 'loader'));
    }

    /**
     * loader function for spl_autoload_register
     *
     * @param   string  $className  require class name
     * @return  void
     */
    protected function loader($className)
    {
        $path = $this->getFilePath($className);
        if ($this->isReadble($path)) {
            require_once $path;
        }
    }

    /**
     * if the path is readble, return true
     *
     * @param   string  $path   require path
     * @return  boolean
     */
    protected function isReadble($path)
    {
        if (is_readable($path)) {
            return true;
        }

        // for include path
        $includePaths = explode(PATH_SEPARATOR, get_include_path());
        foreach ($includePaths as $includePath) {
            if (is_readable($includePath . DIRECTORY_SEPARATOR . $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * get file path by file name
     *
     * @param   string  $className  require class name
     * @return  string              require class path
     *
     */
    protected function getFilePath($className)
    {
        switch (true) {
            // controller
            case preg_match('/^.+Controller$/i', $className):
                return sprintf(
                        '%s' . DIRECTORY_SEPARATOR . '%s' . DIRECTORY_SEPARATOR
                        . 'controllers'. DIRECTORY_SEPARATOR . '%s.php',
                        $this->appPath,
                        $this->appDir,
                        $className
                );
            // model
            case preg_match('/^.+Model$/i', $className):
                return sprintf(
                        '%s' . DIRECTORY_SEPARATOR . '%s' . DIRECTORY_SEPARATOR
                        . 'models'. DIRECTORY_SEPARATOR . '%s.php',
                        $this->appPath,
                        $this->appDir,
                        $className
                );
            // framework
            case preg_match('/^Slimacy_(.+)$/i', $className, $matches):
                return sprintf(
                        'Slimacy' . DIRECTORY_SEPARATOR . '%s.php',
                        $matches[1]
                );
            default:
                return false;
        }
    }
}
