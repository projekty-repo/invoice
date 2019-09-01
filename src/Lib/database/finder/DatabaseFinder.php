<?php

class DatabaseFinder
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function find(string $table, string $column = null, string $value = null, int $limit = null): array
    {
        $modelDataFromDatabase = $this->select($table, $column, $value, $limit);

        $modelObjects = [];
        $modelClassName = get_class($this->databaseManager->getModel());
        foreach ($modelDataFromDatabase as $modelData) {
            $modelObjects[] = new $modelClassName($modelData);
        }

        $modelObjects = DatabaseRelationsFinder::findHasMany($this->databaseManager, $modelObjects);
        $modelObjects = DatabaseRelationsFinder::findHasOne($this->databaseManager, $modelObjects);

        return $modelObjects;
    }

    private function select(string $table, string $column = null, string $value = null, int $limit = null): array
    {
        $query = $this->prepareQuery($table, $column, $value, $limit);
        $pdoStatement = $this->databaseManager->prepareQuery($query);
        $pdoStatement = $this->bindParameters($pdoStatement, $column, $value, $limit);

        $pdoStatement->execute();

        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function prepareQuery(string $table, string $column = null, string $value = null, int $limit = null): string
    {
        $query = "SELECT * FROM $table WHERE is_deleted = false";

        if ($column && $value) {
            $query .= " AND $column = :value";
        }

        if ($limit) {
            $query .= ' LIMIT :limit';
        }

        return $query;
    }

    private function bindParameters(PDOStatement $pdoStatement, string $column = null, string $value = null, int $limit = null): PDOStatement
    {
        if ($column && $value) {
            $pdoStatement->bindParam(':value', $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        if ($limit) {
            $pdoStatement->bindParam(':limit', $limit, PDO::PARAM_INT);
        }

        return $pdoStatement;
    }
}