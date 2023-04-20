<?php


namespace app\components;


use Dotenv\Dotenv;

class Environments
{
    public static function load(string $path): void
    {
        try {
            Dotenv::createImmutable($path)->load();
        } catch (\Dotenv\Exception\ExceptionInterface $e) {
            Logger::add('env', 'Exception Dotenv: ' . $e->getMessage());
            die('Error loading environment variables');
        }
    }
}