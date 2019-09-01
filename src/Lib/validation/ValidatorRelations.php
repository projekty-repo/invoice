<?php

abstract class ValidatorRelations
{
    /**
     * @var Validator
     */
    protected $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    abstract public function valdate(Model $model, string $prefix): void;

    protected function validateRelatedModel(Model $model, string $prefix, DatabaseRelations $databaseRelations, array $relatedModelsNames): void
    {
        foreach ($relatedModelsNames as $relatedModelName) {
            $relatedModelPropertyName = $databaseRelations->generatePropertyName($relatedModelName);
            if (empty($model->$relatedModelPropertyName)) {
                continue;
            }

            $this->executeValidator($model, $relatedModelPropertyName, $prefix);
        }
    }

    abstract protected function executeValidator(Model $model, string $relatedModelPropertyName, string $prefix): void;
}