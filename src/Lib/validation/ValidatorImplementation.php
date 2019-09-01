<?php

class ValidatorImplementation implements Validator
{
    private $errors = [];

    private $numericTypesValues = [
        'tinyint' => [
            'min' => -128,
            'max' => 127,
            'unsigned' => 255,
        ],
        'smallint' => [
            'min' => -32768,
            'max' => 32767,
            'unsigned' => 65595,
        ],
        'mediumint' => [
            'min' => -8388608,
            'max' => 8388607,
            'unsigned' => 16777245,
        ],
        'int' => [
            'min' => -2147483648,
            'max' => 2147493647,
            'unsigned' => 4294967295,
        ],
        'bigint' => [
            'min' => -9223372036854775808,
            'max' => 9223372036854775807,
            'unsigned' => 18446744073709551615,
        ],
    ];

    private $textTypes = [
        'varchar',
        'char',
    ];

    private $numericTypes = [
        'tinyint',
        'smallint',
        'mediumint',
        'int',
    ];

    private $datetimeType = 'datetime';

    private $dateType = 'date';

    private const NOT_NULL_MESSAGE = 'Wartość nie może być pusta';
    private const NOT_NUMERIC_MESSAGE = 'Wartość musi być liczbą';
    private const TOO_LONG_TEXT_MESSAGE = 'Tekst nie może mieć najwyżej %d znaki';
    private const NUMBER_TO0_BIG_MESSAGE = 'Liczba nie może być większa niż %d';
    private const NUMBER_TO0_SMALL_MESSAGE = 'Liczba nie może być mniejsza niż %d';
    private const WRONG_DATE_FORMAT_MESSAGE = 'Data powinna być w formacie YYYY-MM-DD';
    private const WRONG_DATETIME_FORMAT_MESSAGE = 'Data z czasem powinna być w formacie YYYY-MM-DD HH:MI:SS';

    public function validate(Model $model, string $prefix = null): void
    {
        $databaseManager = $model->getManager();
        $prefix = $prefix ?? $databaseManager->getModelTableName();

        $hasOneValidator = new ValidatorRelationsHasOne($this);
        $hasOneValidator->valdate($model, $prefix);

        $hasManyValidator = new ValidatorRelationsHasMany($this);
        $hasManyValidator->valdate($model, $prefix);

        foreach ($this->validateModel($model) as $fieldName => $errorMessage) {
            $this->errors[$prefix . '.' . $fieldName] = $errorMessage;
        }
    }

    private function validateModel(Model $model): array
    {
        $errors = [];
        $columnsValidatorsProperies = $this->setColumnsValidatorsProperties($model);

        foreach ($columnsValidatorsProperies as $columnName => $columnProperties) {
            if ($columnProperties['not_null'] && is_null($model->$columnName)) {
                $errors[$columnName] = self::NOT_NULL_MESSAGE;
                continue;
            }
            if ($columnProperties['is_numeric']) {
                if (!is_numeric($model->$columnName)) {
                    $errors[$columnName] = self::NOT_NUMERIC_MESSAGE;
                }
                if ($model->$columnName > $columnProperties['max_value']) {
                    $errors[$columnName] = sprintf(self::NUMBER_TO0_BIG_MESSAGE, $columnProperties['max_value']);
                }
                if ($model->$columnName < $columnProperties['min_value']) {
                    $errors[$columnName] = sprintf(self::NUMBER_TO0_SMALL_MESSAGE, $columnProperties['min_value']);
                }
            }
            if ($columnProperties['is_string'] && strlen($model->$columnName) > $columnProperties['length']) {
                $errors[$columnName] = sprintf(self::TOO_LONG_TEXT_MESSAGE, $columnProperties['length']);
            }
            if ($columnProperties['is_date'] && !preg_match('/\d{4}-\d{2}-\d{2}/', $model->$columnName)) {
                $errors[$columnName] = self::WRONG_DATE_FORMAT_MESSAGE;
            }
            if ($columnProperties['is_datetime'] && !preg_match('/\d{4}(-\d{2}){2} (:?\d{2}){3}/', $model->$columnName)) {
                $errors[$columnName] = self::WRONG_DATETIME_FORMAT_MESSAGE;
            }
        }

        return $errors;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->errors as $key => $message) {
            $arrayKeys = "['" . str_replace('.', "']['", $key) . "']";
            eval('$errors' . "$arrayKeys='$message';");
        }

