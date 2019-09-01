<?php

abstract class DatabaseRelationsFinder
{
    /**
     * @var array
     */
    protected $modelObjects;

    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var array
     */
    protected $relatedModelsNames;

    protected function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public static function findHasMany(DatabaseManager $databaseManager, array $modelObjects): array
    {
        $relationsFinder = new DatabaseRelationsHasManyFinder($databaseManager);

        return $relationsFinder->addRelatedObjects($modelObjects);
    }

    public static function findHasOne(DatabaseManager $databaseManager, array $modelObjects): array
    {
        $relationsFinder = new DatabaseRelationsHasOneFinder($databaseManager);

        return $relationsFinder->addRelatedObjects($modelObjects);
    }

    public function addRelatedObjects(array $modelObjects): array
    {
        $this->modelObjects = $modelObjects;
        $this->setRelatedModelsNames();

        if (!$this->relationExist()) {

            return $modelObjects;
        }

        foreach ($this->modelObjects as $modelObject) {
            foreach ($this->relatedModelsNames as $relatedModelName) {
                $relatedModel = new $relatedModelName();
                $foreignKey = $this->generateForeignKey($relatedModel);
                $propertyName = $this->generatePropertyName($relatedModelName);
                $modelObject->$propertyName = $this->findRelatedModel($modelObject, $relatedModel, $foreignKey);
            }
        }

        return $modelObjects;
    }

    private function relationExist(): bool
    {
        return !empty($this->relatedModelsNames);
    }

    abstract protected function setRelatedModelsNames(): void;

    protected function generateForeignKey(Model $model): string
    {
        return $this->databaseManager->getTableName(get_class($model)) . '_id';
    }

    protected function generatePropertyName(string $relatedModelName): string
    {
        return lcfirst($relatedModelName);
    }

    abstract protected function findRelatedModel(Model $modelObject, Model $relatedModel, string $foreignKey);
}