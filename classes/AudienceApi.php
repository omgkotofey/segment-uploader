<?php 

namespace Classes;

/**
 *  Класс, занимающийся отправкой отзывов к API Яндекс.Аудиторий
 */
class AudienceApi 
{

    /**
     *  Функция посылает файл к API Яндекс.Аудиторий для создания сегмента
     *
     * @param String $mac_filename имя отправляемого файла
     * @return JSON $responce ответ API Яндекс.Аудиторий
     **/
    public static function upload_file_to_segment($mac_filename){
        //создаем объект CURLFile
        $cFile = curl_file_create($mac_filename, 'text/plain', $mac_filename);
        //вызываем метод создания сегмента API Аудиторий при помощищи curl
        $request_upload = curl_init('https://api-audience.yandex.ru/v1/management/segments/upload_file');
        curl_setopt($request_upload, CURLOPT_POST, true);
        // устанавливаем тело POST запроса
        curl_setopt($request_upload, CURLOPT_POSTFIELDS, ['file' => $cFile]);
        // Указываем дополнительные данные для заголовка
        curl_setopt($request_upload, CURLOPT_HTTPHEADER, [
            'Content-Type: multipart/form-data',
            'Authorization: OAuth ' . YANDEX_OAUTH_TOKEN
        ]);
        curl_setopt($request_upload, CURLOPT_RETURNTRANSFER, true);
        $responce = json_decode(curl_exec($request_upload), true);
        // убиваем инстанс
        curl_close($request_upload);
        return $responce;
    }

    /**
     *  Функция посылает файл к API Яндекс.Аудиторий для сохранения ранее созданного сегмента
     *
     * @param String $segment_id id загруженного сегмента
     * @param String $segment_name имя создааемого сегмента
     * @return JSON $responce ответ API Яндекс.Аудиторий
     **/
    public static function confirm_segment($segment_id, $segment_name){
        // формируем данные для подтверждения создания сегмента из загруженного ранее файла
        $data = ['segment' => [
            'id' => $segment_id, //id созданного сегмента
            'name' => $segment_name,					//имя воздаваемого сегмента (случайный набор цифр)
            'hashed' => 0, 						//захэшированы ли данные
            'content_type' => 'mac' 			//тип данных (mac-адреса)
            ]
        ];
        //вызываем метод API Аудиторий при помощищи curl
        $request_confirm = curl_init('https://api-audience.yandex.ru/v1/management/segment/' .  $segment_id . '/confirm');
        curl_setopt($request_confirm, CURLOPT_POST, true);
        // устанавливаем тело POST запроса
        curl_setopt($request_confirm, CURLOPT_POSTFIELDS, json_encode($data));
        // Указываем дополнительные данные для заголовка
        curl_setopt($request_confirm, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data)),
            'Authorization: OAuth ' . YANDEX_OAUTH_TOKEN
        ]);
        curl_setopt($request_confirm, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($request_confirm);
        // убиваем инстанс
        curl_close($request_confirm);
        return $response;
    }
}


?>