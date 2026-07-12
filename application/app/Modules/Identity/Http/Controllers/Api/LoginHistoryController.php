<?php

declare(strict_types=1);

namespace App\Modules\Identity\Http\Controllers\Api;


use App\Modules\Identity\Application\User\LoginHistory\GetUserLoginHistory;
use App\Modules\Identity\Domain\User\UserId;
use App\Modules\Identity\Http\Requests\LoginHistoryRequest;
use App\Modules\Identity\Http\Responses\LoginHistoryResponse;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
class LoginHistoryController
{
    use ApiResponse;
    public function index(LoginHistoryRequest $request, GetUserLoginHistory $query): JsonResponse
    {
        try{
            $result = $query->execute(
                new UserId($request->user()->id),
                $request->toCriteria()
            );
            return $this->success(LoginHistoryResponse::format($result));
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function byUser(int $id, LoginHistoryRequest $request, GetUserLoginHistory $query): JsonResponse
    {
        try{
            $result = $query->execute(
                new UserId($id),
                $request->toCriteria()
            );
            return $this->success(LoginHistoryResponse::format($result));
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }
}
