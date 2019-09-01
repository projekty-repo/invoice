<?php

class DatabaseRecorder
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    public function recordModel(): void
    {
        $modelData = $this->databaseManager->prepareModelData();
        $modelData['created_at'] = date('Y-m-d H:i:s');

        if (isset($modelData['id'])) {
            unset($modelData['id']);
        }

        $columnsElements = [];
        $valuesElements = [];
        $parameters = [];
        foreach ($modelData as $property => $value) {
            $columnsElements[] = $property;
            $valuesElements[] = ":$property";
            $parameters[":$property"] = $value;
        }
        $columns = implode(', ', $columnsElements);
        $values = implode(', ', $valuesElements);

        $table = $this->databaseManager->getModelTableName();
        $query = "INSERT INTO $table ($columns) VALUES ($values)";

        $pdoStatement = $this->databaseManager->prepareQuery($query);
        $pdoStatement->execute($parameters);
    }

    public function recordHasOne(): void
    {
        $hasOne = DatabaseRelations::createHasOne($this->databaseManager);
        $relatedModelsNames = $hasOne->getRelatedModelsNames();
        if (!$relatedModelsNames) {

            return;
        }

        $model = $this->databaseManager->getModel();
        foreach ($relatedModelsNames as $relatedModelName) {
            $relatedModelPropertyName = $hasOne->generatePropertyName($relatedModelName);
            $relatedModel = $model->$relatedModelPropertyName ?? null;
            if (!$relatedModel) {
                continue;
            }

            $modelForeignKeyName = $hasOne->generateForeignKey($relatedModel);
            $model->$modelForeignKeyName = $relatedModel->getManager()->save();
        }
    }

    public function recordHasMany(): void
    {
        $hasMany = DatabaseRelations::createHasMany($this->databaseManager);
        $relatedModelsNames = $hasMany->getRelatedModelsNames();
        if (!$relatedModelsNames) {

            return;
        }

        $model = $this->databaseManager->getModel();
        $modelForeignKeyName = $hasMany->generateForeignKey($model);
        foreach ($relatedModelsNames as $relatedModelName) {
            $relatedModelPropertyName = $hasMany->generatePropertyName($relatedModelName);
            if (empty($model->$relatedModelPropertyName)) {
                continue;
            }
            foreach ($model->$relatedModelPropertyName as $relatedModel) {
                $relatedModel->$modelForeignKeyName = $model->id;
                $relatedModel->getManager()->save();
            }
        }
    }
}