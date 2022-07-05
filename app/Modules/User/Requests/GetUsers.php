<?php

namespace App\Modules\User\Requests;

use App\Modules\Api\Conditions\NoConditionsTrait;
use App\Modules\Api\Handlers\ApiRequestHandler;
use App\Modules\Api\Helpers\ApiWith;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\Api\Helpers\ApiFilter;
use App\Modules\Api\Helpers\Pagination;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class GetUsers extends ApiRequestHandler
{
    use NoConditionsTrait;

    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {}

    public function processRequest(Request $request): Response
    {
        $pagination = Pagination::from($request);

        $apiFilter = ApiFilter::from($request);

        $apiWith = ApiWith::from($request);

        $users = $this->service->list($pagination, $apiFilter);

        $result = $this->transformer->transformCollection($users, $pagination, $apiWith);

        return ApiResponse::success($result);
    }
}
