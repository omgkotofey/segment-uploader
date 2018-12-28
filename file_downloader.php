<?php 

require_once 'vendor/autoload.php';

use Classes\Config;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!empty($_FILES) && ($_FILES['mac-file']['type'] == 'text/plain')){

	$uploadfile = Config::get('UPLOAD_DIR') . basename($_FILES['mac-file']['name']);

	if (move_uploaded_file($_FILES['mac-file']['tmp_name'], $uploadfile)) {
		echo json_encode(['result' => 'success', 'message' => 'Файл загружен успешно', 'filename' => $_FILES['mac-file']['name']]);
	} 
	else {
		echo json_encode(['result' => 'error', 'message' => 'Невозможно загрузить данный файл. Возможно он поврежден или некорректен']);
	}
}
else{
	echo json_encode(['result' => 'error', 'message' => 'К загрузке принимаются только .txt файлы']);
}

?>
