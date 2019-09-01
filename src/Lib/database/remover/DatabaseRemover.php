<?php

class DatabaseRemover
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function softDelete(): int
    {
        $table = $this->databaseManager->getModelTableName();
        $query = "UPDATE $table SET is_deleted = 1 WHERE id = :id";
        $pdoStatement = $this->databaseManager->prepareQuery($query);
        $id = $this->databaseManager->getModel()->id;
        $parameters[':id'] = $id;
        $pdoStatement->execute($parameters);

        return $id;
    }

    public function deleteHasMany(): void
    {
        $hasMany = DatabaseRelations::createHasMany($this->databaseManager);
        $model = $this->databaseManager->getModel();
        $modelForeignKeyName = $hasMany->generateForeignKey($model);

        $relatedModels = $hasMany->getRelatedModelsNames();
        foreach ($relatedModels as $relatedModel) {
            $relatedModelTableName = $this->databaseManager->getTableName($relatedModel);

            $query = "DELETE FROM $relatedModelTableName WHERE $modelForeignKeyName = :id";

            $id = $model->id;
            $pdoStatement = $this->databaseManager->prepareQuery($query);
            $pdoStatement->bindParam(':id', $id, PDO::PARAM_INT);
            $pdoStatement->execute();
        }
    }
}