<?php


namespace app\components;


class Logger
{
    private static string $path = 'logs';
    private static string $extension = '.log';
    private static string $dateFormat = 'd.m.Y H:i:s';

    public static function add(string $section, string $text, array $context): void
    {
        $date = date(self::$dateFormat);
        $log = "\n\n[$date] $text\nContext: " . json_encode($context, JSON_UNESCAPED_UNICODE);
        file_put_contents(self::getFileName($section), $log, FILE_APPEND);
    }

    private static function getFileName($section): string
    {
        return self::$path . '/' . $section . self::$extension;
    }
}