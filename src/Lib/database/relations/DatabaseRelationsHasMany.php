<?php

class DatabaseRelationsHasMany extends DatabaseRelations
{
    protected function setRelatedModelsNames(): void
    {
        $this->relatedModelsNames = $this->databaseManager->getModel()->getHasMany();
    }

    public function generateForeignKey(Model $relatedModel): string
    {
        return parent::generateForeignKey($this->databaseManager->getModel());
    }

    public function generatePropertyName(string $relatedModelName): string
    {
        return parent::generatePropertyName($relatedModelName) . 's';
    }

    public function getRelatedModelsKeys(Model $model): array
    {
        $hasMany = new self($model->getManager());

        return $this->getRelatedForenignKeys($model->getHasMany(), $hasMany);
    }
}