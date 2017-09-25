<?php

namespace M;

/**
 * Class Images - a model to work with images.
 */
class Images 
{
    use \Core\Traits\Singleton;

    /**
     * Method for image resizing and uploading.
     *
     * @param $file
     * @param $filename
     * @param $upload_dir
     * @return bool
     */
	private function resize_image($file, $filename, $upload_dir)
	{
		$max_height = 240;
		$max_width = 320;
		
		// Determine file type and create new empty file.
		if ($file['type'] == 'image/jpeg') {
			$src = imagecreatefromjpeg($file['tmp_name']);
		}
		elseif ($file['type'] == 'image/png') {
			$src = imagecreatefrompng($file['tmp_name']);
		}
		elseif ($file['type'] == 'image/gif') {
			$src = imagecreatefromgif($file['tmp_name']);
		}

		// Determine image width.
		$w_src = imagesx($src);
		// Determine image height.
		$h_src = imagesy($src);
		
		// If a size of the is optimal - upload file to directory.
		if ($w_src <= $max_width && $h_src <= $max_height) {
			imagejpeg($src, $upload_dir . $filename);
			imagedestroy($src);
			return true;
		}
		else {
		    // Compare file width with maximal width.
			if ($w_src > $max_width) {
			    // Calculate the aspect ratio.
                $w_ratio = $w_src/$max_width;
                // Calculate new file width.
				$w_new = round($w_src/$w_ratio);
				// Calculate new file height.
				$h_new = round($h_src/$w_ratio);
			}
			else {
				$w_new = $w_src;
				$h_new = $h_src;
			}

            // Compare file height with maximal height.
			if ($h_new > $max_height) {
                // Calculate the aspect ratio.
				$h_ratio = $h_new/$max_height;
                // Calculate new file width.
				$w_new = round($w_new/$h_ratio);
                // Calculate new file height.
				$h_new = round($h_new/$h_ratio);
			}

			// Create new image with new width and height.
			$new_image = imagecreatetruecolor($w_new, $h_new);
			// Copy old image to new one.
			imagecopyresampled($new_image, $src, 0, 0, 0, 0, $w_new, $h_new, $w_src, $h_src);
			imagejpeg($new_image, $upload_dir . $filename);
			imagedestroy($new_image);
			imagedestroy($src);
			
			return true;
		}
	}

    /**
     * Method for files validation and uploading to directory.
     *
     * @param $file
     * @param $filename
     * @param string $upload_dir
     * @param array $allowed_types
     * @return array
     */
	public function upload_file($file, $filename, $upload_dir = '..' . DIRECTORY_SEPARATOR . 'images', $allowed_types = ['image/png','image/x-png','image/jpeg','image/jpg','image/gif'])
	{
		// Forbidden extensions list.
	    $blacklist = [".php", ".phtml", ".php3", ".php4"];

	    // Get file extension.
		$ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1);

		if (in_array($ext, $blacklist)) {
			return ['error' => 'Запрещено загружать исполняемые файлы'];
		}

		// Get upload dir.
		$upload_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $upload_dir . DIRECTORY_SEPARATOR;
		// Determine max file size.
		$max_filesize = 8388608;
		// Determine unique file name.
		$new_filename = uniqid() . $ext;

		// Check a directory is writable.
		if (!is_writable($upload_dir)) {
			return ['error' => 'Невозможно загрузить файл в папку "'.$upload_dir.'". Установите права доступа - 777.'];
		}
		// Check file type is allowed.
		elseif (!in_array($file['type'], $allowed_types)) {
			return ['error' => 'Данный тип файла не поддерживается.'];
		}
		// Check file size is allowed.
		elseif (filesize($file['tmp_name']) > $max_filesize) {
			return ['error' => 'файл слишком большой. максимальный размер ' . intval($max_filesize/(1024*1024)).'мб'];
		}
		// Check file uploading was successful.
		elseif ($this->resize_image($file, $new_filename, $upload_dir) !== true) {
			return ['error' => 'При загрузке возникли ошибки. Попробуйте ещё раз.'];
		}
			
		return ['filename' => $new_filename, 'basename' => $filename];
	}
}