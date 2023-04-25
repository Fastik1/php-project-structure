<?php


namespace app\components;

abstract class Model
{
    protected static ?string $table = null;


    public static function all(): array
    {
        $sql = SqlQueryBuilder::select(static::getTable());
        return self::query($sql, [], 'all');
    }

    public static function find(int $id): array
    {
        $sql = SqlQueryBuilder::select(static::getTable(), '`id` = :id');
        return self::query($sql, ['id' => $id], 'one');
    }

    public static function create(array $data): bool
    {
        $sql = SqlQueryBuilder::insert(static::getTable(), $data);
        return self::query($sql, $data);
    }

    public static function update(int $id, array $data): bool
    {
        $data['id'] = $id;
        $sql = SqlQueryBuilder::update(static::getTable(), $data);
        return self::query($sql, $data);
    }

    public static function query(string $sql, array $parameters = [], string|bool $fetchMode = false /* all|one|false */): array|bool
    {
        try {
            $stmt = DB::getConnection()->prepare($sql);
            $execute = $stmt->execute($parameters);
        } catch (\Exception $e) {
            Logger::add('db', 'Exception DB: ' . $e->getMessage(), ['sql' => $sql, 'params' => $parameters, 'fetchMode' => $fetchMode]);
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        return $fetchMode == 'all' ? $stmt->fetchAll() : ($fetchMode == 'one' ? $stmt->fetch() : $execute);
    }

    protected static function getTable(): ?string
    {
        if (static::$table === null) {
            throw new \PDOException('Не определена таблица для модели');
        }
        return self::$table;
    }
}
