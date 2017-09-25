<?php

namespace C\Admin;

use C\Base;
use Core\System;
use M\Auth;
use M\Articles;

/**
 * Class Admin - parent controller for other controllers of the admin panel.
 */
abstract class Admin extends Base
{
    protected $auth;
    protected $title;
    protected $content;
    protected $params;
    
    public function __construct()
    {
        // Check authentication status.
        $this->auth = (Auth::instance())
            ->check_auth_admin();
		
		if (!$this->auth) {
			header("Location: /admin/auth/login");
			exit();
		}
        
        $this->title = 'Наш сайт - ';
        $this->content = '';
    }

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
     * Method generates HTML-document (view).
     *
     * @return string
     */
    public function render()
    {
        $html = System::template('admin/v_main.php', [
            'title' => $this->title,
            'content' => $this->content,
         ]);
         
        return $html;
    } 
}