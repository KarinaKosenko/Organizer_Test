<?php
 
namespace Core\Exceptions;

/**
 * Class Fatal - class to work with database errors.
 */
class Fatal extends Base
{
    public function __construct($message = null, $code = 0, Exception $previous = null)
	{
        $this->dest .= '/fatal';
        parent::__construct($message, $code, $previous);
    }   
}
