<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Pagination;

/**
 * @template T
 */
final readonly class PaginatedResult
{
    /**
     * @param T[] $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param int $lastPage
     */
    public function __construct(
        private array $items,
        private int $total,
        private int $perPage,
        private int $currentPage,
        private int $lastPage,
    ) {}

    /**
     * @return T[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int { return $this->total; }
    public function getPerPage(): int { return $this->perPage; }
    public function getCurrentPage(): int { return $this->currentPage; }
    public function getLastPage(): int { return $this->lastPage; }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }
}
