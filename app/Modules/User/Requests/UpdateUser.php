<?php

namespace App\Modules\User\Requests;

use App\Modules\Api\Handlers\ApiRequestHandler;
use App\Modules\Api\Responses\ApiResponse;
use App\Modules\Api\Utilities\ApiWith;
use App\Modules\User\Conditions\UserDoesExist;
use App\Modules\User\Dto\UserDto;
use App\Modules\User\Services\UserService;
use App\Modules\User\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Nyholm\Psr7\Response;

class UpdateUser extends ApiRequestHandler
{
    public function __construct(
        private UserTransformer $transformer,
        private UserService $service
    ) {
    }

    public function getConditions(): array
    {
        return [
            new UserDoesExist($this->getPathParameterAsInteger('id'))
        ];
    }

    public function processRequest(Request $request): Response
    {
        $userDto = UserDto::from($request);

        $user = $this->service->update($this->getPathParameterAsInteger('id'), $userDto);

        $result = $this->transformer->transform($user, ApiWith::createWithDefault());

        return ApiResponse::success($result);
    }
}