<?php

class DatabaseRelationsBelongsTo extends DatabaseRelations
{
    protected function setRelatedModelsNames(): void
    {
        $this->relatedModelsNames = $this->databaseManager->getModel()->getBelongsTo();
    }

    public function getRelatedModelsKeys(Model $model): array
    {
        $belongsTo = new self($model->getManager());

        return $this->getRelatedForenignKeys($model->getBelongsTo(), $belongsTo);
    }
}