<?php
 
namespace Core\Exceptions;

/**
 * Class E404 - class to work with 404 error.
 */
class E404 extends Base
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
	{
        $this->dest .= '/e404';
        parent::__construct($message, $code, $previous);
    } 
}
