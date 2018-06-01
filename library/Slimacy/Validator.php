<?php

class Slimacy_Validator
{
    /**
     * if the input is not empty, return true
     *
     * @param   mixed   $input      input value
     * @param   array   $options    validator's options
     * @reutrn  boolean
     */
    public function isNotEmpty($input, $options = array())
    {
        if (empty($input)) {
            return false;
        }
        return true;
    }

    /**
     * if the input is array, return true
     *
     * @param   mixed   $input      input value
     * @param   array   $options    validator's options
     * @reutrn  boolean
     */
    public function isInArray($input, $options = array())
    {
        if (!in_array($input, $options, true)) {
            return false;
        }
        return true;
    }

    /**
     * if the input is over the minimum chars, return true
     *
     * @param   mixed   $input      input value
     * @param   array   $options    validator's options
     * @reutrn  boolean
     */
    public function isLengthAtLeast($input, $options = array())
    {
        // validate min value
        if (mb_strlen($input) < $options[0]) {
            return false;
        }
        return true;
    }

    /**
     * if the input is integer, return true
     *
     * @param   mixed   $input      input value
     * @param   array   $options    validator's options
     * @reutrn  boolean
     */
    public function isInt($input, $options = array())
    {
        if (!preg_match('/[0-9]+/', $input)) {
            return false;
        }
        return true;
    }

    /**
     * if the unknown validator is called, the exception is occured
     *
     * @param   string      $name       called function name
     * @param   array       $arguments
     * @throw   Exception
     */
    public function __call($name, $arguments)
    {
        throw new Exception(sprintf('Invalid validator invoked: %s', $name));
    }
}
