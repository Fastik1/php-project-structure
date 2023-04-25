<?php


namespace app\components;


class Logger
{
    private static string $path = 'logs';
    private static string $extension = '.log';
    private static string $dateFormat = 'd.m.Y H:i:s';

    /**
     * @throws \Exception
     */
    public static function add(string $section, string $text, array $context = []): void
    {
        static::createIfNotExistsLogsDir();
        $date = date(self::$dateFormat);
        $log = "\n\n[$date] $text" . (!empty($context) ? "\nContext: " . join("\n", $context) : null);
        file_put_contents(self::getFileName($section), $log, FILE_APPEND);
    }

    private static function getFileName($section): string
    {
        return self::$path . '/' . $section . self::$extension;
    }

    /**
     * @throws \Exception
     */
    private static function createIfNotExistsLogsDir(): void
    {
        if (!is_dir(static::$path)) {
            if (!mkdir(static::$path, recursive: true)) {
                throw new \Exception('Не удалось создать директорию ' . static::$path);
            }
        }
    }
}
