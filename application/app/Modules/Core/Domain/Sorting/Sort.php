<?php


namespace App\Modules\Core\Domain\Sorting;

final class Sort
{
    public function __construct(
        private string        $field,
        private SortDirection $direction = SortDirection::Asc
    )
    {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getDirection(): SortDirection
    {
        return $this->direction;
    }
}
