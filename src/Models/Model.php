<?php

interface Model
{
    public function setValuesToProperties(array $modelData): void;

    public function addHasMany(string $modelName): void;

    public function getHasMany(): array;

    public function addHasOne(string $modelName): void;

    public function getHasOne(): array;

    public function addBelongsTo(string $modelName): void;

    public function getBelongsTo(): array;

    public function getManager(): DatabaseManager;
}