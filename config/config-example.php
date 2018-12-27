<?php 

/**
 * Возвращает основные параметры.
 * Используется в классе Config
 * @see https://oauth.yandex.ru/
 */
return array(
    //Здесь должен быть OAuth-токен приложения
    'YANDEX_OAUTH_TOKEN' => '',

    'UPLOAD_DIR' => realpath(dirname(__FILE__).'/../../') . 'uploads/',
);


?>