<?php


declare(strict_types=1);

namespace App\Modules\Core\Domain\Criteria;


use App\Modules\Core\Domain\Pagination\Pagination;
use App\Modules\Core\Domain\Sorting\SortSet;

final class Criteria
{
    private ?Pagination $pagination = null;
    private ?SortSet $sortSet = null;
    private array $filters = [];

    public function setPagination(?Pagination $pagination): self
    {
        $this->pagination = $pagination;
        return $this;
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }

    public function setSortSet(?SortSet $sortSet): self
    {
        $this->sortSet = $sortSet;
        return $this;
    }

    public function getSortSet(): ?SortSet
    {
        return $this->sortSet;
    }

    /**
     * Добавить фильтр (поддерживаются =, >, <, >=, <=, like, in)
     */
    public function addFilter(string $field, mixed $value, string $operator = '='): self
    {
        $this->filters[] = new Filter($field, $value, $operator);
        return $this;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}
