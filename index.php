<!DOCTYPE html>
<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="description" content="Загружай сегменты из файлов, содержих MAC-адреса устройств, для сервиса Яндекс.Аудитории.">
    
    <title>Segment Easy Uploader - Загружай сегменты для сервиса Яндекс.Аудитории!</title>
    
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    
    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script type="text/javascript" src="assets/js/main.js"></script>
</head>

<body>
    <div id="popup" class="hidden">
        <a href="https://audience.yandex.ru/" target="_blank" rel="noreferrer"><img src="assets/images/audience.png"></a>
        <h2>Сегмент создан успешно!</h2>
        <p class="lead">Новый сегмент доступен в сервисе Яндекс.Аудиторий.<br class="mob-hide"> Он будет готов к работе после обработки данных сегмента сервисом.</p>
        <span class="btn btn-primary" onClick="window.location.reload()">Загрузить еще</span>
    </div>
    <h2>Загружай сегменты для Яндекс.Аудиторий!</h2>
    <p class="lead">Загрузи .txt файл <b>содержащий MAC-адреса устройств</b><br class="mob-hide">
        для конвертации их в новый сегмент</p>
    <!-- UPLOADER BOX  -->
    <form id="file-upload-form" action="file_downloader.php" class="uploader">
        <input id="file-upload" type="file" name="fileUpload" accept=".txt"/>

        <label for="file-upload" id="file-drag">
            <div id="start">
                <i id="file-drag-icon" class="fa fa-download" aria-hidden="true"></i>
                <div>Выберите файл или перетащите его в это поле</div>
                <div id="error-message" class="hidden"></div>
                <span id="file-upload-btn" class="btn btn-primary">Выбрать файл</span>
            </div>
            <div id="file-wrapper">
                <img id="file-remove" src="assets/images/delete-sign.png" alt="Remove" class="hidden" title="Удалить файл">
                <img id="file-image" src="#" alt="Preview" class="hidden">
                <div id="response" class="hidden">
                    <div id="file-name"></div>
                    <div id="messages"></div>
                    <progress class="progress" id="file-progress" value="0">
                        <span>0</span>%
                    </progress>
                </div>
            </div>
        </label>
        <label for="segment-name" id="segment-create">
            <div id="start">
                <i id="file-drag-icon" class="fa fa-pencil" aria-hidden="true"></i>
                <div>Укажите название для создаваемого сегмента</div>
                <input id="segment-name" type="text" name="segmentName"  placeholder='Новый сегмент'/>
                <span id="segment-name-invalid" class="hidden">Имя сегмента содержит недопустимые символы</span>
                <span id="segment-create-btn" class="btn btn-primary disabled" disabled>Создать сегмент</span>
                <p id="segment-please-wait" class="hidden pulse">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Подождите, операция выполняется...
                </p>
            </div>
        </label>
        <span id="segment-send-btn" class="btn btn-primary hidden">Продолжить</span>
    </form>
</body>

</html>