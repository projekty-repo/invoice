<?php

abstract class DatabaseRelations
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;

    /**
     * @var array
     */
    protected $relatedModelsNames;

    public static function createHasMany(DatabaseManager $databaseManager): DatabaseRelations
    {
        return new DatabaseRelationsHasMany($databaseManager);
    }

    public static function createHasOne(DatabaseManager $databaseManager): DatabaseRelations
    {
        return new DatabaseRelationsHasOne($databaseManager);
    }

    public static function createBelongsTo(DatabaseManager $databaseManager): DatabaseRelations
    {
        return new DatabaseRelationsBelongsTo($databaseManager);
    }

    protected function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
        $this->setRelatedModelsNames();
    }

    abstract protected function setRelatedModelsNames(): void;

    public function getRelatedModelsNames(): array
    {
        return $this->relatedModelsNames;
    }

    public function generateForeignKey(Model $model): string
    {
        return $this->databaseManager->getTableName(get_class($model)) . '_id';
    }

    public function generatePropertyName(string $relatedModelName): string
    {
        return lcfirst($relatedModelName);
    }

    abstract public function getRelatedModelsKeys(Model $model): array;

    final protected function getRelatedForenignKeys(array $relatedModels, DatabaseRelations $databaseRelations): array
    {
        $relatedModelsKeys = [];
        if (!$relatedModels) {
            return $relatedModelsKeys;
        }

        foreach ($relatedModels as $relatedModel) {
            $relatedModelsKeys[] = $databaseRelations->generateForeignKey(new $relatedModel());
        }

        return $relatedModelsKeys;
    }
}