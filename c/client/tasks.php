<?php

namespace C\Client;

use M\Tasks as Model;
use M\Images; 
use Core\System;
use Core\Exceptions;
use Core\Validation;

/**
 * Class Tasks - task controller on the client side.
 */
class Tasks extends Client
{
    /**
     * Tasks list page.
     */
    public function action_page()
    {
        // Get tasks model.
        $mTasks = Model::instance($this->params[2]);

        // Work with sorting.
		if (isset($_POST['sort_column']) && isset($_POST['sort_order'])) {
			$sort_column = $_POST['sort_column'];
			$sort_order = $_POST['sort_order'];
			$all_data = $mTasks->getData($sort_column, $sort_order);
		}
		else {
			$all_data = $mTasks->getData();
		}
		
        $this->title .= 'главная';
            
        $this->content = System::template('client/v_tasks_list.php', [
		   'title' => 'Список задач',
		   'start' => $all_data['start'],
		   'data' => $all_data['data'],
		   'rows' => $all_data['rows'],
		   'num_pages' => $all_data['num_pages'],
		   'cur_page' => $this->params[2],
         ]);
    }

    /**
     * One task page.
     *
     * @throws Exceptions\E404
     */
	public function action_one()
    {
        // Get tasks model.
        $mTasks = Model::instance($this->params[2]);
        // Get current task.
        $task = $mTasks->one($this->params[2]);
		
        if ($task === null) {
            throw new Exceptions\E404("task with id {$this->params[2]} is not found");
        }
        else {
            $this->title .= 'просмотр задачи';

            $this->content = System::template('client/v_one.php', [
                    'task' => $task,
            ]);
        }  
    }

    /**
     * Task adding to database.
     */
	public function action_add()
    {
        // Get tasks model.
        $mTasks = Model::instance();
        // Get images model.
        $imageManager = Images::instance();
		
		$msg = 'Пожалуйста, добавьте задачу.';

        // Image uploading.
        if (isset($_FILES['imgfile']) && !empty($_FILES['imgfile']['name'])) {
			$result = $imageManager->upload_file($_FILES['imgfile'], $_FILES['imgfile']['name']);
			
			if (isset($result['error'])) {
				$msg = $result['error'];
			}
			else {
				$_SESSION['image_link'] = '/images/' . $result['filename'];
				$_SESSION['image_original_name'] = $result['basename'];
				$msg = 'Изображение успешно загружено на сервер.';
			}
		}		
		
		if (count($_POST) > 0) {
			$username = $_POST['username'];
			$email = $_POST['email'];
			$text = $_POST['text'];

            // Check image uploading.
			if (isset($_SESSION['image_link']) && isset($_SESSION['image_original_name'])) {
                // Get image link from session.
				$image_link = $_SESSION['image_link'];
                // Get image name from session.
				$image_original_name = $_SESSION['image_original_name'];
				unset($_SESSION['image_link']);
				unset($_SESSION['image_original_name']);
				
				$obj = compact("username", "text", "email", "image_link", "image_original_name");
                // POST-request data validation.
				$valid = new Validation($obj, $mTasks->validationMap());
				$valid->execute('add');
						
				if ($valid->good()) {
                    // Add data to database.
					$mTasks->add($valid->cleanObj());
					header("Location: /tasks");
					exit();
				}
				else {
                    // Get validation errors.
					$errors = $valid->errors();
					$msg = implode('<br>', $errors);
				}
			}
			else {
				$msg = 'Необходимо загрузить изображение.';
			}
        }
        else {
            $username = '';
            $email = '';
            $text = '';
        }
    
        $this->title .= 'добавление задачи';
			
        $this->content = System::template('client/v_add.php', [
            'username' => $username,
            'email' => $email,
            'text' => $text,
            'msg' => $msg,
        ]);
    }
}	
	