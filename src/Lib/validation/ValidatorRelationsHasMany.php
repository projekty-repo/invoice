<?php

class ValidatorRelationsHasMany extends ValidatorRelations
{
    public function valdate(Model $model, string $prefix): void
    {
        $databaseManager = $model->getManager();
        $hasMany = DatabaseRelations::createHasMany($databaseManager);
        $relatedModelsNames = $model->getHasMany();

        $this->validateRelatedModel($model, $prefix, $hasMany, $relatedModelsNames);
    }

    protected function executeValidator(Model $model, string $relatedModelPropertyName, string $prefix): void
    {
        foreach ($model->$relatedModelPropertyName as $index => $relatedModel) {
            $relatedPrefix = $prefix . '.' . $relatedModelPropertyName . '.' . $index;
            $this->validator->validate($relatedModel, $relatedPrefix);
        }
    }
}