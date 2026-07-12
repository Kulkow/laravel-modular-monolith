<?php
declare(strict_types=1);
namespace App\Modules\Identity\Application\User\LoginHistory;



use App\Modules\Core\Domain\Criteria\Criteria;
use App\Modules\Core\Domain\Pagination\PaginatedResult;
use App\Modules\Identity\Domain\Auth\Repository\LoginHistoryRepositoryInterface;
use App\Modules\Identity\Domain\User\UserId;

class GetUserLoginHistory
{
    public function __construct(
        private LoginHistoryRepositoryInterface $repository
    ) {}

    public function execute(UserId $userId, Criteria $criteria): PaginatedResult
    {
        return $this->repository->getByUserId($userId, $criteria);
    }
}
