<?php


namespace Classes;

/**
 * Класс-геттер для конфигурации
 */
class Config
{
    public static $config;
    
    public static function get($key)
    {
       
        if (!self::$config) {
            $config_file = 'config/config.php';
            if (!file_exists($config_file)) {
                throw new \Exception('Файл конфигурации не найден');
            }
            self::$config = require $config_file;
        }
        return self::$config[$key];
    }
}