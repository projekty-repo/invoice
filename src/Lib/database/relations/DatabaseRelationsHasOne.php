<?php

class DatabaseRelationsHasOne extends DatabaseRelations
{
    protected function setRelatedModelsNames(): void
    {
        $this->relatedModelsNames = $this->databaseManager->getModel()->getHasOne();
    }

    public function getRelatedModelsKeys(Model $model): array
    {
        $hasOne = new self($model->getManager());

        return $this->getRelatedForenignKeys($model->getHasOne(), $hasOne);
    }
}