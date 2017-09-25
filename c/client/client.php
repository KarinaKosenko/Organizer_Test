<?php

namespace C\Client;

use C\Base;
use Core\System;

/**
 * Class Client - parent controller for other controllers of the client side.
 */
abstract class Client extends Base
{
    protected $title;
    protected $content;
    protected $params;
	
    
    public function __construct()
	{
		$this->title = 'Наш сайт - ';
        $this->content = '';
    }

    /**
     * Method generates HTML-document (view).
     *
     * @return string
     */
    public function render()
	{
        $html = System::template('client/v_main.php', [
            'title' => $this->title,
            'content' => $this->content,
         ]);
         
        return $html;
    } 
}
