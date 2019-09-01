<?php

interface Validator
{
    public function validate(Model $model, string $prefix = null): void;

    public function getErrors(): array;
}