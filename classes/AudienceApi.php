<?php 

namespace Classes;

/**
 *  Класс, занимающийся отправкой отзывов к API Яндекс.Аудиторий
 */
class AudienceApi 
{
    private $yandex_oauth_token;

    /**
	 * Контруктор класса
	 *
	 * @param String $yandex_oauth_token oauth токен приложения
	 **/
    function __construct($yandex_oauth_token) {
        $this->yandex_oauth_token = $yandex_oauth_token;
    }
    
    /**
     *  Функция посылает файл к API Яндекс.Аудиторий для создания сегмента
     *
     * @param String $mac_filename имя отправляемого файла
     * @return JSON $responce ответ API Яндекс.Аудиторий
     **/
    private function upload_file_to_segment($mac_filename){
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
            'Authorization: OAuth ' . $this->yandex_oauth_token
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
     * @param String $segment_name имя создаваемого сегмента
     * @return JSON $responce ответ API Яндекс.Аудиторий
     **/
    private function confirm_segment($segment_id, $segment_name){
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
            'Authorization: OAuth ' . $this->yandex_oauth_token
        ]);
        curl_setopt($request_confirm, CURLOPT_RETURNTRANSFER, true);
        $responce = curl_exec($request_confirm);
        // убиваем инстанс
        curl_close($request_confirm);
        return $responce;
    }

    /**
     *  Функция обрабатывающая переданный MAC-файл работающая с API Яндекс.Аудиторий,
     *
     * @param MacFile $mac_file файл, содержащий MAC-адреса
     * @param String $segment_name имя создаваемого сегмента
     * @return JSON $responce ответ API Яндекс.Аудиторий
     **/
    public function create_segment($mac_file, $segment_name){
        // посылаем запрос к API на загрузку файла в виде сегмента
        $responce_upload = $this->upload_file_to_segment($mac_file->getPath());
        // если получен JSON верного формата
        if (!empty($responce_upload['segment']['id'])){
            $new_segment_id =  $responce_upload['segment']['id'];
            // посылаем запрос к API на сохранение загруженного сегмента с новым именем
            $responce_confirm = $this->confirm_segment($new_segment_id, $segment_name);
            return $responce_confirm;
        }
        else{
            throw new \Exception('Не удалось создать сегмент. Причина:' . print_r($responce));
        }
        
    }
}


?>