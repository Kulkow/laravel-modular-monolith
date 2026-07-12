<?php

namespace App\Modules\Core\Domain\Sorting;

final class SortSet
{
    /**
     * @var Sort[]
     */
    private array $sorts = [];

    public function add(Sort $sort): self
    {
        $this->sorts[] = $sort;
        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->sorts);
    }

    /**
     * @return Sort[]
     */
    public function all(): array
    {
        return $this->sorts;
    }
}
