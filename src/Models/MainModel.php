<?php

abstract class MainModel implements Model
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    protected $hasOne = [];
    protected $hasMany = [];
    protected $belongsTo = [];

    private const HAS_ONE = 'has_one';
    private const HAS_MANY = 'has_many';

    public function __construct(array $modelData = null)
    {
        $this->databaseManager = new DatabaseManagerImplementation($this);
        $this->initialize();

        if ($modelData) {
            $modelData = $this->moveModelDataUp($modelData);
            $this->setValuesToProperties($modelData);
        } else {
            $this->setNullToProperties();
        }
    }

    protected function initialize(): void
    {

    }

    private function moveModelDataUp(array $modelData): array
    {
        $tableName = $this->getTableName();
        if (empty($modelData[$tableName])) {

            return $modelData;
        }

        $modelData = array_merge($modelData[$tableName], $modelData);
        unset($modelData[$tableName]);

        return $modelData;
    }

    private function setNullToProperties(): void
    {
        $tableName = $this->getTableName();
        $columns = $this->databaseManager->getTableColumnsWithoutId($tableName);
        foreach ($columns as $column) {
            $this->$column = null;
        }

        $this->setNullToHasOneRelations();
        $this->setNullToHasManyRelations();
    }

    private function setNullToHasOneRelations(): void
    {
        $hasOne = DatabaseRelations::createHasOne($this->databaseManager);
        foreach ($this->getHasOne() as $hasOneRelatedModelName) {
            $propertyName = $hasOne->generatePropertyName($hasOneRelatedModelName);
            $this->$propertyName = new $hasOneRelatedModelName();
        }
    }

    private function setNullToHasManyRelations(): void
    {
        $hasMany = DatabaseRelations::createHasMany($this->databaseManager);
        foreach ($this->getHasMany() as $hasManyRelatedModelName) {
            $propertyName = $hasMany->generatePropertyName($hasManyRelatedModelName);
            $this->$propertyName[] = new $hasManyRelatedModelName();
        }
    }

    public function setValuesToProperties(array $modelData): void
    {
        $tableName = $this->getTableName();
        $columns = $this->databaseManager->getTableColumns($tableName);
        foreach ($columns as $column) {
            $this->$column = !empty($modelData[$column]) ? trim($modelData[$column]) : null;
        }

        $this->setValuesToHasManyRelations($modelData, $columns);
        $this->setValuesToHasOneRelations($modelData, $columns);
    }

    private function setValuesToHasManyRelations(array $modelData, array $columns): void
    {
        $this->setValuesToRelations(self::HAS_MANY, $modelData, $columns);
    }

    private function setValuesToHasOneRelations(array $modelData, array $columns): void
    {
        $this->setValuesToRelations(self::HAS_ONE, $modelData, $columns);
    }

    private function setValuesToRelations(string $relationName, array $modelData, array $columns): void
    {
        $className = get_class($this);
        $model = new $className();

        if ($relationName === self::HAS_ONE) {
            $relatedModels = $model->getHasOne();
        } elseif ($relationName === self::HAS_MANY) {
            $relatedModels = $model->getHasMany();
        } else {
            throw new RuntimeException('parameter $relationName should have the value ' . self::HAS_ONE . ' or ' . self::HAS_MANY);
        }

        $modelDataWithoutColumns = array_diff(array_keys($modelData), $columns);
        foreach ($modelDataWithoutColumns as $potentialRelationship) {
            $modelName = $this->databaseManager->getModelName($potentialRelationship);
            if (!class_exists($modelName) && !class_exists(rtrim($modelName, 's'))) {
                continue;
            }

            if ($relationName === self::HAS_ONE) {
                $this->setHasOne($modelName, $relatedModels, $modelData, $potentialRelationship);
            } elseif ($relationName === self::HAS_MANY) {
                $this->setHasMany($modelName, $relatedModels, $modelData, $potentialRelationship);
            }
        }
    }

    private function setHasOne(string $modelName, array $hasOneModels, array $modelData, string $potentialRelationship): void
    {
        if (!in_array($modelName, $hasOneModels, true)) {

            return;
        }

        $this->$potentialRelationship = new $modelName($modelData[$potentialRelationship]);
    }

    private function setHasMany(string $modelName, array $hasManyModelsNames, array $modelData, string $potentialRelationship): void
    {
        foreach ($hasManyModelsNames as $hasManyModelName) {
            if (rtrim($modelName, 's') !== $hasManyModelName) {
                continue;
            }

            $hasMany = DatabaseRelations::createHasMany($this->databaseManager);
            $propertyName = $hasMany->generatePropertyName($hasManyModelName);

            foreach ($modelData[$potentialRelationship] as $index => $relatedModelData) {
                $this->$propertyName[$index] = new $hasManyModelName($relatedModelData);
            }
        }
    }

    private function getTableName(): string
    {
        $className = get_class($this);

        return $this->databaseManager->getTableName($className);
    }

    final public function addHasMany(string $modelName): void
    {
        $this->hasMany[] = $modelName;
    }

    final public function getHasMany(): array
    {
        return $this->hasMany;
    }

    final public function addHasOne(string $modelName): void
    {
        $this->hasOne[] = $modelName;
    }

    final public function getHasOne(): array
    {
        return $this->hasOne;
    }

    final public function addBelongsTo(string $modelName): void
    {
        $this->belongsTo[] = $modelName;
    }

    final public function getBelongsTo(): array
    {
        return $this->belongsTo;
    }

    final public function getManager(): DatabaseManager
    {
        return $this->databaseManager;
    }
}

trait ModelTrait
{
    public static function createManager(): DatabaseManager
    {
        $self = new self();

        return new DatabaseManagerImplementation($self);
    }
}