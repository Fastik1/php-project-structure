<?php


namespace app\components;

class Model
{

    public static function all(): array
    {
        $sql = SqlQueryBuilder::select(static::$table);
        return self::query($sql, [], 'all');
    }

    public static function find(int $id): array
    {
        $sql = SqlQueryBuilder::select(static::$table, '`id` = :id');
        return self::query($sql, ['id' => $id], 'one');
    }

    public static function create(array $data): bool
    {
        $sql = SqlQueryBuilder::insert(static::$table, $data);
        return self::query($sql, $data);
    }

    public static function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = SqlQueryBuilder::update(static::$table, $data);
        return self::query($sql, $data);
    }

    public static function query(string $sql, array $parameters = [], string|bool $fetchMode = false /* all|one|false */): array|bool
    {
        try {
            $stmt = DB::getConnetion()->prepare($sql);
            $execute = $stmt->execute($parameters);
        } catch (\Exception $e) {
            Logger::add('db', 'Exception DB: ' . $e->getMessage(), ['sql' => $sql, 'params' => $parameters, 'fetchMode' => $fetchMode]);
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
        return $fetchMode == 'all' ? $stmt->fetchAll() : ($fetchMode == 'one' ? $stmt->fetch() : $execute);
    }
}