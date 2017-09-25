<?php

namespace C\Admin;

use Core\System;
use Core\Sql;
use C\Base;

/**
 * Class Auth - controller for admin authentication.
 */
class Auth extends Base
{
	protected $title;
    protected $content;
    protected $params;
	protected $msg;
	protected $db;
	
	
    public function __construct()
	{
		$this->db = Sql::instance();
        $this->title = 'Наш сайт - ';
        $this->content = '';
		$this->msg = 'Введите логин и пароль:';	
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

    /**
     * Admin authentication.
     */
	public function action_login()
	{
		if (count($_POST) > 0) {
			$login = trim($_POST['login']);
			$password = trim($_POST['password']);

			// Get user by login.
			$admins = $this->db->select("SELECT * FROM admins WHERE login = :login",
				[
					"login" => $login
				]);
			
			
			if ($admins && password_verify($password, $admins[0]['password'])) {
                // Open session for admin.
			    $_SESSION['auth_admin'] = true;
                $_SESSION['id_admin'] = $admins[0]['id_admin'];

                // Set cookies for admin.
                if(isset($_POST['remember'])) {
                    setcookie('login_admin', $admins[0]['login'], time() + 3600 * 24 * 7, '/');
                    setcookie('password_admin', $admins[0]['password'], time() + 3600 * 24 * 7, '/');
                }

                header("Location: /admin/tasks");
                exit();
			}
			else {
				$this->msg = 'Неверный логин или пароль!';
			}
		}
		
		$this->title .= 'авторизация';
            
        $this->content = System::template('admin/v_login.php', [
			'title' => 'Авторизация',
			'msg' => $this->msg,
         ]);
	}

    /**
     * Admin log out.
     */
	public function action_logout()
	{
        // Close session.
	    unset($_SESSION['auth_admin']);
        unset($_SESSION['id_admin']);

        // Delete cookies.
        if (isset($_COOKIE['login_admin']) && isset($_COOKIE['password_admin'])) {
            setcookie('login_admin', '', time() - 3600, '/');
            setcookie('password_admin', '', time() - 3600, '/');
            unset($_COOKIE['login_admin']);
            unset($_COOKIE['password_admin']);
        }

        header("Location: /tasks");
        exit();
	}
	
}