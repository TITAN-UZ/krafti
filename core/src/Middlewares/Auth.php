<?php

namespace App\Middlewares;

use App\Model\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Auth extends \Vesp\Middlewares\Auth
{
    protected $model = User::class;

    public function __invoke(Request $request, RequestHandler $handler)
    {
        if ($token = $this->getToken($request)) {
            /** @var \Vesp\Models\User $user */
            if ($user = (new $this->model())->query()->where('active', true)->find($token->id)) {
                $request = $request
                    ->withAttribute('user', $user)
                    ->withAttribute('token', $token->token);
            }
        }

        return $handler->handle($request);
    }
}
