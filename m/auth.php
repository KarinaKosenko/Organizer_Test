<?php

namespace M;

use Core\Sql;

/**
 * Class Auth - a model to work with authentication.
 */
class Auth
{
	use \Core\Traits\Singleton;
	
	protected $db;
	
	protected function __construct()
	{
		$this->db = Sql::instance();
    }

    /**
     * Method check admin authentication.
     *
     * @return bool
     */
    public function check_auth_admin()
	{
		// Check admin session.
	    if (!isset($_SESSION['auth_admin'])) {
	        // Check admin cookies.
			if (isset($_COOKIE['login_admin']) && isset($_COOKIE['password_admin'])) {
				$login = trim($_COOKIE['login_admin']);
				$password = trim($_COOKIE['password_admin']);
		
				// Select admin from admins table by login.
				$admins = $this->db->select("SELECT * FROM admins WHERE login =:login",
				[
					"login" => $login
				]);

				// Check admin existence and password.
				if ($admins && $password === $admins[0]['password']) {
				    // Open admin session.
					$_SESSION['auth_admin'] = true;
					return true;
				} else {
					return false;
				}
			}
		} else {
            return true;
        }
	}
	
}
