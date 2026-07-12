<?php
declare(strict_types=1);

namespace App\Modules\Identity\Infrastructure\Persistence\LoginHistory;

use App\Modules\Core\Domain\Criteria\Criteria;
use App\Modules\Core\Domain\Pagination\PaginatedResult;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Identity\Domain\Auth\Entity\LoginHistory;
use App\Modules\Identity\Domain\Auth\Repository\LoginHistoryRepositoryInterface;
use App\Modules\Identity\Domain\User\UserId;


class EloquentLoginHistoryRepository implements LoginHistoryRepositoryInterface
{

    public function save(LoginHistory $history): void
    {
        $model = LoginHistoryModel::firstOrNew(['id' => $history->getId()]);
        $model->fill([
            'user_id'    => $history->getUserId(),
            'ip'         => $history->getIp()->value(),
            'user_agent' => $history->getUserAgent()->value(),
            'status'     => $history->getStatus()->value,
            'created_at' => $history->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
        $model->save();

    }


    public function getByUserId(UserId $userId, Criteria $criteria): PaginatedResult
    {
        $query = LoginHistoryModel::where('user_id', $userId->getValue());

        $this->applyFilters($query, $criteria->getFilters());

        if ($criteria->getSortSet() && !$criteria->getSortSet()->isEmpty()) {
            foreach ($criteria->getSortSet()->all() as $sort) {
                $field = $this->mapField($sort->getField());
                $query->orderBy($field, $sort->getDirection()->value);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $pagination = $criteria->getPagination();
        $page = $pagination?->getPage() ?? 1;
        $perPage = $pagination?->getPerPage() ?? 15;

        $paginator = $query->paginate(perPage: $perPage, page: $page);

        $items = array_map(function ($item){
            return LoginHistoryMapper::toDomain($item);
        }, $paginator->items());

        return new PaginatedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage()
        );
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $filter) {
            $field = $this->mapField($filter->getField());
            $operator = $filter->getOperator();
            $value = $filter->getValue();

            match ($operator) {
                '='      => $query->where($field, $value),
                'like'   => $query->where($field, 'like', $value),
                '>', '<', '>=', '<=' => $query->where($field, $operator, $value),
                'in'     => $query->whereIn($field, (array) $value),
                default  => $query->where($field, $operator, $value),
            };
        }
    }

    private function mapField(string $domainField): string
    {
        return match ($domainField) {
            'createdAt' => 'created_at',
            'userId'    => 'user_id',
            'userAgent' => 'user_agent',
            'ip', 'status' => $domainField,
            default => 'created_at',
        };
    }



}
