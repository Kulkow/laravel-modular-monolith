<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\DTO;


readonly class PaginatedUsersDto
{
    /** @param UserDto[] $items */
    public function __construct(
        public array $items,
        public int   $total,
        public int   $count,
        public int   $currentPage,
        public int   $perPage,
        public int   $lastPage,
    ) {}
}
