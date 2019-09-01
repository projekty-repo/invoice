<?php

class DatabaseManagerImplementation implements DatabaseManager
{
    /**
     * @var DatabaseConnection|PDO
     */
    private $databaseConnection;

    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->databaseConnection = DatabaseConnection::getInstance();
        $this->model = $model;
    }

    public function findById(int $id): Model
    {
        return $this->findBy('id', $id);
    }

    public function findBy(string $column, string $value): Model
    {
        $findResults = $this->findAllBy($column, $value, 1);
        if (!$findResults) {
            $modelName = get_class($this->model);

            return new $modelName();
        }

        return $findResults[0];
    }

    public function findAllBy(string $column, string $value, int $limit = null): array
    {
        $tableName = $this->getModelTableName();
        $finder = new DatabaseFinder($this);

        return $finder->find($tableName, $column, $value, $limit);
    }

    public function findAll(): array
    {
        $tableName = $this->getModelTableName();
        $finder = new DatabaseFinder($this);

        return $finder->find($tableName);
    }

    public function delete(): int
    {
        $remover = new DatabaseRemover($this);

        return $remover->softDelete();
    }

    public function save(): int
    {
        if (!empty($this->model->id)) {

            return $this->update();
        } else {

            return $this->insert();
        }
    }

    private function update(): int
    {
        $updater = new DatabaseUpdater($this);
        $updater->updateHasOne();
        $updater->updateModel();
        $updater->updateHasMany();

        return $this->model->id;
    }

    public function insert(): int
    {
        $recorder = new DatabaseRecorder($this);
        $recorder->recordHasOne();
        $recorder->recordModel();
        $this->model->id = $this->databaseConnection->lastInsertId();
        $recorder->recordHasMany();

        return $this->model->id;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    public function getTableColumns(string $tableName): array
    {
        $query = $this->databaseConnection->query("DESCRIBE $tableName");
        $tableColumns = $query->fetchAll(PDO::FETCH_COLUMN);
        $tableColumns = $this->unsetUnnecessaryColumns($tableColumns, ['updated_at', 'created_at', 'is_deleted']);

        return $tableColumns;
    }

    private function unsetUnnecessaryColumns(array $tableColumns, array $unnecessaryColumns): array
    {
        foreach ($unnecessaryColumns as $unnecessaryColumn) {
            $valueIndex = array_search($unnecessaryColumn, $tableColumns, true);
            if (!$valueIndex) {
                continue;
            }
            unset($tableColumns[$valueIndex]);
        }

        return $tableColumns;
    }

    public function getTableColumnsWithoutId(string $tableName): array
    {
        $tableColumns = $this->getTableColumns($tableName);
        $idIndex = array_search('id', $tableColumns, true);
        if ($idIndex >= 0) {
            unset($tableColumns[$idIndex]);
        }

        return $tableColumns;
    }

    public function getModelTableName(): string
    {
        $modelName = get_class($this->model);

        return $this->getTableName($modelName);
    }

    public function getTableName(string $className): string
    {
        $underscoreBeforeEveryCapitalLetters = preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $className);
        $lowerCase = strtolower($underscoreBeforeEveryCapitalLetters);
        $withoutUnderliningAtTheBeginning = ltrim($lowerCase, '_');

        return $withoutUnderliningAtTheBeginning;
    }

    public function getModelName(string $tableName): string
    {
        $underlineInsteadOfSpaces = str_replace('_', ' ', $tableName);
        $uppercaseFirstCharacterOfEachWord = ucwords($underlineInsteadOfSpaces);
        $noSpacesBetweenWords = str_replace(' ', '', $uppercaseFirstCharacterOfEachWord);

        return $noSpacesBetweenWords;
    }

    public function prepareModelData(): array
    {
        $tableName = $this->getModelTableName();
        $columns = $this->getTableColumnsWithoutId($tableName);

        $modelData = [];
        foreach ($columns as $column) {
            if (!isset($this->model->$column)) {
                continue;
            }
            $modelData[$column] = $this->model->$column;
        }

        return $modelData;
    }

    public function prepareQuery(string $preparedQuery): PDOStatement
    {
        return $this->databaseConnection->prepare($preparedQuery);
    }

    public function tableColumnsProperties(): array
    {
        $table = $this->getModelTableName();

        return $this->databaseConnection->query("SHOW COLUMNS FROM $table")->fetchAll(PDO::FETCH_ASSOC);
    }
}