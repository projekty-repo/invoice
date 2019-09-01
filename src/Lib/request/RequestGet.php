<?php

class RequestGet extends Request
{

    public function retrieve(string $name): ?string
    {
        if (!isset($_GET[$name])) {

            return null;
        }

        return htmlspecialchars($_GET[$name], ENT_QUOTES);
    }

    public function all(): array
    {
        $getData = $_GET;
        array_walk_recursive($getData, function (&$value) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        });

        return $getData;
    }

    public function has(string $name): bool
    {
        return !empty($this->retrieve($name));
    }

    public function isGet(): bool
    {
        return true;
    }

    public function isEmpty(): bool
    {
        $filteredData = $this->filter($this->all());

        return empty($filteredData);
    }
}