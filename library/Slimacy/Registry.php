<?php

class Slimacy_Registry
{
    private static $singleton;
    private static $registries;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$singleton)) {
            self::$singleton = new Slimacy_Registry();
        }
        return self::$singleton;
    }

    /**
     * get the specific registries
     *
     * @param   string  $key    the section's key
     * @return  array           the specific registries
     */
    public static function get($key)
    {
        $registries = self::getAll();
        $registry = array();

        if (isset($registries[$key])) {
            $registry = $registries[$key];
        }

        return $registry;
    }

    /**
     * get the all registries
     *
     * @return  array   the all registries
     */
    public static function getAll()
    {
        return self::$registries;
    }

    /**
     * set the specific registries
     *
     * @param   string  $key        the section's key
     * @param   string  $register   register data
     * @return  void
     */
    public static function set($key, $register)
    {
        $registry = array_merge(self::get($key), $register);
        self::$registries[$key] = $registry;
    }

    /**
     * set the registries From ini file
     *
     * @param   string  $path   ini file path
     * @return  void
     */
    public static function setFromIni($path)
    {
        $ini = parse_ini_file($path, true);
        if ($ini) {
            foreach ($ini as $k => $v) {
                self::set($k, $v);
            }
        }
    }
}
