<?php

interface RequestInterface
{
    public function retrieve(string $name): ?string;

    public function all(): array;

    public function has(string $name): bool;

    public function isEmpty(): bool;

    public function isPost(): bool;

    public function isGet(): bool;
}