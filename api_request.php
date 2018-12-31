<?php

require_once 'vendor/autoload.php';

use \Classes\Config;
use \Classes\MacFile;
use \Classes\AudienceApi;

// файл обязательно должен придти $POST запрос
if (!empty($_POST) && (array_key_exists('mac_file_name', $_POST))){

	// определяем имя файла, с которым будем работать
	$mac_file_name = Config::get('UPLOAD_DIR') . strip_tags($_POST['mac_file_name']);

	if (file_exists($mac_file_name)) {
		try {
			// создаем новый MacFile
			$mac_file = new MacFile($mac_file_name);

			$responce_upload = AudienceApi::upload_file_to_api($mac_file_name);
			$new_segment_id =  $responce_upload['segment']['id'];
			$responce_confirm = confirm_segment($new_segment_id, uniqid());
			print_r($responce);
		} catch (\Exception $e) {
			echo json_encode(['result' => 'error', 'message' => $e->getMessage()]);
		}
	} 
	else {
		echo json_encode(['result' => 'error', 'message' => 'Невозможно продолжить. Загрузите файл повторно.']);
	}

	
}
else{
	echo json_encode(['result' => 'error', 'message' => 'Невозможно продолжить. Не указано имя файла.']);
}
?>