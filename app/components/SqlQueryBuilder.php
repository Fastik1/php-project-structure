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
        $columnsAndValues = join(
            ', ',
            array_map(
                fn($key) => $key . '=:' . $key,
                array_keys($data)
            )
        );
        return "UPDATE $table SET $columnsAndValues WHERE `id` = :id";
    }

    public static function insert(string $table, array $data): string
    {
        if (count($data) == 0) {
            throw new \PDOException('Не переданы данные');
        }

        $sql = "INSERT INTO `{$table}`";
        $sql .= '(`' . join('`, `', array_keys($data[0])) . '`) VALUES ';
        $sql_row = [];
        foreach ($data as $row) {
            $value = sprintf("('%s')", join("', '", array_values($row)));
            $value = str_replace(['\'true\'', '\'TRUE\''], 'TRUE', $value);
            $value = str_replace(['\'false\'', '\'FALSE\''], 'FALSE', $value);
            $value = str_replace(['\'NULL\'', '\'null\''], 'NULL', $value);

            $sql_row[] = $value;
        }

        $sql .= join(', ', $sql_row);

        return $sql;
    }

    public static function delete(string $table): string
    {
        return "DELETE FROM $table WHERE `id` = :id";
    }
}
