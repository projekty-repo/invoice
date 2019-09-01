<?php

class ValidatorRelationsHasOne extends ValidatorRelations
{
    public function valdate(Model $model, string $prefix): void
    {
        $databaseManager = $model->getManager();
        $hasOne = DatabaseRelations::createHasOne($databaseManager);
        $relatedModelsNames = $model->getHasOne();

        $this->validateRelatedModel($model, $prefix, $hasOne, $relatedModelsNames);
    }

    protected function executeValidator(Model $model, string $relatedModelPropertyName, string $prefix): void
    {
        $relatedModel = $model->$relatedModelPropertyName;
        $relatedPrefix = $prefix . '.' . $relatedModelPropertyName;
        $this->validator->validate($relatedModel, $relatedPrefix);
    }
}