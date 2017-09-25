<?php
   
   /**
	* Single entry point to the application.
    */
	session_start();

    // Class loader function.
	function my_class_loader($classname)
	{
        $classname = strtolower($classname);
	    $classname = str_replace('\\', '/', $classname);

		if (!file_exists($classname . '.php')) {
			throw new Exception("class $classname not found");
		}

		require_once($classname . '.php');
    }

    spl_autoload_register('my_class_loader');

    $params = explode('/', $_GET['q']);
    $cnt = count($params);

    if ($params[$cnt - 1] === '') {
        unset($params[$cnt - 1]);
    }

    // Set default parameter.
    $param0 = isset($params[0]) ? $params[0] : 'tasks';

    // Determine admin or client directory.
    if ($param0 === 'admin') {
		$start = "C\Admin\\";

		$params = array_splice($params, 1);
		$param0 = isset($params[0]) ? $params[0] : 'tasks';
	}
	else {
        $start = "C\Client\\";
	}

    // Determine a list of controllers.
	$controllers = ['tasks', 'auth', 'pages'];
	
	if (in_array($param0, $controllers)) {
		$cname = $start . ucfirst($param0);

		// Determine action.
		if (isset($params[1])) {
			$action = 'action_' . $params[1];
		} 
		elseif ($param0 === 'tasks') {
			$action = 'action_page';
			$params[2] = 1;
		}  
	}
	else {
		$cname = 'C\Client\Pages';
		$action = 'show404';
	}

	// Catch exceptions.
	try {
		$controller = new $cname();
		$controller->load($params);
		$controller->$action();
		$html = $controller->render();
		echo $html;
	}
	catch(Core\Exceptions\E404 $e) {
		$controller = new C\Client\Pages();
		$controller->load($params);
		$controller->show404();
		echo $controller->render();
	}
	catch(Core\Exceptions\Fatal $e) {
		$controller = new C\Client\Pages();
		$controller->load($params);
		$controller->show503();
		echo $controller->render();
	}
	catch(Exception $e) {
	   echo 'Unknown error.';
	}
	
	