        return $errors;
    }

    private function setColumnsValidatorsProperties(Model $model): array
    {
        $databaseManager = $model->getManager();
        $requiredColumns = $databaseManager->getTableColumnsWithoutId($databaseManager->getModelTableName());

        $hasOne = DatabaseRelations::createHasOne($databaseManager);
        $relatedModelsKey = $hasOne->getRelatedModelsKeys($model);

        $belongsTo = DatabaseRelations::createBelongsTo($databaseManager);
        $relatedModelsKey += $belongsTo->getRelatedModelsKeys($model);

        $columnsValidatorsProperies = [];
        $tableColumnsProperties = $databaseManager->tableColumnsProperties();
        foreach ($tableColumnsProperties as $tableColumnProperties) {
            $fieldName = $tableColumnProperties['Field'];
            if (in_array($fieldName, $relatedModelsKey, true)) {
                continue;
            }

            if (!in_array($fieldName, $requiredColumns, true)) {
                continue;
            }

            $columnsValidatorsProperies += $this->setColumnValidatorsProperies($fieldName, $tableColumnProperties);
        }

        return $columnsValidatorsProperies;
    }

    private function setColumnValidatorsProperies(string $fieldName, array $tableColumnProperties): array
    {
        $columnsValidatorsProperies[$fieldName] = [];
        $columnsValidatorsProperies[$fieldName] += $this->setNotNull($tableColumnProperties);
        $columnsValidatorsProperies[$fieldName] += $this->setIsTypes($tableColumnProperties);
        $columnsValidatorsProperies[$fieldName] += $this->setUnsigned($tableColumnProperties);

        $isNumeric = $columnsValidatorsProperies[$fieldName]['is_numeric'];
        $unsigned = $columnsValidatorsProperies[$fieldName]['unsigned'];
        $columnsValidatorsProperies[$fieldName] += $this->setMaxAndMinValue($tableColumnProperties, $isNumeric, $unsigned);

        $isString = $columnsValidatorsProperies[$fieldName]['is_string'];
        $columnsValidatorsProperies[$fieldName] += $this->setLength($tableColumnProperties, $isString);

        return $columnsValidatorsProperies;
    }

    private function setNotNull(array $tableColumnProperties): array
    {
        return ['not_null' => $tableColumnProperties['Null'] === 'NO'];
    }

    private function setIsTypes(array $tableColumnProperties): array
    {
        $type = $this->getType($tableColumnProperties['Type']);

        return [
            'is_string' => in_array($type, $this->textTypes, true),
            'is_numeric' => in_array($type, $this->numericTypes, true),
            'is_date' => $type === $this->dateType,
            'is_datetime' => $type === $this->datetimeType,
        ];
    }

    private function setUnsigned(array $tableColumnProperties): array
    {
        $type = $this->getType($tableColumnProperties['Type']);

        return ['unsigned' => strpos($type, 'unsigned') >= 0];
    }

    private function setMaxAndMinValue(array $tableColumnProperties, bool $isNumeric, bool $unsigned): array
    {
        $type = $this->getType($tableColumnProperties['Type']);

        if (!$isNumeric) {
            return [
                'max_value' => null,
                'min_value' => null,
            ];
        }

        return [
            'max_value' => $unsigned ? $this->numericTypesValues[$type]['unsigned'] : $this->numericTypesValues[$type]['max'],
            'min_value' => $unsigned ? 0 : $this->numericTypesValues[$type]['min'],
        ];
    }

    private function setLength(array $tableColumnProperties, bool $isString): array
    {
        if (!$isString) {
            return ['length' => null];
        }

        preg_match('/(\d){1,}/', $tableColumnProperties['Type'], $typeNumber);
        $number = $typeNumber[0] ?? null;

        return ['length' => $number];
    }

    private function getType(string $mysqlType): string
    {
        preg_match('/[a-z]*/', $mysqlType, $typeText);

        return $typeText[0];
    }
}