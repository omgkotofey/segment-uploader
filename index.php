<!DOCTYPE html>
<html lang="ru">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <title>Segment Easy Uploader - Загружай сегменты для сервиса Яндекс.Аудитории!</title>
    <meta name="description" content="Загружай сегменты из файлов, содержих MAC-адреса устройств, для сервиса Яндекс.Аудитории.">

    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/main.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/main.js"></script>
</head>

<body>
    <h2>Загружай сегменты для Яндекс.Аудиторий!</h2>
    <p class="lead">Загрузи .txt файл <b>содержащий MAC-адреса устройств</b><br class="mob-hide">
        для конвертации их в новый сегмент</p>
    <!-- Upload  -->
    <form id="file-upload-form" action="file_downloader.php" class="uploader">
        <input id="file-upload" type="file" name="fileUpload" accept=".txt" />

        <label for="file-upload" id="file-drag">
            <img id="file-image" src="#" alt="Preview" class="hidden">
            <div id="start">
                <i class="fa fa-download" aria-hidden="true"></i>
                <div>Выберите файл или перетащите его в это поле</div>
                <div id="error-message" class="hidden">К загрузке принимаются только .txt файлы</div>
                <span id="file-upload-btn" class="btn btn-primary">Выбрать файл</span>
            </div>
            <div id="response" class="hidden">
                <div id="messages"></div>
                <progress class="progress" id="file-progress" value="0">
                    <span>0</span>%
                </progress>
            </div>
        </label>
        <span id="file-send-btn" class="btn btn-primary">Создать сегмент</span>
    </form>
</body>

</html>