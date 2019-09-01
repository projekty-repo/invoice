<?php

class DatabaseRelationsHasOneFinder extends DatabaseRelationsFinder
{
    protected function setRelatedModelsNames(): void
    {
        $this->relatedModelsNames = $this->databaseManager->getModel()->getHasOne();
    }

    protected function findRelatedModel(Model $modelObject, Model $relatedModel, string $foreignKey): Model
    {
        return $relatedModel->getManager()->findById($modelObject->$foreignKey);
    }
}