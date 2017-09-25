<?php

namespace C\Client;

use Core\System;

/**
 * Class Pages - errors controller.
 */
class Pages extends Client
{
    /**
     * Method generates 404 error.
     */
    public function show404()
   {
        header("HTTP/1.1 404 Not Found");
        $this->title .= 'ошибка 404'; 
        $this->content = System::template('client/v_404.php');
    }

    /**
     * Method generates 503 error.
     */
    public function show503()
	{
        header("HTTP/1.1 503 Server Error");
        $this->title .= 'ошибка 503'; 
        $this->content = System::template('client/v_503.php');
    }
}
