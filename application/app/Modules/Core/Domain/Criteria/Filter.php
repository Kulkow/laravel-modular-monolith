<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Criteria;

final class Filter
{
    public function __construct(
        private string $field,
        private mixed $value,
        private string $operator = '='
    ) {}

    public function getField(): string { return $this->field; }
    public function getValue(): mixed { return $this->value; }
    public function getOperator(): string { return $this->operator; }
}
