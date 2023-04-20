<?php


namespace app\components;


class Logger
{
    private static string $path = 'logs';
    private static string $extension = '.log';
    private static string $dateFormat = 'd.m.Y H:i:s';

    public static function add(string $section, string $text, array $context = []): void
    {
        static::createIfNotExistsLogsDir();
        $date = date(self::$dateFormat);
        $log = "\n\n[$date] $text" . (!empty($context) ? "\nContext: " . json_encode($context, JSON_UNESCAPED_UNICODE) : null);
        file_put_contents(self::getFileName($section), $log, FILE_APPEND);
    }

    private static function getFileName($section): string
    {
        return self::$path . '/' . $section . self::$extension;
    }

    private static function createIfNotExistsLogsDir(): void
    {
        if (!is_dir(static::$path)) {
            mkdir(static::$path);
        }
    }
}