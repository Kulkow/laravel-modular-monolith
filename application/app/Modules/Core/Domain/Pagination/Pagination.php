<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Pagination;
final class Pagination
{
    public function __construct(
        private int $page = 1,
        private int $perPage = 15
    ) {}

    public function getPage(): int { return $this->page; }
    public function getPerPage(): int { return $this->perPage; }
}
