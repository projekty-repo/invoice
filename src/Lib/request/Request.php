<?php

class Request implements RequestInterface
{
    private function __construct()
    {

    }

    public static function create(): Request
    {
        $post = new RequestPost();
        $get = new RequestGet();

        if (!$post->isEmpty()) {

            return $post;
        } elseif (!$get->isEmpty()) {

            return $get;
        } else {

            return new self();
        }
    }

    public static function createForRouter(): Request
    {
        return new RequestGet();
    }

    public function retrieve(string $name): ?string
    {
        return null;
    }

    public function all(): array
    {
        return [];
    }

    public function has(string $name): bool
    {
        return false;
    }

    public function isPost(): bool
    {
        return false;
    }

    public function isGet(): bool
    {
        return false;
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function filter(array $input = []): array
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->filter($value);
            } else {
                $value = trim($value);
            }
        }

        return array_filter($input);
    }
}