<?php

interface DatabaseManager
{
    public function findById(int $id): Model;

    public function findBy(string $column, string $value): Model;

    public function findAllBy(string $column, string $value, int $limit = null): array;

    public function findAll(): array;

    public function delete(): int;

    public function save(): int;

    public function insert(): int;

    public function getModel(): Model;

    public function getTableColumns(string $tableName): array;

    public function getTableColumnsWithoutId(string $tableName): array;

    public function getModelTableName(): string;

    public function getTableName(string $className): string;

    public function getModelName(string $tableName): string;

    public function prepareModelData(): array;

    public function prepareQuery(string $preparedQuery): PDOStatement;

    public function tableColumnsProperties(): array;
}