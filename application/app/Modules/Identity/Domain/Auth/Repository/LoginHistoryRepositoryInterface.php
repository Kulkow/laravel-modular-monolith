<?php


namespace App\Modules\Identity\Domain\Auth\Repository;

use App\Modules\Core\Domain\Criteria\Criteria;
use App\Modules\Core\Domain\Pagination\PaginatedResult;
use App\Modules\Identity\Domain\Auth\Entity\LoginHistory;
use App\Modules\Identity\Domain\User\UserId;

interface LoginHistoryRepositoryInterface
{
    public function save(LoginHistory $history): void;

    public function getByUserId(UserId $userId,Criteria $criteria): PaginatedResult;

}
