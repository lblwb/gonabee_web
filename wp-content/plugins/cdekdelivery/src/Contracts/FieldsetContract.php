<?php

declare(strict_types=1);

namespace {

    defined('ABSPATH') or exit;
}

namespace Cdek\Contracts {

    use InvalidArgumentException;

    abstract class FieldsetContract
    {
        final public function getFieldsNames(): array
        {
            return array_keys($this->getFields());
        }

        abstract protected function getFields(): array;

        final public function isRequiredField(string $fieldName): bool
        {
            $fieldList = $this->getFields();
            if (!isset($fieldList[$fieldName])) {
                throw new InvalidArgumentException('Field not found');
            }

            return $fieldList[$fieldName]['required'] ?? false;
        }

        abstract public function isApplicable(): bool;

        final public function getFieldDefinition(string $fieldName): array
        {
            $fieldList = $this->getFields();
            if (!isset($fieldList[$fieldName])) {
                throw new InvalidArgumentException('Field not found');
            }

            return $fieldList[$fieldName];
        }
    }
}
