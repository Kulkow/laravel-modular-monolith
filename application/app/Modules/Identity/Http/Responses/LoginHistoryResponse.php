<?php
namespace App\Modules\Identity\Http\Responses;

use App\Modules\Core\Domain\Pagination\PaginatedResult;
use App\Modules\Identity\Domain\Auth\Entity\LoginHistory;

class LoginHistoryResponse
{
    public static function format(PaginatedResult $result): array
    {
        $items = array_map(function (LoginHistory $item) {
            return [
                'id' => $item->getId(),
                'ip' => $item->getIp()->value(),
                'user_agent' => $item->getUserAgent()->value(),
                'status' => $item->getStatus()->value,
                'login_time' => $item->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $result->getItems());
        return [
            'items' => $items,
            'pagination' => [
                'total'        => $result->getTotal(),
                'per_page'     => $result->getPerPage(),
                'current_page' => $result->getCurrentPage(),
                'last_page'    => $result->getLastPage(),
            ],
        ];
    }
}
