<?php

class DatabaseUpdater
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function updateModel(): void
    {
        $databaseManager = $this->databaseManager;
        $table = $databaseManager->getModelTableName();
        $query = "UPDATE $table SET ";

        $modelData = $databaseManager->prepareModelData();
        $modelData['updated_at'] = date('Y-m-d H:i:s');

        $queryElements = [];
        $parameters = [];
        foreach ($modelData as $column => $value) {
            $queryElements[] = "$column = :$column";
            $parameters[":$column"] = $value;
        }

        $query .= implode(', ', $queryElements);

        $query .= ' WHERE id = :id';
        $parameters[':id'] = $databaseManager->getModel()->id;

        $pdoStatement = $databaseManager->prepareQuery($query);
        $pdoStatement->execute($parameters);
    }

    public function updateHasOne(): void
    {
        $hasOne = DatabaseRelations::createHasOne($this->databaseManager);
        $relatedModelsNames = $hasOne->getRelatedModelsNames();
        if (!$relatedModelsNames) {

            return;
        }

        $model = $this->databaseManager->getModel();
        foreach ($relatedModelsNames as $relatedModelName) {
            $relatedModelTableName = $this->databaseManager->getTableName($relatedModelName);
            $relatedModel = $this->databaseManager->getModel()->$relatedModelTableName ?? null;
            if (!$relatedModel) {
                continue;
            }

            $modelForeignKeyName = $hasOne->generateForeignKey($relatedModel);
            $model->$modelForeignKeyName = $relatedModel->getManager()->save();
        }
    }

    public function updateHasMany(): void
    {
        $remover = new DatabaseRemover($this->databaseManager);
        $remover->deleteHasMany();
        $this->convertHasManyUpdateToInsert();
        $recorder = new DatabaseRecorder($this->databaseManager);
        $recorder->recordHasMany();
    }

    private function convertHasManyUpdateToInsert(): void
    {
        $hasMany = DatabaseRelations::createHasMany($this->databaseManager);
        $relatedModelsNames = $hasMany->getRelatedModelsNames();
        if (!$relatedModelsNames) {

            return;
        }

        $model = $this->databaseManager->getModel();
        foreach ($relatedModelsNames as $relatedModelName) {
            $relatedModelTableName = $this->databaseManager->getTableName($relatedModelName);
            if (empty($model->$relatedModelTableName)) {
                continue;
            }

            foreach ($model->$relatedModelTableName as $relatedModel) {
                $relatedModel->id = null;
            }
        }
    }
}