<?php

class DatabaseRelationsHasManyFinder extends DatabaseRelationsFinder
{
    protected function setRelatedModelsNames(): void
    {
        $this->relatedModelsNames = $this->databaseManager->getModel()->getHasMany();
    }

    protected function generateForeignKey(Model $relatedModel): string
    {
        return parent::generateForeignKey($this->databaseManager->getModel());
    }

    protected function generatePropertyName(string $relatedModelName): string
    {
        return parent::generatePropertyName($relatedModelName) . 's';
    }

    protected function findRelatedModel(Model $modelObject, Model $relatedModel, string $foreignKey): array
    {
        return $relatedModel->getManager()->findAllBy($foreignKey, $modelObject->id);
    }
}