<?php

namespace C;

use Core\Exceptions;

/**
 * Class Base - base parent controller.
 */
abstract class Base
{
    /**
     * Method generates view.
     */
    public abstract function render();

    /**
     * Method loads params from URL-address.
     *
     * @param $params
     */
    public function load($params)
	{
        $this->params = $params;
    }

    /**
     * Magic method to disable to call undefined actions.
     *
     * @param $name
     * @param $params
     * @throws Exceptions\E404
     */
    public function __call($name, $params)
	{
        throw new Exceptions\E404("undefined action $name");
    }
}
