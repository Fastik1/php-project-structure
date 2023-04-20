<?php


namespace app\components;


class SqlQueryBuilder
{
    public static function select(string $table, ?string $where = null): string
    {
        return "SELECT * FROM `$table`" . ($where ? " WHERE $where" : null);
    }

    public static function update(string $table, array $data): string
    {
        $arrayKeyLast = array_key_last($data);
        foreach ($data as $key => $value) {
            $columnsAndValues .= $key . '=:' . $key . ($key != $arrayKeyLast ? ', ' : null);
        }

        return "UPDATE $table SET $columnsAndValues WHERE `id` = :id";
    }
    
    public static function insert(string $table, array $data): string
    {
        $arrayKeyLast = array_key_last($data);
        $columns = '';
        $values = '';
        foreach ($data as $key => $value) {
            $columns .= "`$key`" . ($key != $arrayKeyLast ? ', ' : null);
            $values .= ':' . $key . ($key != $arrayKeyLast ? ', ' : null);
        }

        return "INSERT INTO `$table` ($columns) VALUES ($values)";
    }

    public static function delete(string $table): string
    {
        return "DELETE FROM $table WHERE `id` = :id";
    }
}