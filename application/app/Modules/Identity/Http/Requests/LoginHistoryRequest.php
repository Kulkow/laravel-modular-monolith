<?php
declare(strict_types=1);

namespace App\Modules\Identity\Http\Requests;

use App\Modules\Core\Domain\Criteria\Criteria;
use App\Modules\Core\Domain\Pagination\Pagination;
use App\Modules\Core\Domain\Sorting\Sort;
use App\Modules\Core\Domain\Sorting\SortDirection;
use App\Modules\Core\Domain\Sorting\SortSet;
use App\Modules\Identity\Domain\Auth\ValueObjects\LoginStatus;
use Illuminate\Foundation\Http\FormRequest;

class LoginHistoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page'      => 'sometimes|integer|min:1',
            'per_page'  => 'sometimes|integer|min:1',
            '_sort'      => 'sometimes|string',
            '_sort_dir'      => 'sometimes|string',
            'status'    => 'sometimes|string|in:success,failure',
            'from_date' => 'sometimes|date',
        ];
    }

    public function toCriteria(): Criteria
    {
        $criteria = new Criteria();
        $criteria->setPagination(new Pagination(
            page: $this->integer('page', 1),
            perPage: $this->integer('per_page', 15)
        ));
        if ($this->has('_sort') && $this->has('_sort_dir')) {
            $field = $this->input('_sort','createdAt');
            $direction = $this->input('_sort_dir', 'desc');
            $sort = new Sort($field, SortDirection::from($direction));
            $criteria->setSortSet((new SortSet())->add($sort));
        }
        if ($status = $this->input('status')) {
            $criteria->addFilter('status', LoginStatus::from($status)->value);
        }
        if ($fromDate = $this->input('from_date')) {
            $criteria->addFilter('createdAt', new \DateTimeImmutable($fromDate), '>=');
        }

        return $criteria;
    }
}
