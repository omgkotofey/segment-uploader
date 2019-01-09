<?php

require_once 'vendor/autoload.php';

use \Classes\Config;
use \Classes\MacFile;
use \Classes\AudienceApi;

// обязательно должен придти $POST запрос верного формата
if (!empty($_POST['mac_file_name']) && !empty($_POST['segment_name'])){

	// формируем полное имя файла, с которым будем работать
	$mac_file_name = Config::get('UPLOAD_DIR') . strip_tags($_POST['mac_file_name']);

	if (file_exists($mac_file_name)) {
		try {
            // создаем экземпляр API
            $ya_audience_api = new AudienceApi(Config::get('YANDEX_OAUTH_TOKEN'));
			// создаем MacFile, скрипт продолжит работу только при успешном создании файла
            $mac_file = new MacFile($mac_file_name);
            // посылаем запрос к API, загружающий файл как новый сегмент
            $responce = $ya_audience_api->create_segment($mac_file, strip_tags($_POST['segment_name']));
            echo json_encode(['result' => 'success', 'message' => 'Сегмент создан успешно', 'responce' => $responce]);
		} catch (\Exception $e) {
			echo json_encode(['result' => 'error', 'message' => $e->getMessage()]);
		}
	} 
	else {
		echo json_encode(['result' => 'error', 'message' => 'Невозможно продолжить. Не обнаружен файл сегмента. Загрузите файл повторно.']);
	}

	
}
else{
	echo json_encode(['result' => 'error', 'message' => 'Невозможно продолжить. Не указано имя сегмента.']);
}
?>