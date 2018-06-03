<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiAuth
{

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {
            $this->checkApiKey($request);
        } catch (BadRequestHttpException $e) {
            return response(['error' => 'missing_token'], 400);
        } catch (UnauthorizedHttpException $e) {
            return response(['error' => 'invalid_token'], 401);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return User|null
     */
    private function checkApiKey(Request $request)
    {

        if (!$request->bearerToken()) {
            throw new BadRequestHttpException('Tokens not provided');
        }

        $user = User::where('api_token', $request->bearerToken())->first();
        if (!$user) {
            throw new UnauthorizedHttpException('Invalid credentials');
        }

    }
}
