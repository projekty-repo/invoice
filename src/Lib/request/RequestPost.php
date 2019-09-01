<?php

class RequestPost extends Request
{

    public function retrieve(string $name): ?string
    {
        if (!isset($_POST[$name])) {

            return null;
        }

        return htmlspecialchars($_POST[$name], ENT_QUOTES);
    }

    public function all(): array
    {
        $postData = $_POST;
        array_walk_recursive($postData, function (&$value) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        });

        return $postData;
    }

    public function has(string $name): bool
    {
        return !empty($this->retrieve($name));
    }

    public function isPost(): bool
    {
        return true;
    }

    public function isEmpty(): bool
    {
        $filteredData = $this->filter($this->all());

        return empty($filteredData);
    }
}